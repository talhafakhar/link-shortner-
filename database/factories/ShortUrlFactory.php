<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ShortUrl;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShortUrl>
 */
class ShortUrlFactory extends Factory
{
    protected $model = ShortUrl::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'original_url' => $this->faker->url,
            'slug' => Str::random(6),
            'expires_at' => $this->faker->boolean(30) ? $this->faker->dateTimeBetween('+1 day', '+30 days') : null,
            'visits' => $this->faker->numberBetween(0, 1000),
        ];  
    }
}
