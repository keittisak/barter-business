<?php

use Illuminate\Database\Seeder;
use App\Libraries\Counter;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\UserType::create(['name' => 'standard']);
        // \App\UserType::create(['name' => 'bussines']);

        $counter = new Counter();
        $users = array(
            [
                'phone' => '0934381629',
                'email' => 'bbg@email.com',
                'password' =>  \Hash::make('123456'),
                'first_name' => 'Barterbusinessglobal',
                'last_name' => '',
                'type' => 1,
                'credit_total_amount' => 0,
                'trade_fee' => 2,
                'expired_at' => Carbon::now()->addYears(1)->format('Y-m-d H:i:s')
            ]
        );
        $roleAdmin = \App\Role::where('name', 'admin')->first();
        $roleCustomer = \App\Role::where('name', 'member')->first();
        foreach( $users as $key => $user ) {
            $code = $counter->next('user','code');
            $user['code'] = $code;
            $result = \App\User::create($user);
            \App\Balance::create([
                'point_id' => 1,
                'user_id' => $result->id,
                'total_amount' => 0,
            ]);
            if( in_array($key, [0]) ) {
                $result->roles()->attach($roleAdmin);
            }
            $result->roles()->attach($roleCustomer);
        }
    }
}
