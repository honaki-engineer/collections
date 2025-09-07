<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\{DB, Storage};
use Illuminate\Support\Str;

class PruneCollectionImages extends Command
{
    protected $signature = 'storage:prune-collection-images
        {--dir=collection_images : storage/app/public 配下の対象ディレクトリ}
        {--dry-run : 削除せず候補のみ表示}
        {--keep=* : 削除対象から常に除外するファイル名（basename。複数指定可）}';

    protected $description = 'DB未参照の画像を削除（アンダースコア以降の一致のみ）';

    public function handle()
    {
        $disk = Storage::disk(config('app.media_disk', 'public')); # 画像保存に使うディスク
        $dir  = $this->option('dir') ?: 'collection_images';

        if(!$disk->exists($dir)) {
            $this->warn("📂 ディレクトリが見つかりません: storage/app/public/{$dir}");
            return self::SUCCESS;
        }

        // 0) 常に残すファイル（固定）＋ オプション --keep
        $alwaysKeep = [
            'github.png',
            'noimage.jpg',   // 大文字小文字の差異を吸収するため小文字化して扱う
            'qiita.png',
            'webapp.png',
            'youtube.png',
        ];
        $optKeep = array_map('strtolower', (array)$this->option('keep'));
        $keepSet = collect(array_unique(array_merge($alwaysKeep, $optKeep)))
            ->flip(); // ← set化（キー存在でO(1)判定）

        // 1) DB側：参照中パスを取得
        $referenced = DB::table('collection_images')->pluck('image_path')->filter();

        // 2) 「_ の後ろ」一致用セット（ベース名の最初の '_' 以降）
        $suffixSet = $referenced
            ->map(function ($p) {
                $base = basename(ltrim((string)$p, '/'));
                return Str::contains($base, '_') ? Str::after($base, '_') : null;
            })
            ->filter() // フィルター
            ->unique() // 重複禁止
            ->flip(); // v:k

        // 3) 実ファイルを走査し、未参照だけ抽出（ただし保護対象は除外）
        $files = collect($disk->files($dir));
        $cands = [];

        foreach($files as $path) {
            $base = basename($path);
            if(Str::startsWith($base, '.')) continue; // .DS_Store等は無視

            // 🔒 保護リストにあるファイル名はスキップ（大小区別なし）
            if($keepSet->has(strtolower($base))) {
                continue;
            }

            // 「_ の後ろ」一致で参照判定
            $after      = Str::contains($base, '_') ? Str::after($base, '_') : null;
            $matchAfter = $after ? $suffixSet->has($after) : false;

            if(!$matchAfter) { // DB内にファイル名合致がなかったら
                $cands[] = $path; // 未参照（削除候補）
            }
        }

        if($this->option('dry-run')) {
            $this->info('🧪 ドライラン: 削除候補一覧');
            foreach ($cands as $p) $this->line(" - {$p}");
            $this->info('合計: ' . count($cands) . ' ファイル');
            return self::SUCCESS;
        }

        // 4) 削除実行
        foreach($cands as $p) {
            $disk->delete($p);
        }

        $this->info('🗑️ 削除完了: ' . count($cands) . ' ファイル');
        return self::SUCCESS;
    }
}
