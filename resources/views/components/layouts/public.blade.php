<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ポートフォリオサイト' }}</title>
    @vite('resources/css/app.css') {{-- Tailwind --}}
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col overflow-y-scroll">
    <header class="bg-gray-200 shadow">
        <div class="container mx-auto">
            <div class="px-4 py-4 flex justify-between items-center">
                <a href="{{ route('collections.index') }}">
                    <h1 class="text-xl font-bold">{{ config('app.name') }}</h1>
                </a>
            </div>
        </div>
    </header>

    <main class="pt-8 flex-grow pt-8">
        {{ $slot }}
    </main>


    <x-footer-public />
</body>

</html>
