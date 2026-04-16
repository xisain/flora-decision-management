<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title', 'Authentication')</title>
</head>

<body>
    <div class="min-h-screen flex items-center justify-center p-8 bg-[var(--flora-mist)]">
        <div class="w-full max-w-[960px] grid grid-cols-1 lg:grid-cols-[1fr_1.05fr] bg-white rounded-[28px] overflow-hidden shadow-[0_24px_80px_rgba(0,0,0,0.12)] min-h-[620px] lg:min-h-[620px]">

            {{-- Image / Branding Panel --}}
            <div class="relative p-10 bg-gradient-to-b from-[var(--flora-sage-pale)] to-white flex flex-col items-center justify-center text-center">
                <img src="{{ asset('storage/images/logo.png') }}" alt="Logo" class="max-w-[240px] w-full h-auto mb-8">
                <div class="max-w-[280px] text-[var(--flora-moss)]">
                    <h2 class="text-[1.75rem] font-semibold mb-3 leading-tight">@yield('image_title', 'Welcome')</h2>
                    <p class="text-base leading-[1.75] text-[rgba(68,68,65,0.85)]">@yield('image_subtitle', 'Securely access your account with our registration form.')</p>
                </div>
            </div>

            {{-- Form Panel --}}
            <div class="p-10 lg:p-12 flex flex-col justify-center gap-4">
                <div class="mb-6 text-center lg:text-left">
                    <h1 class="text-[2rem] font-bold text-[var(--flora-moss)] mb-1">@yield('page_title', 'Authentication')</h1>
                    <p class="text-base text-[rgba(68,68,65,0.8)] leading-[1.75]">@yield('page_subtitle')</p>
                </div>
                @yield('content')
            </div>

        </div>
    </div>
</body>

</html>
