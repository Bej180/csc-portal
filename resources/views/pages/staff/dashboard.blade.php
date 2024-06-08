@php
    $auth = auth()->user();
    $myAccount = $auth->staff;
    $courses = $myAccount->courses()
    ->with('course')
    ->get()
    ->map(function($course) {
        $technologist = $course->technologists();
        $course->technologist = null;
        if (count($technologist)) {
            $course->technologists = $technologist;
        }
        return $course;
    });
    $pendingLabScores = [];
    if (!$myAccount->is('technologist')) {
        $pendingLabScores = \App\Models\Result::whereNot('uploaded_by', $auth->id)
            ->where('status', 'incomplete')
            ->whereIn('course_id', $myAccount->courses->pluck('course_id'))->count();
    }
@endphp
<div ng-controller="StaffController">
    
    
    @if (auth()->user()->is('advisor'))
        @include('pages.staff.advisor-dasbhoard')
    @elseif (auth()->user()->staff->is_hod)
        @include('pages.staff.hod-dashboard')
    @else
        @include('pages.staff.staff-dashboard')
    @endif

    @include('pages.courses.view-course')
</div>
