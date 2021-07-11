<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="admin-id" content="{{ Auth::check() ? Auth::user()->id : '' }}">
        <!-- Fonts -->
        <link href="{{mix('/css/app-admin.css')}}" rel="stylesheet">
        <script src="{{asset('js/push.min.js')}}"> </script>
        <script>
            window.Laravel = {"csrfToken":"{{ csrf_token() }}"}
            window.LaravelDemo = {!! 
                json_encode([
                    'csrf' => csrf_token(),
                    'user' => Auth::check() ? Auth::user()->id : ''
                ])
             !!}//(1)
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
<!-- Note
//(1) for learning only
When using in it js file: Ex: Echo.private(`App.Models.User.${LaravelDemo.user}`)
