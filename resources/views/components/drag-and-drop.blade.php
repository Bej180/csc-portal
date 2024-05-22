@props(['name', 'src'])
@php 

if (!isset($src)) {
  $src = null;
}
if ($src) {
  //dd($src);
} 


@endphp
<div class="flex w-full justify-center drag-and-drop">
  <div class="justify-self-center">
    <x-tooltip label="Choose or Drag Image here">
      <input type="file" id="fileInput" accepts="image/*" style="display: none;">
      <div id="dropZone" class="group drop-zone flex flex-col items-center rounded-full  justify-center">
          <img src="{% image %}"  {{$attributes}} class="w-full h-full object-cover absolute top-0 right-0" id="img-preview"/>
          <p class="text-sm absolute p-1 bg-white hidden group-hover:block opacity-0 group-hover:opacity-50 transform">Drag & Drop image here</p>
      </div>
    </x-tooltip>
  </div>
</div>