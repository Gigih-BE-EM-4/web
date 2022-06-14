<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class CompanyFactory extends Factory
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
            'bio' => $this->faker->text(100),
            'address' => $this->faker->streetAddress() . "," . $this->faker->city() . "," . $this->faker->country(),
            'email' => $this->faker->companyEmail(),
            'contact' => $this->faker->phoneNumber()
        ];
    }
}
