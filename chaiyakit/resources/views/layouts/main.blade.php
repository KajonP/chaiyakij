<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ไชยกิจคอนกรีต</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/datatable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fontawesome/css/all.css') }}">
    
    

</head>

<body
    
    style="background-image: url('assets/image/bg01.jpg');display: grid;height: 100vh;background-position: bottom;background-size: cover;">
    @yield('content')

    <script src="{{ asset('assets/fontawesome/js/all.js') }}"></script>

</body>

</html>