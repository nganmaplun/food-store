<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        \DB::table('users')->insert([
            'username' => 'admin',
            'password' => Hash::make('11111111'),
            'full_name' => 'PS',
            'phone' => '0389879624',
            'role' => 'admin',
            'status' => 0
        ]);
        \DB::table('users')->insert([
            'username' => 'waiter',
            'password' => Hash::make('11111111'),
            'full_name' => 'PS',
            'phone' => '0389879624',
            'role' => 'waiter/waitress',
            'status' => 0
        ]);
        \DB::table('users')->insert([
            'username' => 'chef',
            'password' => Hash::make('11111111'),
            'full_name' => 'PS',
            'phone' => '0389879624',
            'role' => 'chef',
            'status' => 0
        ]);
        \DB::table('users')->insert([
            'username' => 'cashier',
            'password' => Hash::make('11111111'),
            'full_name' => 'PS',
            'phone' => '0389879624',
            'role' => 'cashier',
            'status' => 0
        ]);
    }
}
