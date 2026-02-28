<?php

namespace Tests\Feature;

use App\Mail\DelayedAmortizationMail;
use App\Models\Amortization;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Promoter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class Task3PaymentCommandTest extends TestCase
{
    use RefreshDatabase;

    private function createTestData(string $state, string $scheduleDate, int $amount = 10000): array
    {
        $promoteUser = User::factory()->create();
        $promoter = Promoter::factory()->create([
            'user_id' => $promoteUser->id,
        ]);
        $project = Project::factory()->create([
            'promoter_id' => $promoter->id,
            'wallet_balance' => 500000,
        ]);

        $amortization = Amortization::factory()->create([
            'project_id' => $project->id,
            'promoter_id' => $promoter->id,
            'amount' => $amount,
            'state' => $state,
            'schedule_date' => $scheduleDate,
        ]);

        $investor1 = User::factory()->create();
        $investor2 = User::factory()->create();

        Payment::create([
            'user_id' => $investor1->id,
            'amortization_id' => $amortization->id,
            'amount' => $amount / 2,
            'state' => $state,
        ]);

        Payment::create([
            'user_id' => $investor2->id,
            'amortization_id' => $amortization->id,
            'amount' => $amount / 2,
            'state' => $state,
        ]);

        return compact('promoteUser', 'promoter', 'project', 'amortization', 'investor1', 'investor2');
    }

    public function test_commands_sends_email_for_delayed_amortization(): void
    {
        Mail::fake();

        $this->createTestData('pending', now()->subDays(5)->format('Y-m-d'));

        $this->artisan('payments:process')
            ->assertExitCode(0);

        // 1 promoter + 2 investors = 3 emails
        Mail::assertSent(DelayedAmortizationMail::class, 3);
    }
}
