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
                    <form id="createForm" action="{{ route('collections.store') }}" method="POST" enctype="multipart/form-data">
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
                              <textarea id="description" name="description" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">{{ old('description') }}</textarea>
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
                                <x-input-error :messages="$errors->get('image_path')" class="mt-2" />
                                <label for="image_path" class="leading-7 text-sm text-gray-600">画像</label>
                                <!-- 見えない input -->
                                <input multiple type="file" id="image_path" name="image_path[]" class="hidden" accept="image/*">
                                <!-- セッションの画像データを送信 -->
                                <input type="hidden" name="session_image_src" value="{{ json_encode(session('image_src', [])) }}">
                                <input type="hidden" name="session_file_names" value="{{ json_encode(session('file_names', [])) }}">
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
// --- UUID(一意の識別子)生成 (1回だけ定義)
window.generateUUID = function() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
};

// セッションから画像データを取得
let sessionImageSrces = {!! json_encode(session('image_src', [])) !!}; 
let sessionFileNames = {!! json_encode(session('file_names', [])) !!};


// ⭐️ 画像プレビュー & 削除機能
document.addEventListener("DOMContentLoaded", function() { // これがないと、HTMLの読み込み前にJavaScriptが実行され、エラーになることがある
    // ✅ 変数の初期化
    let selectedFiles = []; // 選択した画像のデータを保持(JavaScriptでは、input type="file"のfilesを直接変更できないため、selectedFilesにデータを保持しておく)
    const mainImageContainer = document.getElementById("mainImageContainer"); // 「大きなプレビュー画像」div要素
    const mainImage = document.getElementById("mainImage"); // 「大きなプレビュー画像」img要素
    const imageInput = document.getElementById("image_path"); // <input type="file">
    const imagePreviewContainer = document.getElementById("imagePreviewContainer");
    let dataTransfer = new DataTransfer();

    // ✅ セッションから画像を復元
    if (sessionImageSrces.length > 0) {
        console.log("セッションから画像を復元:", sessionImageSrces);
        sessionImageSrces.forEach((sessionImageSrc, index) => {
            let sessionFileName = sessionFileNames[index] || "unknown";
            // ファイルデータとして `DataTransfer` に追加
            let file = new File([sessionImageSrc], sessionFileName, { type: "image/png" });
            dataTransfer.items.add(file);
            previewImages(sessionImageSrc, sessionFileName, true, dataTransfer, null);
        });

        imageInput.files = dataTransfer.files;
    }

    imageInput.addEventListener("change", function(event) {
        console.log("画像選択イベント発火");
        const files = event.target.files;
        if (!files || files.length === 0) return;

        let newDataTransfer = new DataTransfer();
        // selectedFiles.forEach(fileObj => dataTransfer.items.add(fileObj.file));
            // 既存のファイルを DataTransfer に追加（null でないことを確認）
        selectedFiles.forEach(fileObj => {
            if (fileObj.file) { // `file` が null でない場合のみ追加
              newDataTransfer.items.add(fileObj.file);
            }
        });

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
              previewImages(e.target.result, file.name, false, newDataTransfer , file);
            };
            reader.readAsDataURL(file);
        });

        imageInput.files = newDataTransfer.files;

        console.log("🔥 `imageInput.files` の内容:", imageInput.files);
    });

    // ✅ プレビューを表示
    function previewImages(imageSrc, fileName, isSessionImage, dataTransfer, file = null) {
        const imageId = "image_" + Date.now(); // 一意のIDを生成、削除時このIDを使って特定の画像を識別
        fileName = fileName.trim(); // 空白削除(uniqueIdを生成時、無駄なスペースが混ざらないように)
        let uniqueId = fileName + '_' + generateUUID(); // UUID
        // selectedFiles.push({ id: imageId, uniqueId, file: file, src: e.target.result });
        selectedFiles.push({ id: imageId, uniqueId, file: file, src: imageSrc });

        // if (!isSessionImage) {
        //     let file = new File([imageSrc], fileName, { type: "image/png" });
        //     dataTransfer.items.add(file);
        // }
        if (!isSessionImage && file) {
            dataTransfer.items.add(file); // `DataTransfer` に追加
        }


        // サムネイルを表示する要素を作成
        const imageWrapper = document.createElement("div");
        imageWrapper.classList.add("relative", "w-24", "h-24");
        imageWrapper.dataset.imageId = imageId; // dataset にIDをセット
        imageWrapper.dataset.fileName = fileName;  // `fileName` をセット
        imageWrapper.dataset.uniqueId = uniqueId;  // `uniqueId` をセット


        // <img> タグを作成し、画像を設定する
        const img = document.createElement("img");
        img.src = imageSrc;
        img.classList.add("w-full", "h-full", "object-cover", "object-center", "rounded", "cursor-pointer");
        img.id = imageId;
        img.onclick = function() {
            changeMainImage(imageSrc); // 画像をクリックするとメイン画像を変更
        };

        // 削除ボタンの作成
        const removeButton = document.createElement("button");
        removeButton.textContent = "×";
        removeButton.classList.add("absolute", "top-0", "right-0", "bg-black", "bg-opacity-50", "text-white", "px-2", "py-1", "text-xs", "rounded-full", "hover:bg-opacity-70");
        removeButton.onclick = function(event) {
            event.preventDefault(); // ページのリロードを防ぐ
            removeImage(imageId);
        };
        imageWrapper.appendChild(img); // img要素をimageWrapperに追加。これでimageWrapperの中に画像が表示される。
        imageWrapper.appendChild(removeButton); // 画像の横に削除ボタンが表示される
        imagePreviewContainer.appendChild(imageWrapper); // 画面上にプレビューが表示される

        // 削除予定
        // imageInput.files = dataTransfer.files;

        // 追加ごとに大きなプレビューを追加画像に変更
        changeMainImage(imageSrc);
        mainImageContainer.classList.remove("hidden");
    };


    // ✅ 画像を削除
    function removeImage(imageId) {
        console.log(`画像 ${imageId} を削除`);

        // 削除対象の画像情報を取得
        let removedImage = selectedFiles.find(image => image.id === imageId);

        // `selectedFiles`から対象の画像以外で再構成(=対象画像を削除)
        selectedFiles = selectedFiles.filter(image => image.id !== imageId); // filter() = 配列の中身を条件で絞り込むメソッド | selectedFilesをimageに代入して、selectedFilesのidを取得しているイメージ

        // `DataTransfer`を作成し、削除後のリストをセット
        let dataTransfer = new DataTransfer();
        // selectedFiles.forEach(image => dataTransfer.items.add(image.file)); // 配列 selectedFilesに保存されているファイルを、DataTransferに追加
        selectedFiles.forEach(image => {
            if (image.file) { // `file` が null でない場合のみ追加
              dataTransfer.items.add(image.file);
            }
        });

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

        // ✅ セッションの画像を削除するためにサーバーにリクエストを送る
        // if (!removedImage.file) { // ファイルオブジェクトが null ならセッション画像
        //     removeSessionImage(removedImage.src);
        // }
    }

    // ✅ メインプレビュー変更
    function changeMainImage(src) {
        mainImage.src = src; // メイン画像を変更 (mainImage.src = src)。
        mainImageContainer.classList.remove("hidden"); // メイン画像エリアを表示 (classList.remove("hidden"))。
    }

    // ✅ セッション画像を削除するための関数
    // function removeSessionImage(imageSrc) {
    //     console.log("サーバーへセッション画像削除リクエストを送信:", imageSrc);

    //     fetch('/remove-session-image', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRFトークンを設定
    //         },
    //         body: JSON.stringify({ image_src: imageSrc })
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         console.log("サーバーからの応答:", data.message);
    //     })
    //     .catch(error => {
    //         console.error("エラー:", error);
    //     });
    // }

});
</script>

