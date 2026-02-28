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

        if ($request->has('amount')) {
            $query->where('amount', $request->input('amount'));
        }

        $amortizations = $query->paginate(15);

        return AmortizationResource::collection($amortizations);
    }
}
