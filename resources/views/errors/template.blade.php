<?php
use Illuminate\Support\Arr;
$code = app()->isDownForMaintenance() ? 'maintenance' : $exception->getStatusCode();
$error = error_page();


$link = Arr::get($error, "$code.button.link_to") === 'home' ? url('/') : (Arr::get($error, "$code.button.link_to") === 'reload' ? url()->current() : url()->previous());
$mapLink = array_map(function($button){
  $name = Arr::get($button, 'name');
  $material = Arr::get($button, 'material');
  $link_to = Arr::get($button, 'link_to');
 
  
  return match(true) {
    Arr::get($button, "link_to") === 'dashboard' => route('home'),
    Arr::get($button, "link_to") === 'refresh' => url()->current(),
    Arr::get($button, "link_to") === 'previous' => url()->previous(),
    default => null,
  };

}, Arr::get($error, "$code.buttons")); 

$links = array_combine($mapLink, Arr::get($error, "$code.buttons"));
$title = Arr::get($error, "$code.title");



$description = trans(Arr::get($error, "$code.description"), ['domain'=> url(request()->getRequestUri())]);

$message =  config('app.env') === 'production' ? $description : $exception->getMessage();
if (!$message || strlen($message) === 0) {
  $message =  $description;
}
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    @include('layouts.head', compact('description', 'title'))

</head>

<body>

  <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
      <div class="flex flex-col items-center gap-3 text-gray-500">
        
        <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
            <div class="px-4 text-lg border-r border-gray-400 tracking-wider">
                {{$code}}                    </div>

            <div class="ml-4 text-lg  uppercase tracking-wider">
                {{$title}}                    </div>
        </div>
        <div>
          <img src="{{asset('svg/no_courses.svg')}}" class="w-40" alt="">
        </div>
        <div class="tracking-wider text-center">
          {!! trans($message, ['domain'=> url(request()->getRequestUri())]) !!}
        </div>
        <div class="flex gap-3">
        @foreach($links as $link => $data) 
        @if($link !== url(request()->getRequestUri()) && Arr::get($data, 'link_to') !== 'refresh')
          <a class="text-center inline-flex items-center" href="{!! $link !!}">
            <span class="material-symbols-rounded">{{ Arr::get($data, "material") }}</span> <span>{{ Arr::get($data, "name") }}</span>
          </a>
        @endif
        @endforeach
        </div>
       
      </div>
    </div>

</body>

</html>
