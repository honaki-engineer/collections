<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          技術タグ・機能タグ一覧
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">

                  <div class="lg:w-2/3 w-full mx-auto overflow-auto">

                    {{-- テーブル --}}
                    <table class="table-auto w-full text-left whitespace-no-wrap mt-6">
                      <thead>
                        <tr>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">詳細</th>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">名前</th>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">種類</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                        <tr>
                          <td class="border-t-2 border-gray-200 px-4 py-3">
                            <a href="" class="text-blue-500">#</a>
                          </td>
                          <td class="border-t-2 border-gray-200 px-4 py-3"></td>
                          <td class="border-t-2 border-gray-200 px-4 py-3"></td>
                        </tr>
                        
                      </tbody>
                    </table>
                  </div>

              </div>
          </div>
      </div>
  </div>
</x-app-layout>