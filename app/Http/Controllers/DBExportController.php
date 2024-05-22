<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; // Import DB facade

class DBExportController extends Controller
{
    public function exportToJson()
    {
        // Get all table names in the database
        $tables = DB::select('SHOW TABLES');
        $exporttable = request()->get('table');

        // Extract table names from the result
        $tableNames = [];
        foreach ($tables as $table) {
            $values = array_values((array)$table);
            $tableName = $values[0];
            if ($exporttable && $exporttable !== $tableName) {
                continue;
            }
            $tableNames[] = $tableName;

            // Retrieve data from the database using Eloquent
            $data = DB::table($tableName)->get();

            // Convert data to JSON format
            $jsonData = $data->toJson(JSON_PRETTY_PRINT);

            // Export JSON data to a file
            $filePath = public_path('db_'.$tableName.'.json');
            file_put_contents($filePath, $jsonData);
            if ($exporttable === $tableName) {
                break;
            }
        }

        // Return the list of table names
        return $tableNames;
    }
}
