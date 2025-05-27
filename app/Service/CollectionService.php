<?php
namespace App\Service;

use App\Models\Collection;
use App\Models\CollectionImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManager;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Drivers\Gd\Driver;

class CollectionService
{
    // ⭐️ - 共通 - index - show --------------------------------
    // ✅ 「公開種別」日本語化
    public static function isPublicLabel($collection)
    {
        $collection->is_public_label = $collection->is_public ? '公開' : '非公開'; // trueが１、falseが0

        return $collection;
    }

    // ✅ 「表示優先度」日本語化
    public static function positionLabel($collection)
    {
        $collection->position_label = match ($collection->position) {
            0 => 'デフォルト',
            1 => '高め',
            2 => 'topページ',
        };

        return $collection;
    }

    // ⭐️ - 共通 - create - edit -------------------------------
    // ✅ 技術タグの「ID」に対応する「色分け用のラベル（frontend など）」を、JavaScriptで使える形に変換する関数
    public static function getTechTypeLabelMapForJS($technologyTags)
    {
        // 🔹 tech_type の数値 → ラベルに変換
        $typeMap = [
            0 => 'frontend',
            1 => 'backend',
            2 => 'infra',
            3 => 'build',
            4 => 'tool',
            5 => 'db',
        ];

        // 🔹 tech_type を文字列に変換（JavaScript用マップ）
        $techTypeMapForJS = $technologyTags
        ->pluck('tech_type', 'id')
        ->mapWithKeys(function($v, $k) use ($typeMap) {
            return [(string)$k => $typeMap[$v] ?? 'default'];
        });

        return $techTypeMapForJS;
    }

    // ⭐️ - 共通 - show - edit ---------------------------------
    // ✅ ログインユーザーの(コレクション&画像&技術タグ)テーブルを取得
    public static function getCollectionWithRelations($id)
    {
        /** @var \App\Models\User $user */
        $collection = Auth::user()
            ->collections()
            ->with([
                'collectionImages' => fn($query) => $query->orderBy('position'),
                'technologyTags',
                'featureTags' => fn($query) => $query,
            ])
            ->findOrFail($id);
        
        // 🔹 技術タグを tech_type でグループ化してプロパティに追加
        $collection->groupedTechnologyTags = $collection->technologyTags
            ->sortBy(fn($tag) => $tag->pivot->position) // positionの昇順
            ->groupBy('tech_type')
            ->sortKeys(); // tech_typeグループの昇順

        // 🔹 機能タグをソート
        $collection->sortByFeatureTags = $collection->featureTags
            ->sortBy(fn($tag) => $tag->pivot->position); // positionの昇順

        return $collection;
    }

    // 上記の一般ページ版(publicで使用)
    public static function getCollectionWithRelationsForPublicUser($id)
    {
        $collection = Collection::with([
            'collectionImages' => fn($query) => $query->orderBy('position'),
            'technologyTags',
            'featureTags' => fn($query) => $query->orderBy('name'),
        ])->findOrFail($id);

        // 🔹 技術タグを tech_type でグループ化してプロパティに追加
        $collection->groupedTechnologyTags = $collection->technologyTags
            ->sortBy(fn($tag) => $tag->pivot->position) // positionの昇順
            ->groupBy('tech_type')
            ->sortKeys(); // tech_typeグループの昇順

        // 🔹 機能タグをソート
        $collection->sortByFeatureTags = $collection->featureTags
            ->sortBy(fn($tag) => $tag->pivot->position); // positionの昇順

        return $collection;
    }

    // ⭐️ - 共通 - store - clearSessionImages ------------------
    // ✅ バリデーションエラーがなければセッション画像を削除
    public static function forgetImageSessionData()
    {
        Session::forget('tmp_images');
        Session::forget('file_names');
        Session::forget('image_order');
        Session::forget('collection.form_input');
    }

