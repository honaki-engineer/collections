<x-layouts.public>
  {{-- ↓ここにトップページのコンテンツを記述 --}}

    <section id="projects" class="py-16 bg-gray-100">
      {{-- <div class="container mx-auto px-4"> --}}
      <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-4">Works</h2>
        <p class="text-center text-gray-600 mb-10">
          腕によりをかけて制作した、愛すべき成果物たちをご紹介します。
        </p>


        {{-- 検索 --}}
        <form action="{{ route('admin.collections.index') }}" method="GET">
          {{-- 使用技術 --}}
          <select name="search_is_public" class="rounded cursor-pointer">
            <option value="">使用技術を選択</option>
            @foreach($technologyTags as $technologyTag)
              <option value="{{ $technologyTag->id }}">{{ $technologyTag->name }}</option>
            @endforeach
          </select>
          <span class="hidden sm:inline">&</span>
          {{-- 実装機能 --}}
          <select name="search_position" class="rounded cursor-pointer">
            <option value="">実装機能を選択</option>
            @foreach($featureTags as $featureTag)
              <option value="{{ $featureTag->id }}">{{ $featureTag->name }}</option>
            @endforeach
          </select>
          <button class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">検索</button>
        </form>
  
        <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          <!-- カード1 -->
          @foreach($collections as $collection)
          <div class="text-center">
            <div class="bg-white rounded shadow hover:shadow-lg transition">
              <a href="{{ route('collections.show', ['collection' => $collection->id]) }}">
                <div class="h-48 bg-cover bg-center rounded-t" style="background-image: url('{{ asset('storage/collection_images/' . $collection->firstImage) }}')"></div>
              </a>
            </div>
            <p class="mt-2 text-gray-800 font-semibold">{{ $collection->title }}</p>
          </div>
          @endforeach
        </div>
        {{ $collections->links() }}
      </div>
    </section>

  
  
</x-layouts.public>

