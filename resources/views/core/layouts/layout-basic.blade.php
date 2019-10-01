<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name', 'Crud Builder') }}</title>
    {{--  <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>  --}}
    <script src="{{ asset('/assets/js/pace.js') }}"></script>
    <link href="{{ mix('/assets/css/app.css') }}" rel="stylesheet" type="text/css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('core.layouts.partials.favicons')
    @yield('styles')
</head>
<body class="layout-default skin-default">
    @include('core.layouts.partials.laraspace-notifs')

    <div id="app" class="site-wrapper">
        @include('core.layouts.partials.header')
        <div class="mobile-menu-overlay"></div>
        @include('core.layouts.partials.sidebar',['type' => 'default'])

        @yield('content')

        @include('core.layouts.partials.footer')
        @if(config('laraspace.skintools'))
            @include('core.layouts.partials.skintools')
        @endif
    </div>

    <script src="{{ mix('/assets/js/plugins.js')}}"></script>
    <script src="{{ mix('/assets/js/app.js')}}"></script>
    @yield('scripts')
</body>
</html>
