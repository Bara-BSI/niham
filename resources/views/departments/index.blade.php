<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Departments') }}
            </h2>
            <div>
                <a href="{{ route('departments.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md 
                        font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <x-heroicon-s-plus class="w-4 h-4 mr-2" />
                    {{ __('New Department') }}
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
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($departments as $department)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $departments->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-2 text-sm font-semibold text-indigo-700 hover:underline">
                                    <a href="{{ route('departments.show',$department) }}">{{ $department->name }}</a>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $department->code }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $department->notes??'_' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $departments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
