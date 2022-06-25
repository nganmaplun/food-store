<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('tables')->insert([
            'name' => 'Bàn 1',
            'max_seat' => '2',
            'floor' => '2',
            'description' => 'description',
            'status' => '1'
        ]);
        \DB::table('tables')->insert([
            'name' => 'Bàn 2',
            'max_seat' => '2',
            'floor' => '3',
            'description' => 'description',
            'status' => '1'
        ]);
        \DB::table('tables')->insert([
            'name' => 'Bàn 3',
            'max_seat' => '2',
            'floor' => '1',
            'description' => 'description',
            'status' => '1'
        ]);
        \DB::table('tables')->insert([
            'name' => 'Bàn 4',
            'max_seat' => '2',
            'floor' => '2',
            'description' => 'description',
            'status' => '1'
        ]);
        \DB::table('tables')->insert([
            'name' => 'Bàn 5',
            'max_seat' => '2',
            'floor' => '3',
            'description' => 'description',
            'status' => '1'
        ]);
        \DB::table('tables')->insert([
            'name' => 'Bàn 6',
            'max_seat' => '2',
            'floor' => '4',
            'description' => 'description',
            'status' => '1'
        ]);
    }
}
