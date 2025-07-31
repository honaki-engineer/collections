<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            主な機能タグ新規作成
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- フォーム --}}
                    <section class="text-gray-600 body-font relative">
                        <form action="{{ route('admin.feature-tags.store') }}" method="POST">
                            @csrf
                            <div class="container px-5 mx-auto">
                                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                    <div class="flex flex-wrap -m-2">
                                        {{-- 名前 --}}
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="names"
                                                    class="leading-7 text-sm text-gray-600">主な機能タグ<br>カンマ +
                                                    半角スペース区切りで、複数入力OK</label>
                                                <input type="text" id="name" name="names"
                                                    value="{{ old('names') }}" placeholder="例)ログイン, 検索, ファイルインポート"
                                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <x-input-error :messages="$errors->get('names')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="p-2 w-full">
                                            <button
                                                class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">新規登録する</button>
                                        </div>
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
</x-app-layout>
