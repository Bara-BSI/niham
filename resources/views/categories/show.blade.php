<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Category: {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card p-6 md:p-8 space-y-8">
                
                {{-- Responsive Two-Column --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Category Details -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200/50 pb-2">Category Details</h3>
                        <p class="text-lg text-gray-700"><strong class="text-gray-900">Category Name:</strong> {{ $category->name }}</p>
                        <p class="text-lg text-gray-700"><strong class="text-gray-900">Code:</strong> {{ $category->code }}</p>
                    </div>

                    <!-- Notes -->
                    <div class="space-y-3 pt-4 md:pt-0 border-t md:border-none border-gray-200/50">
                        <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200/50 pb-2">Notes</h3>
                        <div class="bg-gray-50/80 p-4 rounded-lg border border-gray-200/40 text-gray-700 whitespace-pre-line shadow-sm" style="overflow-wrap: anywhere;">
                            {{ $category->notes ?: 'No notes provided.' }}
                        </div>
                    </div>
                </div>

                <!-- Assigned Assets -->
                <div class="bg-white/50 rounded-xl p-5 border border-gray-200/60 shadow-sm">
                    <h4 class="text-md font-bold text-gray-900 mb-4 border-b border-gray-200/50 pb-2">Assigned Assets</h4>
                    @if($category->assets->isNotEmpty())
                        <div class="overflow-x-auto rounded-lg border border-gray-200/60">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600">Tag</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600">Name</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600">Category</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach($assets as $asset)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-4 py-3 text-gray-800 font-medium">{{ $asset->tag }}</td>
                                            <td class="px-4 py-3 text-accent hover:underline"><a href="{{ route('assets.show', $asset) }}">{{ $asset->name }}</a></td>
                                            <td class="px-4 py-3 text-gray-600">{{ $asset->category->name }}</td>
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
                        <p class="text-sm text-gray-500 italic">No assets assigned to this category.</p>
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
                        @can('update', $category)
                        <!-- Edit Button -->
                        <a href="{{ route('categories.edit', $category) }}"
                        class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md 
                                font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 
                                focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                            <x-heroicon-s-pencil class="w-4 h-4 mr-2" />
                            Edit
                        </a>
                        @endcan
                        @can('delete', $category)
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
                                        
                                        <h2 class="text-lg font-bold text-gray-900 mb-2">Delete Category</h2>
                                        <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete this Category? This action cannot be undone.</p>
                                        
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST">
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