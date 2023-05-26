<?php

namespace Tests\Factories;

use App\Models\Role;
use Faker\Factory as FakerFactory;

class RoleFactory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $this->faker = FakerFactory::create();

        static $roleNames = Role::NAMES;

        return [
            'name' => $this->faker->unique()->randomElement($roleNames),
        ];
    }
}
