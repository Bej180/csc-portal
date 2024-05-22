@props(['label', 'direction', 'relative'])
@php 
$relative_attr = '';
if (isset($relative)) {
  $relative_attr = "data-tooltip-relative='$relative'";
}
@endphp

<span class="ui-tooltip" {{$attributes}}>
  {{$slot}}
  <span class="ui-tooltip-label dir-{{isset($direction)?$direction:'bottom'}}" {!! $relative_attr !!} {{$attributes}}>{{$label}}</span>
</span>