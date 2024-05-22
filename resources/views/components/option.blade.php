@props(['value'])
@php
    if (!isset($value)) {
      $value = $slot;
    }
@endphp
<a class="dropdown-item" href="#" ng-class="{'selected': inSelection('{{$value}}')}" data-value="{{ $value }}" ng-click="selectOption($event)" {{$attributes}}>{{ $slot }}</a>