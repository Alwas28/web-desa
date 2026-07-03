<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        $role = Role::where('name', 'administrator')->first();
        if ($role && ! $admin->roles->contains($role->id)) {
            $admin->roles()->attach($role->id);
        }
    }
}
