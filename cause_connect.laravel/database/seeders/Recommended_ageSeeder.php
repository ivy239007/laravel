<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Recommended_ageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefectures = [
            ['rec_age_id' => 1, 'rec_age' => '全年齢'],
            ['rec_age_id' => 2, 'rec_age' => 'シニア'],
            ['rec_age_id' => 3, 'rec_age' => '社会人'],
            ['rec_age_id' => 4, 'rec_age' => '学生'],
            ['rec_age_id' => 5, 'rec_age' => 'その他'],
        ];

        DB::table('recommended_age')->insert($prefectures);
    }
}
