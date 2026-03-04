<?php

namespace Database\Factories;

use App\Models\Postcode;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Store::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $postcode = Postcode::inRandomOrder()->first() ?? Postcode::factory()->create();

        return [
            'name' => fake()->company().' '.fake()->city(),
            'postcode_id' => $postcode->id,
            'delivery_radius_km' => fake()->numberBetween(10, 50),
        ];
    }
}
