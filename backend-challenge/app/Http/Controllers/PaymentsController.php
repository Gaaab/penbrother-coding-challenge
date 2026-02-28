<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaymentsController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Payment::query()
            ->with('user:id,name');

        if ($request->has('state')) {
            $query->where('state', $request->input('state'));
        }

        if ($request->has('user')) {
            $query->where('user_id', $request->input('user'));
        }

        $payments = $query->paginate(15);

        return PaymentResource::collection($payments);
    }
}
