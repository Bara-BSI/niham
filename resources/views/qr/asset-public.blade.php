{{-- resources/views/qr/asset-public.blade.php --}}
<x-guest-layout>
    <div class="py-4 sm:py-8 min-h-screen">
        <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm p-6 md:p-8 space-y-6">
                <!-- Header -->
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-gray-100 text-center mb-6 border-b border-gray-200/50 pb-4">
                    {{ $asset->name }}
                </h1>

                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Left Column: Image Area -->
                    <div class="w-full lg:w-1/3 flex flex-col items-center">
                        @if ($asset->attachments)
                            <div class="w-full flex justify-center">
                                <img src="{{ asset('storage/' . $asset->attachments->path) }}"
                                     alt="Asset Image"
                                     class="w-full max-w-md lg:max-w-full rounded-xl shadow-lg border border-gray-200/50 dark:border-gray-700/50 object-contain bg-white/90 dark:bg-gray-800/90 backdrop-blur-md" />
                            </div>
                        @else
                            <div class="w-full max-w-md lg:max-w-full aspect-square bg-gray-100/50 rounded-xl shadow-sm border border-gray-200/60 flex items-center justify-center">
                                <span class="text-gray-400">No Image Available</span>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Details grid -->
                    <div class="w-full lg:w-2/3 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                            <!-- Left Info Half -->
                            <div class="space-y-3">
                                <div><strong class="text-gray-900 dark:text-gray-100">Tag:</strong> <span class="text-gray-700 dark:text-gray-300">{{ $asset->tag }}</span></div>
                                <div class="flex items-center gap-2 mt-1">
                                    <strong class="text-gray-900 dark:text-gray-100">Status:</strong> 
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full shadow-sm
                                        @if ($asset->status == 'in_service') bg-green-100 text-green-800
                                        @elseif ($asset->status == 'out_of_service') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 dark:text-gray-200 @endif
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
                                    </span>
                                </div>
                                <div class="mt-1"><strong class="text-gray-900 dark:text-gray-100">Department:</strong> <span class="text-gray-700 dark:text-gray-300">{{ optional($asset->department)->name ?? 'Unknown' }}</span></div>
                                <div><strong class="text-gray-900 dark:text-gray-100">Category:</strong> <span class="text-gray-700 dark:text-gray-300">{{ $asset->category->name }}</span></div>
                            </div>

                            <!-- Right Info Half -->
                            <div class="space-y-3 pt-4 md:pt-0 border-t md:border-none border-gray-200/50">
                                <div>
                                    <strong class="text-gray-900 dark:text-gray-100 flex items-center mb-1 md:inline-block md:mb-0 md:mr-2">Warranty Status:</strong>
                                    @if ($asset->warranty_date)
                                        @php
                                            $expired = \Carbon\Carbon::parse($asset->warranty_date)->isPast();
                                        @endphp
                                        @if ($expired)
                                            <span class="px-2 py-1 text-xs font-semibold text-red-700 dark:text-red-200 bg-red-100 dark:bg-red-900/50 rounded shadow-sm">
                                                Expired ({{ \Carbon\Carbon::parse($asset->warranty_date)->format('d M Y') }})
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold text-green-700 dark:text-green-200 bg-green-100 dark:bg-green-900/50 rounded shadow-sm">
                                                Active until {{ \Carbon\Carbon::parse($asset->warranty_date)->format('d M Y') }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold text-gray-600 dark:text-gray-300 bg-gray-100/80 dark:bg-gray-700/50 rounded shadow-sm">
                                            No Warranty
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-1"><strong class="text-gray-900 dark:text-gray-100">Purchase Date:</strong> <span class="text-gray-700 dark:text-gray-300">{{ $asset->purchase_date?->format('d M Y') ?? '-' }}</span></div>
                                <div><strong class="text-gray-900 dark:text-gray-100">Vendor:</strong> <span class="text-gray-700 dark:text-gray-300">{{ $asset->vendor ?: '-' }}</span></div>
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div class="pt-4 border-t border-gray-200/50">
                            <strong class="text-gray-900 dark:text-gray-100 block mb-2">Remarks:</strong>
                            <div class="bg-gray-50/80 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200/40 dark:border-gray-600 text-gray-700 dark:text-gray-300 whitespace-pre-line shadow-sm" style="overflow-wrap: anywhere;">
                                {{ $asset->remarks ?: 'No remarks provided.' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Action Buttons (Responsive flex logic matched from show.blade.php) -->
                <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-gray-200/60 pt-6">
                    <a href="{{ url('/') }}"
                       class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        {{ __('messages.go_to_system') }}
                    </a>
                    
                    <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-2">
                        <!-- Update Status Button (Only for Auth Users With Permission) -->
                        @auth
                            @can('update', $asset)
                            <x-modal-update-status :asset="$asset">
                                <x-slot name="trigger">
                                    <button type="button" class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                        <x-heroicon-s-pencil-square class="w-4 h-4 mr-2" />
                                        Update Status
                                    </button>
                                </x-slot>
                            </x-modal-update-status>
                            @endcan
                        @endauth

                        <a href="{{ route('assets.show', $asset) }}"
                           class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                            <x-heroicon-s-information-circle class="w-4 h-4 mr-2" />
                            {{ __('messages.see_details') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>