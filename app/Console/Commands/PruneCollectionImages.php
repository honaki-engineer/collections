<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\{DB, Storage};
use Illuminate\Support\Str;

class PruneCollectionImages extends Command
{
    protected $signature = 'storage:prune-collection-images
        {--dir=collection_images : storage/app/public 配下の対象ディレクトリ}
        {--dry-run : 削除せず候補のみ表示}';

    protected $description = 'DB未参照の画像を削除（完全一致 or アンダースコア以降の一致のみ）';

    public function handle()
    {
        $disk = Storage::disk('public');          // = storage/app/public
        $dir  = $this->option('dir');             // collection_images

        if(!$disk->exists($dir)) {
            $this->warn("📂 ディレクトリが見つかりません: storage/app/public/{$dir}");
            return self::SUCCESS;
        }

        // 1) DB側：参照中パスを取得
        $referenced = DB::table('collection_images')
            ->pluck('image_path')
            ->filter();

        // 2) 「_ の後ろ」一致用セット（ベース名の最初の '_' 以降）
        $suffixSet = $referenced
            ->map(function ($p) {
                $base = basename(ltrim((string)$p, '/')); // /を削除
                return Str::contains($base, '_') ? Str::after($base, '_') : null; // ファイル名の最初の _ より後ろだけを取り出す
            })
            ->filter() // 空や偽の値を除外
            ->unique() // 重複禁止
            ->flip(); // 値とキーを反転

        // 3) 実ファイルを捜査して未参照だけ抽出
        $files = collect($disk->files($dir)); //.  collection_image だけを取得
        $cands = [];

        foreach($files as $path) {
            $base  = basename($path); // basename('/var/www/img/photo.webp');     // 'photo.webp'
            if(Str::startsWith($base, '.')) continue; // .DS_Store 等は無視

            // 「_ の後ろ」一致
            $after       = Str::contains($base, '_') ? Str::after($base, '_') : null;
            $matchAfter  = $after ? $suffixSet->has($after) : false; // 画像を照合

            if(!$matchAfter) {
                $cands[] = $path; // 未参照（削除候補） =.  現在未使用と思われるファイ = 削除
            }
        }

        if($this->option('dry-run')) {
            $this->info('🧪 ドライラン: 削除候補一覧');
            foreach($cands as $p) $this->line(" - {$p}");
            $this->info('合計: ' . count($cands) . ' ファイル');
            return self::SUCCESS;
        }

        // 5) 削除実行
        foreach($cands as $p) {
            $disk->delete($p);
        }

        $this->info('🗑️ 削除完了: ' . count($cands) . ' ファイル');
        return self::SUCCESS;
    }
}
