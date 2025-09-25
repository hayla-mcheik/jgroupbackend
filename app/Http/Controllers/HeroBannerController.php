<?php

namespace App\Http\Controllers;

use App\Models\HeroBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroBannerController extends Controller
{
    /**
     * Display a single hero banner.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $herobanner = HeroBanner::first();

        // Return a 404 response if no hero banner is found.
        if (!$herobanner) {
            return response()->json([
                'message' => 'Hero banner information not found'
            ], 404);
        }

        return response()->json([
            'herobanner' => $herobanner
        ]);
    }

    /**
     * Update or create the hero banner.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Find the first banner or create a new instance if none exists.
        $herobanner = HeroBanner::firstOrNew();

        // Validate the incoming request data.
        // Updated the max video size to 7MB (7168 KB).
        $validatedData = $request->validate([
            'titleone' => 'sometimes|string|nullable',
            'titletwo' => 'sometimes|string|nullable',
            'titlethree' => 'sometimes|string|nullable',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
            'video' => 'sometimes|file|mimes:mp4,webm,mov|max:7168|nullable',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // If an old image exists, delete it first.
            if ($herobanner->image) {
                Storage::disk('public')->delete(str_replace('storage/', '', $herobanner->image));
            }
            // Store the new image and get its path.
            $path = $request->file('image')->store('herobanner', 'public');
            $validatedData['image'] = 'storage/' . $path;
        }

        // Handle video upload
        if ($request->hasFile('video')) {
            // If an old video exists, delete it first.
            if ($herobanner->video) {
                Storage::disk('public')->delete(str_replace('storage/', '', $herobanner->video));
            }
            // Store the new video and get its path.
            $path = $request->file('video')->store('herobanner', 'public');
            $validatedData['video'] = 'storage/' . $path;
        }

        // Fill the model with validated data and save it.
        $herobanner->fill($validatedData);
        $herobanner->save();

        return response()->json([
            'message' => 'Hero banner information updated successfully',
            'herobanner' => $herobanner
        ]);
    }
}
