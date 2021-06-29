<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link href="{{mix('/css/app-admin.css')}}" rel="stylesheet">
        <script>
            window.Laravel = {"csrfToken":"{{ csrf_token() }}"}
        </script>
    </head>
    <body>
        <div id="app" @click="clickOutside">
            <!-- Navigation -->
          @include('admin.menu')
            
            <router-view></router-view>

        </div>
    </body>
    <script src="{{mix('js/app-admin.js')}}"></script>
</html>
