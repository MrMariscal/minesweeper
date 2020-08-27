<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <div class="row">
            <nav id="sidebar" class="col-md-3 d-md-block bg-secondary card px-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Minesweeper</h5>
                        Sidebar options.
                    </div>
                </div>
            </nav>
            <main class="col-md-9 d-md-block">
                @yield('content')
            </main>
        </div>
        <!-- JS -->
        @if(isset($js))
            @foreach($js as $script)
                <script src="{{ $script }}"></script>
            @endforeach
        @endif
    </div>
</body>