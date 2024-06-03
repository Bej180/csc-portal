@props(['name'])

@php
$route = $name ?? 'index';
@endphp

<div {{$route !== 'index'?'ng-cloak':''}} ng-if="is_active_route('{{ $route}}')" {{$attributes}}>
  {{ $slot }}
</div>