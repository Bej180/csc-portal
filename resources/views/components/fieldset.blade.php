@props(['title'])
<fieldset class="border-slate-500/50 border p-4 rounded-md my-4">

  @if(isset($title))
    <legend class="font-bold flex items-center">
      {!! $title !!}
    </legend>
  @endif

  {{$slot}}

</fieldset>