<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AggDay extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "total_food" => "300",
                "total_price" => "800000000",
                "order_date" => "2022-07-13",
                "vietnamese_guest" => "70",
                "japanese_guest" => "60",
                "english_guest" => "70",
            ],
            [
                "total_food" => "456",
                "total_price" => "90000000",
                "order_date" => "2022-07-14",
                "vietnamese_guest" => "50",
                "japanese_guest" => "80",
                "english_guest" => "67",
            ],
            [
                "total_food" => "345",
                "total_price" => "60000000",
                "order_date" => "2022-07-15",
                "vietnamese_guest" => "55",
                "japanese_guest" => "45",
                "english_guest" => "70",
            ],
            [
                "total_food" => "333",
                "total_price" => "60000000",
                "order_date" => "2022-07-16",
                "vietnamese_guest" => "77",
                "japanese_guest" => "66",
                "english_guest" => "55",
            ],
            [
                "total_food" => "270",
                "total_price" => "50000000",
                "order_date" => "2022-07-17",
                "vietnamese_guest" => "88",
                "japanese_guest" => "80",
                "english_guest" => "55",
            ],
            [
                "total_food" => "380",
                "total_price" => "65000000",
                "order_date" => "2022-07-18",
                "vietnamese_guest" => "89",
                "japanese_guest" => "80",
                "english_guest" => "45",
            ],
            [
                "total_food" => "350",
                "total_price" => "60000000",
                "order_date" => "2022-07-19",
                "vietnamese_guest" => "45",
                "japanese_guest" => "50",
                "english_guest" => "55",
            ],
            [
                "total_food" => "450",
                "total_price" => "85000000",
                "order_date" => "2022-07-20",
                "vietnamese_guest" => "77",
                "japanese_guest" => "55",
                "english_guest" => "33",
            ],
            [
                "total_food" => "400",
                "total_price" => "80000000",
                "order_date" => "2022-07-21",
                "vietnamese_guest" => "77",
                "japanese_guest" => "88",
                "english_guest" => "99",
            ],
            [
                "total_food" => "500",
                "total_price" => "100000000",
                "order_date" => "2022-07-22",
                "vietnamese_guest" => "120",
                "japanese_guest" => "80",
                "english_guest" => "70",
            ],
        ];
        \App\Entities\AggDay::insert($data);
    }
}
