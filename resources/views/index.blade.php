<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        @if(!Auth::guest())
            <div id="app">
                <app></app>
            </div>
            <script>
              window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
              ]) !!};
            </script>
            @if(Auth::user()->isAdmin())
                <script src="{{ mix('/js/admin.js') }}"></script>
            @else
                <script src="{{ mix('/js/client.js') }}"></script>
            @endif
        @else
            @include('auth.login')
        @endif
    </body>
</html>
