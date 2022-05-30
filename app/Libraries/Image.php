<?php

namespace App\Libraries;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use ImageLibrary;

class Image
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function upload($file, $path = 'images', $size = null)
    {
        $filename = md5(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . microtime()).'.'.$file->getClientOriginalExtension();
        $image = ImageLibrary::make($file);

        if($size == 'l'){
            $image->resize(480, 480, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        
        if (!file_exists(public_path('/images'))) {
            mkdir(public_path('/images'), 0777);
        }
        $image->save(public_path('images/'.$filename));
        $savedImageUri = $image->dirname.'/'.$image->basename;
        $image->stream();
        $storageImageUri = $path.'/'.$filename;

        Storage::disk('public')->put($storageImageUri, $image);

        $image->destroy();
        unlink($savedImageUri);

        $url = Storage::url($storageImageUri);
        return asset($url);


        // $image = $image->save($storagePath.'/'.$filename);

        // return asset('storage/'.$path.'/'.$filename);

        // $image = Storage::disk('public')->put($path, $file);
        // $url = Storage::url($image);
        // return asset($url);
    }

    public function delete($file)
    {
        Storage::disk('public')->delete($file);
    }

}