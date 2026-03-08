<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col items-center">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">{{ __('messages.scan_asset_qr') }}</h2>
                    <div id="reader" style="width: 400px; max-width: 100%"></div>
                    <div id="result" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
    function onScanSuccess(decodedText, decodedResult) {
        // Optionally validate URL structure before redirect
        if (decodedText.startsWith('{{ url('/qr/resolve') }}')) {
            window.location.href = decodedText; // follows signed URL to public page
        } else {
            document.getElementById('result').innerText = '{{ __('messages.invalid_qr_content') }}';
        }
    }
    function onScanFailure(error) {
        // silently ignore frequent decode errors
    }
    const html5QrcodeScanner = new Html5QrcodeScanner('reader', { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</x-app-layout>