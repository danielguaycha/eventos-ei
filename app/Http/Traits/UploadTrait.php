<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Image;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

trait UploadTrait
{
    public function uploadOne(UploadedFile $uploadedFile,
                              $folder = null,
                              $disk = 'public', $filename = null, $prefix = "")
    {

        $name = !is_null($filename) ? $filename : $prefix . "" . time();

        $file = $uploadedFile->storeAs($folder, $name . '.' . $uploadedFile->getClientOriginalExtension(), $disk);

        return $file;
    }

    public function uploadImg($img, $folder, $width = 300, $height=300){
        ini_set('memory_limit','2048M');
        $name = "$folder/".time().'.'.$img->getClientOriginalExtension();
        $image_resize = Image::make($img->getRealPath());
        $image_resize->resize(null, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $file = $image_resize->save(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR. $name));
        if (File::exists($file->basePath())) {
            return $name;
        }
        return null;
    }
}
