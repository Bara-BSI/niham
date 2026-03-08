<x-app-layout>
<div class="py-4 sm:py-8">
        <div class="mx-auto max-w-6xl px-3 sm:px-6 lg:px-8">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200/50 dark:border-gray-700/50">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('messages.user_details') ?? __('messages.user_details') }}
                    </h2>
                </div>
                <div class="p-6 md:p-8 space-y-6">

                <!-- Account Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mx-5">
                    <div class="space-y-3">
                        <div><strong class="text-gray-900 dark:text-gray-100">{{ __('messages.name') }}</strong> <span class="text-gray-700 dark:text-gray-300">{{ $user->name }}</span></div>
                        <div><strong class="text-gray-900 dark:text-gray-100">{{ __('messages.username') }}</strong> <span class="text-gray-700 dark:text-gray-300">{{ $user->username }}</span></div>
                        <div><strong class="text-gray-900 dark:text-gray-100">{{ __('messages.department') }}</strong> <span class="text-gray-700 dark:text-gray-300">{{ $user->department->name ?? '-' }}</span></div>
                        <div><strong class="text-gray-900 dark:text-gray-100">{{ __('messages.role') }}</strong> <span class="text-gray-700 dark:text-gray-300">{{ $user->role->name ?? '-' }}</span></div>
                        <div><strong class="text-gray-900 dark:text-gray-100">{{ __('messages.property') }}</strong> <span class="text-gray-700 dark:text-gray-300">{{ $user->property->name ?? '-' }}</span></div>
                    </div>

                    <div class="space-y-3 pt-4 md:pt-0 border-t md:border-none border-gray-200/50">
                        <div><strong class="text-gray-900 dark:text-gray-100">{{ __('messages.email') }}</strong> <span class="text-gray-700 dark:text-gray-300">{{ $user->email }}</span></div>
                        <div><strong class="text-gray-900 dark:text-gray-100">{{ __('messages.join_date') }}</strong> <span class="text-gray-700 dark:text-gray-300">{{ $user->created_at?->format('d M Y') }}</span></div>
                        <div><strong class="text-gray-900 dark:text-gray-100">{{ __('messages.last_edit') }}</strong> <span class="text-gray-700 dark:text-gray-300">{{ $user->updated_at?->format('d M Y') }}</span></div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-between items-center">
                    <!-- Back Button -->
                    <a href="{{ route('users.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md 
                            font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 
                            focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                        {{ __('messages.back') }}
                    </a>

                    <div class="inline-flex">
                        @can('update', $user)
                        <!-- Edit Button -->
                        <a href="{{ route('users.edit', $user) }}"
                        class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md 
                                font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 
                                focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                            <x-heroicon-s-pencil class="w-4 h-4 mr-2" />
                            {{ __('messages.edit') }}
                        </a>
                        @endcan
                        @can('delete', $user)
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
                                        
                                        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('messages.delete_user') }}</h2>
                                        <p class="text-sm text-gray-600 mb-6">{{ __('messages.are_you_sure_you_want_to_delete_this_user_this_act') }}</p>
                                        
                                        <form action="{{ route('users.destroy', $user) }}" method="POST">
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
