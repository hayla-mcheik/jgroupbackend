<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
        public function index()
{
    $team = Team::orderBy('id', 'asc')->get();
    return response()->json([
        'status' => 'success',
        'data' => $team
    ]);
}

    /**
     * Store a newly created technologies in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exp' => 'required|string',
              'company' => 'nullable|string',
              'linkedin' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
$originalName = $request->file('image')->getClientOriginalName();
$path = $request->file('image')->storeAs('technologies', $originalName, 'public');
$validatedData['image'] = 'storage/' . $path;

        $validatedData['image'] = 'storage/' . $path;
        }

        $team = Team::create($validatedData);

        return response()->json([
            'message' => 'team created successfully',
            'data' => $team
        ], 201);
    }

    /**
     * Display the specified technologies.
     */
    public function show($id)
    {
        $team = Team::find($id);
        
        if (!$team) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }

        return response()->json($team);
    }

    /**
     * Update the specified technologies in storage.
     */
    public function update(Request $request, $id)
    {
        $team = Team::find($id);
        
        if (!$team) {
            return response()->json([
                'message' => 'team not found'
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exp' => 'required|string',
              'company' => 'nullable|string',
              'linkedin' => 'nullable|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($team->image) {
                Storage::delete($team->image);
            }
            
  $originalName = $request->file('image')->getClientOriginalName();
$path = $request->file('image')->storeAs('technologies', $originalName, 'public');
$validatedData['image'] = 'storage/' . $path;

        }

        $team->update($validatedData);

        return response()->json([
            'message' => 'team updated successfully',
            'data' => $team
        ]);
    }

    /**
     * Remove the specified technologies from storage.
     */
    public function destroy($id)
    {
        $team = Team::find($id);
        
        if (!$team) {
            return response()->json([
                'message' => 'team not found'
            ], 404);
        }

        // Delete associated image
        if ($team->image) {
            Storage::delete($team->image);
        }

        $team->delete();

        return response()->json(data: [
            'message' => 'team deleted successfully'
        ]);
    }
}
