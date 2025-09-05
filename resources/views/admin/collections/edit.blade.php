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
                        <form id="editForm"
                            action="{{ route('admin.collections.update', ['collection' => $collection->id]) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="container px-5 mx-auto">
                                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                    <div class="flex flex-wrap -m-2">
                                        {{-- タイトル --}}
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="title"
                                                    class="leading-7 text-sm text-gray-600">タイトル</label>
                                                <input type="text" id="title" name="title"
                                                    value="{{ $collection->title }}"
                                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                            </div>
                                        </div>

                                        {{-- 画像 --}}
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                {{-- 新しい画像アップロード --}}
                                                <div class="relative mt-4">
                                                    <label class="leading-7 text-sm text-gray-600">画像</label>
                                                    <input multiple type="file" id="image_path" name="image_path[]"
                                                        class="hidden" accept=".jpg,.jpeg,.png,.webp,.avif">
                                                    <br>
                                                    <label for="image_path"
                                                        class="file-upload-btn inline-block px-4 py-1 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded-md shadow-sm cursor-pointer hover:bg-gray-200 active:bg-gray-300 transition">
                                                        新しい画像を追加
                                                    </label>
                                                    <x-input-error :messages="$errors->get('image_path')" class="mt-2" />
                                                </div>
                                                {{-- サムネイル一覧 --}}
                                                <div class="relative mt-4">
                                                    {{-- <label class="leading-7 text-sm text-gray-600">サムネイル一覧：</label> --}}
                                                    <div id="imagePreviewContainer"
                                                        class="grid grid-cols-3 gap-3 sm:grid-cols-4 sm:gap-4 md:grid-cols-4 md:gap-4 xl:grid-cols-5 xl:gap-5 w-full place-items-center">

                                                        @foreach ($collection->collectionImages as $image)
                                                            <div class="relative w-20 h-20 sm:w-24 sm:h-24"
                                                                data-image-id="{{ $image->id }}">
                                                                <img src="{{ asset('storage/collection_images/' . $image->image_path) }}"
                                                                    data-src="{{ asset('storage/collection_images/' . $image->image_path) }}"
                                                                    class="thumbnail w-full h-full object-cover cursor-pointer border border-gray-300 rounded-lg hover:shadow-lg transition">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                {{-- 大きなプレビュー画像 --}}
                                                <div id="mainImageContainer"
                                                    class="flex justify-center mt-4 {{ $collection->collectionImages->isNotEmpty() ? '' : 'hidden' }}">
                                                    <img id="mainImage"
                                                        class="w-full h-auto object-cover border rounded-lg shadow-lg"
                                                        src="{{ $collection->collectionImages->isNotEmpty() ? asset('storage/collection_images/' . $collection->collectionImages->first()->image_path) : asset('storage/collection_images/noImage.jpg') }}"
                                                        alt="メイン画像">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- URL --}}
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="url_webapp" class="leading-7 text-sm text-gray-600">WebApp
                                                    URL</label>
                                                <input type="url" id="url_webapp" name="url_webapp"
                                                    value="{{ $collection->url_webapp }}"
                                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <x-input-error :messages="$errors->get('url_webapp')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="url_github" class="leading-7 text-sm text-gray-600">GitHub
                                                    URL</label>
                                                <input type="url" id="url_github" name="url_github"
                                                    value="{{ $collection->url_github }}"
                                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <x-input-error :messages="$errors->get('url_github')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="url_qiita" class="leading-7 text-sm text-gray-600">Qiita
                                                    URL</label>
                                                <input type="url" id="url_qiita" name="url_qiita"
                                                    value="{{ $collection->url_qiita }}"
                                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <x-input-error :messages="$errors->get('url_qiita')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="url_youtube" class="leading-7 text-sm text-gray-600">Youtube
                                                    URL</label>
                                                <input type="url" id="url_youtube" name="url_youtube"
                                                    value="{{ $collection->url_youtube }}"
                                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <x-input-error :messages="$errors->get('url_youtube')" class="mt-2" />
                                            </div>
                                        </div>

                                        {{-- アプリ解説 --}}
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="description"
                                                    class="leading-7 text-sm text-gray-600">アプリ解説</label>
                                                <textarea id="description" name="description"
                                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-y leading-6 transition-colors duration-200 ease-in-out">{{ $collection->description }}</textarea>
                                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                            </div>
                                        </div>

                                        {{-- 技術タグ --}}
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="tech_type"
                                                    class="leading-7 text-sm text-gray-600">技術タグ(複数選択OK)</label>
                                                <select name="technology_tag_ids[]" id="tech_type" multiple
                                                    class="rounded-md js-multiple-tag-select">
                                                    @if (!$technologyTags->isEmpty())
                                                        @foreach ($technologyTags->typeLabels as $type => $label)
                                                            <optgroup label="▼ {{ $label }}">
                                                                {{-- セレクトボックス内でカテゴリを分ける --}}
                                                                @foreach ($technologyTags->where('tech_type', $type) as $technologyTag)
                                                                    {{-- tech_typeカラムの値が$typeと一致するレコードだけを絞り込み --}}
                                                                    <option value="{{ $technologyTag->id }}"
                                                                        {{ in_array($technologyTag->id, $selectedTechTagIds) ? 'selected' : '' }}>
                                                                        {{-- in_array() = 「$selectedTechTagIdsの中に$technologyTag->idがあるか？」を調べる --}}
                                                                        {{ $technologyTag->name }}
                                                                    </option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="techTagSortableWrapper" class="mt-2">
                                                    <div class="text-sm text-gray-600">↓ タグの並び替え（色付き）</div>
                                                    <ul id="technology-tag-sortable"
                                                        class="p-2 border border-gray-300 rounded bg-gray-100 min-h-[40px] flex flex-wrap gap-2">
                                                        {{-- JSでliを追加 --}}
                                                    </ul>
                                                    <input type="hidden" name="technology_tag_order"
                                                        id="technology_tag_order">
                                                </div>
                                                <x-input-error :messages="$errors->get('technology_tag_ids')" class="mt-2" />
                                                <div class="text-right">
                                                    <a href="{{ route('admin.technology-tags.create') }}"
                                                        class="leading-7 text-sm text-gray-600 underline hover:text-gray-900">技術タグを作りたい場合はこちら</a><br>
                                                    <a href="{{ route('admin.technology-tags.index') }}"
                                                        class="toTechTagIndex leading-7 text-sm text-gray-600 underline hover:text-gray-900">技術タグ一覧はこちら</a>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- 主な機能タグ --}}
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="feature_tags"
                                                    class="leading-7 text-sm text-gray-600">主な機能タグ(複数選択OK)</label>
                                                <select name="feature_tag_ids[]" id="feature_tags" multiple
                                                    class="rounded-md js-multiple-tag-select">
                                                    @if (!$featureTags->isEmpty())
                                                        @foreach ($featureTags as $featureTag)
                                                            <option value="{{ $featureTag->id }}"
                                                                {{ in_array($featureTag->id, $selectedFeatureTagIds) ? 'selected' : '' }}>
                                                                {{ $featureTag->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="featureTagSortableWrapper" class="mt-2">
                                                    <div class="text-sm text-gray-600">↓ タグの並び替え（主な機能タグ）</div>
                                                    <ul id="feature-tag-sortable"
                                                        class="p-2 border border-gray-300 rounded bg-gray-100 min-h-[40px] flex flex-wrap gap-2">
                                                        {{-- JSでliを追加 --}}
                                                    </ul>
                                                    <input type="hidden" name="feature_tag_order"
                                                        id="feature_tag_order">
                                                </div>
                                                <x-input-error :messages="$errors->get('feature_tag_ids')" class="mt-2" />
                                                <div class="text-right">
                                                    <a href="{{ route('admin.feature-tags.create') }}"
                                                        class="leading-7 text-sm text-gray-600 underline hover:text-gray-900">主な機能タグを作りたい場合はこちら</a><br>
                                                    <a href="{{ route('admin.feature-tags.index') }}"
                                                        class="toTechTagIndex leading-7 text-sm text-gray-600 underline hover:text-gray-900">主な機能タグ一覧はこちら</a>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 公開、優先度 --}}
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="is_public"
                                                    class="leading-7 text-sm text-gray-600">公開種別</label>
                                                <input type="radio" name="is_public" value="0"
                                                    @if ($collection->is_public === 0) checked @endif
                                                    class="cursor-pointer">非公開
                                                <input type="radio" name="is_public" value="1"
                                                    @if ($collection->is_public === 1) checked @endif
                                                    class="cursor-pointer">一般公開
                                                <x-input-error :messages="$errors->get('is_public')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="position"
                                                    class="leading-7 text-sm text-gray-600">表示優先度</label>
                                                <select name="position" id="position"
                                                    class="rounded-md cursor-pointer">
                                                    <option value="">選択してください</option>
                                                    <option value="0"
                                                        @if ($collection->position === 0) selected @endif>デフォルト</option>
                                                    <option value="1"
                                                        @if ($collection->position === 1) selected @endif>高め</option>
                                                    <option value="2"
                                                        @if ($collection->position === 2) selected @endif>topページ
                                                    </option>
                                                </select>
                                                <x-input-error :messages="$errors->get('position')" class="mt-2" />
                                            </div>
                                        </div>

                                        {{-- 管理者用メモ --}}
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="private_memo"
                                                    class="leading-7 text-sm text-gray-600">管理者用メモ(非表示の管理者メモ)</label>
                                                <textarea id="private_memo" name="private_memo"
                                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-y leading-6 transition-colors duration-200 ease-in-out">{{ $collection->private_memo }}</textarea>
                                                <x-input-error :messages="$errors->get('private_memo')" class="mt-2" />
                                            </div>
                                        </div>

                                        <div class="w-full mt-8">
                                            <button
                                                class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新</button>
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

    {{-- --- ⭐️ Select2 --- --}}
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- カスタムデザイン追加 -->
    <style>
        /* ✅ 複数選択セレクトボックスの外枠全体 */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #4B5563;
            border-radius: 0.375rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            position: relative;
        }

        /* ✅ セレクトがフォーカスされたときの枠線スタイル */
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }

        /* ✅ セレクト内にある検索入力欄そのもの */
        .select2-container .select2-search--inline .select2-search__field {
            font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            height: 2rem;
            line-height: 2rem;
            margin: 0;
        }

        /* ✅ 選択されたタグの表示を完全に隠す */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            display: none !important;
        }

        /* プレースホルダーを初期状態だけ表示し、検索開始時に非表示にする */
        .select2-container--default.select2-container--open .select2-selection--multiple::after {
            content: "";
        }

        /* ✅ プレースホルダー表示 */
        .select2-container--default .select2-selection--multiple::after {
            content: "選択してください(入力検索可能)";
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #1F2937;
            font-size: 0.875rem;
            pointer-events: none;
        }
    </style>

    <!-- jQuery（必要） -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        let techTypeMap = @json($techTypeMapForJS);
        let typeToColorClass = {
            frontend: ['bg-blue-100', 'text-blue-800'],
            backend: ['bg-green-100', 'text-green-800'],
            db: ['bg-red-100', 'text-red-800'],
            infra: ['bg-yellow-100', 'text-yellow-800'],
            build: ['bg-pink-100', 'text-pink-800'],
            tool: ['bg-purple-100', 'text-purple-800'],
            default: ['bg-gray-100', 'text-gray-800'],
        };

        $(document).ready(function() {
            $('.js-multiple-tag-select').select2({
                width: '100%', // 幅をinputに合わせる
                language: {
                    noResults: function() {
                        return "結果が見つかりません";
                    },
                    searching: function() {
                        return "検索中…";
                    },
                    inputTooShort: function() {
                        return "もっと文字を入力してください";
                    }
                }
            });
        });

        // ✅ 技術タグ
        $(document).ready(function() {
            // 🔹 技術タグ表示/非表示：初期表示(DBから復元済みのタグに対応)
            updateTechTagSortableVisibility();

            const select = $('#tech_type');
            const sortableArea = $('#technology-tag-sortable');
            const hiddenOrder = $('#technology_tag_order');
            let initialTagOrder = @json($technologyTagOrderFromDB);

            // 🔹 技術タグ表示/非表示：select2の選択・解除時に動的表示制御
            $('#tech_type').on('select2:select select2:unselect', function() {
                updateTechTagSortableVisibility();
            });

            // 🔹 技術タグの並び替えリストにタグを追加する処理
            function addTag(id, text) {
                // 🔸 重複防止のためのチェック処理
                if ($(`#technology-tag-sortable li[data-id="${id}"]`).length > 0) return;

                const typeRaw = techTypeMap[id.toString()]; // 例：techTypeMap["1"]を参照
                const type = typeof typeRaw === 'string' ? typeRaw.trim() : 'default';
                const [bgColor, textColor] = typeToColorClass[type] || typeToColorClass.default;

                const li = $(`
                    <li class="inline-flex items-center ${bgColor} ${textColor} text-sm px-3 py-1 rounded-full cursor-move"
                        data-id="${id}">
                        <span class="mr-2">${text}</span>
                        <button type="button" class="remove-tag-btn hover:text-red-500 text-lg font-bold leading-none">×</button>
                    </li>
                `);

                // 🔸 ×ボタンで削除処理
                li.find('.remove-tag-btn').on('click', function() {
                    li.remove();
                    const option = $(`#tech_type option[value="${id}"]`);
                    option.prop('selected', false);
                    $('#tech_type').trigger('change');
                    updateOrder();
                    updateTechTagSortableVisibility();
                });

                sortableArea.append(li);
                updateOrder();
            }

            // 🔹 並び順の保存
            function updateOrder() {
                const ids = [];
                sortableArea.find('li').each(function() {
                    ids.push($(this).data('id'));
                });
                hiddenOrder.val(ids.join(','));
            }

            // 🔹 初期表示：DBから渡された順序に基づいてulへ追加
            if (Array.isArray(initialTagOrder) && initialTagOrder.length > 0) {
                initialTagOrder.forEach(function(id) {
                    const option = select.find(`option[value="${id}"]`);
                    if (option.length > 0) {
                        addTag(id, option.text());
                    }
                });
            } else {
                // fallback（初期順序が渡っていない場合、セレクトされた順）
                select.find('option:selected').each(function() {
                    const id = $(this).val();
                    const text = $(this).text();
                    addTag(id, text);
                });
            }

            // 🔹 並び替え可能に
            new Sortable(sortableArea[0], {
                animation: 150,
                onEnd: updateOrder
            });

            // 🔹 select2選択時
            select.on('select2:select', function(e) {
                const id = e.params.data.id;
                const text = e.params.data.text;
                addTag(id, text);
            });

            // 🔹 select2解除時
            select.on('select2:unselect', function(e) {
                $(`#technology-tag-sortable li[data-id="${e.params.data.id}"]`).remove();
                updateOrder();
            });
        });

        // ✅ 技術タグ表示/非表示
        function updateTechTagSortableVisibility() {
            const selectedCount = $('#tech_type').find('option:selected').length;
            const wrapper = $('#technology-tag-sortable').closest('div'); // ulの親div
            if (selectedCount > 0) {
                wrapper.show();
            } else {
                wrapper.hide();
            }
        }

        // ✅ 主な機能タグ並び替え
        $(document).ready(function() {
            // 🔹 主な機能タグ表示/非表示：初期表示(DBから復元済みのタグに対応)
            updateFeatureTagSortableVisibility();

            const featureSelect = $('#feature_tags');
            const featureSortable = $('#feature-tag-sortable');
            const hiddenFeatureOrder = $('#feature_tag_order');
            const initialFeatureOrder = @json($collection->featureTags->sortBy('pivot.position')->pluck('id')->toArray()); // pluck('id') = 並び替えたタグ一覧から id だけを取り出す

            // 🔹 主な機能タグ表示/非表示：select2の選択・解除時に動的表示制御
            $('#feature_tags').on('select2:select select2:unselect', function() {
                updateFeatureTagSortableVisibility();
            });

            // 🔹 並び順更新関数
            function updateFeatureOrder() {
                const ids = [];
                featureSortable.find('li').each(function() {
                    ids.push($(this).data('id')); // $(this).data('id') =  data-id="〇〇"の値を jQuery で取得
                });
                hiddenFeatureOrder.val(ids.join(','));
            }

            // 🔹 タグ追加関数
            function addFeatureTag(id, text) {
                const li = $(`
                    <li class="inline-flex items-center bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full cursor-move"
                        data-id="${id}">
                        <span class="mr-2">${text}</span>
                        <button type="button" class="remove-feature-tag hover:text-red-500 text-lg font-bold leading-none">×</button>
                    </li>
                `);

                // 🔸 ×ボタンを押したらその主な機能タグをリストから削除し、セレクトボックスの選択も解除して、順番を更新する処理
                li.find('.remove-feature-tag').on('click', function() {
                    li.remove();
                    const option = featureSelect.find(`option[value="${id}"]`);
                    option.prop('selected', false); // selected 解除
                    featureSelect.trigger('change'); // selected 解除を確定
                    // 🔹🔹 並び順更新関数
                    updateFeatureOrder();
                    // 🔹🔹 主な機能タグ表示/非表示
                    updateFeatureTagSortableVisibility();
                });

                // 🔸 作成した <li> 要素(主な機能タグの1つ)を、並び替えエリア(#feature-tag-sortable)の最後に追加する
                featureSortable.append(li);
                updateFeatureOrder();
            }

            // 🔹 初期表示(DBの順番)
            if (initialFeatureOrder.length > 0) {
                initialFeatureOrder.forEach(function(id) {
                    const option = featureSelect.find(`option[value="${id}"]`);
                    if (option.length > 0) {
                        addFeatureTag(id, option.text());
                    }
                });
            }

            // 🔹 並び替え可能にする
            new Sortable(featureSortable[0], {
                animation: 150,
                onEnd: updateFeatureOrder
            });

            // 🔹 選択時
            featureSelect.on('select2:select', function(e) {
                const id = e.params.data.id;
                const text = e.params.data.text;
                // 🔸 すでに選ばれているタグでなければ、新しく追加する
                if ($(`#feature-tag-sortable li[data-id="${id}"]`).length === 0) {
                    addFeatureTag(id, text);
                }
            });

            // 🔹 解除時
            featureSelect.on('select2:unselect', function(e) {
                $(`#feature-tag-sortable li[data-id="${e.params.data.id}"]`).remove();
                updateFeatureOrder();
            });
        });

        // ✅ 主な機能タグ表示/非表示
        function updateFeatureTagSortableVisibility() {
            const selectedCount = $('#feature_tags').find('option:selected').length;
            const wrapper = $('#feature-tag-sortable').closest('div'); // ulの親div
            if (selectedCount > 0) {
                wrapper.show();
            } else {
                wrapper.hide();
            }
        }
    </script>
    {{-- --- ⭐️ Select2 --- --}}


    <!-- ⭐️ SortableJSのCDNを追加 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script>
        // ✅ UUID(一意の識別子)生成
        window.generateUUID = function() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0,
                    v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        };
    </script>

    <script>
        // ⭐️ 画像プレビューの追加、削除、アップロード  --}}
        document.addEventListener("DOMContentLoaded",
            function() { // DOMContentLoaded = イベントを監視して処理を実行 | JavaScriptの実行が早すぎてimagePreviewContainerがnullになるのを防ぐ(JavaScriptはデフォルトでHTMLの読み込み中に実行される→まだHTMLのimagePreviewContainerが読み込まれていない場合、nullになってしまう)
                // ✅ 変数の初期化
                let selectedFiles = [];
                const mainImageContainer = document.getElementById("mainImageContainer");
                const mainImage = document.getElementById("mainImage");
                const imageInput = document.getElementById("image_path");
                const imagePreviewContainer = document.getElementById("imagePreviewContainer");
                const noImageSrc = "/storage/collection_images/noImage.jpg";
                let existingFiles = new Set();
                let existingImagePaths = {!! json_encode($collection->collectionImages->pluck('image_path')) !!}; // DBに保存されている画像のリストを取得(image_pathからファイル名を抽出)

                // ✅ テーブルのファイル名取得
                existingImagePaths.forEach(path => {
                    let fileName = path.split('_').pop(); // `_` の後のファイル名を取得
                    existingFiles.add(fileName);
                });
                console.log("🔥 既存ファイルリスト:", existingFiles);

                // ✅ 変数の初期化 既存画像の設定(クリックイベント & 削除ボタン追加)
                function setupExistingImages() {
                    document.querySelectorAll("#imagePreviewContainer div").forEach(
                        imageWrapper => { // imagePreviewContainer内のすべての<div>を取得
                            const imageId = imageWrapper.dataset.imageId; // dataset.imageId → data-image-id属性の値を取得
                            const img = imageWrapper.querySelector("img"); // imageWrapper内の<img>要素を取得
                            const imageSrc = img.src;

                            // 🔹 メイン画像を変更するときに使用
                            img.addEventListener("click", function() {
                                changeMainImage(imageSrc);
                            });

                            // 🔹 削除ボタン追記
                            if (!imageWrapper.querySelector("button")) {
                                const removeButton = createDeleteButton(() => { // createDeleteButton関数 = 削除ボタン生成
                                    removeExistingImage(imageWrapper, imageId,
                                        imageSrc); // removeExistingImage関数 = 既存画像の削除
                                });
                                imageWrapper.appendChild(removeButton);
                            }
                        });

                    // 🔹 サムネイルの処理(メイン画像変更、デザイン変更)
                    const thumbnails = document.querySelectorAll('.thumbnail'); // img要素取得
                    thumbnails.forEach((img, index) => {
                        const imageSrc = img.src;

                        img.addEventListener("click", function() {
                            changeMainImage(imageSrc);
                        });

                        if (index === 0) {
                            img.classList.add('shadow-lg', 'ring-1', 'ring-blue-300');
                        }
                    });
                }

                // ✅ 画像重複禁止
                imageInput.addEventListener("change", function(event) {
                    console.log("画像選択イベント発火");

                    // 🔹 イベントファイル確認
                    const files = event.target.files;
                    if (!files || files.length === 0) return;

                    // 🔹 新しく選択されたファイルを整理し、重複チェックを行うための準備
                    let newDataTransfer = new DataTransfer();
                    let duplicateFiles = [];
                    let newFilesToAdd = [];

                    // 🔹 重複チェック
                    for (let i = 0; i < files.length; i++) {
                        let fileName = files[i].name.trim();

                        if (existingFiles.has(fileName)) { // 既存画像と一致するファイル名があれば重複とみなす
                            duplicateFiles.push(fileName);
                        } else {
                            existingFiles.add(fileName);
                            newFilesToAdd.push(files[i]);
                        }
                    }

                    // 🔹 重複ファイルがある場合、アラートを出して選択をリセット
                    if (duplicateFiles.length > 0) {
                        alert(`⚠️ 以下のファイルはすでに登録されています。\n\n${duplicateFiles.join("\n")}`);
                        imageInput.value = ""; // 選択をリセット
                    }

                    // 🔹 新しいファイルのみDataTransferに追加
                    newFilesToAdd.forEach(file => {
                        newDataTransfer.items.add(file);
                    });

                    // 🔹 input[type="file"] の内容を更新
                    imageInput.files = newDataTransfer.files;
                    console.log("🔥 `imageInput.files` の内容:", imageInput.files);
                });

                // ✅ 画像プレビュー表示(新規アップロード時)
                function previewImages(event) {
                    console.log("画像選択イベント発火");
                    const input = event.target; // どの要素(input type="file")でイベントが発生したかを取得
                    const files = input
                        .files; // 選択されたファイルリストを取得。FileListは、input type="file"でユーザーが選択したファイルの一覧を表すオブジェクト。input.filesを取得すると、その中にFileListが入っている。

                    // 🔹 ファイル選択確認
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
                    selectedFiles.forEach(fileObj => dataTransfer.items.add(fileObj
                        .file
                    )); // fileObj = selectedFilesの各要素(オブジェクト) | fileObj.file = fileObjの中にあるファイル情報(input.files に入れるデータ) | dataTransfer.items.add(fileObj.file) = dataTransferにfileObj.fileを追加

                    // 🔹 選択されたファイルを配列に変換し、1つずつ処理
                    Array.from(files).forEach((file,
                        index
                    ) => { // filesは配列のようなオブジェクト(FileList)なので、直接forEach()やmap()を使えないことがある。Array.from(files)を使うとfilesを本物の配列に変換 できる。 | index = 現在の要素が何番目か(0 から始まるインデックス番号)が入る。
                        const reader = new FileReader(); // FileReader = ファイルの内容を読み取る
                        reader.onload = function(
                            e
                        ) { // onload = ファイルの読み込みが完了したときに実行される | e =「イベントオブジェクト」 | e.target.resultにBase64形式のデータが格納される
                            const imageId = "new_" + Date.now();
                            const fileName = file.name.trim(); // 空白削除(uniqueIdを生成時、無駄なスペースが混ざらないように)
                            const uniqueId = fileName + '_' + generateUUID(); // UUID
                            selectedFiles.push({
                                id: imageId,
                                file: file,
                                src: e.target.result
                            }); // e.target.result = 読み込んだファイルのデータが入る{今回は、画像のデータURL(reader.readAsDataURL(file);で作る)} | e =「イベントオブジェクト」 | reader.onload = 「ファイルの読み込みが完了したら実行する関数」
                            dataTransfer.items.add(file);

                            // 🔹 サムネイルを表示する要素を作成
                            const imageWrapper = document.createElement("div");
                            imageWrapper.classList.add("relative", "w-20", "h-20", "sm:w-24", "sm:h-24");

                            // 🔹 <img> タグを作成し、画像を設定する
                            const img = document.createElement("img");
                            img.src = e.target
                            .result; // e.target.result = 読み込んだファイルのデータが入る{画像のデータURL(reader.readAsDataURL(file);で作る)}
                            img.setAttribute('data-src', e.target.result); // サムネイルのdata-src
                            img.classList.add("thumbnail", "w-full", "h-full", "object-cover",
                                "object-center",
                                "rounded-lg", "cursor-pointer", "border", "border-gray-300",
                                "hover:shadow-lg", "transition");
                            img.onclick = function() {
                                changeMainImage(e.target.result); // メイン画像を変更するときに使用
                            };

                            // 🔹 削除ボタン(×)生成
                            const removeButton = createDeleteButton(() => {
                                removeNewImage(imageId, imageWrapper); // 新規アップロード画像の削除
                            });

                            // 🔹 サムネイル画像作成
                            imageWrapper.appendChild(img);
                            imageWrapper.appendChild(removeButton);
                            imagePreviewContainer.appendChild(imageWrapper);

                            // 🔹 追加画像をsaveImageOrder()へ送る準備
                            imageWrapper.dataset.fileName = fileName;
                            imageWrapper.dataset.uniqueId = uniqueId;
                            imageWrapper.dataset.imageId = null; // 新規画像なので`null`

                            // 🔹 メイン画像変更
                            const allImages = document.querySelectorAll("#imagePreviewContainer img");
                            if (allImages.length > 0) {
                                const lastImage = allImages[allImages.length - 1];
                                changeMainImage(lastImage.src);

                                // 一度全リセット
                                allImages.forEach(img => {
                                    img.classList.remove('shadow-lg', 'ring-1', 'ring-blue-300');
                                });
                                // 右下だけ選択
                                lastImage.classList.add('shadow-lg', 'ring-1', 'ring-blue-300');
                            }

                            // 🔹 画像の並び順を保存
                            saveImageOrder(); // 画像が追加された時に `image_order` を更新
                        };

                        // 🔹 readAsDataURL(file) → 画像データをBase64(URL)に変換
                        reader.readAsDataURL(file); // これにより、ファイルをサーバーにアップロードせずにブラウザ上でプレビューできる
                    });

                    // 🔹 input[type="file"]のfilesを更新
                    input.files = dataTransfer.files;
                }

                // ✅ 削除ボタン生成(共通)
                function createDeleteButton(removeFunction) {
                    const removeButton = document.createElement("button");
                    removeButton.textContent = "×";
                    removeButton.classList.add("absolute", "top-0", "right-0", "bg-black", "bg-opacity-50",
                        "text-white", "px-2", "py-1", "text-xs", "rounded-full", "hover:bg-opacity-70");
                    removeButton.onclick =
                        removeFunction; // removeFunction = 「関数を引数として受け取るための変数」 | removeNewImage()やremoveExistingImage()を入れる箱
                    return removeButton;
                }

                // ✅ 新規アップロード画像の削除
                function removeNewImage(imageId, imageWrapper) {
                    console.log(`削除する画像 ID: ${imageId}`);

                    // 🔹 メイン画像のsrcを取得(比較用)
                    const currentMainSrc = mainImage?.src; // mainImage が存在していれば .src を取得、存在しなければ undefined を返す
                    const targetImg = imageWrapper.querySelector("img");
                    const targetSrc = targetImg?.src;

                    // 🔹 `selectedFiles`から対象画像を削除
                    let removedImage = selectedFiles.find(image => image.id === imageId);
                    if (removedImage) {
                        let fileName = removedImage.file.name.trim();
                        existingFiles.delete(fileName); // 🔥 既存リストから削除
                        console.log("✅ `existingFiles` から削除:", fileName);
                    }

                    // 🔹 `selectedFiles`から対象の画像以外で再構成(=対象画像を削除)
                    selectedFiles = selectedFiles.filter(image => image.id !==
                        imageId); // filter() = 配列の中身を条件で絞り込むメソッド | selectedFilesをimageに代入して、selectedFilesのidを取得しているイメージ

                    // 🔹 `DataTransfer`を作成し、削除後のリストをセット
                    let dataTransfer = new DataTransfer();
                    selectedFiles.forEach(image => dataTransfer.items.add(image.file));
                    imageInput.files = dataTransfer.files;

                    // 🔹 imageWrapper削除 & メイン画像リセット
                    imageWrapper.remove(); // imageWrapper = サムネイルと削除ボタンを含むHTML要素

                    // 🔹 削除対象が選択中（＝メイン）なら、左上にリセット
                    if (currentMainSrc === targetSrc) {
                        resetMainImage();
                    }
                }

                // ✅ 既存画像の削除
                function removeExistingImage(imageWrapper, imageId, imageSrc) {
                    console.log(`既存画像 ID ${imageId} を削除`);
                    imageWrapper.remove();

                    // 🔹 `existingFiles`からファイル名を削除
                    let fullFileName = imageSrc.split('/').pop(); // フルパスからファイル名取得
                    let fileName = fullFileName.split('_').pop(); // `_` の後ろを取得（例: 6.jpg）
                    existingFiles.delete(fileName);
                    console.log("✅ `existingFiles` から削除（既存画像）:", fileName);

                    // 🔹 `<form>` を正しく取得
                    const form = imageInput.closest(
                        "form"
                    ); // closest("form") = imageInputから一番近いformを取得 | document.querySelector("form")だと、上から順に見てあったものを取得してしまうため
                    if (!form) {
                        console.error("❌ フォームが見つかりません！");
                        return;
                    }

                    // 🔹 削除する画像のIDが既にhidden input(<input type="hidden">)があるかチェック → 
                    let existingInput = form.querySelector(
                        `input[name="delete_images[]"][value="${imageId}"]`
                    ); // querySelector(`input[name="delete_images[]"][value="${imageId}"]`) = 条件に合うもの限定で取得
                    if (!existingInput) { // existingInputがない場合、
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

                    // 🔹 削除した画像がメイン画像ならリセット
                    if (mainImage.src === imageSrc) {
                        resetMainImage();
                    }
                }

                // ✅ メイン画像のリセット
                function resetMainImage() {
                    const allImages = document.querySelectorAll(
                        "#imagePreviewContainer img"); // #imagePreviewContainer内にあるすべてのimgタグを取得
                    if (allImages.length > 0) { // allImages.length > 0 → サムネイル画像が1つ以上ある場合
                        changeMainImage(allImages[0].src); // allImages[0].src = 1件目の画像
                    } else {
                        changeMainImage(noImageSrc);
                    }
                }

                // ✅ メイン画像変更
                function changeMainImage(src) {
                    console.log("changeMainImage が実行されました: ", src);
                    if (mainImage) {
                        mainImage.src = src;
                    }

                    // --- サムネイル処理 ---
                    // 🔹 全サムネイルから選択状態を解除
                    document.querySelectorAll('.thumbnail').forEach(img => {
                        img.classList.remove('shadow-lg', 'ring-1', 'ring-blue-300');
                    });

                    // 🔹 選択されたサムネイルに枠線と影を追加
                    const selected = Array.from(document.querySelectorAll('.thumbnail')).find(img => {
                        return img.getAttribute('data-src') === src || img.src === src; // 無名関数、照合
                    });

                    if (selected) {
                        selected.classList.add('shadow-lg', 'ring-1', 'ring-blue-300');
                    } else {
                        console.warn("サムネイル選択できず: ", src);
                    }
                    // --- サムネイル処理 ---
                }

                // ✅ 初期設定
                setupExistingImages();
                document.getElementById("image_path").addEventListener("change", previewImages);


                // {{-- --------- サムネイル移動、順番確定 --------- --}}
                // ✅ 画像の並び順を保存
                function saveImageOrder() { // 画像の並び順を保存する関数
                    let imageOrder = []; // 画像の順番を格納するための空配列を作成

                    // 🔹 画像の順番を格納するための空配列へ順番に保存
                    document.querySelectorAll("#imagePreviewContainer div").forEach((div,
                        index) => { // #imagePreviewContainer内のすべての<div>(画像ラッパー)を取得 | indexは0から順番につく
                        const imageId = div.dataset.imageId || null; // 既存画像は `imageId` を取得、新規画像は `null`
                        const fileName = div.dataset.fileName || "new_image";
                        const uniqueId = div.dataset.uniqueId || generateUUID(); // 新規画像の場合は `uniqueId` を生成

                        if (imageId) {
                            imageOrder.push({
                                fileName,
                                uniqueId,
                                id: imageId,
                                position: index
                            });
                        }
                    });
                    console.log("🚀 送信する並び順:", imageOrder);

                    // 🔹 既存のhidden inputを削除(重複を防いで、最新の画像順序データだけを送信)
                    document.querySelectorAll("input[name='image_order']").forEach(input => input.remove());

                    // 🔹 editForm確認
                    const form = document.getElementById("editForm");
                    if (!form) {
                        console.error("❌ フォームが見つかりません！");
                        return;
                    }

                    // 🔹 フォームにhidden inputを追加
                    const hiddenInput = document.createElement("input");
                    hiddenInput.type = "hidden";
                    hiddenInput.name = "image_order";
                    hiddenInput.value = JSON.stringify(
                        imageOrder); // オブジェクト配列を文字列化 | valueは文字列しかセットできないので、オブジェクトを文字列にする必要がある
                    form.appendChild(hiddenInput);
                    console.log("✅ hidden input に保存:", hiddenInput.value);
                }


                // ----------- SortableJS(ドラッグ&ドロップ)を適用 ----------- 
                // 🔹 imagePreviewContainer確認
                if (!imagePreviewContainer) {
                    console.error("❌ imagePreviewContainer が見つかりません！");
                    return;
                }

                // 🔹 SortableJS(ドラッグ&ドロップ)を適用
                const sortable = new Sortable(
                    imagePreviewContainer, { // new Sortable()を使ってimagePreviewContainer内の要素をドラッグ&ドロップ可能にする
                        animation: 150, // スムーズなアニメーション
                        ghostClass: "sortable-ghost", // ドラッグ中のスタイルを変更
                        onEnd: function() { // onEndイベント = 要素の移動が確定したときに発火
                            saveImageOrder();
                        },
                    });
                // ----------- SortableJS(ドラッグ&ドロップ)を適用 ----------- 



                const links = document.querySelectorAll('.toTechTagIndex');
                links.forEach(link => {
                    link.addEventListener('click', async function(e) { // async を使っているので、await が使える非同期関数。
                        e.preventDefault(); // 通常の遷移を止める

                        const formData = new FormData(editForm);
                        formData.append('return_url', window.location.href);

                        try {
                            await fetch("{{ route('admin.collections.storeSessionWithImage') }}", { // await でレスポンス完了を待つ。
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                },
                                body: formData
                            });

                            // fetch完了後に遷移
                            // → クリックされたリンクの遷移先へ手動でジャンプ。e.preventDefault() で止めたので、ここで明示的に遷移させる必要がある。
                            // window.location.href = "URL" と書くと、**そのURLにブラウザが遷移（＝画面がそのURLのページに切り替わる）
                            window.location.href = link.href;

                        } catch (error) {
                            console.error("送信エラー:", error);
                        }
                    });

                });

            });
    </script>
</x-app-layout>
