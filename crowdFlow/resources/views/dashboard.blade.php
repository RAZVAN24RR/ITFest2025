<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | CrowdFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-8">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Dashboard</h1>
        <p class="text-gray-600">Bine ai venit, {{ Auth::user()->name }}!</p>
        <p class="mt-4 text-gray-700">
            Aceasta este zona de control a aplicației CrowdFlow, unde poți monitoriza în timp real nivelul de aglomerație din diverse locații.
        </p>
        <!-- Poți adăuga aici alte componente sau informații utile pentru utilizator -->
        <div class="mt-6">
            <a href="{{ route('logout') }}" class="text-red-500 hover:text-red-700">
                Logout
            </a>
        </div>
    </div>
</div>
</body>
</html>
