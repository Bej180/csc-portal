<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UploaderController extends Controller
{
    public static function uploadFile($name = 'image', $location = null) {
        $location ??= $name .'s';
        $upload_path = storage_path($location);
        if (!is_dir($upload_path)) {
            mkdir($upload_path);
        }

        $filename = null;

        if (request()->hasFile($name) && request()->file($name)->isValid()) {
            $uploadedImage = request()->file($name);
            $filename = Str::random(10) . '.' . $uploadedImage->getClientOriginalExtension();

             return request()->file($name)->store($location, 'public');
        
            // $uploadedImage->store($upload_path, $filename);
            
        } 
        elseif (request()->has($name)) {
                $base64Image = request()->input($name);
                $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
                $filename = Str::random(10) . '.jpg'; 
                file_put_contents($upload_path.DIRECTORY_SEPARATOR.$filename, $imageData);
        }
        if (!$filename) {
            return null;
        }
        return "$location/$filename";
    }
}
