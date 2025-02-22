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
                    <table class="table-auto w-full text-left whitespace-no-wrap">
                      <thead>
                        <tr>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">詳細</th>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">タイトル</th>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">公開種別</th>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">表示優先度</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($collections as $collection)
                        <tr>
                          <td class="border-t-2 border-gray-200 px-4 py-3">
                            <a href="{{ route('collections.show', ['collection' => $collection->id]) }}" class="text-blue-500">#</a>
                          </td>
                          <td class="border-t-2 border-gray-200 px-4 py-3">{{ $collection->title }}</td>
                          <td class="border-t-2 border-gray-200 px-4 py-3">{{ $collection->is_public_label }}</td>
                          <td class="border-t-2 border-gray-200 px-4 py-3 text-lg text-gray-900">{{ $collection->position_label }}</td>
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
</x-app-layout>