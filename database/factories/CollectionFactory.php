<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Collection>
 */
class CollectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'url_qiita' => $this->faker->optional()->url(),
            'url_webapp' => $this->faker->optional()->url(),
            'url_github' => $this->faker->optional()->url(),
            'is_public' => $this->faker->boolean(),
            'position' => $this->faker->randomElement([0, 1, 2]),
            'user_id' => 1,
        ];
    }
}
