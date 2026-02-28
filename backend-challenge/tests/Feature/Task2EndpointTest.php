<?php

namespace Tests\Feature;

use App\Models\Amortization;
use App\Models\Project;
use App\Models\Promoter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Task2EndpointTest extends TestCase
{
    use RefreshDatabase;

    private function createAmortization(string $state = 'pending', int $amount = 50000): Amortization
    {
        $user = User::factory()->create();
        $promoter = Promoter::factory()->create(['user_id' => $user->id]);
        $project = Project::factory()->create(['promoter_id' => $promoter->id]);

        $amortization = new Amortization();
        $amortization->promoter_id = $promoter->id;
        $amortization->project_id = $project->id;
        $amortization->amount = $amount;
        $amortization->state = $state;
        $amortization->schedule_date = now()->format('Y-m-d');
        $amortization->save();

        return $amortization;
    }

    public function test_amortization_index_filters_by_state()
    {
        $this->createAmortization('pending');
        $this->createAmortization('pending');
        $this->createAmortization('paid');

        $response = $this->getJson('/api/amortizations?state=pending');
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['state' => 'pending']);
    }
}
