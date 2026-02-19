<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QR for {{ $asset->tag }}</title>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: sans-serif;
            background: #f9fafb;
        }
        #qrcode {
            margin-bottom: 1rem;
        }
        .tag {
            font-size: 1.2rem;
            font-weight: 600;
            color: #374151;
        }
    </style>
</head>
<body>
    <div id="qrcode"></div>
    <div class="tag">{{ $asset->tag }}</div>

    <script>
        QRCode.toCanvas(
            document.getElementById('qrcode'),
            "{{ route('qr.resolve', ['uuid' => $asset->uuid]) }}",
            { width: 300, margin: 1 },
            function (error) {
                if (error) console.error(error);
            }
        );
    </script>
</body>
</html>