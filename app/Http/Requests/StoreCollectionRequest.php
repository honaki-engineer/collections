<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;

class StoreCollectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:50'],
            'technology_tag_ids' => ['nullable', 'array'],
            'technology_tag_ids.*' => ['integer', 'exists:technology_tags,id'], // 配列の中の各要素に対してこのバリデーションルールを適用
            'technology_tag_order.*' => ['integer', 'exists:technology_tag_order,id'],
            'feature_tag_ids' => ['nullable', 'array'],
            'feature_tag_ids.*' => ['integer', 'exists:feature_tags,id'],
            'feature_tag_order' => ['nullable', 'string'],
            'description' => ['nullable', 'string', 'max:10000'],
            'url_qiita' => ['nullable', 'url', 'max:500'],
            'url_webapp' => ['nullable', 'url', 'max:500'],
            'url_github' => ['nullable', 'url', 'max:500'],
            'url_youtube' => ['nullable', 'url', 'max:500'],
            'is_public' => ['required', 'boolean'],
            'position' => ['required', 'integer'],
            'image_path' => ['required_without_all:tmp_images'],
            'image_path.*' => ['image', 'mimes:jpeg,jpg,png,webp,avif', 'max:10240'],
            'tmp_images' => ['required_without_all:image_path'],
            'image_order' => ['nullable'],
            'private_memo' => ['nullable', 'string', 'max:10000'],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // ✅ GDドライバーで ImageManager を作成
        $manager = new ImageManager(new Driver());

        // ✅ セッション取得
        $tmpImagePaths = session('tmp_images', []);
        $fileNames = session('file_names', []);
        $imageOrder = session('image_order', []);

        // ✅ フォームのhidden inputから画像順序データを取得
        if ($this->has('image_order')) {
            $imageOrder = json_decode($this->input('image_order'), true);
        }

        // ✅ アップロードされた画像を圧縮し、一時ディレクトリに保存し、そのパスをセッションに記録しながら、画像の並び順(imageOrder)も管理
        if ($this->hasFile('image_path')) {
            // 🔹 リクエストで送信されたimage_pathのファイル取得
            $images = $this->file('image_path');

            // 🔹 アップロードされた画像を処理し、圧縮して一時保存し、セッションに保存
            foreach ($images as $image) {
                // 🔹 ファイル名 & 拡張子を取得
                $fileName = $image->getClientOriginalName(); // ファイル名取得
                $extension = strtolower($image->extension()); // 拡張子を取得(小文字変換)

                // 🔹 画像のエンコーダーを設定(圧縮率を決定)
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

                // 🔹 画像を圧縮
                $compressedImage = $manager->read($image->getRealPath())->encode($encoder);

                // 🔹 画像保存に使うディスク
                $disk = Storage::disk(config('app.media_disk', 'public'));

                // 🔹 一時ディレクトリに保存(storage/app/public/tmp)
                $tmpImageName = time() . uniqid() . '_' . $fileName;
                $disk->put("tmp/{$tmpImageName}", (string) $compressedImage);

                // 🔹 セッションに画像のパスを保存(画像データではなくパスのみ)
                $tmpImagePaths[] = "tmp/{$tmpImageName}";
                $fileNames[] = $fileName;

                // 🔹 `imageOrder`に`fileName`がすでに存在するかチェック
                $foundIndex = array_search($fileName, array_column($imageOrder, 'fileName'));

                // 🔹 画像の順序を維持しながら、新規画像を追加または既存の画像を更新
                if ($foundIndex !== false) {
                    // すでに`imageOrder`に登録済み
                    $imageOrder[$foundIndex]['src'] = "tmp/{$tmpImageName}";
                } else {
                    // 新規画像の場合
                    $imageOrder[] = [
                        'fileName' => $fileName,
                        'src' => "tmp/{$tmpImageName}",
                        'position' => count($imageOrder), // imageOrderの配列の長さ(count($imageOrder))にして、新規画像が最後尾に追加されるようにしている。
                    ];
                }
            }
        }

        // ✅ imageOrderの中でsrc(画像パス)が存在しない場合に、それを復元する
        foreach ($imageOrder as &$image) {
            // &$image = ループ内で$imageを変更すると $imageOrderに反映される(参照渡し)
            if (!isset($image['src'])) {
                $foundKey = array_search($image['fileName'], $fileNames); // 見つかった場合 → そのインデックスを$foundKeyに格納。 例)$foundKey = 0;
                if ($foundKey !== false) {
                    $image['src'] = $tmpImagePaths[$foundKey] ?? '';
                }
            }
        }

        // ✅ セッションに保存
        Session::put('tmp_images', $tmpImagePaths);
        Session::put('file_names', $fileNames);
        Session::put('image_order', $imageOrder);

        parent::failedValidation($validator);
    }
}
