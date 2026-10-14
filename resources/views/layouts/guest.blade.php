<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Login Admin') &middot; Bandung Info Center</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

{{--
    Layout halaman autentikasi: satu-satunya halaman login (admin).
    Latar foto menutup seluruh layar, kartu login berada di tengahnya.
--}}
<body class="relative min-h-screen overflow-hidden bg-navy-900 font-sans text-slate-900 antialiased">
    {{-- Foto latar penuh layar --}}
    <div class="pointer-events-none fixed inset-0 bg-cover bg-center"
         style="background-image: url('{{ asset('images/beranda_admin.jpeg') }}')"></div>
    <div class="pointer-events-none fixed inset-0 bg-gradient-to-br from-navy-900/95 via-navy-800/90 to-navy-900/95"></div>

    <main class="relative flex min-h-screen items-center justify-center px-4 py-12">
        @yield('content')
    </main>
</body>
</html>
