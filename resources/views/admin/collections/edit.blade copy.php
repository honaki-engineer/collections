<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          ポートフォリオ編集
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
                <section class="text-gray-600 body-font relative">

                    {{-- フォーム --}}
                    <form action="{{ route('collections.update', ['collection' => $collection->id ]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                    <div class="container px-5 mx-auto">
                      <div class="lg:w-1/2 md:w-2/3 mx-auto">
                        <div class="flex flex-wrap -m-2">
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('title')" class="mt-2" />
                              <label for="title" class="leading-7 text-sm text-gray-600">タイトル</label>
                              <input type="text" id="title" name="title" value="{{ $collection->title }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('description')" class="mt-2" />
                              <label for="description" class="leading-7 text-sm text-gray-600">アプリ解説</label>
                              <textarea id="description" name="description" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">{{ $collection->description }}</textarea>
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('url_qiita')" class="mt-2" />
                              <label for="url_qiita" class="leading-7 text-sm text-gray-600">Qiita URL</label>
                              <input type="url" id="url_qiita" name="url_qiita" value="{{ $collection->url_qiita }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('url_webapp')" class="mt-2" />
                              <label for="url_webapp" class="leading-7 text-sm text-gray-600">WebApp URL</label>
                              <input type="url" id="url_webapp" name="url_webapp" value="{{ $collection->url_webapp }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('url_github')" class="mt-2" />
                              <label for="url_github" class="leading-7 text-sm text-gray-600">GitHub URL</label>
                              <input type="url" id="url_github" name="url_github" value="{{ $collection->github }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('is_public')" class="mt-2" />
                              <label for="is_public" class="leading-7 text-sm text-gray-600">公開種別</label>
                              <input type="radio" name="is_public" value="0" @if($collection->is_public === 0) checked @endif>非公開
                              <input type="radio" name="is_public" value="1" @if($collection->is_public === 1) checked @endif>一般公開
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('position')" class="mt-2" />
                              <label for="position" class="leading-7 text-sm text-gray-600">表示優先度</label>
                              <select name="position" id="position" class="rounded-md">
                                <option value="">選択してください</option>
                                <option value="0" @if($collection->position === 0) selected @endif>デフォルト</option>
                                <option value="1" @if($collection->position === 1) selected @endif>1ページ目</option>
                                <option value="2" @if($collection->position === 2) selected @endif>topページ</option>
                              </select>
                            </div>
                          </div>
                          {{-- 画像 --}}
                          <div class="p-2 w-full">
                            <div class="relative">
                                <label class="leading-7 text-sm text-gray-600">画像</label>
                                {{-- 大きなプレビュー画像 --}}
                                <div id="mainImageContainer" class="flex justify-center mt-4 {{ $collection->collection_image->isNotEmpty() ? '' : 'hidden' }}">
                                    <img id="mainImage" class="w-4/5 lg:w-3/5 h-auto object-cover border rounded-lg"
                                         src="{{ $collection->collection_image->isNotEmpty() ? asset('storage/collection_images/' . $collection->collection_image->first()->image_path) : asset('storage/collection_images/noImage.jpg') }}"
                                         alt="メイン画像">
                                </div>
                                {{-- サムネイル一覧 --}}
                                <div class="relative mt-4">
                                    <label class="leading-7 text-sm text-gray-600">サムネイル一覧：</label>
                                    <div id="imagePreviewContainer" class="grid grid-cols-3 gap-3 md:grid-cols-4 lg:grid-cols-5 md:gap-4 w-full place-items-center">
                                        @foreach ($collection->collection_image as $image)
                                            <div class="relative w-24 h-24" data-image-id="{{ $image->id }}">
                                                <img src="{{ asset('storage/collection_images/' . $image->image_path) }}"
                                                     class="w-full h-full object-cover cursor-pointer border border-gray-300 rounded-lg hover:border-indigo-500 transition">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                {{-- 新しい画像アップロード --}}
                                <div class="relative mt-4">
                                    <label class="leading-7 text-sm text-gray-600">新しい画像を追加：</label>
                                    <input multiple type="file" id="image_path" name="image_path[]" class="hidden" accept="image/*">
                                    <br>
                                    <label for="image_path" class="file-upload-btn inline-block px-4 py-1 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded-md shadow-sm cursor-pointer hover:bg-gray-200 active:bg-gray-300 transition">
                                        ファイルを選択
                                    </label>
                                </div>
                            </div>
                          </div>
                        

                          <div class="w-full mt-8">
                            <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    </form>

                  </section>
              </div>
          </div>
      </div>
  </div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // --- 変数の初期化
    let selectedFiles = [];
    const mainImageContainer = document.getElementById("mainImageContainer");
    const mainImage = document.getElementById("mainImage");
    const imageInput = document.getElementById("image_path");
    const imagePreviewContainer = document.getElementById("imagePreviewContainer");
    const noImageSrc = "/storage/collection_images/noImage.jpg";

    // --- 既存画像の設定(クリックイベント & 削除ボタン追加)
    function setupExistingImages() {
        document.querySelectorAll("#imagePreviewContainer div").forEach(imageWrapper => { // imagePreviewContainer内のすべての<div>を取得
            const imageId = imageWrapper.dataset.imageId; // dataset.imageId → data-image-id属性の値を取得
            const img = imageWrapper.querySelector("img"); // imageWrapper内の<img>要素を取得
            const imageSrc = img.src;

            // メイン画像を変更するときに使用
            img.addEventListener("click", function () {
                changeMainImage(imageSrc);
            });

            // 削除ボタン追記
            if (!imageWrapper.querySelector("button")) {
                const removeButton = createDeleteButton(() => { // createDeleteButton関数 = 削除ボタン生成
                    removeExistingImage(imageWrapper, imageId, imageSrc); // removeExistingImage関数 = 既存画像の削除
                });
                imageWrapper.appendChild(removeButton);
            }
        });
    }

    // --- 画像プレビュー表示(新規アップロード時)
    function previewImages(event) {
        console.log("画像選択イベント発火");
        const input = event.target; // どの要素(input type="file")でイベントが発生したかを取得
        const files = input.files; // 選択されたファイルリストを取得。FileListは、input type="file"でユーザーが選択したファイルの一覧を表すオブジェクト。input.filesを取得すると、その中にFileListが入っている。

        if (!files || files.length === 0) {
            console.log("ファイルが選択されていません");
            return;
        }

        // 複数ファイルをinput.filesに保持するための特別なオブジェクト。
        // → DataTransferを使うと「選択済みのファイルに、新しいファイルを追加OK」「削除したいファイルを除外してinput.filesを更新OK」
        // → 通常のinput type="file"では「新しいファイルを選択すると、以前のファイルが上書きされてしまう」「複数のファイルを選択した状態を保持できない」
        let dataTransfer = new DataTransfer();
        // すでに選択されているファイルを`DataTransfer`に追加
        // → 初回は、previewImages()が実行された時点ではselectedFiles(=過去に選択された画像のリスト)は空
        // → 2回目以降のpreviewImages()実行時には、すでに選択されたファイルがselectedFilesに入っている(下にあるselectedFiles.pushで入る)
        selectedFiles.forEach(fileObj => dataTransfer.items.add(fileObj.file)); // fileObj = selectedFilesの各要素(オブジェクト) | fileObj.file = fileObjの中にあるファイル情報(input.files に入れるデータ) | dataTransfer.items.add(fileObj.file) = dataTransferにfileObj.fileを追加

        // 選択されたファイルを配列に変換し、1つずつ処理
        Array.from(files).forEach((file, index) => { // filesは配列のようなオブジェクト(FileList)なので、直接forEach()やmap()を使えないことがある。Array.from(files)を使うとfilesを本物の配列に変換 できる。 | index = 現在の要素が何番目か(0 から始まるインデックス番号)が入る。
            const reader = new FileReader(); // FileReader = ファイルの内容を読み取る
            reader.onload = function(e) { // onload = ファイルの読み込みが完了したときに実行される | e =「イベントオブジェクト」 | e.target.resultにBase64形式のデータが格納される
                const imageId = "new_" + Date.now();
                selectedFiles.push({ id: imageId, file: file, src: e.target.result }); // e.target.result = 読み込んだファイルのデータが入る{今回は、画像のデータURL(reader.readAsDataURL(file);で作る)} | e =「イベントオブジェクト」 | reader.onload = 「ファイルの読み込みが完了したら実行する関数」
                dataTransfer.items.add(file);

                // サムネイルを表示する要素を作成
                const imageWrapper = document.createElement("div");
                imageWrapper.classList.add("relative", "w-24", "h-24");

                // <img> タグを作成し、画像を設定する
                const img = document.createElement("img");
                img.src = e.target.result; // e.target.result = 読み込んだファイルのデータが入る{画像のデータURL(reader.readAsDataURL(file);で作る)}
                img.classList.add("w-full", "h-full", "object-cover", "object-center", "rounded", "cursor-pointer");
                img.onclick = function () {
                    changeMainImage(e.target.result); // メイン画像を変更するときに使用
                };

                // 削除ボタン(×)生成
                const removeButton = createDeleteButton(() => {
                    removeNewImage(imageId, imageWrapper);// 新規アップロード画像の削除
                });

                // サムネイル画像作成
                imageWrapper.appendChild(img);
                imageWrapper.appendChild(removeButton);
                imagePreviewContainer.appendChild(imageWrapper);

                if (selectedFiles.length === 1 || index === 0) { // selectedFiles.length === 1 → 最初の画像 | index === 0 → このループで処理されている最初の画像
                    changeMainImage(e.target.result);
                    mainImageContainer.classList.remove("hidden");
                }
            };
            // readAsDataURL(file) → 画像データをBase64(URL)に変換
            reader.readAsDataURL(file); // これにより、ファイルをサーバーにアップロードせずにブラウザ上でプレビューできる
        });

        input.files = dataTransfer.files;
    }

    // --- 削除ボタン生成(共通)
    function createDeleteButton(removeFunction) {
        const removeButton = document.createElement("button");
        removeButton.textContent = "×";
        removeButton.classList.add("absolute", "top-0", "right-0", "bg-black", "bg-opacity-50", "text-white", "px-2", "py-1", "text-xs", "rounded-full", "hover:bg-opacity-70");
        removeButton.onclick = removeFunction; // removeFunction = 「関数を引数として受け取るための変数」 | removeNewImage()やremoveExistingImage()を入れる箱
        return removeButton;
    }

    // --- 新規アップロード画像の削除
    function removeNewImage(imageId, imageWrapper) {
        console.log(`削除する画像 ID: ${imageId}`);
        // `selectedFiles`から対象の画像以外で再構成(=対象画像を削除)
        selectedFiles = selectedFiles.filter(image => image.id !== imageId); // filter() = 配列の中身を条件で絞り込むメソッド | selectedFilesをimageに代入して、selectedFilesのidを取得しているイメージ

        // `DataTransfer`を作成し、削除後のリストをセット
        let dataTransfer = new DataTransfer();
        selectedFiles.forEach(image => dataTransfer.items.add(image.file));
        imageInput.files = dataTransfer.files;

        imageWrapper.remove(); // imageWrapper = サムネイルと削除ボタンを含むHTML要素
        resetMainImage();
    }

    // --- 既存画像の削除
    function removeExistingImage(imageWrapper, imageId, imageSrc) {
        console.log(`既存画像 ID ${imageId} を削除`);
        imageWrapper.remove();

        // `<form>` を正しく取得
        const form = imageInput.closest("form"); // closest("form") = imageInputから一番近いformを取得 | document.querySelector("form")だと、上から順に見てあったものを取得してしまうため
        if (!form) {
            console.error("❌ フォームが見つかりません！");
            return;
        }

        // 削除する画像のIDが既にhidden input(<input type="hidden">)があるかチェック
        let existingInput = form.querySelector(`input[name="delete_images[]"][value="${imageId}"]`); // querySelector(`input[name="delete_images[]"][value="${imageId}"]`) = 条件に合うもの限定で取得
        if (!existingInput) {
            // hidden inputを追加
            const deleteInput = document.createElement("input");
            deleteInput.type = "hidden";
            deleteInput.name = "delete_images[]";
            deleteInput.value = imageId;

            // `setTimeout()`で確実に追加(フォームの更新タイミングによってはhidden inputが消えてしまう → タイミングを固定させる = 「0ミリ秒後に実行」= 「今の処理(removeExistingImage関数)が終わったらすぐに実行」)
            setTimeout(() => form.appendChild(deleteInput), 0); // setTimeout(…, 0) = 指定した時間後に処理を実行する
            console.log("✅ Hidden input を追加:", deleteInput);
        } else {
            console.log("⚠️ 既にhidden inputがあるため追加しませんでした");
        }

        // 削除した画像がメイン画像ならリセット
        if (mainImage.src === imageSrc) {
            resetMainImage();
        }
    }

    // --- メイン画像のリセット
    function resetMainImage() {
        const allImages = document.querySelectorAll("#imagePreviewContainer img"); // #imagePreviewContainer内にあるすべてのimgタグを取得
        if (allImages.length > 0) { // allImages.length > 0 → サムネイル画像が1つ以上ある場合
            changeMainImage(allImages[0].src);
        } else {
            changeMainImage(noImageSrc);
        }
    }

    // --- メイン画像変更
    function changeMainImage(src) {
        console.log("changeMainImage が実行されました: ", src);
        if (mainImage) {
            mainImage.src = src;
        }
    }

    // 初期設定
    setupExistingImages();
    document.getElementById("image_path").addEventListener("change", previewImages);
});
</script>
</x-app-layout>