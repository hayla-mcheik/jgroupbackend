<?php

namespace App\Http\Controllers;

use App\Models\AboutHome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class AboutHomeController extends Controller
{
        public function index()
    {
        $about = AboutHome::first();     
        if (!$about) {
            return response()->json([
                'message' => 'About information not found'
            ], 404);
        }

        // Ensure image has full URL path
        if ($about->image && !str_starts_with($about->image, 'http')) {
            $about->image = asset($about->image); // converts 'storage/...' to full URL
        }

        return response()->json([
            'about' => $about
        ]);
    }

public function update(Request $request)
{
    $about = AboutHome::firstOrNew();

    $validatedData = $request->validate([
        'description' => 'sometimes|string',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('image')) {
        if ($about->image) {
            // Delete old image (from public disk)
            Storage::disk('public')->delete(str_replace('storage/', '', $about->image));
        }

        // Save image to storage/app/public/about
        $path = $request->file('image')->store('about', 'public');
        $validatedData['image'] = 'storage/' . $path;
    }

    $about->fill($validatedData);
    $about->save();

    // Full URL for frontend (optional)
    $about->image = asset($about->image);

    return response()->json([
        'message' => 'About information updated successfully',
        'about' => $about
    ]);
}
}
