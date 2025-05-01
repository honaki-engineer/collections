<x-layouts.public>
    {{-- ↓ここにトップページのコンテンツを記述 --}}

    <section class="container mx-auto">
        <h2 class="text-2xl font-bold text-center">{{ $collection->title }}</h2>
        <div class="grid md:grid-cols-5 gap-10 w-4/5 sm:3/4 mx-auto mt-8 xl:mt-12">

            <!-- 左カラム：サムネイルとメイン画像 -->
            <div class="md:col-span-3 space-y-4">
                {{-- サムネイル --}}
                <div class="flex flex-wrap gap-2 justify-center">
                    @foreach ($collection->collectionImages as $collectionImage)
                        <img src="{{ asset('storage/collection_images/' . $collectionImage->image_path) }}" alt="トップ画面"
                            class="w-20 h-20 object-cover rounded shadow cursor-pointer"
                            onclick="changeMainImage('{{ asset('storage/collection_images/' . $collectionImage->image_path) }}')">
                    @endforeach
                </div>

                <!-- メイン画像 -->
                <div id="mainImageContainer" class="w-full">
                    <img id="mainImage" src="{{ $mainImagePath }}" alt="メイン画像"
                        class="w-full max-h-[600px] object-contain rounded shadow-lg">
                </div>
            </div>

            <!-- 右カラム：説明文 -->
            <div class="md:col-span-2">
                <div class="space-y-2">
                    @if ($collection->url_webapp)
                        <a href="{{ $collection->url_webapp }}" target="_blank"
                            class="inline-flex items-center text-blue-600 hover:underline">
                            <img src="{{ asset('storage/collection_images/webApp.png') }}" alt="GitHub"
                                class="w-5 h-5 mr-2"> Demo (Guest Login)
                        </a>
                        <br>
                    @endif
                    @if ($collection->url_github)
                        <a href="{{ $collection->url_github }}" target="_blank"
                            class="inline-flex items-center text-blue-600 hover:underline">
                            <img src="{{ asset('storage/collection_images/github.png') }}" alt="GitHub"
                                class="w-5 h-5 mr-2"> Github
                        </a>
                        <br>
                    @endif
                    @if ($collection->url_qiita)
                        <a href="{{ $collection->url_qiita }}" target="_blank"
                            class="inline-flex items-center text-blue-600 hover:underline">
                            <img src="{{ asset('storage/collection_images/qiita.png') }}" alt="Demo"
                                class="w-5 h-5 mr-2"> Qiita (設計資料)
                        </a>
                    @endif
                </div>

                {{-- タグ --}}
                <h3 class="text-lg font-semibold mt-6 text-center">使用技術</h3>
                <div class="flex flex-wrap break-words justify-left gap-2 text-sm text-gray-600 mt-2">
                    @foreach ($collection->technologyTags as $technologyTag)
                        <span>{{ $technologyTag->name }}@if (!$loop->last),@endif</span>
                    @endforeach
                </div>

                <h3 class="text-lg font-semibold mt-6 text-center">実装機能</h3>
                <div class="flex flex-wrap break-words justify-left gap-2 text-sm text-gray-600 mt-2">
                    @foreach ($collection->featureTags as $featureTag)
                        <span>{{ $featureTag->name }}@if (!$loop->last),@endif</span>
                    @endforeach
                </div>

            </div>

            <!-- 下レコード：アプリ解説 -->
            <div class="md:col-span-5 break-words overflow-hidden">
                <h3 class="text-lg font-semibold mt-6 text-center md:text-left">アプリ概要</h3>
                @if ($collection->description)
                    <p class="text-gray-700 mt-2">{!! nl2br(e($collection->description)) !!}</p>
                @endif
            </div>
            <!-- 下レコード：開発背景 -->
            <div class="md:col-span-5 break-words overflow-hidden">
                <h3 class="text-lg font-semibold mt-6 text-center md:text-left">開発背景</h3>
                @if ($collection->development_background)
                    <p class="text-gray-700 mt-2">{!! nl2br(e($collection->development_background)) !!}</p>
                @endif
            </div>
        </div>

    </section>

    <script>
        function changeMainImage(src) {
            document.getElementById("mainImage").src = src;
        }
    </script>
</x-layouts.public>
