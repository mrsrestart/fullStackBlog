<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/css/all.css">
        <script>
            (function (){
                window.Laravel = {
                    csrfToken : '{{ csrf_token() }}'
                }
            })();
        </script>
    </head>
    <body class="antialiased">
        <div id="app">
            @if(\Illuminate\Support\Facades\Auth::check())
                <mainapp :user="{{\Illuminate\Support\Facades\Auth::user()}}" :permissions="{{\Illuminate\Support\Facades\Auth::user()->role->permission}}"></mainapp>
            @else
                <mainapp :user="false"></mainapp>
            @endif
        </div>
    </body>

    <script src="{{mix('/js/app.js')}}"></script>
</html>
