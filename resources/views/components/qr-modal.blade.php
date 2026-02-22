<div x-data="{ open: false }" {{ $attributes->merge(['class' => 'relative']) }}>
    <!-- Thumbnail -->
    <img src="{{ route('assets.qr',$asset) }}"
        alt="QR"
        class="h-16 w-16 cursor-pointer"
        @click="open = true">

    <!-- Modal -->
    <div x-show="open"
        x-cloak
        @click.self="open = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-4 rounded-lg shadow-lg relative">
            <!-- Close button with circle -->
            <button @click="open = false"
                    class="absolute top-2 right-2 flex items-center justify-center
                        w-5 h-5 rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300
                        focus:outline-none focus:ring-2 focus:ring-accent">
                <x-heroicon-s-x-mark class="w-4 h-4"/>
            </button>

            <!-- Full-size QR -->
            <img src="{{ route('assets.qr',$asset) }}"
                alt="QR"
                class="h-64 w-64 p-4">

            <!-- Download button -->
            <div class="mt-4 text-center">
                <a href="{{ route('assets.qr',$asset) }}"
                download="{{ $asset->name }} - {{ $asset->tag }}.png"
                class="inline-flex items-center px-4 py-2 bg-accent border border-transparent 
                        rounded-md font-semibold text-xs text-white uppercase tracking-widest 
                        hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-accent 
                        focus:ring-offset-2 transition">
                    ⬇ Download
                </a>
            </div>
        </div>
    </div>
</div>