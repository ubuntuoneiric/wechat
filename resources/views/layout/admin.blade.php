<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title> hAdmin- @yield('title')</title>
    <link href="/hAdmin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <script src="/hAdmin/js/jquery.min.js"></script>
    <script src="/hAdmin/js/bootstrap.min.js"></script>
</head>
<body style="margin-top: 5%">

<!--继承start-->
    <div class='container'>
    @section('content')
    @show
    </div>
<!--继承end-->

</body>
</html>