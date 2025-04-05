<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('feature_tags')->insert([
            [
                'name' => '認証/ログイン機能',
                'user_id' => 1,
            ],
            [
                'name' => 'CRUD',
                'user_id' => 1,
            ],
            [
                'name' => '画像アップロード',
                'user_id' => 1,
            ]
        ]);
    }
}
