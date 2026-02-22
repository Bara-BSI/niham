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
    @php
        $bgImage = isset($activeProperty) && $activeProperty->background_image_path 
            ? asset('storage/' . $activeProperty->background_image_path)
            : asset('global-background.png');
    @endphp
    <body class="font-sans antialiased min-h-screen">
        <!-- Fixed background image (works on all devices including mobile) -->
        <div class="fixed inset-0 z-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $bgImage }}');"></div>

        <!-- Subtle dark overlay so content is readable -->
        <div class="fixed inset-0 bg-black/20 z-0"></div>

        <!-- Floating layout wrapper — centered with max-w, p-4 gaps around edges -->
        <div class="relative z-10 min-h-screen flex flex-col p-3 sm:p-4 gap-3 sm:gap-4 max-w-7xl w-full mx-auto">

            <!-- Floating Navbar Pill (z-30 so dropdowns appear above everything) -->
            <div class="relative z-30">
                @include('layouts.navigation')
            </div>

            <!-- Floating Header Pill -->
            @isset($header)
                <header class="relative z-20 glass-panel px-4 sm:px-6 lg:px-8 py-5">
                    {{ $header }}
                </header>
            @endisset

            <!-- Floating Main Content Card -->
            <main class="relative z-10 flex-grow glass-panel overflow-x-auto">
                {{ $slot }}
            </main>
        </div>

        {{-- Success modal --}}
        @if(session('ok'))
            <div 
                x-data="{ open: true }" 
                x-show="open" 
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            >
                <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl p-6 w-full max-w-sm relative border border-white/20">
                    <!-- Close button -->
                    <button 
                        @click="open = false" 
                        class="absolute top-3 right-3 flex items-center justify-center
                            w-7 h-7 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200
                            focus:outline-none focus:ring-2 focus:ring-accent transition"
                    >
                        <x-heroicon-s-x-mark class="w-4 h-4"/>
                    </button>

                    <!-- Message -->
                    <div class="text-center pt-2">
                        <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-full bg-green-100 mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Success</h3>
                        <p class="mt-2 text-gray-600">{{ session('ok') }}</p>
                    </div>

                    <!-- Action -->
                    <div class="mt-5 text-center">
                        <button 
                            @click="open = false" 
                            class="inline-flex items-center px-5 py-2.5 bg-accent border border-transparent 
                                rounded-xl font-semibold text-xs text-white uppercase tracking-widest 
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
