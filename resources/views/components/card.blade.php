@props(['class'])
<div class="p-5 shadow-md bg-white dark:bg-zinc-950 dark:text-white flex rounded {{isset($class) ? $class : ''}}">
  {{$slot}}
</div>