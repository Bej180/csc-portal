
@php
$role = 'guest';
if (!isset($nav)) {$nav = '';}
if (!isset($style)) { $style = '';}
if (!isset($module)) {$module = 'all';}


if (auth()->check()) {
  $role = auth()->user()->role;
}
$styles = [
    "styles/modules/$role.css",
    "styles/modules/$role-$nav.css",
    "styles/modules/$module.css",
    "styles/modules/$role-$module.css",
    "styles/modules/$nav.css",
    "styles/modules/$nav-$module.css",
];
  $styles = array_unique($styles);
@endphp
<meta charset="UTF-8" />
<title>{!!$title??'Futo CSC Portal'!!}</title>
<meta name="theme-color" content="#000000"/>
@if (isset($description)) 
  <meta name="description" content="{!!$description!!}"/>
@endif
<link rel="icon" type="image/svg+xml" href="{{asset('svg/logo.svg')}}" />
{{-- <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('plugins/feather/feather.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/icons/flags/flags.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fontawesome/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/twitter-bootstrap-wizard/form-wizard.css') }}">
<link rel="stylesheet" href="{{asset('styles/normalize.css')}}">
<link rel="stylesheet" href="{{asset('styles/chosen.css')}}">

<link rel="stylesheet" href="{{asset('styles/base.css')}}">
@vite('resources/css/app.css')

<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.jsxxx"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20,400,0,0" />

<link rel="stylesheet" href="{{asset('styles/student/student.css')}}"/>
@foreach($styles as $_style) 
    @if(file_exists(public_path($_style)))
      <link rel="stylesheet" href="{{asset($_style)}}"/>
    @endif
  @endforeach


{{-- <script src="{{asset('scripts/api.js')}}"></script> --}}
<meta name="csrf_token" content="{{csrf_token()}}"/>
<script src="https://cdn.jsdelivr.net/npm/pjax@VERSION/pjax.min.js"></script>  

@if(file_exists(public_path($style)))
  <link href="{{ asset($style) }}" rel="stylesheet"/>
@endif


@if($style) 
<link rel="stylesheet" href="{{ asset('styles/modules/'.$style.'.css') }}">
@endif