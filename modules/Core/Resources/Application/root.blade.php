<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ ucfirst(config('app.name')) }}</title>

    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="{{ asset('apple-touch-icon.png') }}" rel="apple-touch-icon">
    <link href="{{ asset('favicon-16x16.png') }}" rel="icon" sizes="16x16" type="image/png">
    <link href="{{ asset('favicon-32x32.png') }}" rel="icon" sizes="32x32" type="image/png">
    <link href="{{ asset('android-chrome-192x192.png') }}" rel="icon" sizes="192x192" type="image/png">
    <link href="{{ asset('android-chrome-512x512.png') }}" rel="icon" sizes="512x512" type="image/png">
    <link href="{{ asset('favicon.ico') }}" rel="icon">
    <link href="{{ asset('site.webmanifest') }}" rel="manifest">

    @vite
</head>
<body class="font-sans antialiased h-full isolate">
@hybridly
</body>
</html>
