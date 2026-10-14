<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') &middot; Panel Admin BIC</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="min-h-screen bg-[#f0f2f8] font-sans text-slate-800">
    @include('layouts.partials.admin-sidebar')

    <div class="flex min-h-screen flex-col md:ml-[220px]">
        @include('layouts.partials.admin-topbar')

        <main class="flex-1 px-5 py-7 lg:px-7">
            @include('layouts.partials.flash')
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
