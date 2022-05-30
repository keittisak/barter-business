<?php

use Illuminate\Database\Seeder;
use App\Point;

class PointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Point::create([
            'name' => 'G point',
            'total_amount' => 0,
            'trade_total_amount' => 0
        ]);
    }
}
