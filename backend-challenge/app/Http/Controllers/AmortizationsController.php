<?php

namespace App\Http\Controllers;

use App\Http\Resources\AmortizationResource;
use App\Services\AmortizationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AmortizationsController extends Controller
{
    public function __construct(
        protected AmortizationService $amortizationService
    )
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->amortizationService->getAmortizations($request);
    }

    public function show($amortization)
    {
        $amortization->load('project:id,name,wallet_balance');

        return new AmortizationResource($amortization);
    }
}
