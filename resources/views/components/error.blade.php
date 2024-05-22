@props(['message', 'class'])

@if (!empty($message))
    <div class="no-courses flex flex-col items-center justify-center min-w-full transition {{isset($class) ? $class : ''}}">
        <div>
            <img src="{{asset('svg/no_courses.svg')}}" alt="">
        </div>
        <p class="text-center text-white-800 weight-400 font-size-5">
            {{$message}}
        </p>
    </div>
@endif