<x-app-layout>
    <x-slot name="header"><h2>Scan asset QR</h2></x-slot>
    <div id="reader" style="width: 400px; max-width: 100%"></div>
    <div id="result" class="mt-4"></div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
    function onScanSuccess(decodedText, decodedResult) {
        // Optionally validate URL structure before redirect
        if (decodedText.startsWith('{{ url('/qr/resolve') }}')) {
            window.location.href = decodedText; // follows signed URL to public page
        } else {
            document.getElementById('result').innerText = 'Invalid QR content';
        }
    }
    function onScanFailure(error) {
        // silently ignore frequent decode errors
    }
    const html5QrcodeScanner = new Html5QrcodeScanner('reader', { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</x-app-layout>