<?php

namespace App\Http\Controllers;

use App\Jobs\UploadToMinio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\User_images;
use Illuminate\Contracts\Cache\Store;

class UploadController extends Controller
{
    // use ImageTrait;
    public function __construct() {}

    public function index()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        $chunk = $request->file('chunk');
        $chunkNumber = $request->input('chunkNumber');
        $totalChunks = $request->input('totalChunks');
        $filename = $request->input('filename');

        $tempPath = storage_path('app/public'); // Temporary directory
        $filePath = $tempPath . '/' . $filename;

        if (!File::exists($tempPath)) {
            File::makeDirectory($tempPath, 0755, true); // Recursive creation
        }
        $fileName = $filename;
        User_images::create(['image_name' => $fileName]);
        $mode = $chunkNumber == 1 ? 'w' : 'a'; // Write for first chunk, append for others
        file_put_contents($filePath, $chunk->get(), FILE_APPEND);

        UploadToMinio::dispatch($fileName)->delay(now()->addSeconds(10));

        if ($chunkNumber == $totalChunks) {
            // All chunks received, process the complete file
            $finalPath = 'uploads/' . $filename; // Your final storage path
            Storage::move('app/public/recordings/' . $filename, $finalPath); // Move from temp to final location

            return response()->json(['message' => 'File uploaded and processed successfully']);
        }


        return response()->json(['message' => 'Chunk received']);
    }

    public function uploadOnMinio(Request $request)
    {
        $file = $request->file('files');
        $fileName = time() . '_' . $file->getClientOriginalName();

        $uploaded = Storage::disk('minio')->put($fileName, file_get_contents($file), 'public');

        if ($uploaded) {
            $url = Storage::disk('minio')->url($fileName); // Use the correct fileName
            return response()->json(['message' => 'File uploaded successfully', 'url' => $url]);
        }

        return response()->json(['message' => 'File upload failed']);
    }
}
