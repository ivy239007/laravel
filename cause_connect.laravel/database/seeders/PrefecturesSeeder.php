<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrefecturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefectures = [
            ['pref_id' => 1, 'pref' => '北海道'],
            ['pref_id' => 2, 'pref' => '青森県'],
            ['pref_id' => 3, 'pref' => '岩手県'],
            ['pref_id' => 4, 'pref' => '宮城県'],
            ['pref_id' => 5, 'pref' => '秋田県'],
            ['pref_id' => 6, 'pref' => '山形県'],
            ['pref_id' => 7, 'pref' => '福島県'],
            ['pref_id' => 8, 'pref' => '茨城県'],
            ['pref_id' => 9, 'pref' => '栃木県'],
            ['pref_id' => 10, 'pref' => '群馬県'],
            ['pref_id' => 11, 'pref' => '埼玉県'],
            ['pref_id' => 12, 'pref' => '千葉県'],
            ['pref_id' => 13, 'pref' => '東京都'],
            ['pref_id' => 14, 'pref' => '神奈川県'],
            ['pref_id' => 15, 'pref' => '新潟県'],
            ['pref_id' => 16, 'pref' => '富山県'],
            ['pref_id' => 17, 'pref' => '石川県'],
            ['pref_id' => 18, 'pref' => '福井県'],
            ['pref_id' => 19, 'pref' => '山梨県'],
            ['pref_id' => 20, 'pref' => '長野県'],
            ['pref_id' => 21, 'pref' => '岐阜県'],
            ['pref_id' => 22, 'pref' => '静岡県'],
            ['pref_id' => 23, 'pref' => '愛知県'],
            ['pref_id' => 24, 'pref' => '三重県'],
            ['pref_id' => 25, 'pref' => '滋賀県'],
            ['pref_id' => 26, 'pref' => '京都府'],
            ['pref_id' => 27, 'pref' => '大阪府'],
            ['pref_id' => 28, 'pref' => '兵庫県'],
            ['pref_id' => 29, 'pref' => '奈良県'],
            ['pref_id' => 30, 'pref' => '和歌山県'],
            ['pref_id' => 31, 'pref' => '鳥取県'],
            ['pref_id' => 32, 'pref' => '島根県'],
            ['pref_id' => 33, 'pref' => '岡山県'],
            ['pref_id' => 34, 'pref' => '広島県'],
            ['pref_id' => 35, 'pref' => '山口県'],
            ['pref_id' => 36, 'pref' => '徳島県'],
            ['pref_id' => 37, 'pref' => '香川県'],
            ['pref_id' => 38, 'pref' => '愛媛県'],
            ['pref_id' => 39, 'pref' => '高知県'],
            ['pref_id' => 40, 'pref' => '福岡県'],
            ['pref_id' => 41, 'pref' => '佐賀県'],
            ['pref_id' => 42, 'pref' => '長崎県'],
            ['pref_id' => 43, 'pref' => '熊本県'],
            ['pref_id' => 44, 'pref' => '大分県'],
            ['pref_id' => 45, 'pref' => '宮崎県'],
            ['pref_id' => 46, 'pref' => '鹿児島県'],
            ['pref_id' => 47, 'pref' => '沖縄県'],
        ];

        DB::table('prefectures')->insert($prefectures);
    }
}
