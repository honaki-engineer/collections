<x-layouts.public>
    <section class="container mx-auto">
        <h2 class="text-2xl font-bold text-center">{{ $collection->title }}</h2>

        <!-- スクショ部分 -->
        <div class="w-4/5 sm:3/4 mx-auto mt-8 space-y-10">
            <!-- サムネイルとメイン画像 -->
            <div class="md:col-span-5 space-y-4">
                <!-- サムネイル -->
                <div class="flex flex-wrap gap-2 justify-center">
                    @foreach($collection->collectionImages as $index => $collectionImage)
                        <img
                            src="{{ $collectionImage->src }}"
                            data-src="{{ $collectionImage->src }}"
                            alt="トップ画面"
                            class="thumbnail w-20 h-20 object-cover rounded shadow cursor-pointer hover:shadow-lg
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
        </div>

        <!-- リンク集 -->
        @php
            $links = [];
            // webapp
            if($collection->url_webapp) {
                $links[] = '<a href="' . e($collection->url_webapp) . '" target="_blank" class="inline-flex items-center text-blue-600 hover:underline">
                                <img src="' . asset('storage/collection_images/webApp.png') . '" alt="Demo" class="w-5 h-5 mr-2"> Demo (Guest Login)
                            </a>';
            }
            // github
            if($collection->url_github) {
                $links[] = '<a href="' . e($collection->url_github) . '" target="_blank" class="inline-flex items-center text-blue-600 hover:underline">
                                <img src="' . asset('storage/collection_images/github.png') . '" alt="GitHub" class="w-5 h-5 mr-2"> Github (README + Code)
                            </a>';
            }
            // qiita
            if($collection->url_qiita) {
                $links[] = '<a href="' . e($collection->url_qiita) . '" target="_blank" class="inline-flex items-center text-blue-600 hover:underline">
                                <img src="' . asset('storage/collection_images/qiita.png') . '" alt="Qiita" class="w-5 h-5 mr-2"> Qiita (設計資料)
                            </a>';
            }
            // youtube
            if($collection->url_youtube) {
                $links[] = '<a href="' . e($collection->url_youtube) . '" target="_blank" class="inline-flex items-center text-blue-600 hover:underline">
                                <img src="' . asset('storage/collection_images/YouTube.png') . '" alt="YouTube" class="w-5 h-5 mr-2"> YouTube (操作デモ)
                            </a>';
            }
        @endphp
        <div class="w-4/5 sm:3/4 mx-auto mt-10 flex flex-wrap justify-center gap-x-6 gap-y-2">
            @foreach($links as $link)
                {!! $link !!}
            @endforeach
        </div>

        <!-- アプリ解説 -->
        <div class="w-4/5 sm:3/4 mx-auto mt-10">
            <h3 class="text-lg font-semibold text-center md:text-left">アプリ概要</h3>
            @if ($collection->description)
                <p class="text-gray-700 mt-2">{!! nl2br(e($collection->description)) !!}</p>
            @endif
        </div>

        <!-- タグ集 -->
        <div class="w-4/5 sm:3/4 mx-auto mt-10 space-y-10">
            {{-- 使用技術 --}}
            <div class="text-sm">
                <h3 class="text-lg font-semibold text-center md:text-left">使用技術</h3>
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
            <div class="text-sm">
                <h3 class="text-lg font-semibold text-center md:text-left">実装機能</h3>
                <div class="flex flex-wrap break-words justify-left gap-2 mt-2">
                    @foreach ($collection->sortByFeatureTags as $featureTag)
                        <span class="px-3 py-1 bg-blue-100 text-gray-700 rounded-full text-xs">
                            {{ $featureTag->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

    </section>

    <script>
        function changeMainImage(src) {
            document.getElementById("mainImage").src = src;
            document.querySelectorAll('.thumbnail').forEach(img => {
                img.classList.remove('shadow-lg', 'ring-1', 'ring-blue-300');
            });
            const selected = Array.from(document.querySelectorAll('.thumbnail'))
                .find(img => img.getAttribute('data-src') === src);
            if (selected) {
                selected.classList.add('shadow-lg', 'ring-1', 'ring-blue-300');
            }
        }
    </script>
</x-layouts.public>
