<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'firstname'=>'admin',
            'lastname'=>'admin',
            'phone'=>'111111111111',
            'email'=>'duvanov@ivit.pro',
            'password'=>bcrypt('123'),
            'status'=>'1',
        ]);
    }
}
