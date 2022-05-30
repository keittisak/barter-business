<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
        ]);
        \App\Role::create([
            'name' => 'member',
            'display_name' => 'Member',
        ]);
    }
}
