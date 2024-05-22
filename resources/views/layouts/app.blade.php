@php

    $htmlClass = Cookie::get('darkMode') === 'true' ? 'dark' : '';
    $role = 'guest';
    $style = '';

    if (!isset($nav)) {
        $nav = '';
    }
    if (!isset($module)) {
        $module = $nav;
    }
    if (auth()->check()) {
        $role = auth()->user()->role;
    }

    $active_nav = $nav;
    if (isset($active)) {
        $active_nav = $active;
    }

    $styles = [
        "styles/modules/$role.css",
        "styles/modules/$role-$nav.css",
        "styles/modules/$module.css",
        "styles/modules/$role-$module.css",
        "styles/modules/$nav.css",
        "styles/modules/$nav-$module.css",
    ];
    $styles = array_unique($styles);

    $filteredStyles = array_filter($styles, fn($style) => file_exists(public_path($style)));
@endphp


<!DOCTYPE html>
<html lang="en" ng-app="cscPortal" ng-controller="RootController" ng-class="{'dark': darkMode}"
    ng-resize="handleResize()" class="{{ $htmlClass }}" ng-init="init()" custom-on-change>

  <head>

      <meta charset="UTF-8" />
      <title>@yield('title', 'Futo CSC Portal')</title>
      <meta name="theme-color" content="@yield('themeColor', '#000000')" />

      <meta name="description" content="@yield('description', '')" />
      <link rel="icon" type="image/svg+xml" href="{{ asset('svg/logo.svg') }}" />
      <link rel="stylesheet" href="{{ asset('styles/normalize.css') }}">
      @vite(['resources/css/app.css', 'resources/js/app.js'])

      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta name="csrf_token" content="{{ csrf_token() }}" />
      <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20,400,0,0" />
      <link rel="stylesheet" href="{{ asset('styles/student/student.css') }}" />

      @foreach ($filteredStyles as $style)
          <link rel="stylesheet" href="{{ asset($style) }}" />
      @endforeach


      <script src="{{ asset('scripts/api.js') }}"></script>
      @stack('head')


  </head>

  <body class="page-{{ $role }} select-none">

      <x-overlay />

      <div class="lg:flex items-stretch h-screen relative">

          @include('layouts.aside', compact('nav', 'role'))

          <div class="lg:flex flex-1 flex-col h-full">
              @include('layouts.header')
              <main id="main-slot">

                  @yield('content')

              </main>
          </div>
      </div>

      <span id="footer-slot">
          @include('layouts.script-layer')
          @stack('scripts')
      </span>
  </body>

</html>