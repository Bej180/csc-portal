<?php 

namespace App\Imports;

use App\Models\Result;
use Maatwebsite\Excel\Concerns\ToModel;

class ResultsImport implements ToModel 
{

  public function model(array $row) {
    return new Result([
      'reg_no' => $row[0],
      'course_code' => $row[1],
      'grade'  => $row[2]
    ]);
  }
}