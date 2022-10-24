<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        DB::table('users')->insert([
            'firstName' => 'admin',
            'lastName' => 'admin',
            'phone' => '89999999999',
            'email' => 'duvanov@ivit.pro',
            'password' => bcrypt('123'),
            'status' => '1',
        ]);

    }
}