{{----------- サムネイル移動、順番確定 -----------}}
<!-- SortableJSのCDNを追加 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script>
// // --- 画像の並び順を保存
function saveImageOrder() { // 画像の並び順を保存する関数
    let imageOrder = []; // 画像の順番を格納するための空配列を作成

    // 画像の順番を格納するための空配列へ順番に保存
    document.querySelectorAll("#imagePreviewContainer div").forEach((div, index) => { // #imagePreviewContainer内のすべての<div>(画像ラッパー)を取得 | indexは0から順番につく
        const fileName = div.dataset.fileName;
        const uniqueId = div.dataset.uniqueId;
            if (uniqueId) {
                imageOrder.push({fileName, uniqueId, position: index});
            }
    });

    console.log("🚀 送信する並び順:", imageOrder);

    // 既存のhidden inputを削除(重複を防いで、最新の画像順序データだけを送信)
    document.querySelectorAll("input[name='image_order']").forEach(input => input.remove());

    const form = document.getElementById("createForm");
    if (!form) {
        console.error("❌ フォームが見つかりません！");
        return;
    }

    // フォームにhidden inputを追加
    const hiddenInput = document.createElement("input");
    hiddenInput.type = "hidden";
    hiddenInput.name = "image_order";
    hiddenInput.value = JSON.stringify(imageOrder); // オブジェクト配列を文字列化 | valueは文字列しかセットできないので、オブジェクトを文字列にする必要がある
    form.appendChild(hiddenInput);

    console.log("✅ hidden input に保存:", hiddenInput.value);
}

// ----------- SortableJS(ドラッグ&ドロップ)を適用 ----------- 
document.addEventListener("DOMContentLoaded", function () {
  const imagePreviewContainer = document.getElementById("imagePreviewContainer");

  if (!imagePreviewContainer) {
      console.error("❌ imagePreviewContainer が見つかりません！");
      return;
  }

  // --- SortableJS(ドラッグ&ドロップ)を適用
  const sortable = new Sortable(imagePreviewContainer, { // new Sortable()を使ってimagePreviewContainer内の要素をドラッグ&ドロップ可能にする
      animation: 150, // スムーズなアニメーション
      ghostClass: "sortable-ghost", // ドラッグ中のスタイルを変更
      onEnd: function () { // onEndイベント = 要素の移動が確定したときに発火
          saveImageOrder();
      },
  });
});
</script>
</x-app-layout>