<?php

namespace App\Http\Controllers\Front;

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
    public function index (Request $request) 
    {
        $shop = User::findOrFail($request->user()->id)->shop;
        $data = [
            'shop' => $shop,
            'products' => $shop->products
        ];
        return view('front.products.index', $data);
    }

    public function create (Request $request, $shopID)
    {
        $user = User::findOrFail($request->user()->id);
        $shop = $user->shops()->findOrFail($shopID);
        $data = [
            'shop' => $shop,
            'product' => null
        ];
        return view('front.products.form', $data);
    }

    public function store (Request $request, $shopID)
    {
        $user = User::findOrFail($request->user()->id);
        $shop = $user->shops()->findOrFail($shopID);
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

    public function edit (Request $request, $shopID, $id)
    {
        $user = User::findOrFail($request->user()->id);
        $shop = $user->shops()->findOrFail($shopID);
        $product = $shop->products()->findOrFail($id);
        $data = [
            'shop' => $shop,
            'product' => $product
        ];
        return view('front.products.form', $data);
    }

    public function update (Request $request, $shopID, $id)
    {
        $user = User::findOrFail($request->user()->id);
        $shop = $user->shops()->findOrFail($shopID);
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
                        $currentImageFile = str_replace(url('/').'/storage/','',$currentImage);
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

    public function destroy (Request $request, $shopID, $id)
    {
        $user = User::findOrFail($request->user()->id);
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