    // ⭐️ - store ----------------------------------------------
    // ✅ 画像以外のデータを保存
    public static function storeRequest($request)
    {
        $collection = Collection::create([
            'title' => $request->title,
            'description' => $request->description,
            'url_qiita' => $request->url_qiita,
            'url_webapp' => $request->url_webapp,
            'url_github' => $request->url_github,
            'is_public' => $request->is_public,
            'position' => $request->position,
            'private_memo' => $request->private_memo,
            'user_id' => Auth::id(),
        ]);

        // 🔹 技術タグを同期(多対多中間テーブルに保存)
        if ($request->filled('technology_tag_order')) {
            $ids = explode(',', $request->input('technology_tag_order'));
            $pivot = [];
            foreach ($ids as $i => $id) {
                $pivot[$id] = ['position' => $i];
            }
            $collection->technologyTags()->sync($pivot);
        }
        
        // 🔹 機能タグを同期(多対多中間テーブルに保存)
        if($request->filled('feature_tag_order')) {
            $ids = explode(',', $request->input('feature_tag_order'));
            $positions = array_flip($ids); // array_flip = 配列の「キー」と「値」を入れ替える関数
            $pivot = [];
            foreach($request->input('feature_tag_ids', []) as $id) {
                $pivot[$id] = ['position' => $positions[$id] ?? 9999]; // $positions[$id] に、タグIDに対応する並び順(position)が格納 | ?? 9999 は、もし $positions[$id] が存在しない（undefined）場合、position=9999 という「最後尾扱い」にする安全処理
            }
            $collection->featureTags()->sync($pivot);
        } elseif ($request->has('feature_tag_ids')) {
            $collection->featureTags()->sync($request->feature_tag_ids);
        }

        return $collection;
    }

