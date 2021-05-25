<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}" />
        <script src="dist/js/v-minusplusfield.js" type="text/javascript"></script>
        <link href="dist/css/v-minusplusfield.css" rel="stylesheet" />
        <script src="{{ asset('/js/my_script.js') }}"></script> 
        @stack('scripts')
    </head>
    <body>
        <div id="app">
        @include('layout.header')
        
            @yield('content')
        


        @include('layout.footer')
        </div>

    <script src="{{ mix('/js/app.js') }}"></script>
        <!-- Jquery -->
    <script src="/eshop_js/jquery.min.js"></script>
    <script src="/eshop_js/jquery-migrate-3.0.0.js"></script>
    <script src="/eshop_js/jquery-ui.min.js"></script>
    <!-- Popper JS -->
    <script src="/eshop_js/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="/eshop_js/bootstrap.min.js"></script>
    <!-- Color JS -->
    <script src="/eshop_js/colors.js"></script>
    <!-- Slicknav JS -->
    <script src="/eshop_js/slicknav.min.js"></script>
    <!-- Owl Carousel JS -->
    <script src="/eshop_js/owl-carousel.js"></script>
    <!-- Magnific Popup JS -->
    <script src="/eshop_js/magnific-popup.js"></script>
    <!-- Waypoints JS -->
    <script src="/eshop_js/waypoints.min.js"></script>
    <!-- Countdown JS -->
    <script src="/eshop_js/finalcountdown.min.js"></script>
    <!-- Nice Select JS -->
    <script src="/eshop_js/nicesellect.js"></script>
    <!-- Flex Slider JS -->
    <script src="/eshop_js/flex-slider.js"></script>
    <!-- ScrollUp JS -->
    <script src="/eshop_js/scrollup.js"></script>
    <!-- Onepage Nav JS -->
    <script src="/eshop_js/onepage-nav.min.js"></script>
    <!-- Easing JS -->
    <script src="/eshop_js/easing.js"></script>
    <!-- Active JS -->
    <script src="/eshop_js/active.js"></script>
    </body>
</html>
