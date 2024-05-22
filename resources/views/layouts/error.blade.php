<!doctype html>
  <html lang="en">

  <head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'An Error Occured')</title>
    <meta name="theme-color" content="#000000"/>
    <link rel="icon" type="image/svg+xml" href="{{asset('svg/logo.svg')}}" />
    @vite('resources/css/app.css')
    
    @vite('resources/js/app.js')
    
    <link rel="stylesheet" href="{{asset('styles/normalize.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20,400,0,0" />
    
  </head>

  <body>

    @yield('content')

    
  </body>
</html>
