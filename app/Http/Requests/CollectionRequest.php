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

class CollectionRequest extends FormRequest
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
            'description' => ['nullable', 'string', 'max:10000'],
            'url_qiita' => ['nullable', 'url', 'max:500'],
            'url_webapp' => ['nullable', 'url', 'max:500'],
            'url_github' => ['nullable', 'url', 'max:500'],
            'is_public' => ['required', 'boolean'],
            'position' => ['required', 'integer'],
            'image_path' => ['required_without_all:tmp_images'],
            'tmp_images' => ['required_without_all:image_path'],
            'image_order' => ['nullable'],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // ✅ GDドライバーで ImageManager を作成
        $manager = new ImageManager(new Driver());

        $tmpImagePaths = session('tmp_images', []);
        $fileNames = session('file_names', []);
        $imageOrder = session('image_order', []);

        // フォームの hidden input から画像順序データを取得
        if($this->has('image_order')) {
            $imageOrder = json_decode($this->input('image_order'), true);
        }

        // ✅ `unique_images` の取得を
        if ($this->has('unique_images')) {
            $uniqueImages = is_array($this->input('unique_images'))
                ? $this->input('unique_images')
                : json_decode($this->input('unique_images'), true);
        }

        if($this->hasFile('image_path')) {
            $images = $this->file('image_path');

            foreach ($images as $image) {
                $fileName = $image->getClientOriginalName(); // ファイル名取得
                $extension = strtolower($image->extension()); // 拡張子を取得(小文字変換)
                // $baseFileName = pathinfo($fileName, PATHINFO_FILENAME); // 拡張子なしの `fileName`

                switch ($extension) {
                    case 'png': $encoder = new PngEncoder(9); break;
                    case 'webp': $encoder = new WebpEncoder(80); break;
                    default: $encoder = new JpegEncoder(75);
                }

                // ✅ 画像を圧縮
                $compressedImage = $manager->read($image->getRealPath())->encode($encoder);

                // ✅ 一時ディレクトリに保存（storage/app/public/tmp）
                $tmpImageName = time() .  uniqid() . '_' . $fileName;
                Storage::disk('public')->put("tmp/{$tmpImageName}", (string)$compressedImage);

                // ✅ セッションに画像のパスを保存（画像データではなくパスのみ）
                $tmpImagePaths[] = "tmp/{$tmpImageName}";
                $fileNames[] = $fileName;
                
                // `imageOrder` に `fileName` がすでに存在するかチェック
                $foundIndex = array_search($fileName, array_column($imageOrder, 'fileName'));
                
                if ($foundIndex !== false) {
                    // すでに `imageOrder` に登録済みなら `src` を更新
                    $imageOrder[$foundIndex]['src'] = "tmp/{$tmpImageName}";
                } else {
                    // 新規画像の場合
                    $imageOrder[] = [
                        'fileName' => $fileName,
                        'src' => "tmp/{$tmpImageName}",
                        'position' => count($imageOrder), // 現在のデータ数を position とする
                    ];
                }
            }
        }

        // 🔹 `src` がないエントリーに `tmp_images` から `src` を復元
        foreach($imageOrder as &$image) {
            // dd($imageOrder, $image, $image['fileName'], $fileNames, $tmpImagePaths);
            if(!isset($image['src'])) {
                $foundKey = array_search($image['fileName'], $fileNames);
                if($foundKey !== false) {
                    $image['src'] = $tmpImagePaths[$foundKey] ?? '';
                }
            }
        }

        // セッションに保存
        Session::put('tmp_images', $tmpImagePaths);
        Session::put('file_names', $fileNames);
        Session::put('image_order', $imageOrder);

        parent::failedValidation($validator);
    }
}