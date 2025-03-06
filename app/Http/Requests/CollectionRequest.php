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
            // 'image_path' => ['required'],
            // 'image_order' => ['nullable', 'string'],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $base64Images = session('image_src', []); // 既存のセッションデータを取得
        $fileNames = session('file_names', []); // 既存のファイル名を取得

        if ($this->hasFile('image_path')) {
            $images = $this->file('image_path'); // 配列で取得

            foreach ($images as $image) {
                $base64Images[] = 'data:image/' . $image->extension() . ';base64,' . base64_encode(file_get_contents($image->getRealPath())); // 画像ファイルをBase64エンコードして、HTMLで直接表示できるデータURLに変換
                $fileNames[] = $image->getClientOriginalName(); // ファイル名を保存
            }

            // 以前のセッションデータを削除してから新しいデータを保存
            Session::put('image_src', $base64Images);
            Session::put('file_names', $fileNames);
        }

        parent::failedValidation($validator); // 親クラスのエラーハンドリングを継続
    }
}
