<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 扫码登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="/hAdmin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/hAdmin/css/font-awesome.css?v=4.4.0" rel="stylesheet">

    <link href="/hAdmin/css/animate.css" rel="stylesheet">
    <link href="/hAdmin/css/style.css?v=4.1.0" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">h</h1>

        </div>
        <h3>欢迎使用 hAdmin</h3>
        <p>
            <p>微信扫描下方二维码直接登录</p>
        </p>
            <img src="http://qr.liantu.com/api.php?text={{$url}}">
        <p><a href="">前往账号密码登录</a></p>

    </div>
</div>

<!-- 全局js -->
<script src="{{env('APP_URL')}}./hAdmin/js/jquery.min.js?v=2.1.4"></script>
<script src="{{env('APP_URL')}}./hAdmin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/jquery-3.3.1.js"></script>

</body>
<script>
    //定时器
    var t = setInterval('check()',2000);
    var log = "{{$log}}";
    //检测方法
    function check()
    {
        $.ajax({
            url:"{{url('/admin/checkscan')}}",
            data:{log:log},
            dataType:'json',
            success:function(res){
                if(res.code == 1){
                    clearInterval(t);
                    alert(res.msg);
                    location.href = "{{url('/admin')}}";
                }
            }
        })
    }
</script>