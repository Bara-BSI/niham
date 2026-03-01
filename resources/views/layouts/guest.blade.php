<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('theme') || 'light' }" x-init="$watch('theme', val => localStorage.setItem('theme', val))" x-bind:class="{ 'dark': theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches) }">
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
    <body class="font-sans antialiased bg-gray-50 text-gray-900 dark:text-gray-100 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-200 min-h-screen">
        <!-- Fixed background image (works on all devices including mobile) -->
        <div class="fixed inset-0 z-0">
            <img src="{{ asset('global-background.png') }}" alt="Background" class="object-cover w-full h-full" />
        </div>

        <!-- Dark overlay for contrast -->
        <div class="fixed inset-0 bg-black/30 dark:bg-black/50 z-0"></div>

        <!-- Guest Accessibility Menu (Top Right) -->
        <div class="fixed top-4 right-4 z-50 flex items-center space-x-2">
            <!-- Language Switcher -->
            <x-dropdown align="right" width="32">
                <x-slot name="trigger">
                    <button class="inline-flex items-center p-2 rounded-full bg-white/90 dark:bg-gray-800/90 backdrop-blur-md shadow-sm hover:opacity-90 transition" aria-label="Language options">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ strtoupper(app()->getLocale()) }}</span>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link :href="route('lang.switch', 'en')">🇬🇧 English</x-dropdown-link>
                    <x-dropdown-link :href="route('lang.switch', 'id')">🇮🇩 Indonesia</x-dropdown-link>
                </x-slot>
            </x-dropdown>

            <!-- Theme Switcher -->
            <x-dropdown align="right" width="32">
                <x-slot name="trigger">
                    <button class="inline-flex items-center p-2 rounded-full bg-white/90 dark:bg-gray-800/90 backdrop-blur-md shadow-sm hover:opacity-90 transition" aria-label="Theme options">
                        <template x-if="theme === 'light'"><x-heroicon-s-sun class="w-5 h-5 text-gray-700 dark:text-gray-200"/></template>
                        <template x-if="theme === 'dark'"><x-heroicon-s-moon class="w-5 h-5 text-gray-700 dark:text-gray-200"/></template>
                        <template x-if="theme === 'system'"><x-heroicon-s-computer-desktop class="w-5 h-5 text-gray-700 dark:text-gray-200"/></template>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <button @click="theme = 'light'" class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition">{{ __('messages.light') }}</button>
                    <button @click="theme = 'dark'" class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition">{{ __('messages.dark') }}</button>
                    <button @click="theme = 'system'" class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition">{{ __('messages.system') }}</button>
                </x-slot>
            </x-dropdown>
        </div>

        <!-- Centered floating card -->
        <div class="relative z-10 min-h-screen flex flex-col items-center justify-center p-4 sm:p-6">
            
            <!-- Glass Login Card -->
            <div class="w-full max-w-md">
                <!-- The floating glass card (logo inside) -->
                <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border border-gray-200/50 dark:border-gray-700/50 rounded-xl shadow-xl p-8 sm:p-10">
                    <!-- Logo & Brand inside the card -->
                    <div class="flex flex-col items-center mb-4">
                        <a href="/" class="mb-6">
                            <x-application-logo class="h-16 w-auto" />
                        </a>
                    </div>

                    {{ $slot }}
                </div>

                <!-- Footer text -->
                <p class="text-center text-sm text-white/70 mt-8">
                    &copy; {{ date('Y') }} NIHAM Systems &mdash; New Integrated Hotel Asset Management.
                </p>
            </div>
        </div>

        <x-modal-alert />
    </body>
</html>
