<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Beranda') &middot; Bandung Info Center</title>
    <meta name="description" content="@yield('description', 'Portal informasi resmi Kota Bandung: berita, wisata, kuliner, sejarah, dan galeri.')">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="flex min-h-screen flex-col bg-slate-50 font-sans text-slate-900 antialiased pb-20 lg:pb-0">
    @include('layouts.partials.navbar')

    <main class="flex-1">
        @include('layouts.partials.flash')
        @yield('content')
    </main>

    @include('layouts.partials.footer')
    @stack('scripts')
</body>
</html>
