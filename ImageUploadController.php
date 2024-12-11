<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;

class ImageUploadController extends Controller
{
    // 画像を保存する処理
    public function store(Request $request)
    {
        $request->validate([
            'case_id' => 'required|integer',
            'picture_type' => 'required|integer',
            'picture' => 'required|image|max:2048',
        ]);

        $imageContent = file_get_contents($request->file('picture')->getRealPath());

        Content::create([
            'case_id' => $request->case_id,
            'picture_type' => $request->picture_type,
            'picture' => $imageContent,
        ]);

        return response()->json(['message' => 'Image uploaded successfully'], 201);
    }

    // 画像を取得する処理
    public function show($case_id, $picture_type)
    {
        $content = Content::where('case_id', $case_id)
                          ->where('picture_type', $picture_type)
                          ->firstOrFail();

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($fileInfo, $content->picture);
        finfo_close($fileInfo);

        return response($content->picture)
            ->header('Content-Type', $mimeType);
    }
}
