<?php

namespace App\Services;

use App\Http\Resources\AmortizationResource;
use App\Models\Amortization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AmortizationService
{
    public function getAmortizations(Request $request): AnonymousResourceCollection
    {
        $query = Amortization::query()
            ->with('project:id,name,wallet_balance'); // n+1

        if ($request->has('state')) {
            $query->where('state', $request->input('state'));
        }

        if ($request->has('amount_min')) {
            $query->where('amount', '>=', $request->input('amount_min'));
        }

        if ($request->has('amount_max')) {
            $query->where('amount', '<=', $request->input('amount_max'));
        }

        $amortizations = $query->paginate(15);

        return AmortizationResource::collection($amortizations);
    }
}
