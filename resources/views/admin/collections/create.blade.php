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
                    <form action="{{ route('collections.store') }}" method="POST">
                        @csrf
                    <div class="container px-5 mx-auto">
                      <div class="lg:w-1/2 md:w-2/3 mx-auto">
                        <div class="flex flex-wrap -m-2">
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('title')" class="mt-2" />
                              <label for="title" class="leading-7 text-sm text-gray-600">タイトル</label>
                              <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('description')" class="mt-2" />
                              <label for="description" class="leading-7 text-sm text-gray-600">アプリ解説</label>
                              <textarea id="description" name="description" value="{{ old('description') }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">{{ old('description') }}</textarea>
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('url_qiita')" class="mt-2" />
                              <label for="url_qiita" class="leading-7 text-sm text-gray-600">Qiita URL</label>
                              <input type="url" id="url_qiita" name="url_qiita" value="{{ old('url_qiita') }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('url_webapp')" class="mt-2" />
                              <label for="url_webapp" class="leading-7 text-sm text-gray-600">WebApp URL</label>
                              <input type="url" id="url_webapp" name="url_webapp" value="{{ old('url_webapp') }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('url_github')" class="mt-2" />
                              <label for="url_github" class="leading-7 text-sm text-gray-600">GitHub URL</label>
                              <input type="url" id="url_github" name="url_github" value="{{ old('url_github') }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('is_public')" class="mt-2" />
                              <label for="is_public" class="leading-7 text-sm text-gray-600">公開種別</label>
                              <input type="radio" name="is_public" value="0" {{ old('is_public') == '0' ? 'checked' : '' }}>非公開
                              <input type="radio" name="is_public" value="1" {{ old('is_public') == '1' ? 'checked' : '' }}>一般公開
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <div class="relative">
                              <x-input-error :messages="$errors->get('position')" class="mt-2" />
                              <label for="position" class="leading-7 text-sm text-gray-600">表示優先度</label>
                              <select name="position" id="position">
                                <option value="">選択してください</option>
                                <option value="0" {{ old('position') == '0' ? 'selected' : '' }}>デフォルト</option>
                                <option value="1" {{ old('position') == '1' ? 'selected' : '' }}>1ページ目</option>
                                <option value="2" {{ old('position') == '2' ? 'selected' : '' }}>topページ</option>
                              </select>
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">新規登録</button>
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