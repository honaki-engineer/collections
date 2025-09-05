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
                                    {{-- タイトル --}}
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="title" class="leading-7 text-sm text-gray-600">タイトル</label>
                                            <div
                                                class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                {{ $collection->title }}</div>
                                        </div>
                                    </div>

                                    {{-- 画像 --}}
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="image_path" class="leading-7 text-sm text-gray-600">画像</label>
                                            @if ($collection->collectionImages && $collection->collectionImages->isNotEmpty())
                                                <!-- サムネイル一覧 -->
                                                <div class="relative ">
                                                    {{-- <label class="leading-7 text-sm text-gray-600">サムネイル：</label> --}}
                                                    <div id="imagePreviewContainer"
                                                        class="grid grid-cols-3 gap-3 sm:grid-cols-4 sm:gap-4 md:grid-cols-4 md:gap-4 xl:grid-cols-5 xl:gap-5 w-full place-items-center">
                                                        @foreach ($collection->collectionImages as $image)
                                                            <img src="{{ asset('storage/collection_images/' . $image->image_path) }}"
                                                                data-src="{{ asset('storage/collection_images/' . $image->image_path) }}"
                                                                class="thumbnail w-20 h-20 sm:w-24 sm:h-24 object-cover cursor-pointer border border-gray-300 rounded-lg hover:shadow-lg transition"
                                                                onclick="changeMainImage('{{ asset('storage/collection_images/' . $image->image_path) }}')">
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <!-- 大きなプレビュー画像 -->
                                                <div id="mainImageContainer" class="flex justify-center mt-4">
                                                    <img id="mainImage"
                                                        class="w-full h-auto object-cover border rounded-lg shadow-lg"
                                                        src="{{ asset('storage/collection_images/' . $collection->collectionImages[0]->image_path) }}"
                                                        alt="メイン画像">
                                                </div>
                                            @else
                                                <!-- 大きなプレビュー画像 -->
                                                <div id="mainImageContainer" class="flex justify-center mt-4">
                                                    <img id="mainImage"
                                                        class="w-full h-auto object-cover border rounded-lg shadow-lg"
                                                        src="{{ asset('storage/collection_images/noImage.jpg') }}"
                                                        alt="メイン画像">
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                    {{-- URL --}}
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="url_webapp" class="leading-7 text-sm text-gray-600">WebApp
                                                URL</label>
                                            <div
                                                class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out min-h-10 break-words">
                                                {{ $collection->url_webapp }}</div>
                                        </div>
                                    </div>
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="url_github" class="leading-7 text-sm text-gray-600">GitHub
                                                URL</label>
                                            <div
                                                class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out min-h-10 break-words">
                                                {{ $collection->url_github }}</div>
                                        </div>
                                    </div>
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="url_qiita" class="leading-7 text-sm text-gray-600">Qiita
                                                URL</label>
                                            <div
                                                class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out min-h-10 break-words">
                                                {{ $collection->url_qiita }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="url_youtube" class="leading-7 text-sm text-gray-600">Youtube
                                                URL</label>
                                            <div
                                                class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out min-h-10 break-words">
                                                {{ $collection->url_youtube }}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- アプリ解説 --}}
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="description"
                                                class="leading-7 text-sm text-gray-600">アプリ解説</label>
                                            <div id="description" name="description"
                                                class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-6 transition-colors duration-200 ease-in-out break-words overflow-y-auto resize-y h-32">
                                                {!! nl2br(e($collection->description)) !!}</div>
                                        </div>
                                    </div>

                                    {{-- 技術タグ --}}
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="technology_tag"
                                                class="leading-7 text-sm text-gray-600">技術タグ</label>
                                            <div
                                                class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out min-h-10">
                                                @foreach ($collection->groupedTechnologyTags as $techType => $technology_tags)
                                                    @foreach ($technology_tags as $technology_tag)
                                                        {{ $technology_tag->name }}@if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    {{-- 主な機能タグ --}}
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="feature_tag"
                                                class="leading-7 text-sm text-gray-600">主な機能タグ</label>
                                            <div
                                                class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out min-h-10">
                                                @foreach ($collection->sortByFeatureTags as $feature_tag)
                                                    {{ $feature_tag->name }}@if (!$loop->last)
                                                        ,
                                                    @endif{{-- $loop->lastは「今のループが最後の要素かどうか」を判定 --}}
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 公開、優先度 --}}
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="is_public" class="leading-7 text-sm text-gray-600">公開種別</label>
                                            <div
                                                class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                {{ $collection->is_public_label }}</div>
                                        </div>
                                    </div>
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="position" class="leading-7 text-sm text-gray-600">表示優先度</label>
                                            <div
                                                class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                {{ $collection->position_label }}</div>
                                        </div>
                                    </div>

                                    {{-- 管理者メモ --}}
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="private_memo"
                                                class="leading-7 text-sm text-gray-600">管理者用メモ(非表示の管理者メモ)</label>
                                            <div id="private_memo" name="private_memo"
                                                class="w-full rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-6 transition-colors duration-200 ease-in-out break-words overflow-y-auto resize-y h-32">
                                                {!! nl2br(e($collection->private_memo)) !!}</div>
                                        </div>
                                    </div>

                                    {{-- ボタンエリア --}}
                                    <div class="flex justify-center w-full gap-4 mt-8">
                                        {{-- 編集ボタン --}}
                                        <form
                                            action="{{ route('admin.collections.edit', ['collection' => $collection->id]) }}"
                                            method="get">
                                            <div class=" w-full">
                                                <button
                                                    class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">編集</button>
                                            </div>
                                        </form>
                                        {{-- 削除ボタン --}}
                                        <form
                                            action="{{ route('admin.collections.destroy', ['collection' => $collection->id]) }}"
                                            method="post" id="delete_{{ $collection->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <div class="w-full">
                                                <a href="#" data-id="{{ $collection->id }}"
                                                    onclick="DeleteService.confirmAndDelete(this)"
                                                    {{-- resources/js/services/DeleteService.js --}}
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
        // ✅ メイン画像切り替え＆枠線付与処理
        function changeMainImage(src) {
            // メイン画像のsrcを切り替え
            document.getElementById("mainImage").src = src; // imgタグのsrc属性(.src)をsrcに変更

            // すべてのサムネイルから装飾を外す
            document.querySelectorAll('.thumbnail').forEach(img => {
                img.classList.remove('shadow-lg', 'ring-1', 'ring-blue-300');
            });

            // クリックされた画像だけに装飾を追加
            const selected = Array.from(document.querySelectorAll('.thumbnail'))
                .find(img => img.getAttribute('data-src') === src); // 無名関数
            if (selected) {
                selected.classList.add('shadow-lg', 'ring-1', 'ring-blue-300');
            }
        }

        // ✅ 初期表示時に1枚目のサムネイルに枠線と影を付ける
        document.addEventListener("DOMContentLoaded", function() {
            const thumbnails = document.querySelectorAll('.thumbnail');
            if (thumbnails.length > 0) {
                thumbnails[0].classList.add('shadow-lg', 'ring-1', 'ring-blue-300'); // 0番目のみ
            }
        });
    </script>
</x-app-layout>
