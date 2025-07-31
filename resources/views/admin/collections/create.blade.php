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
                        <form id="createForm" action="{{ route('admin.collections.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="container px-5 mx-auto">
                                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                    <div class="flex flex-wrap -m-2">
                                        {{-- タイトル --}}
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="title"
                                                    class="leading-7 text-sm text-gray-600">タイトル</label>
                                                <input type="text" id="title" name="title"
                                                    value="{{ old('title', session('collection.form_input.title')) }}"
                                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                            </div>
                                        </div>

                                        <!-- 画像アップロード -->
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="image_path"
                                                    class="leading-7 text-sm text-gray-600">画像</label>
                                                <!-- 見えない input -->
                                                <input multiple type="file" id="image_path" name="image_path[]"
                                                    class="hidden" accept=".jpg,.jpeg,.png,.webp,.avif">
                                                <!-- セッションの画像データを送信 -->
                                                @foreach (session('tmp_images', []) as $tmpImage)
                                                    <input type="hidden" name="tmp_images[]"
                                                        value="{{ $tmpImage }}">
                                                @endforeach
                                                @foreach (session('file_names', []) as $fileName)
                                                    <input type="hidden" name="session_file_names[]"
                                                        value="{{ $fileName }}">
                                                @endforeach
                                                <br>
                                                <!-- カスタムアップロードボタン -->
                                                <label for="image_path"
                                                    class="file-upload-btn inline-block px-4 py-1 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded-md shadow-sm cursor-pointer hover:bg-gray-200 active:bg-gray-300 transition">
                                                    ファイルを選択
                                                </label>
                                                @if ($errors->has('image_path'))
                                                    <x-input-error :messages="$errors->get('image_path')" class="mt-2" />
                                                @elseif($errors->has('tmp_images'))
                                                    <x-input-error :messages="$errors->get('tmp_images')" class="mt-2" />
                                                @endif
                                                <!-- サムネイル一覧 -->
                                                <div class="relative mt-4">
                                                    <label class="leading-7 text-sm text-gray-600">選択した画像：</label>
                                                    <div id="imagePreviewContainer"
                                                        class="grid grid-cols-3 gap-3 sm:grid-cols-4 sm:gap-4 md:grid-cols-4 md:gap-4 xl:grid-cols-5 xl:gap-5 w-full place-items-center">
                                                        <!-- 画像プレビューがここに追加される -->
                                                    </div>
                                                </div>
                                                <!-- 大きなプレビュー画像 -->
                                                <div id="mainImageContainer" class="justify-center mt-4 hidden">
                                                    <img id="mainImage"
                                                        class="w-3/5 h-auto object-cover border rounded-lg"
                                                        src="" alt="メイン画像">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- URL --}}
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="url_webapp" class="leading-7 text-sm text-gray-600">WebApp
                                                    URL</label>
                                                <input type="url" id="url_webapp" name="url_webapp"
                                                    value="{{ old('url_webapp', session('collection.form_input.url_webapp')) }}"
                                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <x-input-error :messages="$errors->get('url_webapp')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="url_github" class="leading-7 text-sm text-gray-600">GitHub
                                                    URL</label>
                                                <input type="url" id="url_github" name="url_github"
                                                    value="{{ old('url_github', session('collection.form_input.url_github')) }}"
                                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <x-input-error :messages="$errors->get('url_github')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="url_qiita" class="leading-7 text-sm text-gray-600">Qiita
                                                    URL</label>
                                                <input type="url" id="url_qiita" name="url_qiita"
                                                    value="{{ old('url_qiita', session('collection.form_input.url_qiita')) }}"
                                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <x-input-error :messages="$errors->get('url_qiita')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="url_youtube" class="leading-7 text-sm text-gray-600">YouTube
                                                    URL</label>
                                                <input type="url" id="url_youtube" name="url_youtube"
                                                    value="{{ old('url_youtube', session('collection.form_input.url_youtube')) }}"
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
                                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-y leading-6 transition-colors duration-200 ease-in-out">{{ old('description', session('collection.form_input.description')) }}</textarea>
                                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                            </div>
                                        </div>

                                        {{-- 技術タグ --}}
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="tech_type"
                                                    class="leading-7 text-sm text-gray-600">技術タグ(複数選択OK)</label>
                                                <select name="technology_tag_ids[]" id="tech_type" multiple
                                                    class="rounded-md js-multiple-tag-select sortable-tech-tags">
                                                    @if (!$technologyTags->isEmpty())
                                                        @foreach ($technologyTags->typeLabels as $type => $label)
                                                            <optgroup label="▼ {{ $label }}">
                                                                {{-- セレクトボックス内でカテゴリを分ける --}}
                                                                @foreach ($technologyTags->where('tech_type', $type) as $technologyTag)
                                                                    {{-- tech_typeカラムの値が$typeと一致するレコードだけを絞り込み --}}
                                                                    <option value="{{ $technologyTag->id }}"
                                                                        {{ collect(old('technology_tag_ids', session('collection.form_input.technology_tag_ids', [])))->contains($technologyTag->id) ? 'selected' : '' }}>
                                                                        {{-- selectedの分岐コード --}}
                                                                        {{ $technologyTag->name }}
                                                                    </option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                {{-- 並び替え欄 --}}
                                                <div id="techTagSortableWrapper" class="mt-2">
                                                    <div class="leading-7 text-sm text-gray-600">
                                                        ↓ タグの並び替え<br>↓ 色ごと & 並び替え順で表示されます
                                                    </div>
                                                    {{-- 並び替え用リスト --}}
                                                    <ul id="technology-tag-sortable"
                                                        class="p-2 border border-gray-300 rounded bg-gray-100 min-h-[40px] flex flex-wrap gap-2">
                                                        {{-- JSでliを追加 --}}
                                                    </ul>
                                                    {{-- 並び順を送るhidden input --}}
                                                    <input type="hidden" name="technology_tag_order"
                                                        id="technology_tag_order">
                                                </div>
                                                <x-input-error :messages="$errors->get('technology_tag_ids')" class="mt-2" />
                                                <div class="text-right">
                                                    <a href="{{ route('admin.technology-tags.create') }}"
                                                        class="toTechTagCreate leading-7 text-sm text-gray-600 underline hover:text-gray-900">技術タグを作りたい場合はこちら</a><br>
                                                    <a href="{{ route('admin.technology-tags.index') }}"
                                                        class="toTechTagIndex leading-7 text-sm text-gray-600 underline hover:text-gray-900">技術タグ一覧はこちら</a>{{-- ボタン単体は外に置く --}}
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
                                                    @foreach ($featureTags as $featureTag)
                                                        <option value="{{ $featureTag->id }}"
                                                            {{ collect(old('feature_tag_ids', session('collection.form_input.feature_tag_ids', [])))->contains($featureTag->id) ? 'selected' : '' }}>
                                                            {{ $featureTag->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                {{-- 並び替え欄 --}}
                                                <div id="featureTagSortableWrapper" class="mt-2">
                                                    <div class="leading-7 text-sm text-gray-600">↓ タグの並び替え</div>
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
                                                        class="toFeatureTagCreate leading-7 text-sm text-gray-600 underline hover:text-gray-900">主な機能タグを作りたい場合はこちら</a><br>
                                                    <a href="{{ route('admin.feature-tags.index') }}"
                                                        class="toFeatureTagIndex leading-7 text-sm text-gray-600 underline hover:text-gray-900">主な機能タグ一覧はこちら</a>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 公開、優先度 --}}
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="is_public"
                                                    class="leading-7 text-sm text-gray-600">公開種別</label>
                                                <input type="radio" name="is_public" value="0"
                                                    {{ old('is_public', session('collection.form_input.is_public')) == '0' ? 'checked' : '' }}
                                                    class="cursor-pointer">非公開
                                                <input type="radio" name="is_public" value="1"
                                                    {{ old('is_public', session('collection.form_input.is_public')) == '1' ? 'checked' : '' }}
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
                                                        {{ old('position', session('collection.form_input.position')) == '0' ? 'selected' : '' }}>
                                                        デフォルト</option>
                                                    <option value="1"
                                                        {{ old('position', session('collection.form_input.position')) == '1' ? 'selected' : '' }}>
                                                        高め</option>
                                                    <option value="2"
                                                        {{ old('position', session('collection.form_input.position')) == '2' ? 'selected' : '' }}>
                                                        topページ</option>
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
                                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-y leading-6 transition-colors duration-200 ease-in-out">{{ old('private_memo', session('collection.form_input.private_memo')) }}</textarea>
                                                <x-input-error :messages="$errors->get('private_memo')" class="mt-2" />
                                            </div>
                                        </div>

                                        <div class="w-full mt-8">
                                            <button
                                                class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
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
        let techTypeMap = @json($techTypeMapForJS); // コントローラーから取得
        let typeToColorClass = { // 技術タグの色
            frontend: ['bg-blue-100', 'text-blue-800'],
            backend: ['bg-green-100', 'text-green-800'],
            db: ['bg-red-100', 'text-red-800'],
            infra: ['bg-yellow-100', 'text-yellow-800'],
            build: ['bg-pink-100', 'text-pink-800'],
            tool: ['bg-purple-100', 'text-purple-800'],
            default: ['bg-gray-100', 'text-gray-800'],
        };

        // ✅ 検索結果
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

        // ✅ 技術タグの並び替え処理
        $(document).ready(function() {
            // 🔹 並べ替え欄の表示/非表示：初期表示時(セッション復元にも対応)
            updateTechTagSortableVisibility();

            const select = $('#tech_type');
            const sortableArea = $('#technology-tag-sortable');
            const hiddenOrder = $('#technology_tag_order');

            // 🔹 並べ替え欄の表示/非表示：ユーザー操作時の動的切り替え時
            $('#tech_type').on('select2:select select2:unselect', function() {
                updateTechTagSortableVisibility();
            });

            // 🔹 初期復元
            select.find('option:selected').each(function() {
                const id = $(this).val();
                if ($(`#technology-tag-sortable li[data-id="${id}"]`).length === 0) {
                    addTag(id, $(this).text());
                }
            });

            // 🔹 選択時にli追加
            select.on('select2:select', function(e) { // on = 特定のイベントが発生したときに、指定した関数を実行「select.on('イベント名', 関数);」
                const id = e.params.data.id; // Select2 のイベントでは、e.params というプロパティを使って選択されたアイテムの情報が取得可能
                const text = e.params.data.text;
                if ($(`#technology-tag-sortable li[data-id="${id}"]`).length === 0) {
                    addTag(id, text);
                }
            });

            // 🔹 解除時にli削除
            select.on('select2:unselect', function(e) {
                $(`#technology-tag-sortable li[data-id="${e.params.data.id}"]`).remove();
                updateOrder();
            });

            // 🔹 技術タグの並び替えリストにタグを追加する処理
            function addTag(id, text) {
                // 🔸 重複防止のためのチェック処理
                if ($(`#technology-tag-sortable li[data-id="${id}"]`).length > 0) return;

                const typeRaw = techTypeMap[id.toString()];
                const type = typeof typeRaw === 'string' ? typeRaw.trim() : 'default';
                const [bgColor, textColor] = typeToColorClass[type] || typeToColorClass.default;

                console.log("🧪 techType:", type);
                console.log("🧪 class:", bgColor, textColor);

                const li = $(`
                    <li class="inline-flex items-center ${bgColor} ${textColor} text-sm px-3 py-1 rounded-full cursor-move"
                        data-id="${id}">
                        <span class="mr-2">${text}</span>
                        <button type="button" class="remove-tag-btn hover:text-red-500 text-lg font-bold leading-none">×</button>
                    </li>
                `);

                // 🔸 タグ削除
                li.find('.remove-tag-btn').on('click', function() {
                    li.remove();
                    const option = $('#tech_type option[value="' + id + '"]');
                    option.prop('selected', false); // false は、その <option> の選択状態を外す
                    $('#tech_type').trigger(
                        'change'
                        ); //selected 属性を false にしただけでは Select2 の表示が更新されない。trigger('change') を呼ぶことで、Select2 側に「選択状態が変わったよ」と通知して再描画させている。

                    updateOrder();
                    // 🔹🔹 並べ替え欄の表示/非表示
                    updateTechTagSortableVisibility();
                });

                $('#technology-tag-sortable').append(li); // #technology-tag-sortable に li を表示
                updateOrder();
            }

            // 🔹 並び順の保存
            function updateOrder() {
                const ids = [];
                sortableArea.find('li').each(function() {
                    ids.push($(this).data('id'));
                });
                hiddenOrder.val(ids.join(',')); // .val() = フォーム要素の値を設定するメソッド(hidden input の値をセット)
            }

            // 🔹 タグの <li> 要素をドラッグ＆ドロップで並び替え可能にする処理
            new Sortable(sortableArea[
                0], { // new Sortable(...) = 並び替えできるようにするための命令 | sortableArea[0] = 並び替えしたいリスト（DOMの<ul>）
                animation: 150,
                onEnd: updateOrder // ドラッグ&ドロップしたときに updateOrder() 関数を実行
            });
        });

        // ✅ ページを読み込んだときに、セッションに保存された技術タグの並び順を復元
        document.addEventListener('DOMContentLoaded', function() {
            // 🔹 並び順を保存
            function updateTechnologyTagOrder() {
                const order = Array.from(document.querySelectorAll("#technology-tag-sortable li"))
                    .map(li => li.dataset.id); // map() = 新しい配列を作る
                document.getElementById('technology_tag_order').value = order.join(',');
            }

            // 🔹 タグIDから名前を取得する辞書（例: {1: 'HTML', 2: 'CSS' ...}）
            const techTagMap = @json($technologyTags->pluck('name', 'id')); // pluck = id をキー、name を値とする 連想配列を作成
            const savedOrder = @json(session('collection.form_input.technology_tag_order'));
            const ul = document.getElementById("technology-tag-sortable");

            // 🔹 セッションなどに保存されていた技術タグの並び順を <ul> 要素に復元する
            if (Array.isArray(savedOrder) && ul) {
                savedOrder.forEach(id => {
                    // 🔸 重複禁止
                    if (ul.querySelector(`li[data-id="${id}"]`)) return;

                    const name = techTagMap[id];
                    if (!name) return;

                    const type = techTypeMap[id.toString()] || 'default';
                    const [bgColor, textColor] = typeToColorClass[type] || typeToColorClass.default;

                    // 🔸 HTML, CSS生成
                    const li = document.createElement("li");
                    li.className =
                        `inline-flex items-center ${bgColor} ${textColor} text-sm px-3 py-1 rounded-full cursor-move`;
                    li.dataset.id = id;

                    const span = document.createElement("span");
                    span.className = "mr-2";
                    span.textContent = name;

                    const button = document.createElement("button");
                    button.type = "button";
                    button.className = "remove-tag-btn hover:text-red-500 text-lg font-bold leading-none";
                    button.textContent = "×";

                    // 🔸 「×」クリック時の処理
                    button.addEventListener("click", () => {
                        li.remove();
                        const option = document.querySelector(`#tech_type option[value="${id}"]`);
                        if (option) {
                            option.selected = false; // 選択解除
                            $('#tech_type').trigger('change'); // 「選択解除」を完了させる
                        }

                        updateTechnologyTagOrder(); // 🔹 並び順を保存
                    });

                    // 🔸 要素をHTMLに追加する処理
                    li.appendChild(span);
                    li.appendChild(button);
                    ul.appendChild(li);
                });

                updateTechnologyTagOrder(); // 初期のhidden inputも更新 | 🔹 並び順を保存
            }
        });

        // ✅ 並べ替え欄の表示/非表示
        function updateTechTagSortableVisibility() {
            const selectedCount = $('#tech_type').find('option:selected').length;
            const wrapper = $('#techTagSortableWrapper');
            if (selectedCount > 0) {
                wrapper.show();
            } else {
                wrapper.hide();
            }
        }

        // ✅ 主な機能タグの並び替え処理
        $(document).ready(function() {
            // 🔹 並べ替え欄の表示/非表示：初期表示時(セッション復元にも対応)
            updateFeatureTagSortableVisibility();

            const featureSelect = $('#feature_tags');
            const featureSortableArea = $('#feature-tag-sortable');
            const featureHiddenOrder = $('#feature_tag_order');

            // 🔹 並べ替え欄の表示/非表示：ユーザー操作時の動的切り替え時
            $('#feature_tags').on('select2:select select2:unselect', function() {
                updateFeatureTagSortableVisibility();
            });

            // 🔹 初期復元
            featureSelect.find('option:selected').each(function() {
                const id = $(this).val();
                const text = $(this).text();
                // 🔸 二重チェック(存在しないときだけタグをリストに追加)
                if ($(`#feature-tag-sortable li[data-id="${id}"]`).length === 0) {
                    // 🔹🔹 li を追加する処理
                    addFeatureTag(id, text);
                }
            });

            // 🔹 選択時に li 追加
            featureSelect.on('select2:select', function(e) { // on = select2:select イベントが発生したら、この関数を実行
                const id = e.params.data.id; // Select2 イベントでは、e.params プロパティを使って選択されたアイテムの情報が取得可能
                const text = e.params.data.text;
                // 🔸 二重チェック(存在しないときだけタグをリストに追加)
                if ($(`#feature-tag-sortable li[data-id="${id}"]`).length === 0) {
                    addFeatureTag(id, text);
                }
            });

            // 🔹 解除時に li 削除
            featureSelect.on('select2:unselect', function(e) {
                $(`#feature-tag-sortable li[data-id="${e.params.data.id}"]`).remove();
                // 🔸 並び順更新
                updateFeatureOrder();
            });

            // 🔹 並び順更新
            function updateFeatureOrder() {
                const ids = [];
                featureSortableArea.find('li').each(function() {
                    ids.push($(this).data('id'));
                });
                featureHiddenOrder.val(ids.join(','));
            }

            // 🔹 li を追加する処理
            function addFeatureTag(id, text) {
                const li = $(
                    `<li class="inline-flex items-center bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full cursor-move" data-id="${id}">
                        <span class="mr-2">${text}</span>
                        <button type="button" class="remove-tag-btn hover:text-red-500 text-lg font-bold leading-none">×</button>
                    </li>`
                );

                // 「×ボタン」
                li.find('.remove-tag-btn').on('click', function() {
                    li.remove();
                    const option = featureSelect.find(`option[value="${id}"]`);
                    option.prop('selected', false); // false は、その <option> の選択状態を外す
                    featureSelect.trigger(
                        'change'
                        ); // selected 属性を false にしただけでは Select2 の表示が更新されない。trigger('change') を呼ぶことで、Select2 側に「選択状態が変わったよ」と通知して再描画させている。
                    // 🔹🔹 更新
                    updateFeatureOrder();
                    // 🔹🔹 並べ替え欄の表示/非表示
                    updateFeatureTagSortableVisibility();
                });

                featureSortableArea.append(li);
                updateFeatureOrder();
            }

            // 🔹 SortableJS 適用
            new Sortable(featureSortableArea[0], {
                animation: 150,
                onEnd: updateFeatureOrder,
            });

            // 🔹 セッションからの復元（optional: Bladeから渡すセッションデータ）
            const savedFeatureOrder = @json(session('collection.form_input.feature_tag_order'));
            const featureTagMap = @json($featureTags->pluck('name', 'id'));

            if (Array.isArray(savedFeatureOrder)) {
                savedFeatureOrder.forEach(id => {
                    if (featureSortableArea.find(`li[data-id="${id}"]`).length > 0) return;
                    const name = featureTagMap[id];
                    if (name) addFeatureTag(id, name);
                });
            }
        });

        // ✅ 主な機能タグの並び替え欄の表示/非表示処理
        function updateFeatureTagSortableVisibility() {
            const selectedCount = $('#feature_tags').find('option:selected').length;
            const wrapper = $('#feature-tag-sortable').closest('div'); // ulの親(並び替え欄)
            if (selectedCount > 0) {
                wrapper.show();
            } else {
                wrapper.hide();
            }
        }
    </script>


    {{-- --- ⭐️ Select2 --- --}}


    {{-- ✅ SortableJSのCDNを追加 --}}
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

        // ✅ セッションから画像データを取得
        let sessionImages = {!! json_encode(session('tmp_images', [])) !!};
        let sessionFileNames = {!! json_encode(session('file_names', [])) !!};
        // let sessionImageOrder = {!! json_encode(session('image_order', [])) !!};
        let sessionImageOrder = {!! json_encode(session('image_order', [])) !!};
        let existingFiles = new Set([...{!! json_encode(session('file_names', [])) !!}]); // 既存の画像リストを取得（セッション & 選択済み）

        // ✅ セッション画像をpositionの昇順でソート
        sessionImageOrder.sort((a, b) => a.position - b.position); // a.positionがb.positionより小さい場合は負の値を返す(aがbより前に来る)

        console.log("🔥 セッションから復元した画像リスト:", sessionImages);
        console.log("🔥 セッションから復元したファイル名リスト:", sessionFileNames);
        console.log("🔥 セッション画像順序:", sessionImageOrder);

        // ⭐️ 画像プレビュー & 削除 & 重複禁止 & 並び替え & 画像セッション削除
        document.addEventListener("DOMContentLoaded", function() { // これがないと、HTMLの読み込み前にJavaScriptが実行され、エラーになることがある
            // ✅ 変数の初期化
            let
                selectedFiles = []; // 選択した画像のデータを保持(JavaScriptでは、input type="file"のfilesを直接変更できないため、selectedFilesにデータを保持しておく)
            const mainImageContainer = document.getElementById("mainImageContainer"); // 「大きなプレビュー画像」div要素
            const mainImage = document.getElementById("mainImage"); // 「大きなプレビュー画像」img要素
            const imagePreviewContainer = document.getElementById("imagePreviewContainer");
            const imageInput = document.getElementById("image_path"); // <input type="file">
            const tmpImageInput = document.getElementById("tmp_image");
            // let dataTransfer = new DataTransfer();

            // ✅ セッションから画像を復元
            if (sessionImages.length > 0) {
                console.log("セッションから画像を復元:", sessionImages);
                // 🔥 ここでクリアすればOK！
                selectedFiles = [];
                imagePreviewContainer.innerHTML = "";

                sessionImageOrder.forEach((sessionImage, index) => {
                    // let sessionFileName = sessionFileNames[index] || "unknown";
                    let fileName = sessionImage.fileName;
                    let imageSrc = sessionImage.src;
                    previewImages(imageSrc, fileName, true, null, null, index);
                });
            }

            // ✅ 画像を追加
            imageInput.addEventListener("change", function(event) {
                console.log("画像選択イベント発火");
                const files = event.target.files;
                if (!files || files.length === 0) return;

                // 🔹 既存のファイルをDataTransferに追加（nullでないことを確認）
                let newDataTransfer = new DataTransfer();
                selectedFiles.forEach(fileObj => {
                    if (fileObj.file) { // `file`がnullでない場合のみ追加
                        newDataTransfer.items.add(fileObj.file);
                    }
                });

                // 🔹 選択された各ファイルについて重複チェック
                let duplicateFiles = []; // 重複したファイル名を格納する配列
                let newFilesToAdd = []; // 新規追加するファイルのリスト
                for (let i = 0; i < files.length; i++) {
                    let fileName = files[i].name.trim();

                    if (existingFiles.has(fileName)) { // すでに既存の画像リストがある場合(セッション & 選択済み)
                        duplicateFiles.push(fileName); // 重複したファイル名を格納する配列へ格納
                    } else {
                        existingFiles.add(fileName); // 重複がなかった場合のみ追加
                        newFilesToAdd.push(files[i]); // 新しいファイルとして追加
                    }
                }

                // 🔹 重複したファイルがある場合、すべてのファイル名をアラート表示
                if (duplicateFiles.length > 0) {
                    alert(`⚠️ 今選択した以下のファイルはすでに選択されています。\n\n${duplicateFiles.join("\n")}`);
                    imageInput.value = ""; // 選択をリセット
                }

                // 🔹 新しい画像をpreviewImages()へ
                // Array.from(files).forEach(file => {
                newFilesToAdd.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImages(e.target.result, file.name, false, newDataTransfer, file,
                            null);
                    };
                    reader.readAsDataURL(file);
                });

                // 🔹 input[type="file"]のfilesを更新
                imageInput.files = newDataTransfer.files;
                console.log("🔥 `imageInput.files` の内容:", imageInput.files);
            });

            // ✅ プレビュー表示
            function previewImages(imageSrc, fileName, isSessionImage = false, dataTransfer = null, file = null,
                position) {
                const imageId = "image_" + generateUUID();
                fileName = fileName.trim(); // 空白削除(uniqueIdを生成時、無駄なスペースが混ざらないように)
                let uniqueId = generateUUID() + '_' + fileName; // UUID

                // 🔹 既存のDataTransferがnullの場合、新しく作成(セッションの場合)
                if (!dataTransfer) {
                    dataTransfer = new DataTransfer();
                }

                // 🔹 セッション画像ならstorageパスを付与
                if (isSessionImage) {
                    imageSrc = "/storage/" + imageSrc;
                } else if (file) {
                    dataTransfer.items.add(file); // 新規アップロードの画像のみ追加
                }

                // 🔹 現在選択されている画像のリストを管理BOXへ保存
                selectedFiles.push({
                    id: imageId,
                    uniqueId,
                    file: file,
                    src: imageSrc
                });
                console.log("✅ 追加後の selectedFiles:", selectedFiles); // selectedFiles の状態を確認

                // 🔹 サムネイルを表示する要素を作成
                const imageWrapper = document.createElement("div");
                imageWrapper.classList.add("relative", "w-20", "h-20", "sm:w-24", "sm:h-24"); // sm以上24px
                imageWrapper.dataset.imageId = imageId; // dataset にIDをセット
                imageWrapper.dataset.fileName = fileName; // `fileName` をセット
                imageWrapper.dataset.uniqueId = uniqueId; // `uniqueId` をセット

                // 🔹 <img>タグを作成し、画像を設定
                const img = document.createElement("img");
                img.src = imageSrc;
                img.setAttribute('data-src', imageSrc);
                img.classList.add("w-full", "h-full", "object-cover", "object-center", "rounded-lg",
                    "cursor-pointer", "border", "border-gray-300", "hover:shadow-lg", "transition",
                    "thumbnail"); // オリジナルのclass。サムネイルに色をつけるため。
                img.id = imageId;
                img.onclick = function() {
                    changeMainImage(imageSrc);
                };

                // 🔹 削除ボタンの作成
                const removeButton = document.createElement("button");
                removeButton.textContent = "×";
                removeButton.classList.add("absolute", "top-0", "right-0", "bg-black", "bg-opacity-50",
                    "text-white", "px-2", "py-1", "text-xs", "rounded-full", "hover:bg-opacity-70");
                removeButton.onclick = function(event) {
                    event.preventDefault(); // ページのリロードを防ぐ
                    console.log(`🛠 削除ボタンが押された - imageId: ${imageId}`);
                    removeImage(imageId, imageSrc);
                };

                // 🔹 サムネイルを表示する要素の子要素に、img要素、×ボタンを追加
                imageWrapper.appendChild(img); // img要素をimageWrapperに追加。これでimageWrapperの中に画像が表示される。
                imageWrapper.appendChild(removeButton); // 画像の横に削除ボタンが表示される

                // 🔹 プレビュータグにimageWrapperを追加(プレビュー表示)
                imagePreviewContainer.appendChild(imageWrapper);

                // 🔹 画像追加ごとに大きなプレビューを追加画像に変更
                changeMainImage(imageSrc);
                mainImageContainer.classList.remove("hidden");
                mainImageContainer.classList.add("flex");

                // 🔹 input[type="file"]のfilesを更新
                if (!isSessionImage) {
                    imageInput.files = dataTransfer.files; // ユーザーがアップロードしたファイルのリストをinput[type="file"]に反映させる
                    console.log("🔥 `imageInput.files` の内容:", imageInput.files);
                }

                // 🔹 画像が1枚だけのとき、サムネイルに色をつける
                if (imagePreviewContainer.querySelectorAll('img.thumbnail').length === 1) { // length = 見つかった画像の数
                    img.classList.add('shadow-lg', 'ring-1', 'ring-blue-300');
                }
            };

            // ✅ 画像を削除
            function removeImage(imageId, imageSrc) {
                console.log(`画像 ${imageId} を削除`);
                console.log("🔍 現在の selectedFiles:", selectedFiles); // 現在の selectedFiles を確認

                // 🔹 削除対象の画像情報を取得
                let removedImage = selectedFiles.find(image => image.id === imageId);

                // 🔹 removedImageがない場合、処理終了
                if (!removedImage) {
                    console.error(`❌ 削除対象の画像が見つかりません - imageId: ${imageId}`);
                    return;
                }

                // 🔹 existingFiles(重複アラート)から削除
                if (removedImage.file) { // 削除対象が、新しくアップロードした画像の場合
                    existingFiles.delete(removedImage.file.name.trim());
                    console.log("✅ `existingFiles` から削除:", removedImage.file.name);
                } else { // セッション画像の場合
                    let fullFileName = removedImage.src.split('/').pop(); // フルファイル名を取得
                    let fileName = fullFileName.split('_').pop(); // 最後の `_` の後ろを取得（6.jpg）
                    existingFiles.delete(fileName);
                    console.log("✅ `existingFiles` から削除（セッション画像）:", fileName);
                }

                // 🔹 `selectedFiles`から対象の画像以外で再構成(=対象画像を削除)
                selectedFiles = selectedFiles.filter(image => image.id !==
                    imageId); // filter() = 配列の中身を条件で絞り込むメソッド | selectedFilesをimageに代入して、selectedFilesのidを取得しているイメージ
                // 🔍 削除後の selectedFiles を確認
                console.log("✅ 削除後の selectedFiles:", selectedFiles);

                // 🔹 `DataTransfer`を作成し、削除後のリストをセット
                let dataTransfer = new DataTransfer();
                selectedFiles.forEach(image => {
                    if (image.file) { // `file`がnullでない場合のみ追加
                        dataTransfer.items.add(image.file);
                    }
                });

                // 🔹 `input.files`を更新
                imageInput.files = dataTransfer.files;

                // 🔹 DOMから該当の画像を削除
                const imageElement = document.getElementById(imageId);
                if (imageElement) {
                    imageElement.parentElement.remove();
                }

                // 🔹 メイン画像のリセット(メイン画像を削除の場合のみ、最新画像をメインにする。それ以外はそのまま継続。)
                const mainSrcFileName = mainImage.src.split('/').pop();
                const removedSrcFileName = imageSrc.split('/').pop();

                if (mainSrcFileName === removedSrcFileName) {
                    const allImages = document.querySelectorAll("#imagePreviewContainer img");
                    if (allImages.length > 0) { // サムネイルが1枚以上ある場合
                        const lastImage = allImages[allImages.length - 1]; // 最新サムネイルを取得(右下)
                        const fallbackSrc = lastImage.getAttribute('data-src') || lastImage.src;
                        changeMainImage(fallbackSrc);
                    } else {
                        mainImage.src = "";
                        mainImageContainer.classList.add("hidden");
                    }
                }

                // 🔹 セッション画像を削除するためにサーバーにリクエスト送信
                if (!removedImage.file) { // ファイルオブジェクトがnullならセッション画像
                    removeSessionImage(removedImage.src);
                    console.log("🚀 サーバーへ削除リクエスト:", imageSrc);
                }

                // 🔹 フォームの<input>を更新
                updateSessionImagesInput();
                // 🔹 画像の並び順を更新
                updateImageOrder();
            }

            // ✅ セッション画像削除後のフォームの<input>を更新
            function updateSessionImagesInput() {
                // 🔹 createForm取得
                let form = document.getElementById("createForm");
                if (!form) {
                    console.error("❌ createForm が見つかりません！");
                    return;
                }

                // 🔹 `tmp_images[]`の既存`hidden input`を削除(一度全てのtmp_images[]の<input>を削除して、最新の画像リストで再生成するため)
                document.querySelectorAll("input[name='tmp_images[]']").forEach(input => input.remove());

                // 🔹 セッション画像だけを抽出して、適切な形に変換する処理
                let tmpImages = selectedFiles
                    .filter(image => !image.file) // セッション画像だけを抽出
                    .map(image => image.src.replace("/storage/", "")); // `storage/`を削除
                console.log("🔥 削除後の `tmp_images[]`:", tmpImages);

                // 🔹 セッション画像がoの場合、処理終了
                if (tmpImages.length === 0) {
                    console.log("⚠️ セッション画像がゼロなので、`tmp_images[]` を送信しない");
                    return;
                }

                // 🔹 セッション画像をフォームに送信するために、hiddenの<input>要素を動的に再度追加(最新の画像リストで再生成)
                tmpImages.forEach(imageSrc => {
                    let hiddenInput = document.createElement("input");
                    hiddenInput.type = "hidden";
                    hiddenInput.name = "tmp_images[]";
                    hiddenInput.value = imageSrc;
                    form.appendChild(hiddenInput);
                });
                console.log("✅ `tmp_images[]` 更新後:", document.querySelectorAll("input[name='tmp_images[]']"));
            }

            // ✅ 画像の並び順を更新
            function updateImageOrder() {
                saveImageOrder(); // `saveImageOrder()`を呼び出して並び順を更新
            }

            // ✅ メインプレビュー変更
            function changeMainImage(src) {
                console.log("🚀 修正前の削除リクエスト:", src);

                // 🔹 `src`が`tmp/xxx.jpg`形式なら`/storage/tmp/xxx.jpg`に変換
                if (src.startsWith("tmp/")) {
                    src = "/storage/" + src;
                }

                // 🔹 `collections/`が勝手に入っていたら削除
                if (src.includes("collections")) {
                    src = src.replace("collections/", "");
                }

                // 🔹 メイン画像を変更
                mainImage.src = src;
                mainImageContainer.classList.remove("hidden");
                mainImageContainer.classList.add("flex");

                // 一度全ての選択状態をリセット
                document.querySelectorAll('.thumbnail').forEach(img => {
                    img.classList.remove('shadow-lg', 'ring-1', 'ring-blue-300');
                });

                // ✅ src と data-src を比較 → 合致サムネイルに css 追記
                const selected = Array.from(document.querySelectorAll('.thumbnail')).find(img => {
                    return img.getAttribute('data-src') === src;
                });
                if (selected) {
                    selected.classList.add('shadow-lg', 'ring-1', 'ring-blue-300');
                }
            }

            // ✅ セッション画像を削除するための関数
            function removeSessionImage(imageSrc) {
                // 🔹 サーバーにリクエストを送信して、セッションに保存された画像を削除する
                fetch('/remove-session-image', { // fetchを使って/remove-session-imageにリクエストを送る
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json', // データ形式をJSONに指定
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content') // CSRFトークンを設定
                        },
                        body: JSON.stringify({
                            tmp_image: imageSrc
                        }) // 削除する画像のパスをJSON形式にして送信
                    })
                    .then(response => response.json()) // サーバーからのレスポンスを JSON に変換
                    .then(data => {
                        console.log("サーバーからの応答:", data.message);
                    })
                    .catch(error => {
                        console.error("エラー:", error);
                    });
            }

            // ----------- サムネイル移動、順番確定 -----------
            // ✅ 画像の並び順を保存
            function saveImageOrder() { // 画像の並び順を保存する関数
                let imageOrder = []; // 画像の順番を格納するための空配列を作成

                // 🔹 画像の順番を格納するための空配列へ順番に保存
                document.querySelectorAll("#imagePreviewContainer div").forEach((div,
                    index) => { // #imagePreviewContainer内のすべての<div>(画像ラッパー)を取得
                    const fileName = div.dataset.fileName;
                    const uniqueId = div.dataset.uniqueId;

                    if (uniqueId) {
                        imageOrder.push({
                            fileName,
                            uniqueId,
                            position: index
                        });
                    }
                });
                console.log("🚀 送信する並び順:", imageOrder);

                // 🔹 既存のhidden inputを削除(重複を防いで、最新の画像順序データだけを送信)
                document.querySelectorAll("input[name='image_order']").forEach(input => input.remove());

                // 🔹 createFormがない場合、処理終了
                const form = document.getElementById("createForm");
                if (!form) {
                    console.error("❌ フォームが見つかりません！");
                    return;
                }

                // 🔹 フォームにhidden inputを追加
                let hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "image_order";
                hiddenInput.value = JSON.stringify(
                    imageOrder); // オブジェクト配列を文字列化 | valueは文字列しかセットできないので、オブジェクトを文字列にする必要がある
                form.appendChild(hiddenInput);
                console.log("✅ hidden input に保存:", hiddenInput.value);
            }

            // ----------- ✅ SortableJS(ドラッグ&ドロップ)を適用 -----------
            // 🔹 `saveImageOrder()`が実行されたかどうかを管理する変数
            let imageOrderUpdated = false;

            // 🔹 imagePreviewContainerの確認、なければ処置終了
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
                        imageOrderUpdated = true; // 並び替えが行われたのでtrueに設定
                    },
                });

            // 🔹 フォーム送信時に`image_order`を確実に更新
            document.getElementById("createForm").addEventListener("submit", function(event) {
                saveImageOrder();
            }, {
                once: true
            });
            // ----------- SortableJS(ドラッグ&ドロップ)を適用 ----------- 


            // ----------- ⭐️ セッション管理 ----------- 
            // ✅ 画像セッション管理(バリデーションエラー時)
            // 🔹 ページを離れる前に、セッション画像を削除する処理を待つ
            window.addEventListener("beforeunload", function(e) { // ユーザーが「ページを離れる」「再読み込み」しようとした瞬間に発火するイベント
                clearSessionImages();
            });

            // 🔹 フォーム送信以外のページ遷移時に、セッション内の画像データを削除する非同期処理
            async function clearSessionImages() { // awaitを使って処理の完了を「待つ」ことができる
                // 🔸 新規登録ボタンによる遷移は除外(formのsubmitで発火している場合)
                if (document.activeElement && document.activeElement.closest("form")?.id ===
                    "createForm") { // 現在フォーカス中の要素がcreateForm内にあるかチェック = 「フォーム送信中(規登録ボタン押下中)」なら処理しない
                    return;
                }

                // 🔸 サーバーにセッション画像の削除を非同期で依頼して、結果をログに出力する処理
                try {
                    const response = await fetch(
                        "{{ route('admin.session.clear.images') }}", { // session.clear.imagesにPOSTリクエストを送る
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        });
                    const data = await response.json(); // サーバーから返ってきたJSONレスポンスを受け取る
                    console.log(data.message);
                } catch (error) {
                    console.error("セッション削除エラー:", error);
                }
            }


            // ⭐️ 技術タグ一覧へ遷移する前に、フォームの入力内容をセッションに保存
            // ✅ 初期設定
            const links = document.querySelectorAll(
                '.toTechTagIndex, .toTechTagCreate, .toFeatureTagCreate, .toFeatureTagIndex'
            ); // ← クラス名を複数の要素に共通でつける
            const originalForm = document.getElementById('createForm');
            if (links.length === 0 || !originalForm) {
                console.error("❌ 必要な要素が見つかりません");
                return;
            }

            // ✅ リンククリック時に元フォームの入力値をすべてhidden inputにして、セッション保存用フォームで送信する処理
            links.forEach(link => {
                link.addEventListener('click', async function(e) {
                    e.preventDefault(); // ブラウザのデフォルト動作をキャンセル
                    saveImageOrder(); // 並び順保存
                    const formData = new FormData(originalForm); // `image_path[]`も含め全てのデータが入る

                    // 🔹リクエスト送信(fetch)
                    try {
                        // 🔸 POSTリクエストをこのURLに送る
                        const response = await fetch(
                            "{{ route('admin.collections.storeSessionWithImage') }}", { // fetch() = ブラウザでHTTPリクエストを送るための関数 | await = レスポンスが返ってくるまで次の処理を待つ
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute(
                                        'content'
                                    ), // <meta name="csrf-token" content="...">に埋め込まれたトークンをJavaScriptから取得して送る
                                },
                                body: formData // formData = <form>から取得した「全入力内容＋画像」を含むオブジェクト | bodyにセットすると、それがリクエストの本文として送信される → この中にタイトル・説明・画像などが入ってる！
                            });

                        // 🔸サーバーから返ってきたレスポンスを「JSON形式」として受け取ってる処理
                        const result = await response.json(); // response = fetch()の返り値
                        console.log(result.message);

                        // 🔸 送信完了後に遷移
                        window.location.href = link.href;

                    } catch (error) {
                        console.error("送信エラー:", error);
                    }
                });
            });
        });
    </script>
</x-app-layout>
