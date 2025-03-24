<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          技術タグ / 機能タグ新規作成
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">

                {{-- フォーム --}}
                <section class="text-gray-600 body-font relative">
                  <form action="{{ route('tags.store') }}" method="POST">
                    @csrf
                  <div class="container px-5 mx-auto">
                    <div class="lg:w-1/2 md:w-2/3 mx-auto">
                      <div class="flex flex-wrap -m-2">
                        {{-- 種類 --}}
                        <div class="p-2 w-full">
                          <div class="relative">
                            <label class="leading-7 text-sm text-gray-600">タグの種類</label>
                            <input type="radio" name="type" value="0" id="type_tech">技術タグ
                            <input type="radio" name="type" value="1" id="type_feature">機能タグ
                          </div>
                        </div>
                        {{-- 技術タグの種類（初期は非表示） --}}
                        <div class="p-2 w-full" id="tech_type_section" style="display: none;">
                          <div class="relative">
                            <label class="leading-7 text-sm text-gray-600">技術タグの種類</label>
                            <input type="radio" name="tech_type" value="0">言語
                            <input type="radio" name="tech_type" value="1">フレームワーク
                            <input type="radio" name="tech_type" value="2">ツール
                          </div>
                        </div>
                        {{-- 名前 --}}
                        <div class="p-2 w-full">
                          <div class="relative">
                            <label for="names" class="leading-7 text-sm text-gray-600">タグ名<br>カンマ + 半角スペース区切りで、複数入力OK</label>
                            <input type="text" id="name" name="names" placeholder="例)PHP, Laravel, Slack" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                          </div>
                        </div>
                        <div class="p-2 w-full">
                          <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">新規登録する</button>
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
<script>
// ⭐️ 「技術タグ」時、id="tech_type_section"を表示
document.addEventListener('DOMContentLoaded', function () {
  // ✅ 初期設定
  const typeRadios = document.querySelectorAll('input[name="type"]');
  const skillTypeSection = document.getElementById('tech_type_section');
  const selectedType = document.querySelector('input[name="type"]:checked');


  // ✅「技術タグ」時、id="tech_type_section"を表示
  // 🔹 技術タグ選択時、「技術タグの種類」表示
  if(selectedType && selectedType.value === "0") {
    skillTypeSection.style.display = 'block';
  }

  // 🔹 ラジオボタンが変更されたときの処理(技術タグ→別タグ選択時)
  typeRadios.forEach(radio => {
    radio.addEventListener('change', function () {
      if(this.value === "0") {
        skillTypeSection.style.display = 'block';
      } else {
        skillTypeSection.style.display = 'none';
      }
    });
  });
});
</script>
</x-app-layout>