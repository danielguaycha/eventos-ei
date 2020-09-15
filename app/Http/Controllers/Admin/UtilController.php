<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Image;

class UtilController extends Controller
{
    public function showImg($pathFile, $filename, $h=400){
        $path = storage_path('app/public'.DIRECTORY_SEPARATOR.$pathFile.DIRECTORY_SEPARATOR. $filename);

        if (!File::exists($path)) {
            abort(404);
        }
        $type = \Illuminate\Support\Facades\File::mimeType($path);
        $img = Image::cache(function($image) use ($path, $h) {
            return $image->make($path)->resize(null, $h, function ($constraint) {
                $constraint->aspectRatio();
            });
        }, 10);
        $response = Response::make($img, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}
