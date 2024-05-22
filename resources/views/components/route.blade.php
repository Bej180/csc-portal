@props(['name'])

<div ng-if="is_active_route('{{ $name ?? 'index'}}')" {{$attributes}}>
  {{ $slot }}
</div>