<x-app-layout>
    <div class="py-6 sm:py-8">
        <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
                
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-200/50 dark:border-gray-700/50">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('messages.property_details') ?? __('messages.property_details') }}
                    </h2>
                </div>

                <!-- Card Body -->
                <div class="p-6 md:p-8 space-y-8">
                    
                    <!-- Property Details -->
                    <div>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.name') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $property->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.code') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $property->code }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.address') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $property->address ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.statistics') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $property->users_count }} {{ mb_strtolower(__('messages.users')) }} &middot; 
                                    {{ $property->assets_count }} {{ mb_strtolower(__('messages.assets')) }} &middot; 
                                    {{ $property->departments_count }} {{ mb_strtolower(__('messages.departments')) }} &middot; 
                                    {{ $property->categories_count }} {{ mb_strtolower(__('messages.categories')) }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Nested Card: Users in this Property -->
                    <div class="bg-gray-50/50 dark:bg-gray-800/50 rounded-xl p-5 border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
                        <h3 class="text-md font-bold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200/50 dark:border-gray-700/50 pb-2">{{ __('messages.users') }}</h3>
                        <div class="overflow-x-auto rounded-lg border border-gray-200/60 dark:border-gray-700/60">
                            <table class="min-w-full divide-y divide-gray-200/50 dark:divide-gray-700/50 text-sm">
                                <thead class="bg-gray-100/50 dark:bg-gray-900/50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">{{ __('messages.name') }}</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">{{ __('messages.role') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200/50 dark:divide-gray-700/50 bg-white dark:bg-gray-800">
                                    @forelse ($users as $user)
                                        <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="px-4 py-3 text-gray-900 dark:text-gray-200">{{ $user->name }}</td>
                                            <td class="px-4 py-3 text-gray-900 dark:text-gray-200">{{ optional($user->role)->name ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-4 py-3 text-gray-500 dark:text-gray-400 text-center italic">{{ __('messages.no_users') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">{{ $users->links() }}</div>
                    </div>

                    <!-- Nested Card: Departments in this Property -->
                    <div class="bg-gray-50/50 dark:bg-gray-800/50 rounded-xl p-5 border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
                        <h3 class="text-md font-bold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200/50 dark:border-gray-700/50 pb-2">{{ __('messages.departments') }}</h3>
                        <div class="overflow-x-auto rounded-lg border border-gray-200/60 dark:border-gray-700/60">
                            <table class="min-w-full divide-y divide-gray-200/50 dark:divide-gray-700/50 text-sm">
                                <thead class="bg-gray-100/50 dark:bg-gray-900/50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">{{ __('messages.name') }}</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">{{ __('messages.code') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200/50 dark:divide-gray-700/50 bg-white dark:bg-gray-800">
                                    @forelse ($departments as $dept)
                                        <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="px-4 py-3 text-gray-900 dark:text-gray-200">{{ $dept->name }}</td>
                                            <td class="px-4 py-3 text-gray-900 dark:text-gray-200">{{ $dept->code }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-4 py-3 text-gray-500 dark:text-gray-400 text-center italic">{{ __('messages.no_departments') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">{{ $departments->links() }}</div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 flex justify-between items-center border-t border-gray-200/50 dark:border-gray-700/50 mt-6">
                        <!-- Back Button -->
                        <a href="{{ route('properties.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition mt-4">
                            <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                            {{ __('messages.back') }}
                        </a>

                        <div class="inline-flex gap-2 mt-4">
                            @can('update', $property)
                            <!-- Edit Button -->
                            <a href="{{ route('properties.edit', $property) }}"
                               class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                                <x-heroicon-s-pencil class="w-4 h-4 mr-2" />
                                {{ __('messages.edit') }}
                            </a>
                            @endcan

                            @can('delete', $property)
                            <!-- Delete Button -->
                            <div x-data="{ openDeleteModal: false }" class="inline-flex">
                                <button type="button" @click="openDeleteModal = true"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
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
                                            
                                            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('messages.delete_property') }}</h2>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">{{ __('messages.delete_property_confirm') }}</p>
                                            
                                            <form action="{{ route('properties.destroy', $property) }}" method="POST">
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
