<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script>
            window.App = {!! json_encode([
                'signedIn' => auth()->check(),
                'user' => auth()->user()
            ]) !!}
        </script>

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <style>
            body {
                padding-bottom: 100px;
            }

            h1, h2, h3, h4, h5, h6 {
                margin: 0;
            }

            .level {
                align-items: center;
                display: flex;
            }

            .flex {
                flex: 1;
            }

            [v-cloak] {
                display: none;
            }
        </style>
    </head>
    <body>
        <div id="app">
            @include('layouts.nav')

            <main class="py-4">
                @yield('content')
            </main>

            <flash message="{{ session('flash') }}"></flash>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
