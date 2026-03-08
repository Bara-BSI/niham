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
                            {{-- Delete Button --}}
                            <div x-data="{ openDeleteModal: false, confirmCode: '', propertyCode: '{{ $property->code }}' }" class="inline-flex">
                                <button type="button" @click="openDeleteModal = true"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                                    <x-heroicon-s-trash class="w-4 h-4 mr-2" />
                                    {{ __('messages.delete') }}
                                </button>

                                <template x-teleport="body">
                                    <div x-show="openDeleteModal"
                                         x-cloak
                                         class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 dark:bg-gray-900/60 backdrop-blur-sm p-4">
                                        <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-2xl w-full max-w-lg overflow-hidden" @click.outside="openDeleteModal = false">

                                            {{-- Danger header --}}
                                            <div class="px-6 pt-5 pb-4 border-b border-red-200/40 dark:border-red-900/30 bg-red-50/40 dark:bg-red-900/10">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/40">
                                                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('messages.delete_property') }}</h2>
                                                        <p class="text-xs text-red-600 dark:text-red-400 font-medium">{{ __('messages.permanent_action') ?? 'This action is permanent and cannot be undone' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Body --}}
                                            <div class="px-6 py-5 space-y-4">
                                                {{-- Impact summary --}}
                                                <div class="p-4 bg-red-50/80 dark:bg-red-900/20 border border-red-200/60 dark:border-red-800/50 rounded-lg">
                                                    <p class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">{{ __('messages.delete_property_impact') ?? 'The following data will be permanently deleted:' }}</p>
                                                    <ul class="text-xs text-red-600 dark:text-red-300 space-y-1 list-disc list-inside">
                                                        <li>{{ $property->assets_count }} {{ mb_strtolower(__('messages.assets')) }}</li>
                                                        <li>{{ $property->users_count }} {{ mb_strtolower(__('messages.users')) }}</li>
                                                        <li>{{ $property->departments_count }} {{ mb_strtolower(__('messages.departments')) }}</li>
                                                        <li>{{ $property->categories_count }} {{ mb_strtolower(__('messages.categories')) }}</li>
                                                        <li>{{ __('messages.all_roles_and_histories') ?? 'All roles, asset histories, and attachments' }}</li>
                                                    </ul>
                                                </div>

                                                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                                    {{ __('messages.delete_property_recommendation') ?? 'We strongly recommend downloading a backup before deleting. You can restore the data later into a new property.' }}
                                                </p>

                                                <form action="{{ route('properties.destroy', $property) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    @method('DELETE')

                                                    {{-- Code confirmation input --}}
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                            {!! __('messages.type_code_to_confirm', ['code' => '<code class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-red-600 dark:text-red-400 font-mono text-xs">' . e($property->code) . '</code>']) !!}
                                                        </label>
                                                        <input type="text" name="confirm_code" x-model="confirmCode" autocomplete="off" spellcheck="false"
                                                            placeholder="{{ $property->code }}"
                                                            class="block w-full text-sm border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 placeholder-gray-400" />
                                                        @error('confirm_code')
                                                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    {{-- Action buttons --}}
                                                    <div class="flex justify-end gap-3 pt-3 border-t border-gray-200/50 dark:border-gray-700/50">
                                                        <x-secondary-button type="button" @click="openDeleteModal = false; confirmCode = ''">{{ __('messages.cancel') }}</x-secondary-button>
                                                        <button type="submit"
                                                            :disabled="confirmCode.toUpperCase().trim() !== propertyCode.toUpperCase()"
                                                            :class="confirmCode.toUpperCase().trim() !== propertyCode.toUpperCase() ? 'opacity-50 cursor-not-allowed' : 'hover:bg-red-700'"
                                                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white text-xs font-semibold uppercase tracking-widest rounded-lg transition-all duration-200 shadow-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            {{ __('messages.delete_permanently') ?? 'Delete Permanently' }}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
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
