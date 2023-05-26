<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            'admin'      => [
                'name'     => 'Admin',
                'email'    => 'admin@example.com',
                'password' => bcrypt('password'),
            ],
            'moderator'  => [
                'name'     => 'Moderator',
                'email'    => 'moderator@example.com',
                'password' => bcrypt('password'),
            ],
            'publisher' => [
                'name'     => 'Publisher',
                'email'    => 'publisher@example.com',
                'password' => bcrypt('password'),
            ]
        ];

        foreach ($users as $name => $userData) {
            $user = User::create($userData);
            $role = Role::create(['name' => $name]);
            $user->roles()->attach($role);
            $user->save();
        }
    }
}
