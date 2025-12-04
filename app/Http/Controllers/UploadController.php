<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    /**
     * Upload a single image
     *
     * POST /api/upload/image
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB
            'folder' => 'nullable|string|in:events,venues,users',
        ]);

        $folder = $request->input('folder', 'images');
        $file = $request->file('image');

        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Store file to public disk
        $path = $file->storeAs($folder, $filename, 'public');

        // Generate URL from public disk
        $url = Storage::disk('public')->url($path);

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully.',
            'data' => [
                'filename' => $filename,
                'path' => $path,
                'url' => $url,
                'full_url' => $url,
            ],
        ], 201);
    }

    /**
     * Upload multiple images
     *
     * POST /api/upload/gallery
     */
    public function uploadGallery(Request $request): JsonResponse
    {
        $request->validate([
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'folder' => 'nullable|string|in:events,venues,users',
        ]);

        $folder = $request->input('folder', 'images');
        $uploadedFiles = [];

        foreach ($request->file('images') as $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($folder, $filename, 'public');
            $url = Storage::disk('public')->url($path);

            $uploadedFiles[] = [
                'filename' => $filename,
                'path' => $path,
                'url' => $url,
                'full_url' => $url,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => count($uploadedFiles) . ' images uploaded successfully.',
            'data' => $uploadedFiles,
        ], 201);
    }

    /**
     * Delete an image
     *
     * DELETE /api/upload/image
     */
    public function deleteImage(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $path = $request->input('path');

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Image not found.',
        ], 404);
    }
}
