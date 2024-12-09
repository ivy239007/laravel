<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefectures = [
            ['area_id' => 1, 'area' => '道路'],
            ['area_id' => 2, 'area' => '山'],
            ['area_id' => 3, 'area' => '川'],
            ['area_id' => 4, 'area' => '海'],
            ['area_id' => 5, 'area' => '公園'],
            ['area_id' => 6, 'area' => 'その他'],
        ];

        DB::table('place')->insert($prefectures);
    }
}
