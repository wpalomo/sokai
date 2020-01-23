<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link rel="icon" href="<%= BASE_URL %>favicon.ico"> -->

    <title>SOKAI | Sistema de facturación en linea</title>
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset(mix('css/main.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/iconfont.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/material-icons/material-icons.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/vuesax.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/prism-tomorrow.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}">
    <!--<link rel="stylesheet" href="css/estilos.css">-->
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/logo/favicon-3.png') }}">
  </head>
  <body> 
    <noscript>
      <strong>We're sorry but Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    <div id="app">
      
    </div>

    <!-- <script src="js/app.js"></script> --> 
    <script src="{{ asset(mix('js/app.js')) }}"></script>
    <script src="http://<?=$_SERVER["HTTP_HOST"];?>/js/forge.min.js"></script>
    <script src="http://<?=$_SERVER["HTTP_HOST"];?>/js/moment.min.js"></script>
    <script src="http://<?=$_SERVER["HTTP_HOST"];?>/js/buffer.js"></script>
  </body>
</html>
