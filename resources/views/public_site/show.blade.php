<x-layouts.public>
  {{-- ↓ここにトップページのコンテンツを記述 --}}
  
  <section class="py-16">
    <div class="max-w-7xl mx-auto px-4">
      <div class="grid md:grid-cols-3 gap-10">
        <!-- 左カラム：説明文 -->
        <div class="md:col-span-1">
          <h2 class="text-2xl font-bold text-center mb-4">{{ $collection->title }}</h2>
          @if($collection->description)
            <p class="text-gray-700 mb-4">{!! nl2br(e($collection->description)) !!}</p>
          @endif

          <div class="space-y-2">
            @if($collection->url_qiita)
            <a href="{{ $collection->url_qiita }}" target="_blank" class="inline-flex items-center text-blue-600 hover:underline">
              <img src="{{ asset('storage/collection_images/qiita.png') }}" alt="Demo" class="w-5 h-5 mr-2"> Qiita
            </a>
            <br>
            @endif
            @if($collection->url_github)
            <a href="{{ $collection->url_github }}" target="_blank" class="inline-flex items-center text-blue-600 hover:underline">
              <img src="{{ asset('storage/collection_images/github.png') }}" alt="GitHub" class="w-5 h-5 mr-2"> Github
            </a>
            <br>
            @endif
            @if($collection->url_webapp)
            <a href="{{ $collection->url_webapp }}" target="_blank" class="inline-flex items-center text-blue-600 hover:underline">
              <img src="{{ asset('storage/collection_images/webApp.png') }}" alt="GitHub" class="w-5 h-5 mr-2"> Demo
            </a>
            @endif
          </div>

          <h3 class="text-lg font-semibold mt-6 text-center">使用技術</h3>
          <p class="text-sm text-gray-600">
            @foreach($collection->technologyTags as $technologyTag)
              {{$technologyTag->name}}@if(!$loop->last),@endif
            @endforeach
          </p>
          <h3 class="text-lg font-semibold mt-6 text-center">実装機能</h3>
          <p class="text-sm text-gray-600">
            @foreach($collection->featureTags as $featureTag)
              {{$featureTag->name}}@if(!$loop->last),@endif
            @endforeach
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


