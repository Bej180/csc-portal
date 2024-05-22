<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('subject')</title>
    @include('layouts.head')
</head>

<body style="padding-top: 1.25rem;padding-bottom: 1.25rem;background-color: rgb(250,250,250)">
    <div class="container">
        <header class="text-center flex flex-col items-center">
          <img src="{{asset('svg/logo.svg')}}" alt=""/>
        </header>
        <main class="bg-white p-8 m-5 lg:m-8 border border-zinc-300 rounded-lg">
            @yield('content')

            <a class="btn btn-primary inline-block">Submit</a>
        </main>
        <footer class="text-center text-zinc-400">
            <p>Copyright &copy; {{ date('Y') }} {{ config('app.name') }}</p>
        </footer>
        
    </div>
</body>
</html>