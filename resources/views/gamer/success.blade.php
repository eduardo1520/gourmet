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
        <form method="get">
            <p>Acertei de novo!</p>
            <a href="{{route('home')}}">inicio</a>
        </form>
    </body>
</html>
