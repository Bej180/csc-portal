<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;


class UploaderController extends Controller
{
    public static function ExcelToArray (string $name, array $filter, array $append = [], int $gap = 1) {
    
        if (!Arr::isAssoc($filter)) {
            $filter = array_combine($filter, $filter);
        }
        try {
            $request   =    request();
            $file      =    $request->file($name);
           
    
            // Convert file to Array
            $data = Excel::toArray([], $file);
    
            // clean up the column data, by removing non-alphanumeric and underscore characters
            // and converting the column to lower case
            $cleanValue = fn ($value) => strtolower(preg_replace('/[^a-zA-Z0-9_]+/', '', $value));
    
            $foundRow = false;
            $retrieveColumns = [];
    
    
    
            foreach ($data[0] as $rowNumber => $row) {
    
                foreach ($row as $col) {
                   
    
                    if (array_key_exists($cleanValue($col), $filter)) {
                        // if found, that means the row is the header,
                        // which contains other names, like results, remarks and others
    
                        foreach ($row as $n => $column) {
                            $cleanColumn = $cleanValue($column);
                            if (!array_key_exists($cleanColumn, $filter)) {
                                continue;
                            }
    
                            $retrieveColumns[$n] = $filter[$cleanColumn];
                        }
                        $foundRow = $rowNumber;
                        break;
                    }
                }
            }
    
    
            if ($foundRow === false) {
                return null;
            }

            
            $results = array_splice($data[0], $foundRow + $gap);

            $newResult = [];
            foreach ($results as $result) {
                $appendage = $append;
                
               

                foreach ($retrieveColumns as $index => $retrieved) {
                   $appendage[$retrieved] = $result[$index];
                }
                $newResult[] = $appendage;
            }

            return $newResult;
        } 
        catch(\Exception $e) {
            return null;
        }
    }



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
