<?php

namespace Tests\Feature;

use App\Enums\AmortizationState;
use App\Models\Amortization;
use App\Models\Project;
use App\Models\Promoter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Task1RelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_amortization_belongs_to_project_and_promote(): void
    {
        $user = User::factory()->create();
        $promoter = Promoter::factory()->create(['user_id' => $user->id]);
        $project = Project::factory()->create(['promoter_id' => $promoter->id]);

        $amortization = new Amortization();
        $amortization->promoter_id = $promoter->id;
        $amortization->project_id = $project->id;
        $amortization->amount = 1000;
        $amortization->state = AmortizationState::PENDING->value;
        $amortization->schedule_date = now()->format('Y-m-d'); // date only not datetime

        $amortization->save();
        $amortization->refresh();

        $this->assertNotNull($amortization->project);
        $this->assertEquals($project->id, $amortization->project->id);

        $this->assertNotNull($amortization->promoter);
        $this->assertEquals($promoter->id, $amortization->promoter->id);
    }

    // test the regex validation for the project name
    public function test_project_requires_valid_name(): void
    {
        // Name exceeds 30 characters - should fail
        $response = $this->postJson('/api/projects', [
            'name' => 'Invalid Project Name!',
            'description' => 'A valid description.',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Special characters in name - should fail
        $response = $this->postJson('/api/projects', [
            'name' => 'Invalid@Name!',
            'description' => 'A valid description.',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }
}
