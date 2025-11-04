<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        // Ensure that the 'file' and 'type' fields are returned to the frontend
        $news = News::orderBy('date', 'desc')->get();
        return response()->json($news);
    }

    public function store(Request $request)
    {
        // Added 'type' validation just in case, though it's set dynamically
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'links' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            // Allowed mimes include common image and video formats
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,webm|max:20480', // up to 20MB
        ]);

        // Default type
        $type = 'image';

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('news', 'public');

            // Detect file type (image or video) based on MIME type
            $mimeType = $file->getMimeType();
            $type = str_starts_with($mimeType, 'video') ? 'video' : 'image';

            $validatedData['file'] = 'storage/' . $path; // The path to the media file
            $validatedData['type'] = $type; // The media type (image or video)
        } else {
            // Handle case where file is required but somehow missing (should be caught by validation)
            return response()->json(['message' => 'File upload failed'], 400);
        }

        $news = News::create($validatedData);

        return response()->json([
            'message' => 'News created successfully',
            'data' => $news
        ], 201);
    }

    public function show($id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        return response()->json($news);
    }

    public function update(Request $request, $id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'links' => 'sometimes|string',
            'description' => 'sometimes|string',
            'date' => 'sometimes|date',
            'file' => 'sometimes|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,webm|max:20480',
        ]);

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($news->file && Storage::exists(str_replace('storage/', 'public/', $news->file))) {
                Storage::delete(str_replace('storage/', 'public/', $news->file));
            }

            $file = $request->file('file');
            $path = $file->store('news', 'public');

            // Detect file type
            $mimeType = $file->getMimeType();
            $type = str_starts_with($mimeType, 'video') ? 'video' : 'image';

            $validatedData['file'] = 'storage/' . $path;
            $validatedData['type'] = $type;
        }

        $news->update($validatedData);

        return response()->json([
            'message' => 'News updated successfully',
            'data' => $news
        ]);
    }

    public function destroy($id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        if ($news->file && Storage::exists(str_replace('storage/', 'public/', $news->file))) {
            Storage::delete(str_replace('storage/', 'public/', $news->file));
        }

        $news->delete();

        return response()->json(['message' => 'News deleted successfully']);
    }
}
