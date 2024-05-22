@props(['offset'])
@php
    $style = '';
    if (isset($offset)) {
      $style = "height:calc(-$offset + 100dvh);";
    }
@endphp
<div class="relative -right-2 group">
    <div class="scrollable" style="{{$style}}">

        {{ $slot }}
    </div>

    <div class="scrollable-track">
        <div class="scrollable-thumb"></div>
    </div>
</div>
