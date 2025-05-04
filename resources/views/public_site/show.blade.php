<x-layouts.public>
    {{-- ↓ここにトップページのコンテンツを記述 --}}

    <section class="container mx-auto">
        <h2 class="text-2xl font-bold text-center">{{ $collection->title }}</h2>
        <div class="grid md:grid-cols-5 gap-10 w-4/5 sm:3/4 mx-auto mt-8">

            <!-- 左カラム：サムネイルとメイン画像 -->
            <div class="md:col-span-3 space-y-4">
                {{-- サムネイル --}}
                <div class="flex flex-wrap gap-2 justify-center">
                    @foreach($collection->collectionImages as $index => $collectionImage)
                        <img
                            src="{{ $collectionImage->src }}"
                            data-src="{{ $collectionImage->src }}" {{-- JavaScript側で識別用に使うカスタム属性（クリックした画像と照合するため） --}}
                            alt="トップ画面"
                            class="thumbnail w-20 h-20 object-cover rounded shadow cursor-pointer
                                   {{ $index === 0 ? 'shadow-lg ring-1 ring-blue-300' : '' }}"
                            onclick="changeMainImage('{{ $collectionImage->src }}')"
                        >
                    @endforeach
                </div>

                <!-- メイン画像 -->
                <div id="mainImageContainer" class="w-full">
                    <img id="mainImage" src="{{ $mainImagePath }}" alt="メイン画像"
                        class="w-full max-h-[600px] object-contain rounded shadow-lg">
                </div>
            </div>

            <!-- 右カラム：説明文 -->
            <div class="md:col-span-2 space-y-10">

                {{-- リンク集 --}}
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
                                class="w-5 h-5 mr-2"> Github (README + Code)
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
                <div class="space-y-2 text-sm text-gray-600 mt-6">
                    <h3 class="text-lg font-semibold text-center">使用技術</h3>
                    @foreach($typeLabels as $type => $label)
                        @if(!empty($collection->groupedTechnologyTags[$type]))
                            <div class="flex flex-wrap break-words justify-left gap-2 mt-2">
                                <span class="flex items-center font-semibold">{{ $label }}：</span>
                                @foreach($collection->groupedTechnologyTags[$type] as $technologyTag)
                                    <span class="px-3 py-1 bg-blue-100 text-gray-700 rounded-full text-xs">
                                        {{ $technologyTag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- 実装機能 --}}
                <div class="mt-6 text-sm text-gray-600">
                    <h3 class="text-lg font-semibold text-center">実装機能</h3>
                    <div class="flex flex-wrap break-words justify-left gap-2 mt-2">
                        @foreach ($collection->featureTags as $featureTag)
                            <span class="px-3 py-1 bg-blue-100 text-gray-700 rounded-full text-xs">
                                {{ $featureTag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- 下レコード：アプリ解説 -->
            <div class="md:col-span-5 break-words overflow-hidden">
                <h3 class="text-lg font-semibold text-center md:text-left">アプリ概要</h3>
                @if ($collection->description)
                    <p class="text-gray-700 mt-2">{!! nl2br(e($collection->description)) !!}</p>
                @endif
            </div>
            <!-- 下レコード：開発背景 -->
            <div class="md:col-span-5 break-words overflow-hidden">
                <h3 class="text-lg font-semibold text-center md:text-left">開発背景</h3>
                @if ($collection->development_background)
                    <p class="text-gray-700 mt-2">{!! nl2br(e($collection->development_background)) !!}</p>
                @endif
            </div>
        </div>

    </section>

    <script>
        function changeMainImage(src) {
            // メイン画像を切り替え(メイン画像の src をクリックされた画像の src に差し替える)
            document.getElementById("mainImage").src = src;

            // 全サムネイルの枠をリセット
            document.querySelectorAll('.thumbnail').forEach(img => {
                img.classList.remove('shadow-lg', 'ring-1', 'ring-blue-300'); // 全ての .thumbnail に対して、影と枠線を外す
            });

            // 選択された画像に枠を追加(各サムネイル画像に付けたdata-src属性と、クリックされた画像のsrcを比較。一致したらそれが「今クリックされた画像のサムネイル」)
            const selected = Array.from(document.querySelectorAll('.thumbnail'))
                .find(img => img.getAttribute('data-src') === src); // 「img =>」 = 引数 img を取る無名関数(document.querySelectorAll('.thumbnail')のimgを一つずつ渡して合致するか否かをreturnする)

            if(selected) { // const selected
                selected.classList.add('shadow-lg', 'ring-1', 'ring-blue-300');
            }
        }
    </script>

</x-layouts.public>
