<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


class UploadToMinio implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fileName;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    public function handle()
    {
        Log::info("Job started for file: " . $this->fileName);

        $userImage = DB::table('user_img')
            ->where('image_name', $this->fileName)
            ->where('is_upload', '0')
            ->orderBy('id', 'desc')
            ->first();

        if (!$userImage) {
            Log::error("File not found in database or already uploaded: " . $this->fileName);
            return;
        }

        $fileName = $userImage->image_name;
        $filePath = storage_path('app/public/' . $fileName);
        Log::info("File Path: " . $filePath);

        if (!File::exists($filePath)) {
            Log::error("File not found on the server: " . $filePath);
            return;
        }

        try {
            // Upload file to MinIO
            Log::info("Uploading to MinIO...");
            $fileContents = Storage::get('public/' . $fileName);
            $newFileName = time() . '_' . $fileName;
            $uploaded = Storage::disk('minio')->put($newFileName, $fileContents, 'public');

            if ($uploaded) {
                Log::info("File uploaded successfully to MinIO: " . $newFileName);

                // Update `is_upload` flag in the database
                DB::table('user_img')
                    ->where('id', $userImage->id)
                    ->update(['is_upload' => 1]);

                // Delete the file from local storage
                Storage::delete('public/' . $userImage->image_name);
                Log::info("Local file deleted: " . $userImage->image_name);
            } else {
                Log::error("File upload to MinIO failed: " . $fileName);
            }
        } catch (\Exception $e) {
            Log::error("Error uploading file to MinIO: " . $e->getMessage());
        }
    }
}
