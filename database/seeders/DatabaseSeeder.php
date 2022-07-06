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
            'username' => 'chef_salad',
            'password' => Hash::make('11111111'),
            'full_name' => 'PS',
            'phone' => '0389879624',
            'role' => 'chef_salad',
            'status' => 0
        ]);
        \DB::table('users')->insert([
            'username' => 'chef_steam',
            'password' => Hash::make('11111111'),
            'full_name' => 'PS',
            'phone' => '0389879624',
            'role' => 'chef_steam',
            'status' => 0
        ]);
        \DB::table('users')->insert([
            'username' => 'chef_grill',
            'password' => Hash::make('11111111'),
            'full_name' => 'PS',
            'phone' => '0389879624',
            'role' => 'chef_grill',
            'status' => 0
        ]);
        \DB::table('users')->insert([
            'username' => 'chef_drying',
            'password' => Hash::make('11111111'),
            'full_name' => 'PS',
            'phone' => '0389879624',
            'role' => 'chef_drying',
            'status' => 0
        ]);
        \DB::table('users')->insert([
            'username' => 'chef_drink',
            'password' => Hash::make('11111111'),
            'full_name' => 'PS',
            'phone' => '0389879624',
            'role' => 'chef_drink',
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
