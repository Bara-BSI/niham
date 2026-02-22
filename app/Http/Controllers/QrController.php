<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use URL;

class QrController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('image');
    }

    public function image(Asset $asset)
    {
        $this->authorize('view', $asset);

        $signedUrl = URL::signedRoute('qr.resolve', ['uuid' => $asset->uuid]);

        // Generate QR
        $png = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(400)->margin(1)
            ->errorCorrection('H')
            // ->merge(public_path('niham-logo-cr-rd.png'), 0.2, true)
            ->generate($signedUrl);

        return response($png)->header('Content-Type', 'image/png');
    }

    public function resolve(Request $request, string $uuid)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Invalid or expired QR link');
        }
        $asset = Asset::where('uuid', $uuid)->firstOrFail();

        // Opsi 1: tampilkan halaman minimal yang bisa diakses umum
        return view('qr.asset-public', compact('asset'));
        // Opsi 2: memerlukan login
        // return redirect()->route('assets.show', $asset);
    }
}
