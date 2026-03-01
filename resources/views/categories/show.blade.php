<x-app-layout>
<div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200/50 dark:border-gray-700/50">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('messages.category_details') ?? __('messages.category_details') }}
                    </h2>
                </div>
                <div class="p-6 md:p-8 space-y-8">
                
                {{-- Responsive Two-Column --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Category Details -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 border-b border-gray-200/50 pb-2">{{ __('messages.category_details') }}</h3>
                        <p class="text-lg text-gray-700 dark:text-gray-300"><strong class="text-gray-900 dark:text-gray-100">{{ __('messages.category_name') }}:</strong> {{ $category->name }}</p>
                        <p class="text-lg text-gray-700 dark:text-gray-300"><strong class="text-gray-900 dark:text-gray-100">{{ __('messages.code') }}:</strong> {{ $category->code }}</p>
                    </div>

                    <!-- Notes -->
                    <div class="space-y-3 pt-4 md:pt-0 border-t md:border-none border-gray-200/50">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 border-b border-gray-200/50 pb-2">{{ __('messages.notes') }}</h3>
                        <div class="bg-gray-50/80 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200/40 dark:border-gray-600 text-gray-700 dark:text-gray-300 whitespace-pre-line shadow-sm" style="overflow-wrap: anywhere;">
                            {{ $category->notes ?: __('messages.no_notes_provided') }}
                        </div>
                    </div>
                </div>

                <!-- Assigned Assets -->
                <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl p-5 border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
                    <h4 class="text-md font-bold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200/50 pb-2">{{ __('messages.assigned_assets') }}</h4>
                    @if($category->assets->isNotEmpty())
                        <div class="overflow-x-auto rounded-lg border border-gray-200/60">
                            <table class="min-w-full divide-y divide-gray-200/50 dark:divide-gray-700/50 text-sm">
                                <thead class="bg-gray-50/50 dark:bg-gray-800/50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">{{ __('messages.tag') }}</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">{{ __('messages.name') }}</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">{{ __('messages.category') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200/50 dark:divide-gray-700/50">
                                    @foreach($assets as $asset)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                            <td class="px-4 py-3 text-gray-900 dark:text-gray-200 font-medium">{{ $asset->tag }}</td>
                                            <td class="px-4 py-3 text-accent hover:underline"><a href="{{ route('assets.show', $asset) }}">{{ $asset->name }}</a></td>
                                            <td class="px-4 py-3 text-gray-900 dark:text-gray-200">{{ $asset->category->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $assets->links() }}
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">{{ __('messages.no_assets_assigned') }}</p>
                    @endif
                </div>
                

                <div class="mt-6 flex justify-between items-center">
                    <!-- Back Button -->
                    <a href="{{ route('categories.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md 
                            font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 
                            focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                        {{ __('messages.back_to_categories') }}
                    </a>

                    <div class="inline-flex">
                        @can('update', $category)
                        <!-- Edit Button -->
                        <a href="{{ route('categories.edit', $category) }}"
                        class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md 
                                font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 
                                focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                            <x-heroicon-s-pencil class="w-4 h-4 mr-2" />
                            {{ __('messages.edit') }}
                        </a>
                        @endcan
                        @can('delete', $category)
                        <!-- Delete Button -->
                        <div x-data="{ openDeleteModal: false }" class="inline-flex">
                            <button type="button" @click="openDeleteModal = true"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md 
                                        font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 
                                        focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ml-1">
                                <x-heroicon-s-trash class="w-4 h-4 mr-2" />
                                {{ __('messages.delete') }}
                            </button>

                            <template x-teleport="body">
                                <div x-show="openDeleteModal"
                                    x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 dark:bg-gray-900/60 backdrop-blur-sm">
                                    <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-xl w-full max-w-md p-6 relative" @click.outside="openDeleteModal = false">
                                        <button @click="openDeleteModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                            <x-heroicon-s-x-mark class="w-5 h-5"/>
                                        </button>
                                        
                                        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('messages.delete_category') }}</h2>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">{{ __('messages.delete_category_confirm') }}</p>
                                        
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="flex justify-end gap-3">
                                                <x-secondary-button type="button" @click="openDeleteModal = false">{{ __('messages.cancel') }}</x-secondary-button>
                                                <x-danger-button type="submit">{{ __('messages.yes_delete') }}</x-danger-button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </template>
                        </div>
                        @endcan
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>