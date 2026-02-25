<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OcrScanController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048' // Max 2MB for OCR.space limits usually
        ]);

        $file = $request->file('image');
        
        try {
            // Using a default generic free key if none provided in env
            $apiKey = env('OCR_SPACE_API_KEY', 'helloworld');
            
            $response = Http::attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post('https://api.ocr.space/parse/image', [
                'apikey' => $apiKey,
                'OCREngine' => '5', // Engine 5 is the latest machine learning based OCR engine
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['IsErroredOnProcessing']) && $data['IsErroredOnProcessing']) {
                    return response()->json(['error' => 'OCR Processing Error: ' . ($data['ErrorMessage'][0] ?? 'Unknown error')], 500);
                }

                $parsedText = $data['ParsedResults'][0]['ParsedText'] ?? '';
                
                // --- Parsing Logic ---
                $serialNumber = null;
                $brand = null;
                $assetName = null;

                // Example rough extraction logic:
                // Look for common SN labels
                if (preg_match('/(?:SN|S\/N|Serial(?: No)?\.?)\s*:?\s*([A-Z0-9\-]+)/i', $parsedText, $matches)) {
                    $serialNumber = $matches[1];
                }

                // Split text into lines for heuristics
                $lines = array_values(array_filter(array_map('trim', explode("\n", $parsedText))));
                
                if (count($lines) > 0) {
                    // Very rudimentary fallback: first line is often the title/brand
                    $brandTemp = $lines[0];
                    if (strlen($brandTemp) > 2 && strlen($brandTemp) < 50) {
                        $brand = $brandTemp;
                        $assetName = $brandTemp . ' Asset';
                    }
                }

                return response()->json([
                    'success' => true,
                    'raw_text' => $parsedText,
                    'extracted' => [
                        'serial_number' => $serialNumber,
                        'brand' => $brand,
                        'asset_name' => $assetName
                    ]
                ]);
            }

            return response()->json(['error' => 'Failed to reach OCR service'], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
