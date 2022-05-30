<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Libraries\Image;

use App\User;
use App\ShopType;
use App\Shop;
use DB;
use App\ShopImage;

class ShopController extends Controller
{

    public function create(Request $request, $userID)
    {
        $shopTypes = ShopType::all();
        $data = [
            'userID' => $userID,
            'shopTypes' => $shopTypes,
        ];
        return view('admin.shops.form', $data);
    }

    public function store (Request $request, $memberId)
    {
        $user = User::findOrFail($memberId);
        $request->merge(array('user_id' => $user->id));
        if (isset($request->user()->id)) {
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }

        $validate = [
            "name" => [
                "required",
                "unique:shops,name",
            ],
            // "code" => [
            //     "required",
            //     "unique:shops,code",
            // ],
            "user_id" => [
                "required",
                // "unique:shops,user_id",
            ],
            "type_id" => [
                "required",
                "integer",
                "exists:shop_types,id"
            ],
            "description" => [
                "nullable"
            ],
            "image" => [
                "required",
                'mimes:jpeg,bmp,png',
                'max:6000',
            ],
            "images" => [
                "nullable",
            ],
            "images.*" => [
                "nullable",
                'mimes:jpeg,bmp,png',
                'max:6000',
            ],
            "address" => [
                "nullable"
            ],
            'country_id' => [
                'nullable',
                'integer',
                'exists:countries,id'
            ],
            'province_id' => [
                'nullable',
                'integer',
                'exists:provinces,id'
            ],
            'district_id' => [
                'nullable',
                'integer',
                'exists:districts,id'
            ],
            'subdistrict_id' => [
                'nullable',
                'integer',
                'exists:subdistricts,id'
            ],
            'postalcode' => [
                'nullable',
                'integer',
                "digits:5",
                // 'exists:subdistricts,postalcode'
            ],
            // "phone" => [
            //     'required',
            //     'numeric',
            // ],
            "full_address" => [
                "nullable"
            ],
            // "contact_name" => [
            //     'required',
            // ],
            "line_id" => [
                "nullable"
            ],
            "facebook_id" => [
                "nullable"
            ],
            'status' => [
                'in:active,inactive'
            ],
        ];

        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $shop = DB::transaction(function() use($request, $data) {
                if ($request->hasFile('image')) {
                    $image = new Image();
                    $file = $request->file('image');
                    $path = 'images/shops/'.date('Ymd');
                    $imageUrl = $image->upload($file, $path, 'l');
                    $data['image'] = $imageUrl;
                }
                $shop = new Shop();
                $shop = $shop->create($data);
                if ( $request->images ) {
                    $image = new Image();
                    foreach( $request->images as $key => $item ) {
                        if( $request->hasFile("images.{$key}") ) {
                            $file = $request->file("images.{$key}");
                            $path = 'images/shops/'.date('Ymd');
                            $imageUrl = $image->upload($file, $path, 'l');
                            ShopImage::create([
                                'shop_id' => $shop->id,
                                'image' => $imageUrl,
                                'created_by' => $request->user()->id,
                                'updated_by' => $request->user()->id,
                            ]);
                        }
                    }
                }
                return $shop;
            });
            return response($shop, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function show (Request $request, $userID, $id)
    {
        $user = User::findOrFail($userID);
        $shop = $user->shop()->with('shop_type')->findOrFail($id);
        $data = array(
            'userID' => $userID,
            'user' => $user,
            'shop'=> $shop,
            'images' => $shop->images,
            'products' => $shop->products,
        );
        return view('admin.shops.show', $data);
    }

    public function edit (Request $request, $userID, $id)
    {
        $shop = User::findOrFail($userID)->shop()->findOrFail($id);
        $shopTypes = ShopType::all();
        $data = array(
            'userID' => $userID,
            'shop'=> $shop,
            'images' => $shop->images,
            'products' => $shop->products,
            'shopTypes' => $shopTypes
        );
        return view('admin.shops.form', $data);
    }

    public function update (Request $request, $userID, $id)
    {
        $shop = User::findOrFail($userID)->shop()->findOrFail($id);
        $request->merge(array('user_id' => $shop->user_id));
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
        }

        $validate = [
            "name" => [
                "required",
                "unique:shops,name,".$shop->id,
            ],
            // "code" => [
            //     "required",
            //     "unique:shops,code,".$shop->id,
            // ],
            "user_id" => [
                "required",
                // "unique:shops,user_id,".$shop->id,
            ],
            "type_id" => [
                "required",
                "integer",
                "exists:shop_types,id"
            ],
            "description" => [
                "nullable"
            ],
            // "image" => [
            //     "required",
            //     'mimes:jpeg,bmp,png',
            //     'max:5000',
            // ],
            // "images" => [
            //     "nullable",
            // ],
            // "images.*" => [
            //     "nullable",
            //     'mimes:jpeg,bmp,png',
            //     'max:5000',
            // ],
            "address" => [
                "nullable"
            ],
            'country_id' => [
                'nullable',
                'integer',
                'exists:countries,id'
            ],
            'province_id' => [
                'nullable',
                'integer',
                'exists:provinces,id'
            ],
            'district_id' => [
                'nullable',
                'integer',
                'exists:districts,id'
            ],
            'subdistrict_id' => [
                'nullable',
                'integer',
                'exists:subdistricts,id'
            ],
            'postalcode' => [
                'nullable',
                'integer',
                "digits:5",
                // 'exists:subdistricts,postalcode'
            ],
            // "phone" => [
            //     'required',
            //     'numeric',
            // ],
            "full_address" => [
                "nullable"
            ],
            // "contact_name" => [
            //     'required',
            // ],
            "line_id" => [
                "nullable"
            ],
            "facebook_id" => [
                "nullable"
            ],
            'status' => [
                'in:active,inactive'
            ],
        ];

        if ($request->hasFile('image')) {
            $validate['image'] = [
                'required',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ];
        }
        if ( $request->images ) {
            $validate['images'] = [
                'nullable'
            ];
            $validate['images.*'] = [
                // 'required',
                'nullable',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ];
        }

        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $shop = DB::transaction(function() use($request, $data, $shop) {
                $currentImage = $shop->image;
                $image = new Image();
                if ($request->hasFile('image')){
                    $image = new Image();
                    $file = $request->file('image');
                    $path = 'images/shops/'.date('Ymd');
                    $imageUrl = $image->upload($file, $path, 'l');
                    $data['image'] = $imageUrl;
                    
                    if( !empty($currentImage) ) {
                        $currentImageFile = str_replace(env('ASSET_URL').'/storage/','',$currentImage);
                        $image->delete($currentImageFile);
                    }
                }
                $shop->update($data);
                if ( $request->images ) {
                    $shopImageID = [];
                    foreach( $request->images as $key => $item ) {
                        if( $request->hasFile("images.{$key}") ) {
                            $file = $request->file("images.{$key}");
                            $path = 'images/shops/'.date('Ymd');
                            $imageUrl = $image->upload($file, $path, 'l');
                            $shopImage = ShopImage::create([
                                'shop_id' => $shop->id,
                                'image' => $imageUrl,
                                'created_by' => $request->user()->id,
                                'updated_by' => $request->user()->id,
                            ]);
                            $shopImageID[] = $shopImage->id;
                        }
                    }
                    $shopImageID = array_merge($shopImageID, empty($request->image_id) ? array() : $request->image_id );
                    $shopImages = $shop->images()->whereNotIn('id', $shopImageID)->get();
                    foreach( $shopImages as $item ) {
                        if( !empty($item->image) ) {
                            $currentImageFile = str_replace(env('ASSET_URL').'/storage/','',$item->image);
                            $image->delete($currentImageFile);
                        }
                        ShopImage::findOrFail($item->id)->delete();
                    }
                }
                return $shop;
            });
            return response($shop, 200);
        }catch (\Exception $e) {
            // return $e->getMessage();
            return response(['message' => $e->getMessage()],500);
        }
    }
}
