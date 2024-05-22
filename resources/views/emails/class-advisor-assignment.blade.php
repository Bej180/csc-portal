@component('mail::message')
# Congratulations! You have been assigned as a class advisor.

Congratulations, {{ $user->name }}!

You have been assigned as the class advisor for {{ $class->name }}.

This means you will be responsible for providing guidance and support to the students enrolled in this class.

Please visit the class page for more details and to access resources:

<a href="{{ url('/classes/' . $class->id) }}">View Class</a>


<p>Sincerely,</p>

The {{ config('app.name') }} Team
@endcomponent