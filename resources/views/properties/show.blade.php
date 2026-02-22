<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $property->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('properties.edit', $property) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md 
                           font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <x-heroicon-s-pencil class="w-4 h-4 mr-2" />
                    {{ __('Edit') }}
                </a>
                <form method="POST" action="{{ route('properties.destroy', $property) }}" 
                      onsubmit="return confirm('Are you sure you want to delete this property?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md 
                                    font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 
                                    focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                        <x-heroicon-s-trash class="w-4 h-4 mr-2" />
                        {{ __('Delete') }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Property Details -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
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
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Users</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
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
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Departments</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
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
