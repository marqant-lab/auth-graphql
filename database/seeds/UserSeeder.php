<?php

namespace Marqant\AuthGraphGL\Seeds;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserSeeder
 *
 * @package Marqant\AuthGraphGL\Seeds
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = 'Password123$';

        $model = config('auth.providers.users.model');

        $model::updateOrCreate([
            'email' => 'demo@demo.com',
        ], [
            'name'     => 'Demo User',
            'password' => Hash::make($password),
        ]);

        $model::updateOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name'     => 'Admin',
            'password' => Hash::make($password),
        ]);
    }
}
