<?php

use Illuminate\Database\Seeder;
use App\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::create([
            'name' => 'master',
            'commission' => 0.03,
            'trade_fee' => 0.03,
            'renewal_fee' => 200,
            'new_member' => 3000,
            'recommend' => 3000,
        ]);
    }
}
