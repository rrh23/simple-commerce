<?php

namespace Database\Factories;;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_title' => fake()->jobTitle(),
            'image_url' => fake()->url(),
            'web_id' => Str::uuid()->toString(),
            'price' => fake()->randomFloat(2,0,3),
            'rating' => fake()->randomFloat(1,0,1),
            'category' => fake()->name(),
            'is_deleted' => false
        ];
    }
}
