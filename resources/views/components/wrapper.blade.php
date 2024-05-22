@props(['navs', 'active'])
@php

$dashboard = ['/dashboard' => 'Dashboard'];
    $navs ??= $dashboard;

    if (!is_array($navs)) {
        $navs = $dashboard;
    }
   

@endphp
<div class="p-2.5 lg:p-5 flex flex-col">
  @if (isset($active)) 
    <x-navbar :navs="$navs" :active="$active"/>
  @endif
  {{$slot}}
</div>