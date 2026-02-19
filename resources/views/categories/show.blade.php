<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Category: {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
                
                {{-- Responsive Two-Column --}}
                <div class="grid grid-cols-2 gap-1 justify-evenly">
                    <!-- Category Details -->
                    <div class="col-span-2 md:col-span-1">
                        <h3 class="text-lg font-medium text-gray-900">Category Details</h3>
                        <p class="text-lg text-gray-600">Category Name: <strong>{{ $category->name }}</strong></p>
                        <p class="text-lg text-gray-600">Code: <strong>{{ $category->code }}</strong></p>
                    </div>

                    <!-- Notes -->
                    <div class="col-span-2 md:col-span-1">
                        <h3 class="text-lg font-medium text-gray-900">Notes</h3>
                        <p class=" bg-gray-100"  style="overflow-wrap: anywhere;"> {{ $category->notes ?: '-' }} </p>
                    </div>
                </div>

                <!-- Assigned Assets -->
                <div class="">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">Assigned Assets</h4>
                    @if($category->assets->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-medium text-gray-600">Tag</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-600">Name</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-600">Category</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($assets as $asset)
                                        <tr>
                                            <td class="px-4 py-2">{{ $asset->tag }}</td>
                                            <td class="px-4 py-2">{{ $asset->name }}</td>
                                            <td class="px-4 py-2">{{ $asset->category->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $assets->links() }}
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No assets assigned to this category.</p>
                    @endif
                </div>
                

                <div class="mt-6 flex justify-between items-center">
                    <!-- Back Button -->
                    <a href="{{ route('categories.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md 
                            font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 
                            focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                        Back to categories
                    </a>

                    <div class="inline-flex">
                        <!-- Edit Button -->
                        <a href="{{ route('categories.edit', $category) }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md 
                                font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 
                                focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                            <x-heroicon-s-pencil class="w-4 h-4 mr-2" />
                            Edit
                        </a>
                        <!-- Delete Button -->
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Category?');">
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