<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('collections')->insert([
            [
                'title' => 'タイトル',
                'description' => 'アプリ解説',
                'url_qiita' => 'https://qiita.com/',
                'url_webapp' => null,
                'url_github' => 'https://github.com/',
                'is_public' => 0,
                'position' => 0,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [                
                'title' => 'タイトル2',
                'description' => 'アプリ解説2',
                'url_qiita' => 'https://qiita.com/',
                'url_webapp' => null,
                'url_github' => 'https://github.com/',
                'is_public' => 1,
                'position' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
