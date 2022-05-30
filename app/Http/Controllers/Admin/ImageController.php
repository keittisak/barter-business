<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Image;

class ImageController extends Controller
{
    public function upload (Request $request)
    {
        $validate = [
            'file' => [
                'required',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ],
        ];
        $request->validate($validate);
        try{
            $image = new Image();
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = 'images/about';
                $imageUrl = $image->upload($file, $path);
                return response($imageUrl,200);
            }
            return response('', 422);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }
}
