<?php

namespace App\Http\Controllers;

use App\Models\Locations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class LocationsController extends Controller
{
            public function index()
    {
        $locations = Locations::all();
        return response()->json($locations);
    }

    /**
     * Store a newly created locations in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'description' => 'required|string',
            'position_x' => 'required|string',
            'position_y' => 'required|string',
            'color' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
              $path = $request->file('image')->store('locations', 'public');
        $validatedData['image'] = 'storage/' . $path;    
        }

        $locations = Locations::create($validatedData);

        return response()->json([
            'message' => 'locations created successfully',
            'data' => $locations
        ], 201);
    }

    /**
     * Display the specified locations.
     */
    public function show($id)
    {
        $location = Locations::find($id);
        
        if (!$location) {
            return response()->json([
                'message' => 'locations not found'
            ], 404);
        }

        return response()->json($location);
    }

    /**
     * Update the specified locations in storage.
     */
    public function update(Request $request, $id)
    {
        $locations = Locations::find($id);
        
        if (!$locations) {
            return response()->json([
                'message' => 'locations not found'
            ], 404);
        }

        $validatedData = $request->validate([
         'name' => 'sometimes|string|max:255',
        'phone' => 'nullable|string|max:255',
        'email' => 'nullable|string|max:255',
        'description' => 'sometimes|string',
'position_x' => 'required|numeric|between:0,100',
'position_y' => 'required|numeric|between:0,100',
    'color' => 'required|string',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($locations->image) {
                Storage::delete($locations->image);
            }
            
           $path = $request->file('image')->store('locations', 'public');
        $validatedData['image'] = 'storage/' . $path;
        }

        $locations->update($validatedData);

        return response()->json([
            'message' => 'locations updated successfully',
            'data' => $locations
        ]);
    }

    /**
     * Remove the specified locations from storage.
     */
    public function destroy($id)
    {
        $locations = Locations::find($id);
        
        if (!$locations) {
            return response()->json([
                'message' => 'locations not found'
            ], 404);
        }

        // Delete associated image
        if ($locations->image) {
            Storage::delete($locations->image);
        }

        $locations->delete();

        return response()->json([
            'message' => 'locations deleted successfully'
        ]);
    }
}
