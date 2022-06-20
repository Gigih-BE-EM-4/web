<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'name' => $this->faker->company(),
      'images' => $this->faker->imageUrl(),
      'description' => $this->faker->text(100),
      'active' => $this->faker->numberBetween(0, 1),
      'company_id' => $this->faker->numberBetween(1, 2),
    ];
  }
}
