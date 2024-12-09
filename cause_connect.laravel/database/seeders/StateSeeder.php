<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefectures = [
            ['state_id' => 1, 'state_type' => '進行度１'],
            ['state_id' => 2, 'state_type' => '進行度２'],
            ['state_id' => 3, 'state_type' => '進行度３'],
            ['state_id' => 4, 'state_type' => '進行度４'],
        ];

        DB::table('state')->insert($prefectures);
    }
}
