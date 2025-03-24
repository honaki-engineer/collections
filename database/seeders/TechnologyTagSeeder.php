<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechnologyTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('technology_tags')->insert([
            [
                'name' => 'PHP',
                'tech_type' => 0,
                'user_id' => 1,
            ],
            [
                'name' => 'Laravel',
                'tech_type' => 1,
                'user_id' => 1,
            ],
            [
                'name' => 'Xserver',
                'tech_type' => 2,
                'user_id' => 1,
            ]
        ]);
    }
}
