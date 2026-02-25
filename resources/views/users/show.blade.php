<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Account Details') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="mx-auto max-w-6xl px-3 sm:px-6 lg:px-8">
            <div class="glass-card p-6 md:p-8 space-y-6">

                <!-- Account Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mx-5">
                    <div class="space-y-3">
                        <div><strong class="text-gray-900">Name:</strong> <span class="text-gray-700">{{ $user->name }}</span></div>
                        <div><strong class="text-gray-900">Username:</strong> <span class="text-gray-700">{{ $user->username }}</span></div>
                        <div><strong class="text-gray-900">Department:</strong> <span class="text-gray-700">{{ $user->department->name ?? '-' }}</span></div>
                        <div><strong class="text-gray-900">Role:</strong> <span class="text-gray-700">{{ $user->role->name ?? '-' }}</span></div>
                        <div><strong class="text-gray-900">Property:</strong> <span class="text-gray-700">{{ $user->property->name ?? '-' }}</span></div>
                    </div>

                    <div class="space-y-3 pt-4 md:pt-0 border-t md:border-none border-gray-200/50">
                        <div><strong class="text-gray-900">Email:</strong> <span class="text-gray-700">{{ $user->email }}</span></div>
                        <div><strong class="text-gray-900">Join Date:</strong> <span class="text-gray-700">{{ $user->created_at?->format('d M Y') }}</span></div>
                        <div><strong class="text-gray-900">Last Edit:</strong> <span class="text-gray-700">{{ $user->updated_at?->format('d M Y') }}</span></div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-between items-center">
                    <!-- Back Button -->
                    <a href="{{ route('users.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md 
                            font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 
                            focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                        Back
                    </a>

                    <div class="inline-flex">
                        @can('update', $user)
                        <!-- Edit Button -->
                        <a href="{{ route('users.edit', $user) }}"
                        class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md 
                                font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 
                                focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                            <x-heroicon-s-pencil class="w-4 h-4 mr-2" />
                            Edit
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
                                Delete
                            </button>

                            <template x-teleport="body">
                                <div x-show="openDeleteModal"
                                    x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
                                    <div class="bg-white/90 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl w-full max-w-md p-6 relative" @click.outside="openDeleteModal = false">
                                        <button @click="openDeleteModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                            <x-heroicon-s-x-mark class="w-5 h-5"/>
                                        </button>
                                        
                                        <h2 class="text-lg font-bold text-gray-900 mb-2">Delete User</h2>
                                        <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete this user? This action cannot be undone.</p>
                                        
                                        <form action="{{ route('users.destroy', $user) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="flex justify-end gap-3">
                                                <x-secondary-button type="button" @click="openDeleteModal = false">Cancel</x-secondary-button>
                                                <x-danger-button type="submit">Yes, Delete</x-danger-button>
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
</x-app-layout>
