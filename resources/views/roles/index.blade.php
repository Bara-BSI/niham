<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Roles') }}
            </h2>
            <div>
                <a href="{{ route('roles.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md 
                        font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <x-heroicon-s-plus class="w-4 h-4 mr-2" />
                    {{ __('New Role') }}
                </a>
            </div>
        </div>
    </x-slot>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-2">
            
            <!-- Table -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-scroll rounded">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" rowspan="2">No</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" rowspan="2">Name</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" colspan="4">Permissions</th>
                        </tr>
                        <tr>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Create</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Read</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Update</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($roles as $role)
                            <tr>
                                <!-- No -->
                                <td class="px-4 py-2 text-center text-sm text-gray-700">
                                    {{ $roles->firstItem() + $loop->index }}
                                </td>

                                <!-- Role Name -->
                                <td class="px-4 py-2 text-center text-sm font-medium text-indigo-700 font-semibold hover:underline">
                                    <a href="{{ route('roles.show', $role) }}">{{ ucwords($role->name) }}</a>
                                </td>

                                <!-- Permissions -->
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" disabled {{ $role->can_create ? 'checked' : '' }}
                                        class="h-4 w-4 text-gray-600 border-gray-300 rounded">
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" disabled {{ $role->can_read ? 'checked' : '' }}
                                        class="h-4 w-4 text-gray-600 border-gray-300 rounded">
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" disabled {{ $role->can_update ? 'checked' : '' }}
                                        class="h-4 w-4 text-gray-600 border-gray-300 rounded">
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" disabled {{ $role->can_delete ? 'checked' : '' }}
                                        class="h-4 w-4 text-gray-600 border-gray-300 rounded">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <!-- Pagination -->
            <div class="mt-4">
                {{ $roles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
