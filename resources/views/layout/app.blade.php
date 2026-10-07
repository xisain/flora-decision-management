<!DOCTYPE html>
<html lang="id" class="@yield('html-class')">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|instrument-sans:400,500,600,700" rel="stylesheet">
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="public-site" x-data="{}" style="position: relative; min-height: 100vh; display: flex; flex-direction: column;">
    <a href="#main-content" class="public-skip-link">Lewati navigasi</a>
    @include('components.navigation.app')
    <main id="main-content" tabindex="-1" style="flex: 1;">
        @yield('content')
    </main>
    @include('components.footer')
</body>

</html>
