<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        try {
            // Check for file in different field names
            $file = null;
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
            } elseif ($request->hasFile('filepond')) {
                $file = $request->file('filepond');
            } elseif ($request->hasFile('upload')) {
                $file = $request->file('upload');
            } elseif ($request->files->count() > 0) {
                // Get the first uploaded file
                foreach ($request->files->all() as $uploadedFile) {
                    if (is_array($uploadedFile)) {
                        $file = reset($uploadedFile);
                    } else {
                        $file = $uploadedFile;
                    }
                    if ($file) break;
                }
            }

            if (!$file) {
                return response()->json(['error' => 'No file uploaded'], 400);
            }

            // Ensure uploads/temp directory exists
            if (!Storage::disk('public')->exists('uploads/temp')) {
                Storage::disk('public')->makeDirectory('uploads/temp', 0755, true);
            }

            // Create filename with timestamp to prevent conflicts
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . Str::slug($originalName) . '.' . $extension;
            
            // Store the file
            $path = Storage::disk('public')->putFileAs(
                'uploads/temp',
                $file,
                $filename
            );

            // Return success response with the stored path
            return response()->json([
                'path' => $path,
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'url' => Storage::disk('public')->url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }
}
