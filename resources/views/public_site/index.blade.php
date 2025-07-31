<x-layouts.public>
    {{-- ↓ここにトップページのコンテンツを記述 --}}
    <section id="projects" class=" bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-4">Collections</h2>
            <p class="text-center text-gray-600 mb-10">
                腕によりをかけて制作した、愛すべき成果物たちをご紹介します。
            </p>

            {{-- 🔍 sm未満で表示される 検索トグルボタン --}}
            <div class="sm:hidden text-center mb-4">
                <button type="button" id="toggleSearchForm"
                    class="{{ $isSearching ? 'bg-gray-300 text-gray-800 hover:bg-gray-400' : 'bg-indigo-500 text-white hover:bg-indigo-600' }} px-4 py-2 rounded-md transition">
                    {{ $isSearching ? '❌ 検索を閉じる' : '🔍 検索' }}
                </button>
            </div>

            {{-- 検索フォーム --}}
            <form action="{{ route('collections.index') }}" method="GET" id="searchForm"
                class="p-4 bg-white shadow-md rounded-md w-full max-w-3xl mx-auto mb-8 {{ $isSearching ? '' : 'hidden' }} sm:block">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                    {{-- 使用技術 --}}
                    <div>
                        <select name="search_technology_tag_id" id="search_tech"
                            class="js-multiple-tag-select w-full rounded-md">
                            <option value="">使用技術を選択</option>
                            @foreach ($technologyTags->typeLabels as $type => $label)
                                <optgroup label="▼ {{ $label }}">
                                    {{-- セレクトボックス内でカテゴリを分ける --}}
                                    @foreach ($technologyTags->where('tech_type', $type) as $technologyTag)
                                        {{-- tech_typeカラムの値が$typeと一致するレコードだけを絞り込み --}}
                                        <option value="{{ $technologyTag->id }}"
                                            {{ request('search_technology_tag_id') == $technologyTag->id ? 'selected' : '' }}>
                                            {{ $technologyTag->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    {{-- 主な機能 --}}
                    <div>
                        <select name="search_feature_tag_id" id="search_feature"
                            class="js-multiple-tag-select w-full rounded-md">
                            <option value="">主な機能を選択</option>
                            @foreach ($featureTags as $featureTag)
                                <option value="{{ $featureTag->id }}"
                                    {{ request('search_feature_tag_id') == $featureTag->id ? 'selected' : '' }}>
                                    {{ $featureTag->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 検索ボタン --}}
                    <div>
                        <button type="submit"
                            class="w-full bg-indigo-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600 transition text-lg">
                            検索
                        </button>
                    </div>
                </div>
            </form>

            <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <!-- カード一覧 -->
                @foreach ($collections as $collection)
                    <div class="text-center">
                        <div class="bg-white rounded shadow hover:shadow-lg transition">
                            <a href="{{ route('collections.show', ['collection' => $collection->id]) }}">
                                <div class="h-48 md:h-40 lg:h-56 xl:h-64 bg-cover bg-center rounded"
                                    style="background-image: url('{{ asset('storage/collection_images/' . $collection->firstImage) }}')">
                                </div>
                            </a>
                        </div>
                        <p class="mt-2 text-gray-800 font-semibold">{{ $collection->title }}</p>
                    </div>
                @endforeach
            </div>
            {{ $collections->links() }}
        </div>
    </section>
    {{-- HTML ここまで --}}

    {{-- JavaScript/CSS 読み込み --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- CSS --}}
    <style>
        /* ⭐️ select2 */
        /* ✅ セレクトボックスの高さをボタンと揃える */
        .select2-container--default .select2-selection--single {
            height: 2.875rem;
            /* 検索ボタンと同じくらいの高さ (py-2 + text-lg 換算) */
            padding: 0.5rem 0.75rem;
            display: flex;
            align-items: center;
            font-size: 1.125rem;
            /* text-lg 相当 */
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            /* border-gray-300 */
        }

        /* ✅ プルダウンの▼ボタン位置を調整 */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            top: 0;
            right: 0.75rem;
        }

        /* 🔵 Select2 の検索欄を丸くする */
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border-radius: 0.375rem;
            /* Tailwindの rounded-md 相当 */
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            /* border-gray-300 */
            font-size: 1rem;
        }
    </style>

    {{-- JavaScript --}}
    <script>
        // ⭐️ select2
        $(document).ready(function() {
            // ✅ 使用技術セレクトボックス
            $('#search_tech').select2({
                placeholder: "使用技術を選択",
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 0, // 検索バーを表示するかどうか 0=常に表示
                language: {
                    noResults: () => "結果が見つかりません",
                    searching: () => "検索中…",
                    inputTooShort: () => "もっと文字を入力してください"
                }
            });

            // ✅ 主な機能セレクトボックス
            $('#search_feature').select2({
                placeholder: "主な機能を選択",
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 0,
                language: {
                    noResults: () => "結果が見つかりません",
                    searching: () => "検索中…",
                    inputTooShort: () => "もっと文字を入力してください"
                }
            });

            // ✅ 検索バーにプレースホルダーを表示するための処理(開いたときに実行)
            $('#search_tech, #search_feature').on('select2:opening', function() {
                setTimeout(() => {
                    $('.select2-search__field').attr('placeholder', '入力検索できます');
                }, 0);
            });

        });

        // ⭐️ 検索フォームの表示/非表示
        document.addEventListener('DOMContentLoaded', () => { // HTMLドキュメントの読み込みが完了したときに処理を実行
            const toggleBtn = document.getElementById('toggleSearchForm');
            const searchForm = document.getElementById('searchForm');

            if (toggleBtn && searchForm) {
                toggleBtn.addEventListener('click', () => {
                    // searchForm.classList.toggle('hidden');
                    const isHidden = searchForm.classList.toggle(
                    'hidden'); // searchForm.classList.toggle('hidden')は実行される | true(hiddenあり)/false(hiddenなし)を変数に入れる
                    toggleBtn.textContent = isHidden ? '🔍 検索' : '❌ 検索を閉じる';

                    // 🔁 色の主従を切り替え（検索=主役=青、閉じる=グレー）
                    if (isHidden) {
                        // 検索表示前（閉じてる） → 主役にする
                        toggleBtn.classList.remove('bg-gray-300', 'text-gray-800', 'hover:bg-gray-400');
                        toggleBtn.classList.add('bg-indigo-500', 'text-white', 'hover:bg-indigo-600');
                    } else {
                        // 検索表示中 → 脇役にする
                        toggleBtn.classList.remove('bg-indigo-500', 'text-white', 'hover:bg-indigo-600');
                        toggleBtn.classList.add('bg-gray-300', 'text-gray-800', 'hover:bg-gray-400');
                    }
                });
            }
        });
    </script>


</x-layouts.public>
