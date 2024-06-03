@props(['title','nav','style','module','minimize', 'bg', 'controller'])

@php
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Arr;

    $htmlClass = Cookie::get('darkMode') === 'true' ? 'dark' : '';

    $defaults = [
        'title' => config('app.title', 'Futo CSC Portal'), 
        'nav' => '',
        'style' => false,
        'module' => $module ?? $module ?? $nav ?? '', 
        'bg' => 'bg-color',
    ];
    foreach ($defaults as $default => $value) {
        if (!isset($$default)) {
            $$default = $value;
        }
    }
    $script = null;
    if (isset($controller)) {
        $controller = preg_replace('/Controller$/', '', $controller);
        $controller = strtolower(preg_replace('/([a-z_])([A-Z]+)/', '$1-$2', $controller));
        $script = asset('/js/modules/'.$controller.'.js');
    }
    

    
    

    if (!isset($module)) {
        $module = $nav;
    }

    $role = 'guest';

    if (auth()->check()) {
        $role = auth()->user()->role;
    }
    $active_nav = $nav;
    if (isset($active)) {
        $active_nav = $active;
    }

    if (!isset($minimize)) {
        $minimize = false;
    }

@endphp
<x-app>
<head>
    @include('layouts.head', compact('title', 'style'))

</head>


<body class="page-{{ $role }} select-none {{$bg}}">
    

    <x-overlay />




    <div class="lg:flex items-stretch h-dvh relative">

        @include('layouts.aside', compact('nav', 'role', 'minimize'))

        <div class="lg:flex flex-1 flex-col h-full">
            @include('layouts.header')
            <main id="main-slot" {{$attributes}}>
                

                {{ $slot }}

            </main>
        </div>
    </div>
    
    @include('layouts.footer', compact('script'))
</body>

</x-app>
