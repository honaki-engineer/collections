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
                    <form action="{{ route('collections.update', ['collection' => $collection->id ]) }}" method="POST">
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
                          
                          <div class="p-2 w-full">
                            <div class="relative">
                                <label class="leading-7 text-sm text-gray-600">画像</label>
                        
                                <!-- ✅ 大きなプレビュー画像 -->
                                <div id="mainImageContainer" class="flex justify-center mt-4">
                                    <img id="mainImage" class="w-4/5 lg:w-3/5 h-auto object-cover border rounded-lg" 
                                         src="{{ $collection->collection_image->isNotEmpty() ? asset('storage/collection_images/' . $collection->collection_image->first()->image_path) : asset('storage/collection_images/noImage.jpg') }}" 
                                         alt="メイン画像">
                                </div>
                        
                                <!-- ✅ サムネイル一覧 -->
                                <div class="relative mt-4">
                                    <label class="leading-7 text-sm text-gray-600">サムネイル一覧：</label>
                                    <div id="imagePreviewContainer" class="grid grid-cols-3 gap-3 md:grid-cols-4 lg:grid-cols-5 md:gap-4 w-full place-items-center">
                                        @foreach ($collection->collection_image as $image)
                                            <div class="relative w-24 h-24">
                                                <img src="{{ asset('storage/collection_images/' . $image->image_path) }}" 
                                                     class="w-full h-full object-cover cursor-pointer border border-gray-300 rounded-lg hover:border-indigo-500 transition"
                                                     onclick="changeMainImage('{{ asset('storage/collection_images/' . $image->image_path) }}')">
                                                
                                                <!-- ✅ 削除ボタン -->
                                                <button type="button" class="absolute top-0 right-0 bg-black bg-opacity-50 text-white px-2 py-1 text-xs rounded-full hover:bg-opacity-70" 
                                                        onclick="removeExistingImage(this, '{{ $image->id }}')">×</button>
                        
                                                <!-- 削除用の hidden input -->
                                                <input type="hidden" name="delete_images[]" value="" class="delete-image-input">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                        
                                <!-- ✅ 新しい画像アップロード -->
                                <div class="relative mt-4">
                                    <label class="leading-7 text-sm text-gray-600">新しい画像を追加：</label>
                                    <input multiple type="file" id="image_path" name="image_path[]" class="hidden" accept="image/*" onchange="previewImages(event)">
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
document.addEventListener("DOMContentLoaded", function() {
    let selectedFiles = []; // 新規アップロード画像のデータを保持
    const mainImageContainer = document.getElementById("mainImageContainer");
    const mainImage = document.getElementById("mainImage");
    const imageInput = document.getElementById("image_path");

    // --- サムネイル画像のクリックイベントを設定
    function setThumbnailClickEvents() {
        document.querySelectorAll("#imagePreviewContainer img").forEach(img => {
            img.addEventListener("click", function() {
                changeMainImage(this.src);
            });
        });
    }

    // --- 画像プレビュー表示（新規アップロード時）
    function previewImages(event) {
        console.log("画像選択イベント発火");
        const input = event.target;
        const files = input.files;

        if (!files || files.length === 0) {
            console.log("ファイルが選択されていません");
            return;
        }

        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        let dataTransfer = new DataTransfer();

        // 既存の選択済みファイルを維持
        selectedFiles.forEach(fileObj => dataTransfer.items.add(fileObj.file));

        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageId = "new_" + Date.now();
                selectedFiles.push({ id: imageId, file: file, src: e.target.result });
                dataTransfer.items.add(file);

                // サムネイルを作成
                const imageWrapper = document.createElement("div");
                imageWrapper.classList.add("relative", "w-24", "h-24");

                const img = document.createElement("img");
                img.src = e.target.result;
                img.classList.add("w-full", "h-full", "object-cover", "object-center", "rounded", "cursor-pointer");
                img.onclick = function() {
                    changeMainImage(e.target.result);
                };

                // 削除ボタン
                const removeButton = document.createElement("button");
                removeButton.textContent = "×";
                removeButton.classList.add("absolute", "top-0", "right-0", "bg-black", "bg-opacity-50", "text-white", "px-2", "py-1", "text-xs", "rounded-full", "hover:bg-opacity-70");
                removeButton.onclick = function() {
                    removeNewImage(imageId, imageWrapper);
                };

                imageWrapper.appendChild(img);
                imageWrapper.appendChild(removeButton);
                imagePreviewContainer.appendChild(imageWrapper);

                // ✅ 最初の画像ならメイン画像にする（index 0 の画像 = 一番最初に選択された画像）
                if (selectedFiles.length === 1 || index === 0) {
                    changeMainImage(e.target.result);
                    mainImageContainer.classList.remove("hidden");
                }
            };
            reader.readAsDataURL(file);
        });

        input.files = dataTransfer.files;
        setThumbnailClickEvents(); // 新しく追加された画像にもクリックイベントを追加
    }

    // --- 既存の画像を削除
    function removeExistingImage(button, imageId) {
        const imageWrapper = button.parentElement;
        imageWrapper.remove();
        document.querySelector(`input[name="delete_images[]"]`).value = imageId;
    }

    // --- 新しく選択した画像を削除
    function removeNewImage(imageId, imageWrapper) {
        selectedFiles = selectedFiles.filter(image => image.id !== imageId);
        let dataTransfer = new DataTransfer();
        selectedFiles.forEach(image => dataTransfer.items.add(image.file));
        imageInput.files = dataTransfer.files;
        imageWrapper.remove();
    }

    // --- メイン画像変更
    function changeMainImage(src) {
        console.log("changeMainImage が実行されました: ", src);
        if (mainImage) {
            mainImage.src = src; // メイン画像を変更
            console.log("メイン画像のsrcを変更しました:", mainImage.src);
        } else {
            console.error("mainImage が見つかりませんでした。");
        }
    }

    // 初期設定
    setThumbnailClickEvents();
    document.getElementById("image_path").addEventListener("change", previewImages);
});
</script>
</x-app-layout>