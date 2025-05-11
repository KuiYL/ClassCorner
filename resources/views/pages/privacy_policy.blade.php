<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Условия обслуживания</title>
    <link rel="stylesheet" href="{{ 'css/style.css' }}">
    <link rel="stylesheet" href="{{ 'css/layout.css' }}">
    <link rel="stylesheet" href="{{ 'css/components.css' }}">
    <link rel="stylesheet" href="{{ 'css/adaptation.css' }}">

    <script src="{{ 'js/script.js' }}" defer></script>
    <meta name="close-icon" content="{{ asset('images/faq-close.svg') }}">
    <meta name="open-icon" content="{{ asset('images/faq-open.svg') }}">
</head>

<body>
    @include('layout.header')

    

    @include('layout.footer')
</body>

</html>
