<?php

namespace App\Console\Commands;

use App\Entities\Food;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class InsertFoodCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:food';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert csv food to database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // read file
        $file = public_path() . '/files/foods.csv';
        $fp = fopen($file, 'r');
        fgets($fp);
        $data = [];
        $i = 0;
        while (($row = fgetcsv($fp, 0, ",")) !== FALSE) {
            if (!in_array($row[10], ['Mozukusu', 'Salad', 'Sashimi', 'Sushi', 'Otsumami'])) {
                continue;
            }
            $data[$i]['japanese_name'] = $row[1] ?? '';
            $data[$i]['short_name'] = $row[4] ?? '';
            $data[$i]['vietnamese_name'] = $row[5] ?? '';
            $data[$i]['english_name'] = $row[6] ?? '';
            $data[$i]['price'] = $row[7] ?? '';
            $category = match ($row[10]) {
                'Mozukusu', 'Salad', 'Sashimi', 'Sushi', 'Otsumami' => '1',
            };
            $data[$i]['category'] = $category ?? '';
            $data[$i]['image'] = strtolower(str_replace(' ', '', $row[6]) . '.jpg');
            $data[$i]['food_recipe'] = '';
            $data[$i]['description'] = '';
            $data[$i]['status'] = 1;
            $i++;
        }
        Food::insert($data);
    }
}
