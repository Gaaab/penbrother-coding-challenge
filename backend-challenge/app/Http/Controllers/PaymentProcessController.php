<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessPaymentRequest;
use Illuminate\Support\Facades\Artisan;

class PaymentProcessController extends Controller
{
    public function __invoke(ProcessPaymentRequest $request)
    {
        $params = [];
        if ($request->has('date')) {
            $params['date'] = $request->input('date');
        }

        Artisan::call('payments:process', $params);

        return response()->json([
            'message' => 'Payment processing completed',
            'output' => Artisan::output(),
        ]);
    }
}
