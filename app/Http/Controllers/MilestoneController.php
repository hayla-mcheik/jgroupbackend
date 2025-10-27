<?php

namespace App\Http\Controllers;

use App\Models\Milestones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class MilestoneController extends Controller
{
    public function index()
{
    $milestone = Milestones::orderBy('id', 'asc')->get();
    return response()->json($milestone);
}

    /**
     * Store a newly created milestone in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
                 $path = $request->file('image')->store('milestone', 'public');
        $validatedData['image'] = 'storage/' . $path;
        }

        $milestone = Milestones::create($validatedData);

        return response()->json([
            'message' => 'milestone created successfully',
            'data' => $milestone
        ], 201);
    }

    /**
     * Display the specified milestone.
     */
    public function show($id)
    {
        $milestone = Milestones::find($id);
        
        if (!$milestone) {
            return response()->json([
                'message' => 'milestone not found'
            ], 404);
        }

        return response()->json($milestone);
    }

    /**
     * Update the specified milestone in storage.
     */
    public function update(Request $request, $id)
    {
        $milestone = Milestones::find($id);
        
        if (!$milestone) {
            return response()->json([
                'message' => 'milestone not found'
            ], 404);
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'date' => 'sometimes|date',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($milestone->image) {
                Storage::delete($milestone->image);
            }
            
       $path = $request->file('image')->store('milestone', 'public');
        $validatedData['image'] = 'storage/' . $path;
        }

        $milestone->update($validatedData);

        return response()->json([
            'message' => 'milestone updated successfully',
            'data' => $milestone
        ]);
    }

    /**
     * Remove the specified milestone from storage.
     */
    public function destroy($id)
    {
        $milestone = Milestones::find($id);
        
        if (!$milestone) {
            return response()->json([
                'message' => 'milestone not found'
            ], 404);
        }

        // Delete associated image
        if ($milestone->image) {
            Storage::delete($milestone->image);
        }

        $milestone->delete();

        return response()->json([
            'message' => 'milestone deleted successfully'
        ]);
    }
}
