@props(['type', 'class', 'style'])

@if(isset($slot) && strlen($slot) > 0)
  @php 
    $defaults = ['class'=>'', 'style'=>'', 'id'=>''];

    foreach($defaults as $default=>$value) {
      if(!isset($$default)) {
        $$default = $value;
      }
    }
    
    $color = match($type) {
      'danger', 'error' => '!bg-red-200 !text-red-600',
      'warning' => '!bg-yellow-200 !text-yellow-600',
      'info' => '!bg-blue-200 !text-blue-600',
      'success' => '!bg-green-200 !text-green-600',
      default => '!bg-red-200 !text-red-600',
    };
  @endphp
  {{-- <div class="text-center !text-sm px-2 py-1 rounded-md {{$class}} {{$color}}" id="{{$id}}" style="{{$style}}">{{$slot}}</div> --}}
@endif