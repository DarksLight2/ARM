<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>{{ $title ?? 'AI Monitor' }}</title>
{{--    @vite(['resources/css/ai-monitor.css', 'resources/js/app.js'])--}}
    <link rel="stylesheet" href="{{ asset('vendor/ai-monitor/assets/ai-monitor.css') }}">
</head>
<body class="min-h-screen relative bg-[#0b1020] [background-image:radial-gradient(1200px_600px_at_20%_-10%,rgba(93,135,255,0.28),transparent_60%),radial-gradient(900px_520px_at_85%_0%,rgba(110,255,188,0.18),transparent_55%),radial-gradient(900px_720px_at_50%_120%,rgba(255,152,80,0.14),transparent_60%)]">
<div class="container mx-auto">
    @yield('content')
</div>
</body>
</html>
