<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technologies;
use Illuminate\Support\Facades\Storage;

class TechnologiesController extends Controller
{
    public function index()
{
    $technologies = Technologies::orderBy('created_at', 'desc')->get();

    return response()->json([
        'status' => 'success',
        'data' => $technologies
    ]);
}

    /**
     * Store a newly created technologies in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string',
              'links' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
             $path = $request->file('image')->store('technologies', 'public');
        $validatedData['image'] = 'storage/' . $path;
        }

        $technologies = Technologies::create($validatedData);

        return response()->json([
            'message' => 'technologies created successfully',
            'data' => $technologies
        ], 201);
    }

    /**
     * Display the specified technologies.
     */
    public function show($id)
    {
        $project = Technologies::find($id);
        
        if (!$project) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }

        return response()->json($project);
    }

    /**
     * Update the specified technologies in storage.
     */
    public function update(Request $request, $id)
    {
        $technologies = Technologies::find($id);
        
        if (!$technologies) {
            return response()->json([
                'message' => 'technologies not found'
            ], 404);
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'color' => 'sometimes|string',
            'links' => 'nullable|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($technologies->image) {
                Storage::delete($technologies->image);
            }
            
          $path = $request->file('image')->store('technologies', 'public');
        $validatedData['image'] = 'storage/' . $path;
        }

        $technologies->update($validatedData);

        return response()->json([
            'message' => 'technologies updated successfully',
            'data' => $technologies
        ]);
    }

    /**
     * Remove the specified technologies from storage.
     */
    public function destroy($id)
    {
        $technologies = Technologies::find($id);
        
        if (!$technologies) {
            return response()->json([
                'message' => 'technologies not found'
            ], 404);
        }

        // Delete associated image
        if ($technologies->image) {
            Storage::delete($technologies->image);
        }

        $technologies->delete();

        return response()->json([
            'message' => 'technologies deleted successfully'
        ]);
    }
}
