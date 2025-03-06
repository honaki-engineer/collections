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
            // 'image_path' => ['nullable', 'string'],
            // 'image_order' => ['nullable', 'string'],
        ];
    }

    // protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    // {
    //     if ($this->hasFile('image_path')) {
    //         $images = $this->file('image_path'); // 配列で取得
    //         $base64Images = [];
    //         $fileNames = [];

    //         foreach ($images as $image) {
    //             $base64Images[] = 'data:image/' . $image->extension() . ';base64,' . base64_encode(file_get_contents($image->getRealPath())); // 画像ファイルをBase64エンコードして、HTMLで直接表示できるデータURLに変換
    //             $fileNames[] = $image->getClientOriginalName(); // ファイル名を保存
    //         }

    //         Session::put('image_src', $base64Images); // 複数画像を保存
    //         Session::put('file_names', $fileNames); // ファイル名をセッションに保存
    //     }

    //     parent::failedValidation($validator); // 親クラスのエラーハンドリングを継続
    // }
}
