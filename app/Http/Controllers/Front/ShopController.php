<?php

namespace App\Http\Controllers\Front;

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
    
    public function category (Request $request)
    {
        $shopType = ShopType::with(['shops' => function($q){
            $q->where('status', 'active');
        }])->get()->sortByDesc( function($q) { return $q->shops->count(); });
        $data = [
            'shopTypes' => $shopType
        ];
        return view('front.shops.category', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $shopType = ShopType::findOrFail($id);
        // dd($shopType);
        $data = [
            'shopType' => $shopType
        ];
        return view('front.shops.index', $data);
    }

    public function show (Request $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $data = [
            'shop' => $shop,
            'user' => $shop->user,
            'images' => $shop->images,
            'products' => $shop->products
        ];
        return view('front.shops.show',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $shopTypes = ShopType::all();
        $data = [
            'shopTypes' => $shopTypes,
        ];
        return view('front.shops.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request->user()->id)) {
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
            $request->merge(array('user_id' => $request->user()->id));
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
            // return $e->getMessage();
            return response(['message' => $e->getMessage()],500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($request->user()->id);
        $shop = $user->shops()->findOrFail($id);
        $shopTypes = ShopType::all();
        $data = [
            'shopTypes' => $shopTypes,
            'shop' => $shop,
            'images' => $shop->images
        ];
        return view('front.shops.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($request->user()->id);
        $shop = $user->shops()->findOrFail($id);
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
            $request->merge(array('user_id' => $request->user()->id));
        }

        $validate = [
            "name" => [
                "required",
                "unique:shops,name,".$shop->id,
            ],
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
                }
                $shop->update($data);
                if( !empty($currentImage) ) {
                    $currentImageFile = str_replace(url('/').'/storage/','',$currentImage);
                    $image->delete($currentImageFile);
                }
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
                            $currentImageFile = str_replace(url('/').'/storage/','',$item->image);
                            $image->delete($currentImageFile);
                        }
                        $image = ShopImage::find($item->id);
                        $image->delete();
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

    public function search (Request $request)
    {
        $validate = [
            'text' => [
                'required'
            ]
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $result = DB::transaction(function() use($request, $data) {
                $result = Shop::where('code', 'like', '%'.$data['text'].'%')->orWhere('phone', 'like', '%'.$data['text'].'%')
                            ->whereHas('user',function($q){
                                $q->where('status','active');
                            });
                if( $result->exists() ) {
                    $result = $result->get();
                } else {
                    $result = User::where('phone', $data['text'])->where('status', 'active');
                    if( $result->exists() ) {
                        $result = array( $result->first()->shop );
                    } else {
                        $result = array();
                    }
                }
                return $result;
            });
            return response($result, 200);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function updateImage (Request $request)
    {
        $types = ShopType::all();
        foreach($types as $type){
            $image = $type->image;
            $type->update(['image' => asset('assets/images/'.$image)]);
        }
    }

}
