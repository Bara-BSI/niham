{{-- resources/views/qr/asset-public.blade.php --}}
<x-guest-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ $asset->name }}
                    </h1>

                    <!-- Image Preview -->
                    @if ($asset->attachments)
                        <div class="flex justify-center">
                            <img src="{{ asset('storage/' . $asset->attachments->path) }}"
                                alt="Asset Image"
                                class="max-w-xs rounded-md shadow-md border border-gray-200" />
                        </div>
                    @endif

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="font-semibold text-gray-700">Asset Tag</dt>
                            <dd class="text-gray-900">{{ $asset->tag }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-700">Status</dt>
                            <dd>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if ($asset->status == 'in_service')
                                            bg-green-100 text-green-800
                                        @elseif ($asset->status == 'out_of_service')
                                            bg-red-100 text-red-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
                                    </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-700">Department</dt>
                            <dd class="text-gray-900">
                                {{ optional($asset->department)->name ?? 'Unknown' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-700">Category</dt>
                            <dd class="text-gray-900">{{ $asset->category->name }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-700">Warranty Status</dt>
                            <dd class="text-gray-900">
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
                            </dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-700">Remarks</dt>
                            <dd class="text-gray-900 bg-gray-100" style="overflow-wrap: anywhere;">
                                {{ $asset->remarks ?? '_' }}
                            </dd>
                        </div>
                    </dl>

                    <div class="pt-6 flex justify-between items-center">
                        <a href="{{ url('/') }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent 
                                  rounded-md font-semibold text-xs text-white uppercase tracking-widest 
                                  hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 
                                  focus:ring-offset-2 transition">
                            {{ __('Go to system') }}
                        </a>
                        <a href="{{ route('assets.show', $asset) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md 
                                font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 
                                focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                            {{ __('See Details') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>