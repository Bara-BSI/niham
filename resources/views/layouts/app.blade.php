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
        <style>
            [x-cloak] { display: none !important; }
            :root {
                --accent-color: {{ $activeProperty->accent_color ?? '#4f46e5' }};
            }
            .bg-accent { background-color: var(--accent-color) !important; }
            .text-accent { color: var(--accent-color) !important; }
            .border-accent { border-color: var(--accent-color) !important; }
            .ring-accent:focus { outline: none; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.4); border-color: var(--accent-color) !important; }
        </style>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        @php
            $bgImage = isset($activeProperty) && $activeProperty->background_image_path 
                ? asset('storage/' . $activeProperty->background_image_path)
                : null;
        @endphp

        <div class="min-h-screen bg-gray-100 relative">
            @if($bgImage)
                <div class="fixed inset-0 z-0 bg-cover bg-center" style="background-image: url('{{ $bgImage }}');">
                    <div class="absolute inset-0 bg-white bg-opacity-80 backdrop-blur-md"></div>
                </div>
            @endif

            <div class="relative z-10 min-h-screen flex flex-col">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white bg-opacity-90 shadow border-b border-gray-200 backdrop-blur">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-grow">
                    {{ $slot }}
                </main>
            </div>
        </div>

        {{-- Ok modal --}}
        @if(session('ok'))
            <div 
                x-data="{ open: true }" 
                x-show="open" 
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            >
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm relative">
                    <!-- Close button -->
                    <button 
                        @click="open = false" 
                        class="absolute top-2 right-2 flex items-center justify-center
                            w-6 h-6 rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300
                            focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        <x-heroicon-s-x-mark class="w-4 h-4"/>
                    </button>

                    <!-- Message -->
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-800">Success</h3>
                        <p class="mt-2 text-gray-600">{{ session('ok') }}</p>
                    </div>

                    <!-- Action -->
                    <div class="mt-4 text-center">
                        <button 
                            @click="open = false" 
                            class="inline-flex items-center px-4 py-2 bg-accent border border-transparent 
                                rounded-md font-semibold text-xs text-white uppercase tracking-widest 
                                hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-accent 
                                focus:ring-offset-2 transition"
                        >
                            OK
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </body>
</html>
