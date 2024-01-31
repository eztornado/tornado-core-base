<?php

namespace Database\Seeders;

use App\Models\Core\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $prueba = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'active' => 1,
            'roles_id' => 1,
            'password' => Hash::make('Admin2024')
        ]);
    }
}
