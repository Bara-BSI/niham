@props(['asset'])

<div class="relative inline-block"
    x-data="{
        hovering: false,
        x: 0,
        y: 0,
        asset: {
            name: @js($asset->name),
            vendor: @js($asset->vendor ?? 'N/A'),
            warranty: @js($asset->warranty_date ? (\Carbon\Carbon::parse($asset->warranty_date)->isPast() ? 'Expired' : \Carbon\Carbon::parse($asset->warranty_date)->format('M d, Y')) : 'None'),
            image: @js($asset->attachments ? asset('storage/' . $asset->attachments->path) : '')
        }
    }"
    @mouseenter="hovering = true; x = $event.clientX; y = $event.clientY"
    @mouseleave="hovering = false"
    @mousemove="x = $event.clientX; y = $event.clientY"
>
    <!-- Trigger slot (typically the asset name link) -->
    {{ $slot }}

    <template x-teleport="body">
        <div x-show="hovering"
             x-transition.opacity.duration.200ms
             x-cloak
             class="fixed z-50 w-64 bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-2xl p-4 pointer-events-none"
             :style="`top: ${y + 15}px; left: ${x + 15}px;`"
        >
            <template x-if="asset.image">
                <img :src="asset.image" :alt="asset.name" class="w-full h-32 object-cover rounded-lg mb-3">
            </template>
            <template x-if="!asset.image">
                <div class="w-full h-32 bg-gray-100 dark:bg-gray-900 rounded-lg mb-3 flex items-center justify-center text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </template>
            <h4 class="font-bold text-gray-800 dark:text-gray-200 text-base mb-1" x-text="asset.name"></h4>
            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1"><span class="font-semibold">Vendor:</span> <span x-text="asset.vendor"></span></p>
            <p class="text-xs text-gray-600 dark:text-gray-400"><span class="font-semibold">Warranty:</span> <span x-text="asset.warranty"></span></p>
        </div>
    </template>
</div>
