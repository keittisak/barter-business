<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Sms;
use DB;
use GuzzleHttp\Client;
use Carbon\Carbon;
use DataTables;

class SmsController extends Controller
{
    public function index (Request $request)
    {
        return view('admin.sms.index');
    }

    public function data (Request $request)
    {
        $result = Sms::with(['user']);
        $result->when( !empty($request->created_at), function($q) use ($request){
            $dateExplode = explode(' - ', $request->created_at);
            $startDate = Carbon::createFromFormat('d/m/Y', $dateExplode[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $dateExplode[1])->format('Y-m-d');
            $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
        });
        return DataTables::of($result)->make(true);
    }

    public function form (Request $request)
    {
        return view('admin.sms.form');
    }

    public function sender (Request $request)
    {
        $validate = [
            'message' => [
                'required',
                'max:255'
            ],
            'phones.*.number' => [
                'required',
                'digits:10'
            ]
        ];
        $request->validate($validate);
        try{
            $result = DB::transaction(function() use($request) {
                $phones = [];
                foreach( $request->phones as $item ){
                    $data = [
                        'phone' => $item['number'],
                        'user_id' => ($item['user_id']) ?? null,
                        'message' => $request->message
                    ];
                    Sms::create($data);
                    $phones[] = substr_replace($item['number'], "66", 0, 1);
                }

                $client = new Client();
                $response = $client->request('get', env('MAILBIT_SERVICE_URL').'/pushsms.aspx', [
                    'query' => [
                        'user' => env('MAILBIT_USER'),
                        'password' => env('MAILBIT_PASSWORD'),
                        'msisdn' => implode(',', $phones),
                        'sid' => "Barter BA",
                        'msg' => $request->message,
                        'fl' => 0,
                        'dc' => 8
                    ]
                ]);
                $content = json_decode($response->getBody()->getContents(),true);
                return $content;
            });
            if( $result['ErrorCode'] == 000){
                return response($result, 201);
            }
            return response(['message' => $result['ErrorMessage'], $result], 500);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function checkBalance ()
    {
        $client = new Client();
        $response = $client->request('get', env('MAILBIT_SERVICE_URL').'/CheckBalance.aspx', [
            'query' => [
                'user' => env('MAILBIT_USER'),
                'password' => env('MAILBIT_PASSWORD'),
            ]
        ]);
        // dd($response,$response->getBody()->getContents());
        $content = $response->getBody()->getContents();
        $balance = intval(str_replace('Success#Promotional:','',$content));
        return number_format($balance);
    }
}
