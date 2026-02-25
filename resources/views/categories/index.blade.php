<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Categories') }}
            </h2>
            <div>
                @can('create', App\Models\Category::class)
                <a href="{{ route('categories.create') }}"
                class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md 
                        font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 
                        focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                    <x-heroicon-s-plus class="w-4 h-4 mr-2" />
                    {{ __('New Category') }}
                </a>
                @endcan
            </div>
        </div>
    </x-slot>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-2">

            <!-- Table -->
            <div class="glass-card overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            @if(Auth::user()->isSuperAdmin())
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($categories as $category)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $categories->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-2 text-sm font-semibold text-accent hover:underline">
                                    <a href="{{ route('categories.show',$category) }}">{{ $category->name }}</a>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $category->code }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $category->notes??'_' }}</td>
                                @if(Auth::user()->isSuperAdmin())
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white shadow-sm" style="background-color: {{ optional($category->property)->accent_color ?? '#6b7280' }}">
                                            {{ optional($category->property)->name ?? '-' }}
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
