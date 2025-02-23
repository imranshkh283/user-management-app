<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

trait ImageTrait
{
    /**
     * Rename image with time
     *
     * @param Request $request
     * @return UploadedFile
     */
    public function renameImageWithTime(Request $request)
    {
        if ($request instanceof Request) {

            $files = $request->allFiles();
            $fileNames = array_keys($files)[0];

            $image = $request->file($fileNames);

            $name = str_replace('.' . $image->getClientOriginalExtension(), '', $image->getClientOriginalName());
            $fileExtension = $image->getClientOriginalExtension();
            $newFileName = time() . '_' . $name . '.' . $fileExtension;

            $newImage = new UploadedFile(
                $image->getRealPath(),
                $newFileName,
                $image->getMimeType(),
                $image->getSize(),
                null,
                true

            );
        }
        return $newImage;
    }
}
