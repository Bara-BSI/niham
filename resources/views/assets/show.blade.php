<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Asset Details') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="mx-auto max-w-6xl px-3 sm:px-6 lg:px-8">
            <div class="glass-card p-6 md:p-8 space-y-6">

                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Left Column: Image & QR -->
                    <div class="w-full lg:w-1/3 flex flex-col items-center space-y-6">
                        <!-- Image Preview -->
                        @if ($asset->attachments)
                            <div class="w-full flex justify-center">
                                <img src="{{ asset('storage/' . $asset->attachments->path) }}"
                                     alt="Asset Image"
                                     class="w-full max-w-md lg:max-w-full rounded-xl shadow-lg border border-gray-200/60 object-contain bg-white/50" />
                            </div>
                        @else
                            <div class="w-full max-w-md lg:max-w-full aspect-square bg-gray-100/50 rounded-xl shadow-sm border border-gray-200/60 flex items-center justify-center">
                                <span class="text-gray-400">No Image Available</span>
                            </div>
                        @endif

                        <div class="w-full flex justify-center">
                            {{-- QR --}}
                            <x-qr-modal :asset="$asset" />
                        </div>
                    </div>

                    <!-- Right Column: Details -->
                    <div class="w-full lg:w-2/3 space-y-6">
                        <!-- Asset Info Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                            <div class="space-y-3">
                                <div><strong class="text-gray-900">Tag:</strong> <span class="text-gray-700">{{ $asset->tag }}</span></div>
                                <div><strong class="text-gray-900">Name:</strong> <span class="text-gray-700">{{ $asset->name }}</span></div>
                                <div><strong class="text-gray-900">Category:</strong> <span class="text-gray-700">{{ $asset->category->name ?? '-' }}</span></div>
                                <div><strong class="text-gray-900">Department:</strong> <span class="text-gray-700">{{ $asset->department->name ?? '-' }}</span></div>
                                <div class="flex items-center gap-2">
                                    <strong class="text-gray-900">Status:</strong> 
                                    <span class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $asset->status)) }}</span>
                                    @can('update', $assetClass)
                                    <x-modal-update-status :asset="$asset">
                                        <x-slot name="trigger">
                                            <button type="button" class="text-accent hover:text-indigo-800 transition" title="Update Status">
                                                <x-heroicon-s-pencil-square class="w-4 h-4"/>
                                            </button>
                                        </x-slot>
                                    </x-modal-update-status>
                                    @endcan
                                </div>
                                <div><strong class="text-gray-900">Serial Number:</strong> <span class="text-gray-700">{{ $asset->serial_number ?: '-' }}</span></div>
                            </div>

                            <div class="space-y-3 pt-4 md:pt-0 border-t md:border-none border-gray-200/50">
                                <div><strong class="text-gray-900">Purchase Date:</strong> <span class="text-gray-700">{{ $asset->purchase_date?->format('d M Y') ?? '-' }}</span></div>
                                <div>
                                    <strong class="text-gray-900 flex items-center mb-1 md:inline-block md:mb-0 md:mr-2">Warranty Status:</strong>
                                    @if ($asset->warranty_date)
                                        @php
                                            $expired = \Carbon\Carbon::parse($asset->warranty_date)->isPast();
                                        @endphp
                                        @if ($expired)
                                            <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded shadow-sm">
                                                Expired ({{ \Carbon\Carbon::parse($asset->warranty_date)->format('d M Y') }})
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded shadow-sm">
                                                Active until {{ \Carbon\Carbon::parse($asset->warranty_date)->format('d M Y') }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-100/80 rounded shadow-sm">
                                            No Warranty
                                        </span>
                                    @endif
                                </div>
                                <div><strong class="text-gray-900">Purchase Cost:</strong> <span class="text-gray-700">{{ $asset->purchase_cost ? 'Rp ' . number_format($asset->purchase_cost, 0, ',', '.') : '-' }}</span></div>
                                <div><strong class="text-gray-900">Vendor:</strong> <span class="text-gray-700">{{ $asset->vendor ?: '-' }}</span></div>
                                <div><strong class="text-gray-900">Last Editor:</strong> <span class="text-gray-700">{{ $asset->editorUser->name ?: 'N/A' }}</span></div>
                            </div>
                        </div>

                        <!-- Remarks Block -->
                        <div class="pt-4 border-t border-gray-200/50">
                            <strong class="text-gray-900 block mb-2">Remarks:</strong>
                            <div class="bg-gray-50/80 p-4 rounded-lg border border-gray-200/40 text-gray-700 whitespace-pre-line shadow-sm" style="overflow-wrap: anywhere;">
                                {{ $asset->remarks ?: 'No remarks provided.' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <!-- Back Button -->
                    <a href="{{ route('assets.index') }}"
                    class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md 
                            font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 
                            focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                        Back
                    </a>

                    <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-2">
                        @if (Auth::user()->hasExecutiveOversight())
                            <a href="{{ route('assets.history', $asset) }}"
                            class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md 
                                    font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                <x-heroicon-s-clock class="w-4 h-4 mr-2" />
                                History
                            </a>
                        @endif
                        @can('update', $assetClass)
                            <!-- Edit Button -->
                            <a href="{{ route('assets.edit', $asset) }}"
                            class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md 
                                    font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 
                                    focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                                <x-heroicon-s-pencil class="w-4 h-4 mr-2" />
                                Edit
                            </a>
                        @endcan
                        @can('delete', $assetClass)
                            <!-- Delete Button & Modal -->
                            <div x-data="{ openDeleteModal: false }" class="inline-flex w-full sm:w-auto">
                                <button type="button" @click="openDeleteModal = true"
                                        class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md 
                                            font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 
                                            focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                                    <x-heroicon-s-trash class="w-4 h-4 mr-2" />
                                    Delete
                                </button>

                                <template x-teleport="body">
                                    <div x-show="openDeleteModal"
                                        x-cloak
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
                                        <div class="bg-white/90 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl w-full max-w-md p-6 relative" @click.outside="openDeleteModal = false">
                                            <button @click="openDeleteModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                                <x-heroicon-s-x-mark class="w-5 h-5"/>
                                            </button>
                                            
                                            <h2 class="text-lg font-bold text-gray-900 mb-2">Delete Asset</h2>
                                            <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete this asset? This action cannot be undone.</p>
                                            
                                            <form action="{{ route('assets.destroy', $asset) }}" method="POST">
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
