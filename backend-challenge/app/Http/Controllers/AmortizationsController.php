<?php

namespace App\Http\Controllers;

use App\Http\Resources\AmortizationResource;
use App\Http\Resources\PaymentResource;
use App\Services\AmortizationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AmortizationsController extends Controller
{
    public function __construct(
        protected AmortizationService $amortizationService
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->amortizationService->getAmortizations($request);
    }

    public function show(\App\Models\Amortization $amortization)
    {
        $amortization->load('project:id,name,wallet_balance');

        return new AmortizationResource($amortization);
    }

    public function payments(\App\Models\Amortization $amortization)
    {
        $payments = $amortization->payments()->with('user:id,name')->paginate(15);

        return PaymentResource::collection($payments);
    }

    public function stats()
    {
        $total = \App\Models\Amortization::count();
        $paid = \App\Models\Amortization::where('state', 'paid')->count();
        $pending = \App\Models\Amortization::where('state', 'pending')->count();
        $completed = \App\Models\Amortization::where('state', 'completed')->count();
        $cancelled = \App\Models\Amortization::where('state', 'cancelled')->count();

        $onTime = \App\Models\Amortization::where('state', 'paid')
            ->whereColumn('schedule_date', '>=', 'updated_at')
            ->count();
        $delayed = $paid - $onTime;

        return response()->json([
            'total' => $total,
            'paid' => $paid,
            'pending' => $pending,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'on_time' => $onTime,
            'delayed' => $delayed,
        ]);
    }
}
