<?php

namespace App\Listeners;

use App\Events\TradeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTradeNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TradeNotification  $event
     * @return void
     */
    public function handle(TradeNotification $event)
    {
        if (env('TRADE_NOTIFICATION', 'disabled') !== "enabled"){ return false; } 
        
        $trade = $event->trade;
        \App\Jobs\TradeNotification::dispatch($trade)->onQueue('send_trade_notification');
    }
}
