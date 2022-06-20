<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectRoleFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'name' => $this->faker->randomElement(['Frontend', 'Backend', 'DevOps', 'Designer']),
      'quota' => $this->faker->numberBetween(1, 10),
      'description' => $this->faker->text(100),
      'project_id' => $this->faker->numberBetween(1, 2),
      'extra_question' => $this->faker->text(100),
    ];
  }
}
