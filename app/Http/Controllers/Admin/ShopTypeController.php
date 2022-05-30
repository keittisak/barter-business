<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Libraries\Counter;
use App\Libraries\Image;
use App\ShopType;
use DB;
use Carbon\Carbon;
use DataTables;

class ShopTypeController extends Controller
{
    public function index (Request $request)
    {
        return view('admin.shop_types.index');
    }

    public function data (Request $request)
    {
        $results = ShopType::all();
        return DataTables::of($results)->addIndexColumn()->make(true);
    }

    public function create (Request $request)
    {
        return view('admin.shop_types.form');
    }

    public function store (Request $request)
    {
        if (isset($request->user()->id)) {
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }

        $validate = [
            'name' => [
                'required',
                'max:255'
            ],
            "image" => [
                'required',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ],
            'status' => [
                // 'required',
                'in:active,inactive'
            ],
            'created_by' => [

            ],
            'updated_by' => [
                
            ]
        ];

        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $type = DB::transaction(function() use($request, $data) {
                $image = new Image;
                if( $request->hasFile("image") ) {
                    $file = $request->file("image");
                    $path = 'images/shop_types/'.date('Ymd');
                    $imageUrl = $image->upload($file, $path, 'l');
                    $data['image'] = $imageUrl;
                }
                $type = ShopType::create($data);
                return $type;
                
            });
            return response($type, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function edit (Request $request, $id)
    {
        $shopType = ShopType::findOrFail($id);
        $data = [
            'shopType' => $shopType
        ];
        return view('admin.shop_types.form', $data);
    }

    public function update (Request $request, $id)
    {
        $shopType = ShopType::findOrFail($id);
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
        }

        $validate = [
            'name' => [
                'required',
                'max:255'
            ],
            'status' => [
                // 'required',
                'in:active,inactive'
            ],
            'created_by' => [

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
            $shopType = DB::transaction(function() use($request, $data, $shopType) {
                $currentImage = $shopType->image;
                $image = new Image;
                if( $request->hasFile("image") ) {
                    $file = $request->file("image");
                    $path = 'images/shop_types/'.date('Ymd');
                    $imageUrl = $image->upload($file, $path, 'l');
                    $data['image'] = $imageUrl;
                    if( !empty($currentImage) ) {
                        $currentImageFile = str_replace(env('ASSET_URL').'/storage/','',$currentImage);
                        $image->delete($currentImageFile);
                    }
                }
                $shopType->update($data);
                return $shopType;
                
            });
            return response($shopType, 200);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function destroy (Request $request, $id)
    {
        $shopType = ShopType::findOrFail($id);
        try{
            $currentImage = $shopType->image;
            $image = new Image;
            if( !empty($currentImage) ) {
                $currentImageFile = str_replace(env('ASSET_URL').'/storage/','',$currentImage);
                $image->delete($currentImageFile);
            }
            $shopType->delete();
            return response('','204');
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }
}
