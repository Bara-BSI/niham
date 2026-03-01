<x-app-layout>
<div class="py-4 sm:py-8">
        <div class="mx-auto max-w-4xl px-3 sm:px-6 lg:px-8">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200/50 dark:border-gray-700/50">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('messages.edit_property') ?? __('messages.edit_property') }}
                    </h2>
                </div>
                <div class="p-6">
                <form method="POST" action="{{ route('properties.update', $property) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="m-8 grid grid-cols-2 gap-4 justify-evenly">
                        <!-- Name -->
                        <div class="col-span-2 md:col-span-1">
                            <x-input-label for="name" :value="__('messages.property_name')" />
                            <x-text-input
                                id="name"
                                name="name"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('name', $property->name)"
                                required
                            />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Code -->
                        <div class="col-span-2 md:col-span-1">
                            <x-input-label for="code" :value="__('messages.code')" />
                            <x-text-input
                                id="code"
                                name="code"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('code', $property->code)"
                                required
                            />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="col-span-2">
                            <x-input-label for="address" :value="__('messages.address')" />
                            <textarea
                                id="address"
                                name="address"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-100 shadow-sm focus:ring-accent focus:border-accent"
                                rows="3"
                            >{{ old('address', $property->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- Logo -->
                        <div class="col-span-2 md:col-span-1 mt-4">
                            <x-input-label for="logo" :value="__('messages.property_logo')" />
                            @if($property->logo_path)
                                <div class="mt-2 mb-2 p-2 bg-gray-100 rounded-md inline-block">
                                    <img src="{{ asset('storage/' . $property->logo_path) }}" alt="Logo" class="h-12 w-auto object-contain">
                                </div>
                            @endif
                            <input type="file" id="logo" name="logo" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-accent hover:file:bg-indigo-100" accept="image/*" />
                            <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                        </div>

                        <!-- Accent Color -->
                        <div class="col-span-2 md:col-span-1 mt-4">
                            <x-input-label for="accent_color" :value="__('messages.accent_color')" />
                            <div class="flex items-center space-x-3 mt-1">
                                <input type="color" id="accent_color" name="accent_color" value="{{ old('accent_color', $property->accent_color ?? '#4f46e5') }}" class="h-10 w-10 border-0 rounded-md cursor-pointer" />
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.select_theme_color') }}</span>
                            </div>
                            <x-input-error :messages="$errors->get('accent_color')" class="mt-2" />
                        </div>

                        <!-- Background Image -->
                        <div class="col-span-2 mt-4">
                            <x-input-label for="background_image" :value="__('messages.background_image')" />
                            @if($property->background_image_path)
                                <div class="mt-2 mb-2">
                                    <img src="{{ asset('storage/' . $property->background_image_path) }}" alt="Background" class="h-32 w-full object-cover rounded-md shadow-sm">
                                </div>
                            @endif
                            <input type="file" id="background_image" name="background_image" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-accent hover:file:bg-indigo-100" accept="image/*" />
                            <x-input-error :messages="$errors->get('background_image')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between items-center">
                        <div class="mt-6 flex justify-start">
                            <x-secondary-button onclick="window.history.back()">
                                <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                                {{ __('messages.back') }}
                            </x-secondary-button>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <x-primary-button>
                                <x-heroicon-s-bookmark class="w-4 h-4 mr-2" />
                                {{ __('messages.save') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
