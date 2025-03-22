<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearCollectionImages extends Command
{
    // コマンド名（ここが実行時に使うやつ）
    protected $signature = 'storage:clear-collection-images';

    // 説明（`php artisan list` に出るやつ）
    protected $description = 'storage/app/public/collection_images の画像をすべて削除します';

    public function handle()
    {
        // storage/app/public/collection_imagesディレクトリを対象にするため、対象フォルダ名を変数$directoryに保存。
        $directory = 'collection_images';

         // ディレクトリが存在するかチェック
        if(!Storage::disk('public')->exists($directory)) {
            $this->warn("📂 ディレクトリが存在しません: storage/app/public/{$directory}");
            return Command::SUCCESS; // return Command::SUCCESS; = 「うまく終わったよ」の意味。
        }

        // storage/app/public/collection_imagesの中にある全ファイルのパスを配列として取得。
        $files = Storage::disk('public')->files($directory);

        // ファイルが1つも存在しない場合
        if(empty($files)) {
            $this->info("🧼 削除する画像はありませんでした。");
            return Command::SUCCESS;
        }

        // 各ファイルをループで回して、1つずつ削除
        foreach($files as $file) {
            Storage::disk('public')->delete($file);
        }

        $this->info("🗑️ {$directory} 内の画像を削除しました。削除数: " . count($files));

        return Command::SUCCESS;
    }
}
