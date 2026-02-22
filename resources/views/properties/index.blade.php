<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Properties') }}
            </h2>
            <div>
                <a href="{{ route('properties.create') }}"
                class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md 
                        font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 
                        focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                    <x-heroicon-s-plus class="w-4 h-4 mr-2" />
                    {{ __('New Property') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-2">
            <!-- Table -->
            <div class="bg-white/70 backdrop-blur-sm shadow-md rounded-xl border border-white/30 overflow-x-scroll">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assets</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departments</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($properties as $property)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $properties->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-2 text-sm text-accent font-semibold hover:underline">
                                    <a href="{{ route('properties.show', $property) }}">{{ $property->name }}</a>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $property->code }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $property->address ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $property->users_count }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $property->assets_count }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $property->departments_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $properties->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
