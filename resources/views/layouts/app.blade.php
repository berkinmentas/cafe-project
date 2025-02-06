<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cafe Menu')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
@include('components.nav')

<main class="container mx-auto px-4 py-8 my-8">
    @yield('content')
</main>

@include('components.footer')
</body>
</html>
