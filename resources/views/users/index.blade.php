<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Users') }}
            </h2>
            <div>
                @can('create', App\Models\User::class)
                <a href="{{ route('users.create') }}"
                class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md 
                        font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 
                        focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                    <x-heroicon-s-plus class="w-4 h-4 mr-2" />
                    {{ __('New User') }}
                </a>
                @endcan
            </div>
        </div>
    </x-slot>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-2">
            <!-- Filter Toggle & Panel -->
            @php
                $activeFilters = collect(['department', 'role'])
                    ->filter(fn($f) => request($f))->count();
            @endphp
            <div x-data="{ filtersOpen: {{ $activeFilters > 0 ? 'true' : 'false' }} }" class="mb-4">
                <!-- Toggle Button -->
                <div class="flex items-center gap-2">
                    <button
                        @click="filtersOpen = !filtersOpen"
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2 glass-card text-sm font-medium text-gray-700 hover:bg-gray-500/10 transition rounded-xl"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span>Filters</span>
                        @if($activeFilters > 0)
                            <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-accent rounded-full">{{ $activeFilters }}</span>
                        @endif
                        <svg class="w-4 h-4 transition-transform duration-200" :class="filtersOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    @if($activeFilters > 0)
                        <a href="{{ route('users.index') }}" class="text-sm text-gray-500 hover:text-gray-700 underline">Clear all</a>
                    @endif
                </div>

                <!-- Collapsible Filter Panel -->
                <div
                    x-show="filtersOpen"
                    x-collapse
                    x-cloak
                >
                    <form method="GET" action="{{ route('users.index') }}" class="glass-card p-4 sm:p-5 mt-3">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Department -->
                            <div>
                                <label for="department" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Department</label>
                                <select name="department" id="department" class="block w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-accent focus:border-accent">
                                    <option value="">All</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="role" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Role</label>
                                <select name="role" id="role" class="block w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-accent focus:border-accent">
                                    <option value="">All</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Apply button inline with filters -->
                            <div class="flex items-end">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-lg
                                        font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90
                                        focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    {{ __('Apply') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="glass-card overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            @if (Auth::user()->isSuperAdmin())
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $users->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-2 text-sm text-accent font-semibold hover:underline">
                                    <a href="{{ route('users.show',$user) }}">{{ $user->name }}</a>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ optional($user->department)->name ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ optional($user->role)->name ?? '-' }}</td>
                                @if (Auth::user()->isSuperAdmin())
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white shadow-sm" style="background-color: {{ optional($user->property)->accent_color ?? '#6b7280' }}">
                                            {{ optional($user->property)->name ?? '-' }}
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
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
