<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Balance;
use App\Branch;
use DB;

class BranchController extends Controller
{
    public function index (Request $request)
    {
        $barnch = Branch::with('updated_by_user')->findOrFail(1);
        return view('admin.branchs.index', $barnch);
    }

    public function update (Request $request)
    {
        $barnch = Branch::findOrFail(1);
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            // 'commission' => [
            //     'required',
            //     'numeric',
            //     'min:0'
            // ],
            'trade_fee' =>[
                'required',
                'numeric',
                'min:0'
            ],
            'renewal_fee' => [
                'required',
                'numeric',
                'min:0'
            ],
            'new_member' => [
                'required',
                'numeric',
                'min:0'
            ],
            'recommend' => [
                'required',
                'numeric',
                'min:0'
            ],
            'created_by' => [
                'nullable'
            ],
            'updated_by' => [
                'nullable'
            ],
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $barnch = DB::transaction(function() use($request, $data, $barnch) {
                // $data['commission'] = $data['commission'] / 100;
                $data['trade_fee'] = $data['trade_fee'] / 100;
                $barnch = $barnch->update($data);
                return $barnch;
            });
            return response($barnch, 200);
        } catch (\Exception $e) {   
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function descriptionUpdate (Request $request)
    {
        $barnch = Branch::findOrFail(1);
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            'description' => [
                'nullable'
            ],
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $barnch = DB::transaction(function() use($request, $data, $barnch) {
                $barnch->update($data);
                return $barnch;
            });
            return response($barnch, 200);
        } catch (\Exception $e) {   
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function about (Request $request, $id)
    {
        $barnch = Branch::with('updated_by_user')->findOrFail($id);
        return view('admin.branchs.about', $barnch);
    }

    public function aboutStore (Request $request, $id)
    {
        $barnch = Branch::findOrFail($id);
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            'title' => [
                'required'
            ],
            'description' => [
                'required'
            ],
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $barnch = DB::transaction(function() use($request, $data, $barnch) {
                if( empty($barnch->about) )
                {
                    $jsonData = json_encode([$data]);
                    $barnch->update(['about' => $jsonData]);
                }else{
                    $about = json_decode($barnch->about, true);
                    $i = 0;
                    foreach($about as $key => $item){
                        $jsonData[$key] = $item;
                        $i = $key;
                    }
                    $key++;
                    $jsonData[$key] = $data;
                    $jsonData = json_encode($jsonData);
                    $barnch->update(['about' => $jsonData]);
                }
                return $barnch;
            });
            return response($barnch, 200);
        } catch (\Exception $e) {   
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function aboutUpdate (Request $request, $id, $key)
    {
        $barnch = Branch::findOrFail($id);
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            'title' => [
                'required'
            ],
            'description' => [
                'required'
            ],
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $barnch = DB::transaction(function() use($request, $data, $barnch, $key) {
                $about = json_decode($barnch->about,true);
                if( isset($about[$key]) ){
                    $about[$key] = $data;
                    $jsonData = json_encode($about);
                    $barnch->update(['about' => $jsonData]);
                }
                return $barnch;
            });
            return response($barnch, 200);
        } catch (\Exception $e) {   
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function aboutDelete (Request $request, $id, $key)
    {
        $barnch = Branch::findOrFail($id);
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
        }
        try{
            $barnch = DB::transaction(function() use($request, $barnch, $key) {
                $about = json_decode($barnch->about,true);
                if( isset($about[$key]) ){
                    unset($about[$key]);
                    $jsonData = json_encode($about);
                    $barnch->update(['about' => $jsonData]);
                }
                return $barnch;
            });
            return response($barnch, 200);
        } catch (\Exception $e) {   
            return response(['message' => $e->getMessage()],500);
        }
    }
}
