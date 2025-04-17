<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ポートフォリオ一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                        {{-- 検索 --}}
                        <form action="{{ route('admin.collections.index') }}" method="GET">
                            <select name="search_is_public" class="rounded cursor-pointer">
                                <option value="">公開種別を選択</option>
                                <option value="0" {{ request('search_is_public') == '0' ? 'selected' : '' }}>非公開
                                </option>
                                <option value="1" {{ request('search_is_public') == '1' ? 'selected' : '' }}>公開
                                </option>
                            </select>
                            <span class="hidden sm:inline">&</span>
                            <select name="search_position" class="rounded cursor-pointer">
                                <option value="">表示優先度を選択</option>
                                <option value="0" {{ request('search_position') == '0' ? 'selected' : '' }}>デフォルト
                                </option>
                                <option value="1" {{ request('search_position') == '1' ? 'selected' : '' }}>1ページ目
                                </option>
                                <option value="2" {{ request('search_position') == '2' ? 'selected' : '' }}>topページ
                                </option>
                            </select>
                            <button
                                class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg mt-1 sm:mt-0">検索</button>
                        </form>

                        {{-- テーブル --}}
                        <table class="table-auto w-full text-left whitespace-no-wrap mt-6 whitespace-nowrap">
                            <thead>
                                <tr>
                                    <th
                                        class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">
                                        詳細</th>
                                    <th
                                        class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                        タイトル</th>
                                    <th
                                        class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                        公開種別</th>
                                    <th
                                        class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                        表示優先度</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($collections as $collection)
                                    <tr>
                                        <td class="border-t-2 border-gray-200 px-4 py-3">
                                            <a href="{{ route('admin.collections.show', ['collection' => $collection->id]) }}"
                                                class="text-blue-500">#</a>
                                        </td>
                                        <td class="border-t-2 border-gray-200 px-4 py-3">{{ $collection->title }}</td>
                                        <td class="border-t-2 border-gray-200 px-4 py-3">
                                            {{ $collection->is_public_label }}</td>
                                        <td class="border-t-2 border-gray-200 px-4 py-3 text-lg text-gray-900">
                                            {{ $collection->position_label }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $collections->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- フローティングボタン -->
    <a href="{{ route('admin.collections.create') }}"
        class="sm:hidden fixed z-50 bottom-4 right-4 w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-blue-700">
        <!-- アイコン（プラス記号） -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </a>
</x-app-layout>
