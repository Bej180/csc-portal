@props(['type', 'nav', 'title'])
@php 
$defaults = [
  'type' => '',
  'nav' => '',
  'title' => ''
  ];

  foreach($defaults as $default => $value) {
    if (!isset($$default)) {
      $$default = $value;
    }
  }
  $user = null;
  $role = 'guest';

  if (auth()->check()) {
    $user = auth()->user();
    $role = $user->role;
  }

@endphp

@if($type == 'one')
  <x-single-layout nav="{$nav}" title="{$title}">{{$slot}}</x-single-layout>
@elseif($role == 'guest')
  <x-guest-layout nav="{$nav}" title="{$title}">{{$slot}}</x-guest-layout>
@elseif($role == 'admin') 
  <x-admin-layout nav="{$nav}" title="{$title}">{{$slot}}</x-admin-layout>
@elseif($role == 'advisor') 
  <x-advisor-layout nav="{$nav}" title="{$title}">{{$slot}}</x-advisor-layout>
@elseif($role == 'student') 
  <x-student-layout nav="{$nav}" title="{$title}">{{$slot}}</x-student-layout>
@endif