<x-layouts.public>
  {{-- ↓ここにトップページのコンテンツを記述 --}}

    <section id="projects" class="py-16 bg-gray-100">
      {{-- <div class="container mx-auto px-4"> --}}
      <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-4">Works</h2>
        <p class="text-center text-gray-600 mb-10">
          腕によりをかけて制作した、愛すべき成果物たちをご紹介します。
        </p>
  
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          <!-- カード1 -->
          <div class="text-center">
            <div class="bg-white rounded shadow hover:shadow-lg transition">
              <a href="#modal0">
                <div class="h-48 bg-cover bg-center rounded-t" style="background-image: url('{{ asset('asset/img/sample_app1.png') }}')"></div>
              </a>
            </div>
            <p class="mt-2 text-gray-800 font-semibold">The Sample App</p>
          </div>
  
          <!-- カード2 -->
          <div class="text-center">
            <div class="bg-white rounded shadow hover:shadow-lg transition">
              <a href="#modal1">
                <div class="h-48 bg-cover bg-center rounded-t" style="background-image: url('{{ asset('asset/img/webserver1.png') }}')"></div>
              </a>
            </div>
            <p class="mt-2 text-gray-800 font-semibold">クラウドWebサーバー構築</p>
          </div>
  
          <!-- カード3 -->
          <div class="text-center">
            <div class="bg-white rounded shadow hover:shadow-lg transition">
              <a href="#modal2">
                <div class="h-48 bg-cover bg-center rounded-t" style="background-image: url('{{ asset('asset/img/hodalog3-1.jpg') }}')"></div>
              </a>
            </div>
            <p class="mt-2 text-gray-800 font-semibold">Hodalog</p>
          </div>
  
          <!-- カード4 -->
          <div class="text-center">
            <div class="bg-white rounded shadow hover:shadow-lg transition">
              <a href="#modal3">
                <div class="h-48 bg-cover bg-center rounded-t" style="background-image: url('{{ asset('asset/img/hoda_portfolio.jpg') }}')"></div>
              </a>
            </div>
            <p class="mt-2 text-gray-800 font-semibold">Hoda's Portfolio</p>
          </div>
  
          <!-- カード5 -->
          <div class="text-center">
            <div class="bg-white rounded shadow hover:shadow-lg transition">
              <a href="#modal4">
                <div class="h-48 bg-cover bg-center rounded-t" style="background-image: url('{{ asset('asset/img/Lucy.jpg') }}')"></div>
              </a>
            </div>
            <p class="mt-2 text-gray-800 font-semibold">ルーシーのSlack Bot</p>
          </div>
  
          <!-- カード6 -->
          <div class="text-center">
            <div class="bg-white rounded shadow hover:shadow-lg transition">
              <a href="#modal5">
                <div class="h-48 bg-cover bg-center rounded-t" style="background-image: url('{{ asset('asset/img/macrobu1.jpg') }}')"></div>
              </a>
            </div>
            <p class="mt-2 text-gray-800 font-semibold">マクロ部</p>
          </div>
        </div>
      </div>
    </section>

  
  
</x-layouts.public>

