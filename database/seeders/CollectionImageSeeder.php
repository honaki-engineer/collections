<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CollectionImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ✅ --- 1~6のファイル ---
        // 🔹 初期設定
        $defaultImages = ['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg'];
        $copiedImageNames = []; // ランダムなファイル名を保持する配列
        
        // 🔹 指定した画像を、ランダムなファイル名でpublic/image/からstorage/collection_images/にコピーする処理
        foreach($defaultImages as $originalFileName) {
            $publicPath = public_path("image/{$originalFileName}"); // public/image/元画像パス
            $newFileName = time() . uniqid() . '_' . $originalFileName; // ランダムな保存名を生成（元ファイル名の前に付ける）
            $destinationPath = storage_path("app/public/collection_images/{$newFileName}"); // storage/collection_images/保存先パス
        
            // 🔹 まだファイルがなければコピー
            if(!File::exists($destinationPath)) { 
                File::copy($publicPath, $destinationPath);
            }

            // 🔹 ファイル名を保存
            $copiedImageNames[] = $newFileName;
        }


        // ✅ --- noImage.jpg ---
        // 🔹 指定した画像を、ランダムなファイル名でpublic/image/からstorage/collection_images/にコピーする処理
        $noImagePublicPath = public_path('image/noImage.jpg'); // `public/image/noImage.jpg`、 public/image/元画像パス
        $noImageStoragePath = storage_path('app/public/collection_images/noImage.jpg'); // storage/collection_images/保存先パス
        if (!File::exists($noImageStoragePath)) { File::copy($noImagePublicPath, $noImageStoragePath); } // まだファイルがなければコピー


        // ✅ --- アイコンファイル(Demo、GitHub、) ---
        // 🔹 初期設定
        $defaultImages = ['github.png', 'qiita.png', 'webApp.png'];
                
        // 🔹 public/image/からstorage/collection_images/にコピーする処理
        foreach($defaultImages as $defaultImage) {
            $publicPath = public_path("image/{$defaultImage}"); // public/image/元画像パス
            $destinationPath = storage_path("app/public/collection_images/{$defaultImage}");
        
            // 🔹 まだファイルがなければコピー
            if(!File::exists($destinationPath)) { 
                File::copy($publicPath, $destinationPath);
            }
        }

        

        // × 本番環境で使用しない
        // ✅ --- collection_imagesテーブルにデータを一括挿入する処理 ---
        // DB::table('collection_images')->insert([
        //     [
        //         'collection_id' => 1,
        //         'image_path' => $copiedImageNames[0],
        //         'position' => 0,
        //     ],
        //     [
        //         'collection_id' => 1,
        //         'image_path' => $copiedImageNames[1],
        //         'position' => 1,
        //     ],
        //     [
        //         'collection_id' => 1,
        //         'image_path' => $copiedImageNames[2],
        //         'position' => 2,
        //     ],
        //     [
        //         'collection_id' => 2,
        //         'image_path' => $copiedImageNames[3],
        //         'position' => 0,
        //     ],
        //     [
        //         'collection_id' => 2,
        //         'image_path' => $copiedImageNames[4],
        //         'position' => 1,
        //     ],
        //     [
        //         'collection_id' => 2,
        //         'image_path' => $copiedImageNames[5],
        //         'position' => 2,
        //     ]
        // ]);
    }
}
