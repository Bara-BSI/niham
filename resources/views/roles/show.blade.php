<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Role: {{ $role->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-sm shadow-md rounded-xl border border-white/30 p-6 space-y-6">
                
                {{-- Responsive Two-Column --}}
                <div class="grid grid-cols-2 gap-1 justify-evenly">
                    <!-- Role Details -->
                    <div class="col-span-2 md:col-span-1">
                        <h3 class="text-lg font-medium text-gray-900">Role Details</h3>
                        <p class="text-lg text-gray-600">Role Name: <strong>{{ ucwords($role->name) }}</strong></p>
                    </div>

                    <!-- Permissions -->
                    <div class="col-span-2 md:col-span-1">
                        <h3 class="text-lg font-medium text-gray-900">Role Permissions</h3>
                        <p class="text-lg text-gray-600">
                            {{ $role->can_create ? 'Create,':'' }}
                            {{ $role->can_read ? 'Read,':'' }}
                            {{ $role->can_update ? 'Update,':'' }}
                            {{ $role->can_delete ? 'Delete,':'' }}
                        </p>
                    </div>
                </div>
                
                <!-- Assigned Users -->
                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-2">Assigned Users</h4>
                    @if($role->users->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-medium text-gray-600">Name</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-600">Department</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-600">Joined</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="px-4 py-2">{{ $user->name }}</td>
                                            <td class="px-4 py-2">{{ $user->department->name }}</td>
                                            <td class="px-4 py-2">{{ $user->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No users assigned to this role.</p>
                    @endif
                </div>

                <div class="mt-6 flex justify-between items-center">
                    <!-- Back Button -->
                    <a href="{{ route('roles.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md 
                            font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 
                            focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                        Back to roles
                    </a>

                    <div class="inline-flex">
                        <!-- Edit Button -->
                        <a href="{{ route('roles.edit', $role) }}"
                        class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md 
                                font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 
                                focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                            <x-heroicon-s-pencil class="w-4 h-4 mr-2" />
                            Edit
                        </a>
                        <!-- Delete Button -->
                        <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Department?');">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md 
                                        font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 
                                        focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ml-1">
                                <x-heroicon-s-trash class="w-4 h-4 mr-2" />
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>