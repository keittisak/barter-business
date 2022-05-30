<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Billing;
use Carbon\Carbon;
use App\Libraries\Counter;

class AddBillingUserExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:add-bill-user-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add billing user expire';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::whereHas('roles', function($q){
                    $q->where('name','member');
                })
                ->whereHas('billings', function($q){
                    $q->whereDate('created_at', '<', Carbon::now()->toDateString());
                    $q->whereNotIn('status',['paid','unpaid','cancel']);
                })
                ->whereDate('expired_at', Carbon::now()->addDays(1)->toDateString())
                ->get();
        $counter = new Counter();
        foreach($users as $user){
            $code = $counter->generateCode('bab');
            $data['code'] = $code;
            Billing::create([
                'code' => $code,
                'total_amount' => 600,
                'user_id' => $user->id,
                'remark' => 'ค่าธรรมเนียม ราย 6 เดือน',
                'status' => 'pending'
            ]);
        }
    }
}
