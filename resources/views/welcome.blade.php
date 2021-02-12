<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-grid.min.css') }}" rel="stylesheet">
    <style>
    #app{
        font-family: sans-serif,'Roboto',Montserrat;
        overflow-x:hidden;
    }
    .home-page{
        background-color:#0f4c81;
        width:100%;
    }
    .home-word{
        height:100vh;
        color:#deedfb;
    }
    .home-circle{
        margin:auto;
        border-radius:50%;
        height:60vh;
        width:60vh;
        background-color:#FFF;
    }
    </style>
    </head>
<body style="overflow-x: hidden">
    <div id="app">
        @include('inc.navbar')
        @yield('content')
<script src="https://kit.fontawesome.com/4e4ab773fc.js" crossorigin="anonymous"></script>
<script>
    var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
      alert(msg);
    }
</script>
</body>
</html>
