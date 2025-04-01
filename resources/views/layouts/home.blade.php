<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-body-tertiary bg-white">
    <div class="wrapper d-flex" id="app">
        
        @if(!empty($showSidebar) && ($showSidebar ?? true)) 
            @include('partials.filter-sidebar')
        @endif

        <div class="main flex-grow-1">
            @if(!empty($showNavbar) && ($showNavbar ?? true)) 
                @include('partials.navbar')
            @endif
            <main class="content px-1 py-2">
                @include('components.tall-toasts')
                @include('partials.breadcrumbs')
                @yield('content')
            </main>
            @include('partials.footer')
        </div>
    </div>
    @stack('scripts')
</body>
</html>
