<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NIHAM') }}</title>

        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('niham-logo-cr-rd.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" 
            rel="stylesheet">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        class="font-sans text-gray-900 antialiased min-h-screen bg-cover bg-center bg-fixed bg-no-repeat"
        style="background-image: url('{{ asset('global-background.png') }}');"
    >
        <!-- Dark overlay for contrast -->
        <div class="fixed inset-0 bg-black/30 z-0"></div>

        <!-- Centered floating card -->
        <div class="relative z-10 min-h-screen flex flex-col items-center justify-center p-4 sm:p-6">
            
            <!-- Glass Login Card -->
            <div class="w-full max-w-md">
                <!-- Logo above card -->
                <div class="flex justify-center mb-8">
                    <a href="/" class="flex items-center space-x-3">
                        <x-application-logo class="h-14 w-auto drop-shadow-lg" />
                    </a>
                </div>

                <!-- The floating glass card -->
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl p-8 sm:p-10 border border-white/20">
                    {{ $slot }}
                </div>

                <!-- Footer text -->
                <p class="text-center text-sm text-white/70 mt-8">
                    &copy; {{ date('Y') }} NIHAM Systems &mdash; New Integrated Hotel Asset Management.
                </p>
            </div>
        </div>
    </body>
</html>
