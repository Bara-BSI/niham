<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Asset History:') }} {{ $asset->name }} ({{ $asset->tag }})
            </h2>
            <a href="{{ route('assets.history.export', $asset) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export HTML/CSV
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-2">
            <div class="glass-card mb-4 text-sm p-4">
                <a href="{{ route('dashboard') }}" class="text-accent hover:underline mb-2 inline-block">&larr; Back to Dashboard</a>
            </div>
            <div class="glass-card overflow-x-auto p-0">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Changes Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($histories as $h)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $h->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $h->user?->name ?? 'System' }}</td>
                                <td class="px-4 py-3 text-sm font-semibold 
                                    {{ $h->action == 'created' ? 'text-green-600' : ($h->action == 'updated' ? 'text-blue-600' : 'text-red-600') }}">
                                    {{ ucfirst($h->action) }}
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600 max-w-xl truncate">
                                    @if($h->changes)
                                        {{ json_encode($h->changes) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-sm text-gray-500 text-center">No history recorded yet for this asset.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $histories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
