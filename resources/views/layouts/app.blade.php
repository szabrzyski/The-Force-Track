<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'The Force Track')</title>
    @vite(['resources/css/app.css'])
    @stack('css')
    @stack('head')
    @vite(['resources/js/app.js'])
    @stack('js')
</head>

<body>
    <main id="app">
    </main>
</body>

</html>
