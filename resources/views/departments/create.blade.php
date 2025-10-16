<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Create Department') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('departments.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Responsive Two-Column Layout -->
                    <div class="m-8 grid grid-cols-5 gap-1 justify-evenly">

                        <!-- Left Column -->
                        <div class="col-span-5 md:col-span-4">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Department Name')" />
                                <x-text-input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    value="{{ old('name') }}"
                                />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-span-2 md:col-span-1">

                            <!-- Code -->
                            <div>
                                <x-input-label for="code" :value="__('Department Code')" />
                                <x-text-input
                                    id="code"
                                    name="code"
                                    type="text"
                                    class="mt-1 block w-full"
                                    value="{{ old('code') }}"
                                />
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    {{-- Notes --}}
                            <div 
                                x-data="{ count: {{ strlen(old('notes', $asset->notes ?? '')) }} }"
                                class="m-8"
                            >
                                <x-input-label for="notes" :value="__('Notes')" />

                                <textarea
                                    id="notes"
                                    name="notes"
                                    maxlength="200"
                                    rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="Add Note (max 200 chars)"
                                    x-on:input="count = $event.target.value.length"
                                ></textarea>

                                <div class="flex justify-between mt-1 text-sm text-gray-500">
                                    <span>Max 200 characters</span>
                                    <span x-text="count + '/200'"></span>
                                </div>

                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>

                    <div class="mt-6 flex justify-between items-center">
                        <!-- Back Button -->
                        <div class="mt-6 flex justify-start">
                            <x-secondary-button onclick="window.history.back()">
                                <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                                {{ __('Back') }}
                            </x-secondary-button>

                        </div>
                        <!-- Submit Button -->
                        <div class="mt-6 flex justify-end">
                            <x-primary-button>
                                <x-heroicon-s-bookmark class="w-4 h-4 mr-2" />
                                {{ __('Save Department') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
