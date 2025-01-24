<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- TailwindCSS for animations (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
</head>
<body class="font-sans text-gray-900 antialiased h-screen bg-gradient-to-r from-green-500 to-indigo-600">

    <!-- Full screen container -->
    <div class="w-full h-full flex items-center justify-center relative">

        <!-- Top left image -->
        <div class="absolute top-5 left-5">
            <img src="logo_ugr.png" alt="Left Image" class="w-16 h-16 object-contain">
        </div>

        <!-- Top center image -->
        <div class="absolute top-5 left-1/2 transform -translate-x-1/2">
            <img src="departamento.png" alt="Center Image" class="w-35 h-35 object-contain">
        </div>

        <!-- Top right image -->
        <div class="absolute top-5 right-5">
            <img src="leon2.png" alt="Right Image" class="w-16 h-16 object-contain">
        </div>

        <!-- Bottom images -->
        <div class="absolute bottom-10  flex space-x-4">
            <img src="cciaugr.png" alt="Bottom Left Image" class="w-25 h-25 object-contain">
        </div>

        <!-- Main Content (Slot for login or other views) -->
        <div class="w-full max-w-lg p-8 bg-white bg-opacity-90 rounded-lg shadow-xl transform transition-all duration-500 ease-in-out hover:scale-105">
            {{ $slot }}
        </div>

    </div>

</body>
</html>
