<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ポートフォリオサイト' }}</title>
    @vite('resources/css/app.css') {{-- Tailwind --}}
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-gray-200 shadow">
        <div class="container mx-auto">
            <div class="px-4 py-4 flex justify-between items-center">
                <a href="{{ route('collections.index') }}">
                    <h1 class="text-xl font-bold">Honda Collections Site</h1>
                </a>
            </div>
        </div>
    </header>

    <main class="py-8">
        {{ $slot }}
    </main>

    <footer class="shadow bg-gray-200 mt-12 py-4 text-center text-sm text-gray-500">
        © {{ date('Y') }} ポートフォリオサイト
    </footer>
</body>
</html>
