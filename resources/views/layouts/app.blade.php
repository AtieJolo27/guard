<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        <style>https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/css/bootstrap.min.css</style>
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/current_time.js','resources/js/scanner.js', 'resources/css/color.css'])

        @livewireStyles
    </head>
    <body>
        <nav>
            <livewire:partials.navigation-bar />
        </nav>
        {{ $slot }}

        @livewireScripts
        @livewireScriptConfig
    </body>
</html>
