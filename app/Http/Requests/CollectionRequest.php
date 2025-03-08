<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

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
            'image_path' => ['nullable'],
            'image_order' => ['nullable'],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $base64Images = session('image_src', []);
        $fileNames = session('file_names', []);
        $imageOrder = session('image_order', []);

        // フォームの hidden input から画像順序データを取得
        if($this->has('image_order')) {
            $imageOrder = json_decode($this->input('image_order'), true);
        }

        if ($this->hasFile('image_path')) {
            $images = $this->file('image_path');

            foreach ($images as $image) {
                $base64Image = 'data:image/' . $image->extension() . ';base64,' . base64_encode(file_get_contents($image->getRealPath()));
                $fileName = $image->getClientOriginalName();

                // 画像の重複登録を防ぐ
                if (!in_array($base64Image, $base64Images)) {
                    $base64Images[] = $base64Image;
                    $fileNames[] = $fileName;

                    // `imageOrder` に `fileName` がすでに存在するかチェック
                    $foundIndex = array_search($fileName, array_column($imageOrder, 'fileName'));

                    if ($foundIndex !== false) {
                        // すでに `imageOrder` に登録済みなら `src` を更新
                        $imageOrder[$foundIndex]['src'] = $base64Image;
                    } else {
                        // 新規画像の場合
                        $imageOrder[] = [
                            'fileName' => $fileName,
                            'src' => $base64Image,
                        ];
                    }
                }
            }
        }

        // セッションに保存
        Session::put('image_src', $base64Images);
        Session::put('file_names', $fileNames);
        Session::put('image_order', $imageOrder);

        parent::failedValidation($validator);
    }
}