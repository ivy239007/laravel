<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PictureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefectures = [
            ['picture_type' => 1, 'type_name' => '画像(基本情報)'],
            ['picture_type' => 2, 'type_name' => '画像(依頼詳細)'],
            ['picture_type' => 3, 'type_name' => '参加者の写真'],
            ['picture_type' => 4, 'type_name' => '依頼場所の写真'],
            ['picture_type' => 5, 'type_name' => '実行前の写真'],
            ['picture_type' => 6, 'type_name' => '実行後の写真'],
        ];

        DB::table('picture')->insert($prefectures);
    }
}
