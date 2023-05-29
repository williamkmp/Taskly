<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Taskly</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @stack("head")
    @stack("component")
</head>
<body>
    <x-toast-manager></x-toast-manager>
    @yield('body')
    @stack('page')
    @include("components.notification-script")
</body>
</html>
