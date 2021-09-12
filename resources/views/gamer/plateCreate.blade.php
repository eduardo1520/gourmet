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
<form method="get" action="{{ route('plate.create') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="category" value="{{ $category['id'] }}">
    <p>{{ $titulo }}</p>
    <input name="name" type="text" />
    <button value="1" name="answer">{{ $btn }}</button>
</form>
</body>
</html>
