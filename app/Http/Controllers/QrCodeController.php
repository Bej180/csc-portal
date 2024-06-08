<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Cache;

class QrCodeController extends Controller
{
    public function generate(Request $request)
    {
        $url = $request->input('url', 'https://example.com'); // Default URL if none is provided
        $cacheKey = 'qr_code_' . md5($url);

        // Check if QR code is in cache
        if (Cache::has($cacheKey)) {
            $qrCode = Cache::get($cacheKey);
        } else {
            // Generate QR code and cache it
            $qrCode = QrCode::size(300)->generate($url);
            Cache::put($cacheKey, $qrCode, 60 * 60 * 24); // Cache for 24 hours
        }

        return response($qrCode)->header('Content-Type', 'image/png');
    }
}
