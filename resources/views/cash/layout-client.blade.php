<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @livewireStyles

    <!-- Scripts -->
    <script src="{{ asset('js/alpine.js') }}" defer></script>
    <script src="{{ asset('js/socket.io.min.js') }}"></script>
</head>
<body class="font-sans antialiased">
{{--<div class="min-h-screen bg-gradient-to-b from-blue-900 via-indigo-900 to-purple-900">--}}
<div class="min-h-screen bg-blue-900">
{{--@livewire('navigation-dropdown')--}}

<!-- Page Heading -->
    <header class="max-w-7xl mx-auto">
        <nav class="py-4 px-3 sm:px-6 lg:px-8 flex items-center justify-between flex-wrap">
            <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
                <div class="text-sm lg:flex-grow">
                    {{ $header }}
                </div>
                {{--@livewire('cash.cart-mini')--}}
            </div>
        </nav>
    </header>

    <!-- Page Content -->
    <main>
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </div>
    </main>
</div>
@include('cash.components.flash')
@include('cash.components.client-sounds')
@stack('modals')

@livewireScripts
<script src="{{ asset('js/client.js') }}"></script>
</body>
</html>
