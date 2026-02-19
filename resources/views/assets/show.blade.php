<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Asset Details') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">

                <!-- Image Preview -->
                @if ($asset->attachments)
                    <div class="flex justify-center">
                        <img src="{{ asset('storage/' . $asset->attachments->path) }}"
                             alt="Asset Image"
                             class="max-w-xs rounded-md shadow-md border border-gray-200" />
                    </div>
                @endif

                <!-- Asset Info -->
                <div class="grid grid-cols-2 gap-1 justify-evenly mx-5">
                    <div class="col-span-2 md:col-span-1">
                        <div><strong>Tag:</strong> {{ $asset->tag }}</div>
                        <div><strong>Name:</strong> {{ $asset->name }}</div>
                        <div><strong>Category:</strong> {{ $asset->category->name ?? '-' }}</div>
                        <div><strong>Department:</strong> {{ $asset->department->name ?? '-' }}</div>
                        <div><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $asset->status)) }}</div>
                        <div><strong>Serial Number:</strong> {{ $asset->serial_number ?: '-' }}</div>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <div><strong>Purchase Date:</strong> {{ $asset->purchase_date?->format('d M Y') ?? '-' }}</div>
                        <div>
                            <strong>Warranty Status:</strong>

                            @if ($asset->warranty_date)
                                @php
                                    $expired = \Carbon\Carbon::parse($asset->warranty_date)->isPast();
                                @endphp

                                @if ($expired)
                                    <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded">
                                        Expired ({{ \Carbon\Carbon::parse($asset->warranty_date)->format('d M Y') }})
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded">
                                        Active until {{ \Carbon\Carbon::parse($asset->warranty_date)->format('d M Y') }}
                                    </span>
                                @endif
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded">
                                    No Warranty
                                </span>
                            @endif
                        </div>

                        <div><strong>Purchase Cost:</strong> {{ $asset->purchase_cost ? 'Rp ' . number_format($asset->purchase_cost, 0, ',', '.') : '-' }}</div>
                        <div><strong>Vendor:</strong> {{ $asset->vendor ?: '-' }}</div>
                        <div><strong>Last Editor:</strong> {{ $asset->editorUser->name ?: 'N/A' }}</div>
                    </div>
                </div>
                <div class="mx-5">
                    <strong>Remarks:</strong>
                    <p class=" bg-gray-100"  style="overflow-wrap: anywhere;"> {{ $asset->remarks ?: '-' }} </p>
                </div>
                {{-- QR --}}
                <x-qr-modal :asset="$asset" class="flex justify-center items-center"/>

                <div class="mt-6 flex justify-between items-center">
                    <!-- Back Button -->
                    <a href="{{ route('assets.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md 
                            font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 
                            focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                        Back
                    </a>

                    <div class="inline-flex">
                        @can('update', $assetClass)
                            <!-- Edit Button -->
                            <a href="{{ route('assets.edit', $asset) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md 
                                    font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                <x-heroicon-s-pencil class="w-4 h-4 mr-2" />
                                Edit
                            </a>
                        @endcan
                        @can('delete', $assetClass)
                            <!-- Delete Button -->
                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this asset?');">
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
                        @endcan
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
