<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Promoter;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory()
            ->count(40)
            ->state(new Sequence(
                ['promoter_id' => Promoter::find(1)],
                ['promoter_id' => Promoter::find(2)],
                ['promoter_id' => Promoter::find(3)],
                ['promoter_id' => Promoter::find(4)],
            ))
            ->create();
    }
}
