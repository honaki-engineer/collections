<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            機能タグ一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="lg:w-2/3 w-full mx-auto overflow-auto">

                        {{-- collections.createへ戻るフォーム --}}
                        <a href="{{ session('collection_return_url', route('admin.collections.index')) }}"
                            class="leading-7 text-sm text-gray-600 underline hover:text-gray-900">{{ session('collection_return_label', 'ポートフォリオ入力へ戻る') }}</a>

                        {{-- 検索フォーム --}}
                        <form class="mt-6" method="GET" action="{{ route('admin.feature-tags.index') }}">
                            <input type="text" name="search_name" placeholder="フリー検索 🔍"
                                class="rounded cursor-pointer" value="{{ request()->input('search_name') }}">
                            <button
                                class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">検索</button>
                        </form>

                        {{-- テーブル --}}
                        <table class="table-auto w-full text-left whitespace-no-wrap mt-6 whitespace-nowrap">
                            <thead>
                                <tr>
                                    <th
                                        class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">
                                        #</th>
                                    <th
                                        class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                        タグ</th>
                                </tr>
                            </thead>
                            <tbody>


                                @foreach ($featureTags as $featureTag)
                                    <tr>
                                        <td class="border-t-2 border-gray-200 px-4 py-3">
                                            <div class="flex space-x-2 items-center">
                                                {{-- 編集 --}}
                                                <form method="GET"
                                                    action="{{ route('admin.feature-tags.edit', ['feature_tag' => $featureTag->id]) }}">
                                                    <button
                                                        class="flex text-white bg-blue-500 border-0 py-1 px-3 focus:outline-none hover:bg-blue-600 rounded">編集</button>
                                                </form>
                                                {{-- 削除 --}}
                                                <form method="POST"
                                                    action="{{ route('admin.feature-tags.destroy', ['feature_tag' => $featureTag->id]) }}"
                                                    id="delete_{{ $featureTag->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" data-id="{{ $featureTag->id }}"
                                                        onclick="DeleteService.confirmAndDelete(this)"
                                                        class="flex text-white bg-pink-500 border-0 py-1 px-3 focus:outline-none hover:bg-pink-600 rounded">削除</a>{{-- resources/js/services/DeleteService.js --}}
                                                </form>
                                            </div>
                                        </td>
                                        <td class="border-t-2 border-gray-200 px-4 py-3">{{ $featureTag->name }}</td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                    {{ $featureTags->links() }}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
