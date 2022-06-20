<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ApplyFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'user_id' => 1,
      'project_id' => $this->faker->numberBetween(1, 2),
      'project_role_id' => $this->faker->numberBetween(1, 3),
      'cv' => $this->faker->imageUrl(),
      'extra_answer' => $this->faker->text(100),
    ];
  }
}
