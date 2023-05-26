<?php

namespace Database\Factories;

use App\Models\Publication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Publication>
 */
class PublicationFactory extends Factory
{
        /**
         * The name of the factory's corresponding model.
         *
         * @var string
         */
        protected $model = Publication::class;

        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition(): array
        {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'status' => Publication::STATUS_ACTIVE,
            'user_id' => User::factory(),
        ];
    }
}
