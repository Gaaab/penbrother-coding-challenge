<?php

namespace App\Console\Commands;

use App\Enums\AmortizationState;
use App\Mail\DelayedAmortizationMail;
use App\Models\Amortization;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProcessAmortizationPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:process {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all pending amortization payments up to the specified cutoff date.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateInput = $this->argument('date') ?? now()->format('Y-m-d');

        try {
            $date = Carbon::createFromFormat('Y-m-d', $dateInput);
        } catch (Exception $e) {
            $this->error('Invalid date format. Please use Y-m-d.');
            return Command::FAILURE;
        }

        $today = Carbon::today();
        $processed = 0;
        $delayed = 0;

        $this->info('Processing amortizations with scheduled date...');

        // Process pending amortizations in chunks of 200 for memory efficiency
        Amortization::where('state', AmortizationState::PENDING->value)
            ->where('schedule_date', '<=', $date)
            ->with(['project', 'promoter.user', 'payments.user'])
            ->chunkById(200, function ($amortizations) use ($today, &$processed, &$delayed) {
                foreach ($amortizations as $amortization) {
                    DB::transaction(function () use ($amortization, $today, &$processed, &$delayed) {
                        // Update all payments to paid
                        $amortization->payments()->update(['state' => AmortizationState::PAID->value]);

                        // Deduct the amortization amount from the project's wallet balance
                        $amortization->project->decrement('wallet_balance', $amortization->amount);

                        // Mark the amortization as paid
                        $amortization->update(['state' => AmortizationState::PAID->value]);

                        $processed++;

                        // Check if the amortization is delayed (scheduled date is before today)
                        if ($amortization->schedule_date->lt($today)) {
                            $delayed++;
                            $this->sendDelayedNotification($amortization);
                        }
                    });
                }
            });

        $this->info("Processing complete. Total processed: $processed. Total delayed: $delayed.");
        return Command::SUCCESS;
    }

    private function sendDelayedNotification(Amortization $amortization): void
    {
        $mail = new DelayedAmortizationMail($amortization);

        // Send to promoters email
        $promoterEmail = $amortization->promoter->user->email;
        Mail::to($promoterEmail)->send($mail);

        // Send to each investor who has payment in this amortization
        $investorEmails = $amortization->payments
            ->pluck('user.email')
            ->unique()
            ->filter();

        foreach ($investorEmails as $email) {
            Mail::to($email)->send($mail);
        }
    }
}
