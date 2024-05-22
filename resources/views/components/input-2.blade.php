@props(['placeholder', 'value', 'id', 'type', 'name', 'keyup', 'attrs', 'size'])
@php 
$defaults = ['placeholder'=>'', 'value'=>'', 'id'=>'', 'type'=>'text', 'name'=>'','keyup'=>'', 'attrs'=>'', 'size'=>'sm'];
foreach($defaults as $key => $dvalue) {
  if (!isset($$key)) {
    $$key = $dvalue;
  }
}

@endphp
<fieldset class="flex flex-col relative input input-{{$size}}">
  <legend>
    {{$placeholder}}
  </legend>
  <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" placeholder="{{ $placeholder }}" value="{{ $value }}" x-on:keyup="{{$keyup}}">
</fieldset>