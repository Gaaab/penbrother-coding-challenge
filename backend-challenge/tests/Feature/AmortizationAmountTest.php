<?php

namespace Tests\Feature;

use App\Enums\AmortizationState;
use App\Models\Amortization;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Promoter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AmortizationAmountTest extends TestCase
{
    use RefreshDatabase;

    public function test_amounts_are_stored_and_returned_as_decimals(): void
    {
        $user = User::factory()->create();
        $promoter = Promoter::factory()->create(['user_id' => $user->id]);
        $project = Project::factory()->create([
            'promoter_id' => $promoter->id,
            'wallet_balance' => 1500.12345,
            'investment_goal' => 3000.50000,
        ]);

        $amortization = new Amortization();
        $amortization->promoter_id = $promoter->id;
        $amortization->project_id = $project->id;
        $amortization->amount = 250.75123;
        $amortization->state = AmortizationState::PENDING->value;
        $amortization->schedule_date = now()->format('Y-m-d');
        $amortization->save();

        $payment = new Payment();
        $payment->user_id = $user->id;
        $payment->amortization_id = $amortization->id;
        $payment->amount = 125.37561;
        $payment->state = AmortizationState::PENDING->value;
        $payment->save();

        // Verify stored values
        $amortization->refresh();
        $project->refresh();
        $payment->refresh();

        $this->assertEquals('250.75123', $amortization->amount);
        $this->assertEquals('1500.12345', $project->wallet_balance);
        $this->assertEquals('3000.50000', $project->investment_goal);
        $this->assertEquals('125.37561', $payment->amount);

        // Verify API returns decimal values
        $response = $this->getJson("/api/amortizations/{$amortization->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['amount' => '250.75123']);

        // Verify wallet_balance is returned via the resource
        $data = $response->json('data');
        $this->assertEquals('1500.12345', $data['project_wallet_balance']);
    }

    public function test_wallet_balance_decrements_correctly_with_decimals(): void
    {
        $user = User::factory()->create();
        $promoter = Promoter::factory()->create(['user_id' => $user->id]);
        $project = Project::factory()->create([
            'promoter_id' => $promoter->id,
            'wallet_balance' => 1000.00000,
            'investment_goal' => 2000.00000,
        ]);

        $amortization = new Amortization();
        $amortization->promoter_id = $promoter->id;
        $amortization->project_id = $project->id;
        $amortization->amount = 123.45678;
        $amortization->state = AmortizationState::PENDING->value;
        $amortization->schedule_date = now()->subDay()->format('Y-m-d');
        $amortization->save();

        $payment = new Payment();
        $payment->user_id = $user->id;
        $payment->amortization_id = $amortization->id;
        $payment->amount = 123.45678;
        $payment->state = AmortizationState::PENDING->value;
        $payment->save();

        $response = $this->postJson('/api/payments/process', [
            'date' => now()->format('Y-m-d'),
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['processed' => 1]);

        $project->refresh();
        $this->assertEquals('876.54322', $project->wallet_balance);
    }
}
