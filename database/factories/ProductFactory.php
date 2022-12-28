<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'price'=>$this->faker->numberBetween(100, 2000),
            'quantity'=>$this->faker->numberBetween(10, 20),
            'name'=>$this->faker->text(10),
            'slug'=>$this->faker->slug(10),
            'description'=>$this->faker->text()
        ];
    }
}
