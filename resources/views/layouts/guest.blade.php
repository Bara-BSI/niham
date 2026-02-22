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
    <body class="font-sans text-gray-900 antialiased min-h-screen flex bg-white">
        <!-- Left Side: Graphic / Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-blue-900 flex-col justify-between p-12 relative overflow-hidden">
            <!-- Background element -->
            <div class="absolute inset-0 bg-blue-800 opacity-50 z-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?fit=crop&w=1920&q=80'); mix-blend-mode: overlay;"></div>
            
            <div class="relative z-10 flex items-center">
                <div class="flex items-center space-x-3 text-white">
                    <x-application-logo class="h-10 w-auto fill-current" />
                    <span class="text-2xl font-bold tracking-wider">NIHAM</span>
                </div>
            </div>

            <div class="relative z-10 text-white max-w-lg">
                <h1 class="text-4xl font-bold mb-4 leading-tight">New Integrated Hotel Asset Management.</h1>
                <p class="text-lg text-blue-100">Streamline your hotel operations with intelligent tracking, comprehensive reporting, and multi-property oversight.</p>
            </div>
            
            <div class="relative z-10 text-sm text-blue-200">
                &copy; {{ date('Y') }} NIHAM Systems. All rights reserved.
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12">
            <div class="w-full max-w-md">
                <div class="lg:hidden mb-10 flex justify-center">
                    <a href="/" class="flex items-center space-x-3 text-blue-900">
                        <x-application-logo class="h-12 w-auto fill-current" />
                    </a>
                </div>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
