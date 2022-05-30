<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Libraries\Image;

use App\User;
use App\Shop;
use App\Product;
use App\Point;
use DB;

class ProductController extends Controller
{
    public function create (Request $request, $userID, $shopId)
    {
        $shop = User::findOrFail($userID)->shops()->findOrFail($shopId);
        $data = [
            'userID' => $userID,
            'shop' => $shop,
            'product' => null
        ];
        return view('admin.products.form', $data);
    }

    public function store (Request $request, $userID, $shopId)
    {
        $shop = User::findOrFail($userID)->shop()->findOrFail($shopId);
        $request->merge(array('shop_id' => $shop->id));

        if (isset($request->user()->id)) {
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            'name' => [
                'required',
                'max:255'
            ],
            'shop_id' => [
                'required',
                // 'exists:shops,id'
            ],
            'price' => [
                'required',
                'numeric',
                'min:0'
            ],
            'image' => [
                'required',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ],
            'status' => [
                'required',
                'in:for_sale,sold_out'
            ],
            'created_by' => [

            ],
            'updated_by' => [
                
            ]
        ];

        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $product = DB::transaction(function() use($request, $data) {
                if ($request->hasFile('image')) {
                    $image = new Image();
                    $file = $request->file('image');
                    $path = 'images/products/'.date('Ymd');
                    $imageUrl = $image->upload($file, $path, 'l');
                    $data['image'] = $imageUrl;
                }
                $product = Product::create($data);
                return $product;
            });
            return response($product, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function edit (Request $request, $userID, $shopId, $id)
    {
        $shop = User::findOrFail($userID)->shops()->findOrFail($shopId);
        $product = $shop->products()->findOrFail($id);
        $data = [
            'userID' => $userID,
            'shop' => $shop,
            'product' => $product
        ];
        return view('admin.products.form', $data);
    }

    public function update (Request $request, $userID, $shopId, $id)
    {
        $shop = User::findOrFail($userID)->shop()->findOrFail($shopId);
        $product = $shop->products()->findOrFail($id);
        $request->merge(array('shop_id' => $shop->id));
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
        }

        $validate = [
            'name' => [
                'required',
                'max:255'
            ],
            'shop_id' => [
                'required',
                // 'exists:shops,id'
            ],
            'price' => [
                'required',
                'numeric',
                'min:0'
            ],
            'status' => [
                'required',
                'in:for_sale,sold_out'
            ],
            'updated_by' => [
                
            ]
        ];

        if ($request->hasFile('image')) {
            $validate['image'] = [
                'required',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ];
        }

        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $product = DB::transaction(function() use($request, $data, $product) {
                $image = new Image();
                $currentImage = $product->image;
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $path = 'images/products/'.date('Ymd');
                    $imageUrl = $image->upload($file, $path, 'l');
                    $data['image'] = $imageUrl;
                    if( !empty($currentImage) ) {
                        $currentImageFile = str_replace(env('ASSET_URL').'/storage/','',$currentImage);
                        $image->delete($currentImageFile);
                    }
                }
                $product->update($data);
                return $product;
            });
            return response($product, 200);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function destroy (Request $request, $userID, $shopID, $id)
    {
        $user = User::findOrFail($userID);
        $shop = $user->shops()->findOrFail($shopID);
        $product = $shop->products()->findOrFail($id);
        try{
            $product->delete();
            return response('','204');
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }
}
