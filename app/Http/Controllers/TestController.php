<?php

namespace App\Http\Controllers;

use App\Trade;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class TestController extends Controller
{
    public function category (Request $request)
    {
        $items = [
            [
                'title' => 'เสื้อผ้าแฟชั่น',
                'quantity' => 110,
                'icon' => asset('assets/images/tshirt_1.png')
            ],
            [
                'title' => 'เครื่องสำอางและความงาม',
                'quantity' => 110,
                'icon' => asset('assets/images/makeup.png')
            ],
            [
                'title' => 'บ้านและที่ดิน',
                'quantity' => 110,
                'icon' => asset('assets/images/infrastructure.png')
            ],
            [
                'title' => 'ของเล่น ของสะสม ของที่ระลึก',
                'quantity' => 110,
                'icon' => asset('assets/images/robot.png')
            ],
            [
                'title' => 'แม่และเด็ก',
                'quantity' => 110,
                'icon' => asset('assets/images/maternity.png')
            ],
            [
                'title' => 'ศิลปะหัตถกรรม',
                'quantity' => 110,
                'icon' => asset('assets/images/graphic-tool.png')
            ],
            [
                'title' => 'ของใช้ของตกแต่งบ้าน',
                'quantity' => 110,
                'icon' => asset('assets/images/living-room.png')
            ],
            [
                'title' => 'อาหารและสุขภาพ',
                'quantity' => 110,
                'icon' => asset('assets/images/dish.png')
            ],
            [
                'title' => 'เทคโนโลยี มือถือ คอมพิวเตอร์',
                'quantity' => 110,
                'icon' => asset('assets/images/devices.png')
            ],
            [
                'title' => 'บันเทิง ดนตรีและภาพยนต์',
                'quantity' => 110,
                'icon' => asset('assets/images/clapperboard.png')
            ],
            [
                'title' => 'สัตว์เลี้ยงและอุปกรณ์',
                'quantity' => 110,
                'icon' => asset('assets/images/pawprint.png')
            ],
            [
                'title' => 'โรงแรมและรีสอร์ท',
                'quantity' => 110,
                'icon' => asset('assets/images/hotel.png')
            ],
        ];
        return view('category',['items' => $items]);
    }

    public function shop ()
    {
        return view('shop');
    }
    public function shopShow (Request $request)
    {
        return view('shop_show');
    }
    public function shopEdit (Request $request)
    {
        return view('shop_edit');
    }
    public function shopForm (Request $request)
    {
        return view('shop_form');
    }
    public function notication (Request $request)
    {
        return view('notication');
    }

    public function testLineNotify (Request $request)
    {
        $trade = Trade::with(['seller_by_user','buyer_by_user'])->find(1);
        event(new \App\Events\TradeNotification($trade));
        exit;
        
        $client = new Client();
        try {
            $response = $client->request('POST', 'https://notify-api.line.me/api/notify', [
                    'headers' => [
                        "Authorization" => 'Bearer '.env('TRADE_LINE_NOTIFY_TOKEN'),
                        "Content-type" => 'application/x-www-form-urlencoded',
                        'http_errors' => false
                    ],
                    'form_params' => [
                        'message' => 'เลขที่ 6220 : '.$trade->seller_by_user->first_name.' '.$trade->seller_by_user->last_name.'('.$trade->seller_by_user->code.') โอน '.number_format($trade->total_amount).' เทรดบาทให้ '.$trade->buyer_by_user->first_name.' '.$trade->buyer_by_user->last_name.' *'.$trade->remark
                    ]

                ]
            );
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $response = $e->getResponse();
        }

        $content = json_decode($response->getBody()->getContents(),true);
        dd($content);
    }
}
