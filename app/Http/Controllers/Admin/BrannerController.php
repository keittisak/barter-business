<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Libraries\Counter;
use App\Libraries\Image;
use App\Branner;
use DB;
use Carbon\Carbon;
use DataTables;

class BrannerController extends Controller
{
    public function index (Request $request)
    {
        if( !isset($request->after_login) ) { abort(404); }
        return view('admin.branners.index');
    }

    public function data (Request $request)
    {
        $results = Branner::when( !empty($request->after_login), function($q) use ($request){ $q->where('after_login', $request->after_login);  })->get();
        return DataTables::of($results)->addIndexColumn()->make(true);
    }

    public function create (Request $request)
    {
        if( !isset($request->after_login) ) { abort(404); }
        return view('admin.branners.form');
    }

    public function store (Request $request)
    {
        if (isset($request->user()->id)) {
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }

        $validate = [
            'description' => [
                'nullable',
            ],
            "image" => [
                'required',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ],
            'after_login' => [
                'required',
                'in:n,y'
            ],
            'created_by' => [

            ],
            'updated_by' => [
                
            ]
        ];

        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $branner = DB::transaction(function() use($request, $data) {
                $image = new Image;
                if( $request->hasFile("image") ) {
                    $file = $request->file("image");
                    $path = 'images/branners/'.date('Ymd');
                    $imageUrl = $image->upload($file, $path, 'l');
                    $data['image'] = $imageUrl;
                }
                $branner = Branner::create($data);
                return $branner;
                
            });
            return response($branner, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function edit (Request $request, $id)
    {
        if( !isset($request->after_login) ) { abort(404); }
        $branner = Branner::findOrFail($id);
        $data = [
            'branner' => $branner
        ];
        return view('admin.branners.form', $data);
    }

    public function update (Request $request, $id)
    {
        $branner = Branner::findOrFail($id);
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
        }

        $validate = [
            'description' => [
                'nullable',
            ],
            'created_by' => [

            ],
            'after_login' => [
                'required',
                'in:n,y'
            ],
            'updated_by' => [
                
            ]
        ];
        if( $request->hasFile("image") ) {
            $validate['image'] = [
                'required',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ];
        }

        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $branner = DB::transaction(function() use($request, $data, $branner) {
                $currentImage = $branner->image;
                $image = new Image;
                if( $request->hasFile("image") ) {
                    $file = $request->file("image");
                    $path = 'images/branners/'.date('Ymd');
                    $imageUrl = $image->upload($file, $path, 'l');
                    $data['image'] = $imageUrl;
                    if( !empty($currentImage) ) {
                        $currentImageFile = str_replace(env('ASSET_URL').'/storage/','',$currentImage);
                        $image->delete($currentImageFile);
                    }
                }
                $branner->update($data);
                return $branner;
                
            });
            return response($branner, 200);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function destroy (Request $request, $id)
    {
        $branner = Branner::findOrFail($id);
        try{
            $currentImage = $branner->image;
            $image = new Image;
            if( !empty($currentImage) ) {
                $currentImageFile = str_replace(env('ASSET_URL').'/storage/','',$currentImage);
                $image->delete($currentImageFile);
            }
            $branner->delete();
            return response('','204');
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }
}
