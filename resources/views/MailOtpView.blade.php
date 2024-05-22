@component('mail::message')
    
Sign Up {{ $name}}
@component('mail::button', ['url'=>'https://google.com'])
Click Me
    
@endcomponent
@endcomponent