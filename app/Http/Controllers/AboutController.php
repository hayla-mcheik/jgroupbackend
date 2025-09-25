<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
        public function index()
    {
        $about = About::first();     
        if (!$about) {
            return response()->json([
                'message' => 'About information not found'
            ], 404);
        }


        return response()->json([
            'about' => $about
        ]);
    }

public function update(Request $request)
{
    $about = About::firstOrNew();

    $validatedData = $request->validate([
        'description' => 'sometimes|string',
        'mission' => 'sometimes|string',
        'vision' => 'sometimes|string',
    ]);
    $about->fill($validatedData);
    $about->save();

    return response()->json([
        'message' => 'About information updated successfully',
        'about' => $about
    ]);
}
}
