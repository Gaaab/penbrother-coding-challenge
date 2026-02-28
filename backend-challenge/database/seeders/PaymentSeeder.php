<?php

namespace Database\Seeders;

use App\Models\Amortization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amortizations = Amortization::all();
        $userIds = User::limit(20)->pluck('id')->toArray();
        $userCount = count($userIds);

        foreach ($amortizations as $amortization) {
            $payments = [];
            $totalAmount = $amortization->amount;
            $paymentsCount = 1000;

            $baseAmount = intval($totalAmount / $paymentsCount);
            $remainder = $totalAmount - ($baseAmount * $paymentsCount);

            for ($i = 0; $i < $paymentsCount; $i++) {
                $amount = $baseAmount + ($i < $remainder ? 1 : 0); // Distribute the remainder among the first few payments

                $payments[] = [
                    'user_id' => $userIds[$i % $userCount],
                    'amortization_id' => $amortization->id,
                    'amount' => $amount,
                    'state' => $amortization->state,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($payments) >= 500) {
                    DB::table('payments')->insert($payments);
                    $payments = [];
                }
            }

            if (! empty($payments)) {
                DB::table('payments')->insert($payments);
            }
        }
    }
}
