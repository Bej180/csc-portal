@props(['nav', 'title'])
@php 
  $defaults = [
    'nav' => '',
    'title' => 'CSC Portal',
    ];
  
    foreach($defaults as $default =>$value) {
      if (!isset($$default)) {
        $$default = $value;
      }
    }

@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title }}</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @include('partials.head')
        @stack('head')
    </head>
    <body>

      <main class="lg:flex">
        <nav>
        @include('partials.new.menu', [
          'nav' => $nav
        ])
        </nav>
        <aside class="flex-1 max-h-[100%] min-h-[100%]">
          <div class="h-full overflow-auto">
            {{ $slot }}
          </div>
        </aside>
      </main>
    </body>
    @stack('scripts');
</html>