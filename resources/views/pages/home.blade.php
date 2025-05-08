<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Главная</title>
    <link rel="stylesheet" href="{{ 'css/style.css' }}">
    <link rel="stylesheet" href="{{ 'css/layout.css' }}">

    <script src="{{ 'js/script.js' }}" defer></script>
</head>

<body>
    @include('layout.header')

    @include('layout.footer')
</body>

</html>
