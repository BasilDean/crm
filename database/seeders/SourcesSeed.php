<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SourcesSeed extends Seeder
{
    public function run() {
        DB::table('sources')->insert([
            [
                'title'=>'instagram'
            ],
            [
                'title'=>'viber'
            ],
            [
                'title'=>'website'
            ],
            [
                'title'=>'phone'
            ],
        ]);
    }
}
