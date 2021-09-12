<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Document</title>
    </head>
    <body>
    <form method="get" action="{{ route('home') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <p>
            Pense em um prato que gosta?
        </p>
        <a href="{{route('start')}}">Pensei</a>
    </form>
    </body>
</html>


