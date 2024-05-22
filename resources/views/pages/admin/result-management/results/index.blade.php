@php 
use App\Models\AcademicRecord;
$results = \App\Models\Result::groupBy('reg_no')->with(['course', 'student'])->get();

foreach($results as $result) {
  //dd($result);
}

@endphp
<style>
  table, th, td {border:1px solid #000}
  </style>
<x-app>
  <table>
    <thead> 
      <tr>    
        <th>Name</th>
        <th>Reg No</th>
        <th>Course</th>
        <th>Score</th>
        <th>Grade</th>
        <th>Remark</th>
      </tr>
    </thead>
    <tbody>
      @foreach($results as $result)
     
      <tr>
        <th align="left">{{$result->student->user->name}}</th>
        <th>{{$result->reg_no}}</th>
        <th>{{$result->course->code}}</th>
        <th>{{$result->score}}</th>
        <th>{{AcademicRecord::scoreToGrade($result->score)}}</th>
        <th>{{$result->score < 40 ? 'F' : 'Pass'}}</th>
      </tr>
      @endforeach
    </tbody>
  </table>
</x-app>