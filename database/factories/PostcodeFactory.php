<?php

namespace Database\Factories;

use App\Models\Postcode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Postcode>
 */
class PostcodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Postcode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $latitude = fake()->latitude();
        $longitude = fake()->longitude();

        return [
            'postcode' => fake()->postcode(),
            'place_name' => fake()->city(),
            'county' => fake()->county(),
            'country' => 'United Kingdom',
            'type' => 'Standard',
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }
}
