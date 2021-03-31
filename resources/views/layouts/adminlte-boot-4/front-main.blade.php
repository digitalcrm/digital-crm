<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Front-Page')</title>

    @include('includes.partials.home-page-styles')

    @livewireStyles
</head>

<body>
    <div id="app">
        
        @include('includes.partials.nav-header')

        <main class="py-4">
            @yield('content')
        </main>

    </div>

    @include('includes.partials.home-page-scripts')
    @include('ticketing.message')
    @livewireScripts
</body>

</html>
