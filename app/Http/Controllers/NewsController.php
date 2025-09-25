<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class NewsController extends Controller
{
            public function index()
    {
        $news = News::orderBy('date', 'desc')->get();
        return response()->json($news);
    }

    /**
     * Store a newly created news in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'links' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
              $path = $request->file('image')->store('news', 'public');
        $validatedData['image'] = 'storage/' . $path;
        }

        $news = News::create($validatedData);

        return response()->json([
            'message' => 'News created successfully',
            'data' => $news
        ], 201);
    }

    /**
     * Display the specified news.
     */
    public function show($id)
    {
        $news = News::find($id);
        
        if (!$news) {
            return response()->json([
                'message' => 'News not found'
            ], 404);
        }

        return response()->json($news);
    }

    /**
     * Update the specified news in storage.
     */
    public function update(Request $request, $id)
    {
        $news = News::find($id);
        
        if (!$news) {
            return response()->json([
                'message' => 'News not found'
            ], 404);
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
                    'links' => 'required|string',
            'description' => 'sometimes|string',
            'date' => 'sometimes|date',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($news->image) {
                
                Storage::delete($news->image);
            }
            
               $path = $request->file('image')->store('news', 'public');
        $validatedData['image'] = 'storage/' . $path;
        }

        $news->update($validatedData);

        return response()->json([
            'message' => 'News updated successfully',
            'data' => $news
        ]);
    }

    /**
     * Remove the specified news from storage.
     */
    public function destroy($id)
    {
        $news = News::find($id);
        
        if (!$news) {
            return response()->json([
                'message' => 'News not found'
            ], 404);
        }

        // Delete associated image
        if ($news->image) {
            Storage::delete($news->image);
        }

        $news->delete();

        return response()->json([
            'message' => 'News deleted successfully'
        ]);
    }
}
