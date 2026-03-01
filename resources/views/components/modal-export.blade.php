@props(['route'])

<div x-data="{ openExportModal: false }" class="inline-block w-full sm:w-auto">
    <!-- Trigger Button -->
    <button type="button" @click="openExportModal = true"
        {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition w-full sm:w-auto justify-center']) }}>
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        {{ __('messages.export') }}
    </button>
    
    <template x-teleport="body">
        <div x-show="openExportModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 dark:bg-gray-900/60 backdrop-blur-sm p-4">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border border-gray-200/50 dark:border-gray-700/50 rounded-xl shadow-xl w-full max-w-sm p-6 relative" @click.outside="openExportModal = false">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Choose Export Format</h2>
                
                <div class="flex flex-col gap-3 mt-4">
                    <button @click="document.getElementById('export-format').value='excel'; document.getElementById('filter-form').target='_self'; document.getElementById('filter-form').action='{{ $route }}'; document.getElementById('filter-form').submit(); openExportModal=false;" type="button"
                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-500 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export to Excel
                    </button>
                    <button @click="document.getElementById('export-format').value='pdf'; document.getElementById('filter-form').target='_blank'; document.getElementById('filter-form').action='{{ $route }}'; document.getElementById('filter-form').submit(); setTimeout(() => { document.getElementById('filter-form').target='_self'; }, 100); openExportModal=false;" type="button"
                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-500 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Export to PDF
                    </button>
                    <x-secondary-button @click="openExportModal = false" class="mt-2 w-full justify-center">
                        Cancel
                    </x-secondary-button>
                </div>
            </div>
        </div>
    </template>
</div>
