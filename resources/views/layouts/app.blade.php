<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        a {
        color: #0d6efd;
        text-decoration: inherit !important;
        }
        .form-control {
            background-color: #ffffff !important;
        }

        .pageLoader{
            background: url({{url('img/loader.gif')}}) no-repeat center center;
            background-size: 100px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            z-index: 9999999;
            background-color: #ffffff8c;

        }
    </style>

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

      <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="hold-transition sidebar-mini">
    <div  class="pageLoader" id="pageLoader"></div>
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.navbar')
        <!-- /.navbar -->
        
         <!-- Main Sidebar Container -->
         @include('layouts.sidebar')
         <div class="content-wrapper">
            @yield('content')
         </div>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Main Footer -->
        @include('layouts.footer')
    </div>

</body>
</html>
