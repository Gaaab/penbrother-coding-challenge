<?php

namespace App\Http\Controllers;

use App\Enums\AmortizationState;
use App\Http\Requests\ProcessPaymentRequest;
use App\Mail\DelayedAmortizationMail;
use App\Models\Amortization;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PaymentProcessController extends Controller
{
    public function __invoke(ProcessPaymentRequest $request)
    {
        $date = Carbon::parse($request->input('date', now()->format('Y-m-d')));
        $today = Carbon::today();

        $processed = 0;
        $delayed = 0;
        $totalPayments = 0;
        $totalAmount = 0;

        Amortization::where('state', AmortizationState::PENDING->value)
            ->where('schedule_date', '<=', $date)
            ->with(['project', 'promoter.user', 'payments.user'])
            ->chunkById(200, function ($amortizations) use ($today, &$processed, &$delayed, &$totalPayments, &$totalAmount) {
                foreach ($amortizations as $amortization) {
                    DB::transaction(function () use ($amortization, $today, &$processed, &$delayed, &$totalPayments, &$totalAmount) {
                        $paymentCount = $amortization->payments()->count();
                        $amortization->payments()->update(['state' => AmortizationState::PAID->value]);
                        $amortization->project->decrement('wallet_balance', $amortization->amount);
                        $amortization->update(['state' => AmortizationState::PAID->value]);

                        $processed++;
                        $totalPayments += $paymentCount;
                        $totalAmount += $amortization->amount;

                        if ($amortization->schedule_date->lt($today)) {
                            $delayed++;
                            $this->sendDelayedNotification($amortization);
                        }
                    });
                }
            });

        return response()->json([
            'message' => $processed > 0
                ? "Processed {$processed} amortizations"
                : 'No pending amortizations found for the given date',
            'processed' => $processed,
            'delayed' => $delayed,
            'total_payments' => $totalPayments,
            'total_amount' => $totalAmount,
            'date' => $date->format('Y-m-d'),
        ]);
    }

    private function sendDelayedNotification(Amortization $amortization): void
    {
        try {
            $mail = new DelayedAmortizationMail($amortization);
            Mail::to($amortization->promoter->user->email)->send($mail);

            $investorEmails = $amortization->payments
                ->pluck('user.email')
                ->unique()
                ->filter();

            foreach ($investorEmails as $email) {
                Mail::to($email)->send($mail);
            }
        } catch (\Exception $e) {
            // Don't block processing if mail fails
        }
    }
}
