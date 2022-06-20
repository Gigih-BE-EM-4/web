<?php

namespace Database\Seeders;

use App\Models\Apply;
use App\Models\Project;
use App\Models\ProjectRole;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Project::factory()->count(20)->create();
    ProjectRole::factory()->count(20)->create();
    Apply::factory()->count(20)->create();
  }
}
