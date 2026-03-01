<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center text-gray-900 dark:text-gray-100">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('messages.assets') ?? 'Assets' }}
            </h2>
            <div>
                @can('create', App\Models\Asset::class)
                <a href="{{ route('assets.create') }}"
                class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md 
                        font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 
                        focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                    <x-heroicon-s-plus class="w-4 h-4 mr-2" />
                    {{ __('messages.new_asset') }}
                </a>
                @endcan
            </div>
        </div>
    </x-slot>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-2">
            <!-- Filter Toggle & Panel -->
            @php
                $activeFilters = collect(['category', 'department', 'status', 'sort', 'search'])
                    ->filter(fn($f) => request($f))->count();
            @endphp
            <div x-data="{ filtersOpen: {{ $activeFilters > 0 ? 'true' : 'false' }} }" class="mb-4">
                <!-- Toggle Button -->
                <div class="flex items-center gap-2">
                    <button
                        @click="filtersOpen = !filtersOpen"
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border border-gray-200/50 dark:border-gray-700/50 shadow-sm text-sm font-medium text-gray-700 dark:text-gray-100 transition rounded-xl"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span>{{ __('messages.filters') }}</span>
                        @if($activeFilters > 0)
                            <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-accent rounded-full">{{ $activeFilters }}</span>
                        @endif
                        <svg class="w-4 h-4 transition-transform duration-200" :class="filtersOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    @if($activeFilters > 0)
                        <a href="{{ route('assets.index') }}" class="text-sm text-gray-500 hover:text-gray-700 underline">{{ __('messages.clear_all') }}</a>
                    @endif
                </div>

                <!-- Collapsible Filter Panel -->
                <div
                    x-show="filtersOpen"
                    x-collapse
                    x-cloak
                >
                    <form id="filter-form" method="GET" action="{{ route('assets.index') }}" class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm p-4 sm:p-5 mt-3">
                        <input type="hidden" name="format" id="export-format" value="">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('messages.category') }}</label>
                                <select name="category" id="category" class="block w-full border-gray-300 dark:border-gray-700 rounded-lg shadow-sm text-sm focus:ring-accent focus:border-accent dark:bg-gray-900/50 dark:text-gray-100">
                                    <option value="">{{ __('messages.all') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Department -->
                            @if (Auth::user()->hasExecutiveOversight() || Auth::user()->isRole('admin') || Auth::user()->isSuperAdmin())
                                <div>
                                    <label for="department" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('messages.department') }}</label>
                                    <select name="department" id="department" class="block w-full border-gray-300 dark:border-gray-700 rounded-lg shadow-sm text-sm focus:ring-accent focus:border-accent dark:bg-gray-900/50 dark:text-gray-100">
                                        <option value="">{{ __('messages.all') }}</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('messages.status') }}</label>
                                <select name="status" id="status" class="block w-full border-gray-300 dark:border-gray-700 rounded-lg shadow-sm text-sm focus:ring-accent focus:border-accent dark:bg-gray-900/50 dark:text-gray-100">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="in_service" {{ request('status') == 'in_service' ? 'selected' : '' }}>{{ __('messages.in_service') }}</option>
                                    <option value="out_of_service" {{ request('status') == 'out_of_service' ? 'selected' : '' }}>{{ __('messages.out_of_service') }}</option>
                                    <option value="disposed" {{ request('status') == 'disposed' ? 'selected' : '' }}>{{ __('messages.disposed') }}</option>
                                </select>
                            </div>

                            <!-- Sort -->
                            <div>
                                <label for="sort" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('messages.sort_by') }}</label>
                                <select name="sort" id="sort" class="block w-full border-gray-300 dark:border-gray-700 rounded-lg shadow-sm text-sm focus:ring-accent focus:border-accent dark:bg-gray-900/50 dark:text-gray-100">
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>{{ __('messages.name') }}</option>
                                    <option value="tag" {{ request('sort') == 'tag' ? 'selected' : '' }}>{{ __('messages.tag') }}</option>
                                    <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>{{ __('messages.status') }}</option>
                                </select>
                            </div>

                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('messages.search') }}</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_placeholder') }}"
                                    class="block w-full border-gray-300 dark:border-gray-700 rounded-lg shadow-sm text-sm focus:ring-accent focus:border-accent dark:bg-gray-900/50 dark:text-gray-100" />
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mt-4 pt-3 border-t border-gray-200/50">
                            <button type="submit"
                                class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-lg
                                    font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90
                                    focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                {{ __('messages.apply') }}
                            </button>

                                <x-modal-export route="{{ route('assets.export') }}" />
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm overflow-x-auto mt-6">
                <table class="min-w-full divide-y divide-gray-200/50 dark:divide-gray-700/50">
                    <thead class="bg-gray-50/50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('messages.no') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('messages.tag') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('messages.name') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('messages.category') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('messages.department') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('messages.status') }}</th>
                            @if(Auth::user()->isSuperAdmin())
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('messages.property') }}</th>
                            @endif
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('messages.qr') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/50 dark:divide-gray-700/50">
                        @forelse($assets as $a)
                            <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ $assets->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ $a->tag }}</td>
                                <td class="px-4 py-2 text-sm text-accent font-semibold relative">
                                    <x-hover-card :asset="$a">
                                        <a href="{{ route('assets.show',$a) }}" class="transition-colors hover:underline" :class="hovering ? 'text-red-500 font-bold' : ''">{{ $a->name }}</a>
                                    </x-hover-card>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ $a->category->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">{{ optional($a->department)->name ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if ($a->status == 'in_service')
                                            bg-green-100 text-green-800
                                        @elseif ($a->status == 'out_of_service')
                                            bg-red-100 text-red-800
                                        @else
                                            bg-gray-100 text-gray-800 dark:text-gray-200
                                        @endif
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $a->status)) }}
                                    </span>
                                </td>
                                @if(Auth::user()->isSuperAdmin())
                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white shadow-sm" style="background-color: {{ optional($a->property)->accent_color ?? '#6b7280' }}">
                                            {{ optional($a->property)->name ?? '-' }}
                                        </span>
                                    </td>
                                @endif
                                {{-- QR --}}
                                <td class="px-4 py-2">
                                    <x-qr-modal :asset="$a"/>
                                </td>


                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('messages.no_data_found') ?? 'No data found' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $assets->links() }}
            </div>

            @if (Auth::user()->isRole('admin'))
                {{-- Backup --}}
                <div class="flex gap-2">
                    <form action="{{ route('backup.download') }}" method="POST">
                        @csrf
                        <x-primary-button>{{ __('messages.download_backup') ?? 'Download Backup' }}</x-primary-button>
                    </form>

                    <!-- Trigger button -->
                    <x-danger-button x-data @click="$dispatch('open-restore-modal')">
                        {{ __('messages.restore_data') ?? 'Restore Data' }}
                    </x-danger-button>

                    <!-- Modal -->
                    <template x-teleport="body">
                        <div 
                            x-data="{ open: false }"
                            x-on:open-restore-modal.window="open = true"
                            x-show="open"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 dark:bg-gray-900/60 backdrop-blur-sm"
                            x-cloak
                        >
                            <div class="bg-white dark:bg-gray-800 border border-gray-200/50 dark:border-gray-700/50 rounded-xl shadow-xl w-full max-w-md p-6 relative">
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                    ⚠️ {{ __('messages.restore_backup') }}
                                </h2>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    {!! __('messages.restoring_a_backup_will_strong_replace_all_current') !!}
                                </p>

                                <form action="{{ route('backup.restore') }}" method="POST" enctype="multipart/form-data"
                                    class="mt-4 space-y-4">
                                    @csrf
                                    <input type="file" name="backup" accept=".zip" required
                                        class="block w-full text-sm text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-md cursor-pointer dark:bg-gray-900/50 focus:outline-none focus:ring focus:ring-accent" />

                                    <div class="flex justify-end gap-2">
                                        <x-secondary-button type="button" @click="open = false">
                                            {{ __('messages.cancel') }}
                                        </x-secondary-button>
                                        <x-danger-button type="submit">
                                            {{ __('messages.restore_now') }}
                                        </x-danger-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </template>
                </div>
            @endif





        </div>
    </div>
</x-app-layout>
