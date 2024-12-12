<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel;

class RequestController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|integer',
            'case_name' => 'required|string|max:255',
            'achieve' => 'required|string',
            'lower_limit' => 'required|integer|min:1',
            'upper_limit' => 'required|integer|min:1',
            'case_date' => 'required|date',
            'start_activty' => 'required|integer',
            'end_activty' => 'required|integer',
            'address_id' => 'required|integer',
            'equipment' => 'nullable|string',
            'area_id' => 'nullable|integer',
            'theme_id' => 'nullable|integer',
            'rec_age_id' => 'nullable|integer',
            'feature_id' => 'nullable|integer',
            'area_detail' => 'nullable|string',
            'content' => 'nullable|string',
            'contents' => 'nullable|string',
            'state_id' => 'required|integer',
        ]);

        $newRequest = RequestModel::create($validatedData);

        return response()->json(['message' => '依頼が投稿されました', 'data' => $newRequest], 201);
    }
}
