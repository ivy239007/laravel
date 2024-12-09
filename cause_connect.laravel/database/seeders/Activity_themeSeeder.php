<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Activity_themeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefectures = [
            ['theme_id' => 1, 'theme' => '地域美化'],
        ];

        DB::table('activity_theme')->insert($prefectures);
    }
}
