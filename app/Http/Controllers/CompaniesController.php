<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class CompaniesController extends Controller
{
    public function index()
{
    $projects = Companies::orderBy('created_at', 'desc')->get();

    return response()->json([
        'status' => 'success',
        'data' => $projects
    ]);
}

    /**
     * Store a newly created projects in storage.
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
       $path = $request->file('image')->store('projects', 'public');
        $validatedData['image'] = 'storage/' . $path;
        }

        $projects = Companies::create($validatedData);

        return response()->json([
            'message' => 'Projects created successfully',
            'data' => $projects
        ], 201);
    }

    /**
     * Display the specified projects.
     */
    public function show($id)
    {
        $project = Companies::find($id);
        
        if (!$project) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }

        return response()->json($project);
    }

    /**
     * Update the specified projects in storage.
     */
    public function update(Request $request, $id)
    {
        $projects = Companies::find($id);
        
        if (!$projects) {
            return response()->json([
                'message' => 'Projects not found'
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
            if ($projects->image) {
                Storage::delete($projects->image);
            }
            
          $path = $request->file('image')->store('projects', 'public');
        $validatedData['image'] = 'storage/' . $path;
        }

        $projects->update($validatedData);

        return response()->json([
            'message' => 'projects updated successfully',
            'data' => $projects
        ]);
    }

    /**
     * Remove the specified projects from storage.
     */
    public function destroy($id)
    {
        $projects = Companies::find($id);
        
        if (!$projects) {
            return response()->json([
                'message' => 'projects not found'
            ], 404);
        }

        // Delete associated image
        if ($projects->image) {
            Storage::delete($projects->image);
        }

        $projects->delete();

        return response()->json([
            'message' => 'projects deleted successfully'
        ]);
    }
}
