<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prefectures;

class PrefectureController extends Controller
{
    public function index()
    {
        $prefectures = Prefectures::all();
        \Log::info('Prefectures:', $prefectures->toArray());
        return response()->json($prefectures);
        //return response()->json(Prefectures::all());
        // $prefecturesの内容をターミナルに表示
    }
}
