<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          ポートフォリオ新規登録
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
                <section class="text-gray-600 body-font relative">

                    {{-- フォーム --}}
                    <form action="{{ route('collections.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                    <div class="container px-5 mx-auto">
                      <div class="lg:w-1/2 md:w-2/3 mx-auto">
                        <div class="flex flex-wrap -m-2">
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('title')" class="mt-2" />
                              <label for="title" class="leading-7 text-sm text-gray-600">タイトル</label>
                              <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('description')" class="mt-2" />
                              <label for="description" class="leading-7 text-sm text-gray-600">アプリ解説</label>
                              <textarea id="description" name="description" value="{{ old('description') }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">{{ old('description') }}</textarea>
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('url_qiita')" class="mt-2" />
                              <label for="url_qiita" class="leading-7 text-sm text-gray-600">Qiita URL</label>
                              <input type="url" id="url_qiita" name="url_qiita" value="{{ old('url_qiita') }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('url_webapp')" class="mt-2" />
                              <label for="url_webapp" class="leading-7 text-sm text-gray-600">WebApp URL</label>
                              <input type="url" id="url_webapp" name="url_webapp" value="{{ old('url_webapp') }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('url_github')" class="mt-2" />
                              <label for="url_github" class="leading-7 text-sm text-gray-600">GitHub URL</label>
                              <input type="url" id="url_github" name="url_github" value="{{ old('url_github') }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('is_public')" class="mt-2" />
                              <label for="is_public" class="leading-7 text-sm text-gray-600">公開種別</label>
                              <input type="radio" name="is_public" value="0" {{ old('is_public') == '0' ? 'checked' : '' }}>非公開
                              <input type="radio" name="is_public" value="1" {{ old('is_public') == '1' ? 'checked' : '' }}>一般公開
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('position')" class="mt-2" />
                              <label for="position" class="leading-7 text-sm text-gray-600">表示優先度</label>
                              <select name="position" id="position" class="rounded-md">
                                <option value="">選択してください</option>
                                <option value="0" {{ old('position') == '0' ? 'selected' : '' }}>デフォルト</option>
                                <option value="1" {{ old('position') == '1' ? 'selected' : '' }}>1ページ目</option>
                                <option value="2" {{ old('position') == '2' ? 'selected' : '' }}>topページ</option>
                              </select>
                            </div>
                          </div>

                          <!-- 画像アップロード -->
                          <div class="p-2 w-full">
                            <div class="relative">
                                <label for="image_path" class="leading-7 text-sm text-gray-600">画像</label>
                                <!-- 見えない input -->
                                <input multiple type="file" id="image_path" name="image_path[]" class="hidden" accept="image/*" onchange="previewImages(event)">
                                <br>
                                <!-- カスタムアップロードボタン -->
                                <label for="image_path" class="file-upload-btn inline-block px-4 py-1 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded-md shadow-sm cursor-pointer hover:bg-gray-200 active:bg-gray-300 transition">
                                  ファイルを選択
                                </label>
                                <!-- サムネイル一覧 -->
                                <div class="relative mt-4">
                                    <label class="leading-7 text-sm text-gray-600">選択した画像：</label>
                                    <div id="imagePreviewContainer" class="grid grid-cols-3 gap-3 md:grid-cols-4 lg:grid-cols-5 md:gap-4 w-full place-items-center">
                                      <!-- 画像プレビューがここに追加される -->
                                    </div>
                                </div>
                                <!-- 大きなプレビュー画像 -->
                                <div id="mainImageContainer" class="flex justify-center mt-4 hidden">
                                    <img id="mainImage" class="w-3/5 h-auto object-cover border rounded-lg" src="" alt="メイン画像">
                                </div>
                            </div>
                          </div>

                          <div class="w-full mt-8">
                              <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                                  新規登録
                              </button>
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
// ⭐️画像プレビュー & 削除機能
document.addEventListener("DOMContentLoaded", function() { // これがないと、HTMLの読み込み前にJavaScriptが実行され、エラーになることがある
    // --- 変数の初期化
    let selectedFiles = []; // 選択した画像のデータを保持(JavaScriptでは、input type="file"のfilesを直接変更できないため、selectedFilesにデータを保持しておく)
    const mainImageContainer = document.getElementById("mainImageContainer"); // 「大きなプレビュー画像」div要素
    const mainImage = document.getElementById("mainImage"); // 「大きなプレビュー画像」img要素
    const imageInput = document.getElementById("image_path"); // <input type="file">

    // --- 画像を選択したらプレビューを表示
    function previewImages(event) {
        console.log("画像選択イベント発火"); // デバッグ用（コンソールにメッセージを出す）
        const input = event.target; // どの要素(input type="file")でイベントが発生したかを取得
        const files = input.files; // 選択されたファイルリストを取得。FileListは、input type="file"でユーザーが選択したファイルの一覧を表すオブジェクト。input.filesを取得すると、その中にFileListが入っている。

        // ファイルがない場合、ここで終了
        if (!files || files.length === 0) { 
            console.log("ファイルが選択されていません");
            return;
        }

        // 画像のサムネイルを表示するためのエリア (<div id="imagePreviewContainer">) を取得
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');

        // 複数ファイルをinput.filesに保持するための特別なオブジェクト。
        // → DataTransferを使うと「選択済みのファイルに、新しいファイルを追加OK」「削除したいファイルを除外してinput.filesを更新OK」
        // → 通常のinput type="file"では「新しいファイルを選択すると、以前のファイルが上書きされてしまう」「複数のファイルを選択した状態を保持できない」
        let dataTransfer = new DataTransfer();

        // すでに選択されているファイルを`DataTransfer`に追加
        // → 初回は、previewImages()が実行された時点ではselectedFiles(=過去に選択された画像のリスト)は空
        // → 2回目以降のpreviewImages()実行時には、すでに選択されたファイルがselectedFilesに入っている(下にあるselectedFiles.pushで入る)
        selectedFiles.forEach(fileObj => dataTransfer.items.add(fileObj.file)); // fileObj = selectedFilesの各要素(オブジェクト) | fileObj.file = fileObjの中にあるファイル情報(input.files に入れるデータ) | dataTransfer.items.add(fileObj.file) = dataTransferにfileObj.fileを追加

        Array.from(files).forEach((file) => { // files(選択されたファイルのリスト)を配列に変換してforEach()で処理
            const reader = new FileReader(); // FileReader = ファイルの内容を読み取る
            reader.onload = function(e) { // onload = ファイルの読み込みが完了したときに実行される | e =「イベントオブジェクト」
                const imageId = "image_" + Date.now(); // 一意のIDを生成、削除時このIDを使って特定の画像を識別
                
                // `selectedFiles`を更新（新しい画像を追加）
                selectedFiles.push({ id: imageId, file: file, src: e.target.result }); // file = input.filesで取得したFileオブジェクト(forEachで回している) | e.target.result = 読み込んだファイルのデータが入る{今回は、画像のデータURL(reader.readAsDataURL(file);で作る)} | e =「イベントオブジェクト」 | reader.onload = 「ファイルの読み込みが完了したら実行する関数」

                // `DataTransfer`に新しく選択した画像を追加(こうすることで、新しい画像を選択しても、前の画像が消えないようにする)
                dataTransfer.items.add(file);

                // サムネイルを表示する要素を作成
                const imageWrapper = document.createElement("div");
                imageWrapper.classList.add("relative", "w-24", "h-24");

                // <img> タグを作成し、画像を設定する
                const img = document.createElement("img");
                img.src = e.target.result;
                img.classList.add("w-full", "h-full", "object-cover", "object-center", "rounded", "cursor-pointer");
                img.id = imageId;
                img.onclick = function() {
                    changeMainImage(e.target.result); // 画像をクリックするとメイン画像を変更
                };

                // 削除ボタンの作成
                const removeButton = document.createElement("button");
                removeButton.textContent = "×";
                removeButton.classList.add("absolute", "top-0", "right-0", "bg-black", "bg-opacity-50", "text-white", "px-2", "py-1", "text-xs", "rounded-full", "hover:bg-opacity-70");
                removeButton.onclick = function() {
                    removeImage(imageId);
                };

                imageWrapper.appendChild(img); // img要素をimageWrapperに追加。これでimageWrapperの中に画像が表示される。
                imageWrapper.appendChild(removeButton); // 画像の横に削除ボタンが表示される
                imagePreviewContainer.appendChild(imageWrapper); // 画面上にプレビューが表示される

                // 画像が1枚目ならメイン画像として表示
                if (selectedFiles.length === 1) {
                    changeMainImage(e.target.result);
                    mainImageContainer.classList.remove("hidden");
                }
            };

            reader.readAsDataURL(file); // FileReaderを使ってfileをbase64形式(画像のデータURL)に変換する
        });

        // input.filesを更新（画像を保持）
        input.files = dataTransfer.files;
    }

    // --- 画像を削除
    function removeImage(imageId) {
        console.log(`画像 ${imageId} を削除`);

        // `selectedFiles`から対象の画像以外で再構成(=対象画像を削除)
        selectedFiles = selectedFiles.filter(image => image.id !== imageId); // filter() = 配列の中身を条件で絞り込むメソッド | selectedFilesをimageに代入して、selectedFilesのidを取得しているイメージ

        // `DataTransfer`を作成し、削除後のリストをセット
        let dataTransfer = new DataTransfer();
        selectedFiles.forEach(image => dataTransfer.items.add(image.file)); // 配列 selectedFilesに保存されているファイルを、DataTransferに追加

        // `input.files`を更新
        imageInput.files = dataTransfer.files;

        // DOMから該当の画像を削除
        const imageElement = document.getElementById(imageId);
        if (imageElement) {
            imageElement.parentElement.remove();
        }

        // メイン画像のリセット（リストの最初の画像をメインにする or 非表示）
        if (selectedFiles.length > 0) {
            changeMainImage(selectedFiles[0].src);
        } else {
            mainImage.src = "";
            mainImageContainer.classList.add("hidden");
        }
    }

    // --- メインプレビュー変更
    function changeMainImage(src) {
        mainImage.src = src; // メイン画像を変更 (mainImage.src = src)。
        mainImageContainer.classList.remove("hidden"); // メイン画像エリアを表示 (classList.remove("hidden"))。
    }

    // --- 画像が選択された時だけプレビューを表示
    document.getElementById("image_path").addEventListener("change", previewImages); // 「ファイルが選択されたときに実行」なのでchange(監視イベント) | previewImages()にするとページが読み込まれた瞬間に即実行となるためNG
});
</script>

</x-app-layout>