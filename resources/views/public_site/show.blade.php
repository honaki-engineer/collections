<x-layouts.public>
  {{-- ↓ここにトップページのコンテンツを記述 --}}
  
  <section class="py-16">
    <div class="max-w-7xl mx-auto px-4">
      <div class="grid md:grid-cols-3 gap-10">
        <!-- 左カラム：説明文 -->
        <div class="md:col-span-1">
          <h2 class="text-2xl font-bold text-center mb-4">The Sample App</h2>
          <p class="text-gray-700 mb-4">
            Ruby on Railsのチュートリアルに沿って制作したWebアプリケーションです。Twitterのように小さなコメントを投稿したり、ユーザーをフォローして簡単なコミュニケーションが取れます。
            このチュートリアルを通してテスト駆動開発、CRUD、ユーザー登録などの基礎を学びました。
          </p>

          <div class="space-y-2">
            <a href="https://rails-sample-app-by-hodaka.herokuapp.com/" target="_blank" class="inline-flex items-center text-blue-600 hover:underline">
              <img src="{{ asset('asset/img/video-icon.svg') }}" alt="Demo" class="w-5 h-5 mr-2"> Demo
            </a>
            <br>
            <a href="https://github.com/hodanov/ruby-on-rails-tutorial" target="_blank" class="inline-flex items-center text-gray-800 hover:underline">
              <img src="{{ asset('asset/img/github-icon.svg') }}" alt="GitHub" class="w-5 h-5 mr-2"> GitHub
            </a>
          </div>

          <h3 class="text-lg font-semibold mt-6 text-center">使用技術</h3>
          <p class="text-sm text-gray-600">
            Ruby on Rails, HTML/CSS(SASS), Bulma, jQuery, Sqlite（開発）,
            Postgresql（本番）, Heroku, SendGrid
          </p>
        </div>

        <!-- 右カラム：サムネイルとメイン画像 -->
        <div class="md:col-span-2 space-y-4">
          <div class="grid grid-cols-8 gap-2">
            @foreach($collection->collectionImages as $collectionImage)
              <img
                src="{{ asset('storage/collection_images/' . $collectionImage->image_path) }}"
                alt="トップ画面"
                class="w-24 h-24 object-cover rounded shadow"
              >
            @endforeach
          </div>

          <div>
            <a href="https://rails-sample-app-by-hodaka.herokuapp.com/" target="_blank">
              <img src="{{ asset('asset/img/sample_app1.png') }}" alt="メイン画像" class="w-full rounded shadow-lg">
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

</x-layouts.public>


