<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $property->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('properties.edit', $property) }}"
                   class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md 
                           font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 
                           focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                    <x-heroicon-s-pencil class="w-4 h-4 mr-2" />
                    {{ __('Edit') }}
                </a>
                <div x-data="{ openDeleteModal: false }" class="inline-flex">
                    <button type="button" @click="openDeleteModal = true"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md 
                                    font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 
                                    focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                        <x-heroicon-s-trash class="w-4 h-4 mr-2" />
                        {{ __('Delete') }}
                    </button>

                    <template x-teleport="body">
                        <div x-show="openDeleteModal"
                            x-cloak
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
                            <div class="bg-white/90 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl w-full max-w-md p-6 relative" @click.outside="openDeleteModal = false">
                                <button @click="openDeleteModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                    <x-heroicon-s-x-mark class="w-5 h-5"/>
                                </button>
                                
                                <h2 class="text-lg font-bold text-gray-900 mb-2">Delete Property</h2>
                                <p class="text-sm text-gray-600 mb-6">Are you sure you want to permanently delete this property? This action cannot be undone.</p>
                                
                                <form action="{{ route('properties.destroy', $property) }}" method="POST">
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
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">
            <!-- Property Details -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Property Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $property->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Code</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $property->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $property->address ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Statistics</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $property->users_count }} users · 
                            {{ $property->assets_count }} assets · 
                            {{ $property->departments_count }} departments · 
                            {{ $property->categories_count }} categories
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Users in this Property -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Users</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $user->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ optional($user->role)->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-2 text-sm text-gray-500 text-center">No users</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-2">{{ $users->links() }}</div>
            </div>

            <!-- Departments in this Property -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Departments</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($departments as $dept)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $dept->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $dept->code }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-2 text-sm text-gray-500 text-center">No departments</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-2">{{ $departments->links() }}</div>
            </div>

        </div>
    </div>
</x-app-layout>
