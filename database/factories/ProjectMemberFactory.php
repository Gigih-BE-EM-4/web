<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectMemberFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'project_id' => $this->faker->numberBetween(1, 2),
      'project_role_id' => $this->faker->numberBetween(1, 2),
      'user_id' => $this->faker->numberBetween(1, 2),
    ];
  }
}
