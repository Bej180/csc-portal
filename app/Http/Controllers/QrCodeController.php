<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class QrCodeController extends Controller
{
    /**
     * Generate a QR code for the given URL.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $url = $request->input('url');

        // Generate a unique cache key based on the URL
        $cacheKey = 'qr_code_' . md5($url);

        // Check if the QR code is already cached
        if (Cache::has($cacheKey)) {
            $filePath = Cache::get($cacheKey);

            // Return the cached QR code image
            if (File::exists($filePath)) {
                return response()->file($filePath, ['Content-Type' => 'image/svg+xml']);
            }
        }

        // Generate the QR code as SVG
        $qrCode = QrCode::format('svg')->size(200)->generate($url);

        // Store the QR code image in the public directory
        $fileName = 'qr_code_' . Str::random(10) . '.svg';
        $filePath = public_path('qr_codes/' . $fileName);

        // Ensure the directory exists
        if (!File::exists(public_path('qr_codes'))) {
            File::makeDirectory(public_path('qr_codes'), 0755, true);
        }
        
        File::put($filePath, $qrCode);

        // Cache the file path for future requests
        Cache::put($cacheKey, $filePath, now()->addDays(30)); // Cache for 30 days

        // Return the QR code image
        return response()->file($filePath, ['Content-Type' => 'image/svg+xml']);
    }




    


}