    // ✅ 画像を保存（通常アップロード & セッション画像）
    public static function storeRequestImage($request, $collection)
    {
        // 🔹初期値
        $orderData = json_decode($request->input('image_order'), true) ?? [];
        $sessionTmpImages = $request->input('tmp_images');
        $sessionFileNames = $request->input('session_file_names');

        // 🔹 `ImageManager`を`gd`ドライバー指定で作成
        $manager = new ImageManager(new Driver());

        // 🔹 `image_order` にある画像の最大 position を取得
        $existingPositions = array_column($orderData, 'position');
        $maxPosition = !empty($existingPositions) ? max($existingPositions) + 1 : 0;

        // 🔹 セッション画像の保存（追加画像がある場合も処理）
        if ($sessionTmpImages) {
            foreach ($sessionTmpImages as $index => $tmpImage) {
                // 🔸 'image_path'保存準備
                $imageName = str_replace('tmp/', '', $tmpImage);

                // ファイル名取得
                $parts = explode('_', $imageName);
                $fileName = end($parts);

                // 🔸 'position'取得
                $order = collect($orderData)->first(fn($item) => str_ends_with($item['uniqueId'], $fileName));
                $position = $order ? $order['position'] : $maxPosition++;

                // 🔸 Storage画像保存
                $newPath = str_replace('tmp/', 'collection_images/', $tmpImage);
                Storage::disk('public')->move($tmpImage, $newPath);

                // 🔸 DB に保存
                CollectionImage::create([
                    'collection_id' => $collection->id,
                    'image_path' => $imageName,
                    'position' => $position,
                ]);
            }
        }

        // 🔹 アップロード画像の保存（セッション画像の有無を問わず処理する）
        if ($request->hasFile('image_path')) {
            $uploadedFiles = $request->file('image_path');

            foreach ($uploadedFiles as $imagePath) {
                $fileName = trim($imagePath->getClientOriginalName()); // アップロードファイル名取得
                $order = collect($orderData)->first(fn($item) => str_ends_with($item['uniqueId'], $fileName));
                $position = $order ? $order['position'] : $maxPosition++;
                // $imageName = time() . '_' . uniqid() . '.' . $imagePath->getClientOriginalExtension(); // テーブル保存用
                $baseName = pathinfo($fileName, PATHINFO_FILENAME); // 拡張子を除いたファイル名
                $extension = strtolower($imagePath->extension()); // 元の拡張子
                $imageName = time() . uniqid() . '_' . $baseName . '.' . $extension;

                // 🔸 画像に合わせた拡張子選択
                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        $encoder = new JpegEncoder(75); // JPG / JPEG → 非可逆圧縮
                        break;
                    case 'png':
                        $encoder = new PngEncoder(9); // PNG → 可逆圧縮(高圧縮率)
                        break;
                    case 'webp':
                        $encoder = new WebpEncoder(80); // WebP → 高圧縮率
                        break;
                    case 'avif':
                        $encoder = new JpegEncoder(75); // AVIF → JPEGへ変換(互換性のため)
                        break;
                    default:
                        throw new \Exception('対応していない画像フォーマットです: ' . $extension); // 未対応形式はエラー
                }

                // 🔸 画像を圧縮
                $compressedImage = $manager->read($imagePath->getRealPath())->encode($encoder);

                // 🔸 圧縮画像を保存
                Storage::disk('public')->put('collection_images/' . $imageName, (string) $compressedImage);

                // 🔸 DB に保存
                CollectionImage::create([
                    'collection_id' => $collection->id,
                    'image_path' => $imageName,
                    'position' => $position,
                ]);
            }
        }
    }

    // ⭐️ - update ---------------------------------------------
    // ✅ update(画像以外)
    public static function updateRequest($collection, $request)
    {
        $collection->title = $request->title;
        $collection->description = $request->description;
        $collection->url_qiita = $request->url_qiita;
        $collection->url_webapp = $request->url_webapp;
        $collection->url_github = $request->url_github;
        $collection->is_public = $request->is_public;
        $collection->position = $request->position;
        $collection->private_memo = $request->private_memo;
        
        $collection->save();

        // 🔹 技術タグを同期(多対多中間テーブルを更新)
        $orderedIds = array_filter(explode(',', $request->input('technology_tag_order', '')));
        $syncData = [];
        foreach($orderedIds as $position => $tagId) { // position順に変更
            $syncData[$tagId] = ['position' => $position];
        }
        $collection->technologyTags()->sync($syncData); // sync = 中間テーブルの関連データを丸ごと上書き
        
        // 🔹 機能タグも position 順に保存
        if($request->filled('feature_tag_order')) {
            $ids = explode(',', $request->input('feature_tag_order'));
            $positions = array_flip($ids); // array_flip = 「キー」と「値」を逆にする関数
            $pivot = [];
            foreach($request->input('feature_tag_ids', []) as $id) {
                $pivot[$id] = ['position' => $positions[$id] ?? 9999]; // 位置が不明なら末尾扱い
            }
            $collection->featureTags()->sync($pivot); // sync = 中間テーブルの「関係を上書き」するメソッド
        } else {
            $collection->featureTags()->sync($request->input('feature_tag_ids', []));
        }
    }

    // ✅ 削除リクエストがある場合、該当画像を削除
    public static function deleteRequestImage($request)
    {
        if ($request->delete_images) {
            foreach ($request->delete_images as $imageId) {
                $image = CollectionImage::find($imageId);
                if ($image) {
                    Storage::delete('public/collection_images/' . $image->image_path);
                    $image->delete();
                }
            }
        }
    }

    // ✨✅ 追加画像保存、既存画像position変更
    public static function updateRequestImage($request, $collection)
    {
        // 🔹 追加画像あり
        if ($request->hasFile('image_path')) {
            // 🔸 初期設定
            $uploadedFiles = $request->file('image_path');
            $orderData = json_decode($request->input('image_order'), true) ?? [];
            $imageIdMap = [];
            $manager = new ImageManager(new Driver()); // ImageManager初期化

            // 🔸 圧縮、保存処理
            foreach ($uploadedFiles as $index => $imagePath) {
                // ▫️ 追加画像のファイル名を生成
                $fileName = trim($imagePath->getClientOriginalName()); // ファイル名
                $baseName = pathinfo($fileName, PATHINFO_FILENAME); // 拡張子を除いたファイル名
                $extension = strtolower($imagePath->extension()); // 元の拡張子
                $imageName = time() . uniqid() . '_' . $baseName . '.' . $extension;

                // 🟣 エンコーダー選択
                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        $encoder = new JpegEncoder(75);
                        break;
                    case 'png':
                        $encoder = new PngEncoder(9);
                        break;
                    case 'webp':
                        $encoder = new WebpEncoder(80);
                        break;
                    case 'avif':
                        $encoder = new JpegEncoder(75);
                        break;
                    default:
                        throw new \Exception('対応していない画像フォーマットです: ' . $extension);
                }

                // 🟣 画像圧縮処理
                $compressedImage = $manager->read($imagePath->getRealPath())->encode($encoder);

                // 🟣 保存
                \Storage::disk('public')->put("collection_images/{$imageName}", (string) $compressedImage);

                // 🟣 追加画像のposition確定
                $order = !empty($fileName) ? collect($orderData)->first(fn($item) => str_starts_with($item['uniqueId'], $fileName)) : null;

                // データベースに保存
                $image = CollectionImage::create([
                    'collection_id' => $collection->id,
                    'image_path' => $imageName,
                    'position' => $order ? $order['position'] : 0,
                ]);
            }

            // 🔸 既存画像position更新
            if (isset($orderData)) {
                foreach ($orderData as $order) {
                    // 既存画像か新規画像かを判、既存画像ならテーブルidがある、新規の場合は'null'が入ってる
                    $imageId = $order['id'] === 'null' ? null : $order['id']; // JavaScript側で文字列化のため → hiddenInput.value = JSON.stringify(imageOrder); // オブジェクト配列を文字列化 | valueは文字列しかセットできないので、オブジェクトを文字列にする必要がある

                    if ($imageId !== null) {
                        CollectionImage::where('id', $imageId)->update(['position' => $order['position']]);
                    }
                }
            }
        }

        // 🔹 追加画像なし、既存position更新
        if (!$request->hasFile('image_path') && $request->filled('image_order')) {
            $imageOrders = json_decode($request->input('image_order'), true); // JSONを配列に変換
            if (is_array($imageOrders)) {
                foreach ($imageOrders as $order) {
                    CollectionImage::where('id', $order['id'])->update(['position' => $order['position']]);
                }
            }
        }
    }

    // ⭐️ - removeSessionImage ---------------------------------
    // ✅ 特定のセッション画像を削除するメソッド
    public static function removeSessionImage(string $tmpImage): bool
    {
        // セッションから現在の画像データを取得
        $sessionTmpImages = Session::get('tmp_images', []);
        $sessionFileNames = Session::get('file_names', []);

        // `/storage/` がついていたら取り除く
        $tmpImage = str_replace('/storage/', '', $tmpImage);

        // 対象画像のインデックスを探す
        $index = array_search($tmpImage, $sessionTmpImages);

        if ($index !== false) {
            // 配列から削除
            unset($sessionTmpImages[$index]);
            unset($sessionFileNames[$index]);

            // 配列のインデックスを詰め直して保存
            Session::put('tmp_images', array_values($sessionTmpImages));
            Session::put('file_names', array_values($sessionFileNames));

            // ストレージから物理削除
            if (Storage::disk('public')->exists($tmpImage)) {
                Storage::disk('public')->delete($tmpImage);
            }

            return true;
        }

        return false;
    }

    // ⭐️ - storeSessionWithImage ------------------------------
    // ✅ フォームの入力内容をセッションに保存して、技術タグ一覧ページへリダイレクトする処理
    public static function storeSessionWithImage($request): void
    {
        // 🔹 初期値
        $formInput = $request->except('image_path');
        $imageOrderInput = [];

        // 🔹 セッション初期値
        $tmpImagePaths = Session::get('tmp_images', []);
        $fileNames = Session::get('file_names', []);
        $imageOrder = [];

        // 🔹 image_orderをマッピング形式に変換
        if ($request->filled('image_order')) {
            $decoded = json_decode($request->input('image_order'), true);
            if (is_array($decoded)) {
                $imageOrderInput = collect($decoded)->keyBy('uniqueId')->toArray();
            }
        }

        // 🔹 Intervention Image 初期化
        $manager = new ImageManager(new Driver());

        // 🔹 アップロード画像処理
        if ($request->hasFile('image_path')) {
            foreach ($request->file('image_path') as $image) {
                $fileName = $image->getClientOriginalName();

                if (in_array($fileName, $fileNames)) {
                    continue;
                }

                $extension = strtolower($image->getClientOriginalExtension());
                $encoder = match ($extension) {
                    'jpg', 'jpeg' => new JpegEncoder(75),
                    'png' => new PngEncoder(9),
                    'webp' => new WebpEncoder(80),
                    default => new JpegEncoder(75),
                };

                $compressedImage = $manager->read($image->getRealPath())->encode($encoder);
                $tmpImageName = time() . uniqid() . '_' . $fileName;
                Storage::disk('public')->put("tmp/{$tmpImageName}", (string) $compressedImage);

                $tmpImagePaths[] = "tmp/{$tmpImageName}";
                $fileNames[] = $fileName;

                $matched = collect($imageOrderInput)->first(fn($item) => str_ends_with($item['uniqueId'], $fileName));

                $imageOrder[] = [
                    'fileName' => $fileName,
                    'src' => "tmp/{$tmpImageName}",
                    'position' => $matched['position'] ?? count($imageOrder),
                    'uniqueId' => $matched['uniqueId'] ?? uniqid() . '_' . $fileName,
                ];
            }
        }

        // 🔹 既存セッション画像の順序復元
        foreach ($tmpImagePaths as $index => $path) {
            $fileName = $fileNames[$index] ?? null;
            if (!$fileName) {
                continue;
            }

            $matched = collect($imageOrderInput)->first(fn($item) => str_ends_with($item['uniqueId'], $fileName));

            if ($matched) {
                $imageOrder[] = [
                    'fileName' => $fileName,
                    'src' => $matched['src'] ?? $path,
                    'position' => $matched['position'] ?? $index,
                    'uniqueId' => $matched['uniqueId'] ?? uniqid() . '_' . $fileName,
                ];
            }
        }

        // 🔹 並び順でソート & 重複排除
        $imageOrder = collect($imageOrder)->keyBy('uniqueId')->sortBy('position')->values()->all();

        // 🔹 セッション保存
        Session::put('tmp_images', $tmpImagePaths);
        Session::put('file_names', $fileNames);
        Session::put('image_order', $imageOrder);
        Session::put('collection.form_input', $formInput);

        // 🔹 技術タグの並び順もセッションに保存
        if ($request->has('technology_tag_order')) {
            Session::put('collection.form_input.technology_tag_order', explode(',', $request->technology_tag_order));
        }
    }
}
?>
