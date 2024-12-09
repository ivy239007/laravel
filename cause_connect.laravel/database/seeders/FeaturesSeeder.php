<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefectures = [
            ['feature_id' => 1, 'feature' => '親子で参加できる'],
            ['feature_id' => 2, 'feature' => '初心者歓迎'],
            ['feature_id' => 3, 'feature' => '体力に自信がある人'],
        ];

        DB::table('feature')->insert($prefectures);
    }
}
