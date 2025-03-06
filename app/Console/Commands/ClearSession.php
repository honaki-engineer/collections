<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;

class ClearSession extends Command
{
    /**
     * コマンドのシグネチャ（ターミナルで使う名前）
     *
     * @var string
     */
    protected $signature = 'session:clear';

    /**
     * コマンドの説明
     *
     * @var string
     */
    protected $description = 'Laravelのセッションデータを削除します';

    /**
     * コマンドの実行ロジック
     */
    public function handle()
    {
        // セッションを削除
        Session::flush();

        // ストレージに保存されたセッションファイルを削除（fileドライバ使用時）
        if (config('session.driver') === 'file') {
            $path = storage_path('framework/sessions');
            if (is_dir($path)) {
                array_map('unlink', glob("$path/*"));
            }
        }

        $this->info('✅ セッションがクリアされました。');
    }
}
