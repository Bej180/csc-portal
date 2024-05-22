@component('mail::message')
# Congratulations!.

Congratulations, {{ $user->name }}!

{{ $course_code }} results you uploaded on {{ $date }} has been approved



<p>Sincerely,</p>

The {{ config('app.name') }} Team
@endcomponent