<x-layouts.public>
  {{-- ↓ここにトップページのコンテンツを記述 --}}

    <section id="projects" class="py-16 bg-gray-100">
      {{-- <div class="container mx-auto px-4"> --}}
      <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-4">Works</h2>
        <p class="text-center text-gray-600 mb-10">
          腕によりをかけて制作した、愛すべき成果物たちをご紹介します。
        </p>
  
        <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          <!-- カード1 -->
          @foreach($collections as $collection)
          <div class="text-center">
            <div class="bg-white rounded shadow hover:shadow-lg transition">
              <a href="#modal0">
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

