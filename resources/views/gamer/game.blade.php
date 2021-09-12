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
    <form method="get" action="{{ route('plate.show',['id'=>$ct->id]) }}">

        <p>O prato que você pensou é {{ $ct->name }}?</p>
        <button value="1" name="answer">Sim</button>
        <button value="0" name="answer">Não</button>
    </form>
</body>
</html>
