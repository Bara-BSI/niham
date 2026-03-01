<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-2">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm mb-6 flex flex-wrap justify-between items-center w-full p-4 md:p-6">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('messages.asset_history') }} {{ $asset->name }} ({{ $asset->tag }})
                </h2>
                <a href="{{ route('assets.history.export', $asset) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition shadow-sm mt-3 md:mt-0">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('messages.export_html_csv') }}
                </a>
            </div>

            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm mb-4 text-sm p-4">
                <a href="{{ route('assets.show', $asset->id) }}" class="text-accent dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:underline mb-2 inline-block">&larr; {{ __('messages.back_to_asset') }}</a>
            </div>
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm overflow-x-auto p-0">
                <table class="min-w-full divide-y divide-gray-200/50 dark:divide-gray-700/50">
                    <thead class="bg-gray-50/50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('messages.date') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('messages.user') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('messages.action') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('messages.changes_details') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/50 dark:divide-gray-700/50">
                        @forelse($histories as $h)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200 whitespace-nowrap">{{ $h->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200 whitespace-nowrap">{{ $h->user?->name ?? __('messages.system') }}</td>
                                <td class="px-4 py-3 text-sm font-semibold 
                                    {{ $h->action == 'created' ? 'text-green-600 dark:text-green-400' : ($h->action == 'updated' ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400') }}">
                                    {{ ucfirst($h->action) }}
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-900 dark:text-gray-200 max-w-xl truncate">
                                    @if($h->changes)
                                        {{ json_encode($h->changes) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-sm text-gray-500 dark:text-gray-400 text-center">{{ __('messages.no_history_recorded_yet_for_this_asset') }}</td>
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
