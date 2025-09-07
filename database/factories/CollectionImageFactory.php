<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Mmo\Faker\PicsumProvider;
use Mmo\Faker\UnsplashProvider;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CollectionImage>
 */
class CollectionImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // `faker` に PicsumProvider を追加
        $this->faker->addProvider(new PicsumProvider($this->faker));

        // 1️⃣ 一時ディレクトリに画像をダウンロード
        $tmpPath = $this->faker->picsum(null, 640, 480); // `null` で一時フォルダに保存

        if (!$tmpPath) {
            throw new \Exception('画像が生成されませんでした。ディレクトリの権限を確認してください。');
        }

        // 2️⃣ `storage/app/public/collection_images/` に保存
        $storagePath = time() . uniqid() . '_' . Str::random(5) . '.jpg'; // ランダムなファイル名を作成
        $disk = Storage::disk(config('app.media_disk', 'public')); //  画像保存に使うディスク
        $disk->put('collection_images/' . $storagePath, file_get_contents($tmpPath)); // `storage/app/public/collection_images/` に保存

        return [
            'collection_id' => $this->faker->numberBetween(3, 12),
            'image_path' => $storagePath, // `storage/collection_images/` に保存されたパス
            'position' => 0,
        ];
    }
}
