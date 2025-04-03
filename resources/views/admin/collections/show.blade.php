<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          ポートフォリオ詳細
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
                <section class="text-gray-600 body-font relative">

                    <div class="container px-5 mx-auto">
                      <div class="lg:w-1/2 md:w-2/3 mx-auto">
                        <div class="flex flex-wrap -m-2">
                          <div class="p-2 w-full">
                            <div class="relative">
                              <label for="title" class="leading-7 text-sm text-gray-600">タイトル</label>
                              <div class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $collection->title }}</div>
                            </div>
                          </div>
                          {{-- 技術タグ --}}
                          <div class="p-2 w-full">
                            <div class="relative">
                              <label for="tech_type" class="leading-7 text-sm text-gray-600">技術タグ</label>
                              <div class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out min-h-10">
                                @foreach($collection->technologyTags as $technology_tag)
                                  {{ $technology_tag->name }}@if(!$loop->last),@endif{{-- $loop->lastは「今のループが最後の要素かどうか」を判定 --}}
                                @endforeach
                              </div>
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <label for="description" class="leading-7 text-sm text-gray-600">アプリ解説</label>
                              <div id="description" name="description" class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-6 transition-colors duration-200 ease-in-out break-words overflow-y-auto resize-y h-32">{{ $collection->description}}</div>
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <label for="url_qiita" class="leading-7 text-sm text-gray-600">Qiita URL</label>
                              <div class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out min-h-10">{{ $collection->url_qiita }}
                              </div>
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <label for="url_webapp" class="leading-7 text-sm text-gray-600">WebApp URL</label>
                              <div class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out min-h-10">{{ $collection->url_webapp }}</div>
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <label for="url_github" class="leading-7 text-sm text-gray-600">GitHub URL</label>
                              <div class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out min-h-10">{{ $collection->url_github }}</div>
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <label for="is_public" class="leading-7 text-sm text-gray-600">公開種別</label>
                              <div class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $collection->is_public_label }}</div>
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <label for="position" class="leading-7 text-sm text-gray-600">表示優先度</label>
                              <div class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $collection->position_label }}</div>
                            </div>
                          </div>
                          {{-- 画像 --}}
                          <div class="p-2 w-full">
                            <div class="relative">
                              <label for="image_path" class="leading-7 text-sm text-gray-600">画像</label>
                                @if($collection->collectionImages && $collection->collectionImages->isNotEmpty())
                                  <!-- 大きなプレビュー画像 -->
                                  <div id="mainImageContainer" class="flex justify-center mt-4">
                                      <img id="mainImage" class="w-4/5 lg:w-3/5 h-auto object-cover border rounded-lg" src="{{ asset('storage/collection_images/' . $collection->collectionImages[0]->image_path) }}" alt="メイン画像">
                                  </div>
                                  <!-- サムネイル一覧 -->
                                  <div class="relative mt-4">
                                      <label class="leading-7 text-sm text-gray-600">サムネイル：</label>
                                      <div id="imagePreviewContainer" class="grid grid-cols-3 gap-3 sm:grid-cols-4 sm:gap-4 md:grid-cols-4 md:gap-4 xl:grid-cols-5 xl:gap-5 w-full place-items-center">
                                          @foreach ($collection->collectionImages as $image)
                                              <img src="{{ asset('storage/collection_images/' . $image->image_path) }}" 
                                                  class="w-20 h-20 sm:w-24 sm:h-24 object-cover cursor-pointer border border-gray-300 rounded-lg hover:border-indigo-500 transition"
                                                  onclick="changeMainImage('{{ asset('storage/collection_images/' . $image->image_path) }}')">
                                          @endforeach
                                      </div>
                                  </div>
                                @else
                                  <!-- 大きなプレビュー画像 -->
                                  <div id="mainImageContainer" class="flex justify-center mt-4">
                                    <img id="mainImage" class="w-4/5 lg:w-3/5 h-auto object-cover border rounded-lg" src="{{ asset('storage/collection_images/noImage.jpg') }}" alt="メイン画像">
                                   </div>
                                @endif

                            </div>
                          </div>
                        
                          {{-- ボタンエリア --}}
                          <div class="flex justify-center w-full gap-4 mt-8">
                            {{-- 編集ボタン --}}
                            <form action="{{ route('collections.edit', ['collection' => $collection->id]) }}" method="get">
                            <div class=" w-full">
                              <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">編集</button>
                            </div>
                            </form>
                            {{-- 削除ボタン --}}
                            <form action="{{ route('collections.destroy', ['collection' => $collection->id]) }}" method="post"
                              id="delete_{{ $collection->id }}">
                              @csrf
                              @method('DELETE')
                            <div class="w-full">
                              <a href="#" data-id="{{ $collection->id }}" onclick="DeleteService.confirmAndDelete(this)" {{-- resources/js/services/DeleteService.js --}}
                                class="flex mx-auto text-white bg-pink-500 border-0 py-2 px-8 focus:outline-none hover:bg-pink-600 rounded text-lg">削除</a>
                            </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>

                </section>
              </div>
          </div>
      </div>
  </div>
<script>
// メインプレビュー変更
function changeMainImage(src) {
    document.getElementById("mainImage").src = src; // imgタグのsrc属性(.src)をsrcに変更
}
</script>
</x-app-layout>