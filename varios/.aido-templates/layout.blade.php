<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AIDO Project')</title>
    
    <!-- Recursos locales vía Vite (sin CDN externos) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Estilos adicionales por página -->
    @stack('styles')
</head>
<body>
    
    <!-- Header/Navigation -->
    @include('layouts.partials.header')
    
    <!-- Contenido principal -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('layouts.partials.footer')
    
    <!-- Scripts adicionales por página -->
    @stack('scripts')
    
</body>
</html>
