<!--/* Copyright © 2020 Chien-Yu Lin. All rights reserved. @ chienyld.github.io */-->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" translate="no">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-grid.min.css') }}" rel="stylesheet">
    @laravelPWA
</head>
<body style="overflow-x: hidden" translate="no">
    <div id="app" style="font-family: sans-serif,'Roboto',Montserrat;overflow-x:hidden;min-height:70vh">
        @include('inc.navbar')
        <div class="container" style="overflow-x: hidden;margin-top:22px">
            @include('inc.messages')
            @yield('content')
        </div>
    </div>
    <br>
    @include('footer')

    <!--     <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script> -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://kit.fontawesome.com/4e4ab773fc.js" crossorigin="anonymous"></script>
    <script>
    var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
      alert(msg);
    }
    </script>
    


    <!-- <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
    <script>
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } );
</script>
    <script>
        CKEDITOR.replace( 'article-ckeditor' );
    </script>-->
    <script>
    $(document).ready(function () {

    $('.first-button').on('click', function () {

    $('.animated-icon1').toggleClass('open');
    });
    $('.second-button').on('click', function () {

    $('.animated-icon2').toggleClass('open');
    });
    $('.third-button').on('click', function () {

    $('.animated-icon3').toggleClass('open');
    });
    });
    </script>

</body>
</html>
