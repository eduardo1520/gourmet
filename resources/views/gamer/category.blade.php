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
    <form method="post" action="{{ route('category.store') }}">
        <p>{{ $titulo }}</p>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input name="category" type="hidden" value="{{$category->id}}">
        <button value="1" name="answer">Sim</button>
        <button value="0" name="answer">Não</button>
    </form>
</body>
</html>