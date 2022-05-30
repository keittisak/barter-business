<?php

namespace App\Jobs;

use App\Trade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;

class TradeNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $trade;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Trade $trade)
    {
        $this->trade = $trade;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $trade = $this->trade;
        $client = new Client();
        $response = $client->request('POST', 'https://notify-api.line.me/api/notify', 
            [
                'headers' => [
                    "Authorization" => 'Bearer '.env('TRADE_LINE_NOTIFY_TOKEN'),
                    "Content-type" => 'application/x-www-form-urlencoded',
                    'http_errors' => false
                ],
                'form_params' => [
                    'message' => 'เลขที่ '.$trade->id.' : '.$trade->seller_by_user->first_name.' '.$trade->seller_by_user->last_name.'('.$trade->seller_by_user->code.') โอน '.number_format($trade->total_amount).' เทรดบาทให้ '.$trade->buyer_by_user->first_name.' '.$trade->buyer_by_user->last_name.' *'.$trade->remark
                ]

            ]
        );
    }
}
