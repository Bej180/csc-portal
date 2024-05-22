    
@component('mail::message')
# Lab Scores submitted

Hi {{ $user->name }},
    
{{ $uploader }} has submitted lab scores for {{ $course_code }}. You are required to check and approve them

Thanks,<br>
{{ config('app.name') }}
@endcomponent