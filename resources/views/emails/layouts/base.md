
<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('subject')</title>
    <style>
        /* Your email styling here */
    </style>
</head>
<body>
    <header>
        <h1>{{ config('app.name') }}</h1>
    </header>
    <main>
        @yield('content')
    </main>
    <footer>
        <p>Copyright © {{ date('Y') }} {{ config('app.name') }}</p>
    </footer>
</body>
</html>
