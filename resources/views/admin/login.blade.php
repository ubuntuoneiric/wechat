<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="/hAdmin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/hAdmin/css/font-awesome.css?v=4.4.0" rel="stylesheet">

    <link href="/hAdmin/css/animate.css" rel="stylesheet">
    <link href="/hAdmin/css/style.css?v=4.1.0" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">h</h1>

        </div>
        <h3>欢迎使用 hAdmin</h3>

        <form class="m-t" role="form" action="{{url('/admin/dologin')}}" method="post">
            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="用户名" required="" id="name">
            </div>
            <div class="form-group">
                <input type="password" name="pwd" class="form-control" placeholder="密码" required="" id="pwd">
            </div>
            <div class="form-group">
                <input type="text" name="code" class="form-control" style="width:50%;float:left" placeholder="微信验证码" id="code">
                <input type="button" class="btn btn-info" value="获取验证码" id="btn" onclick="sendemail()">
            </div>

            <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>


            {{--<p class="text-muted text-center"> <a href="login.html#"><small>忘记密码了？</small></a> | <a href="register.html">注册一个新账号</a>--}}
            {{--</p>--}}
            <p><a href="{{url('/admin/scanlogin')}}">点击前往微信扫码登录</a></p>
            <img src="/0.jpg" alt="" width="150">
            <p>微信账号绑定,请扫上方二维码</p>
        </form>
    </div>
</div>

<!-- 全局js -->
<script src="{{env('APP_URL')}}./hAdmin/js/jquery.min.js?v=2.1.4"></script>
<script src="{{env('APP_URL')}}./hAdmin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/jquery-3.3.1.js"></script>

</body>

<script>
    var countdown=60;
    var obj = $("#btn");
    //验证码的点击事件
    $("#btn").on('click',function(){
        var name = $("#name").val();
        var pwd = $("#pwd").val();

        $.ajax({
            url:"{{url('/admin/createCode')}}",
            type:"post",
            data:{name:name,pwd:pwd},
            dataType:'json',
            success:function(res){
                alert(res.font);
                if(res.code == 1){
                    settime(obj);
                }
            }
        })
    })
    /*60秒*/
    function settime(obj) { //发送验证码倒计时
        if (countdown == 0) {
            obj.attr('disabled',false);
            //obj.removeattr("disabled");
            obj.val("获取验证码");
            countdown = 60;
            return;
        } else {
            obj.attr('disabled',true);
            obj.val("重新发送(" + countdown + ")");
            countdown--;
        }
        setTimeout(function() {
                settime(obj) }
            ,1000)
    }
</script>
<script>
    /*
     * 注意：
     * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
     * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
     * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
     *
     * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
     * 邮箱地址：weixin-open@qq.com
     * 邮件主题：【微信JS-SDK反馈】具体问题
     * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
     */
    wx.config({
        debug: true,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: '<?php echo $signPackage["timestamp"];?>',
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            // 所有要调用的 API 都要加到这个列表中
            'updateAppMessageShareData',
            'onMenuShareAppMessage'
        ]
    });
    // wx.ready(function () {
    //     // 在这里调用 API
    // });
    wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
        wx.onMenuShareAppMessage({
            title: '一拳超人', // 分享标题
            desc: '来到乐柠教育,我变秃了也变强了', // 分享描述
            link: 'http://haiwanlvzhu.cn/admin/login', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1564035701397&di=1395c4418c80bdcb556dcf2ba380bb97&imgtype=0&src=http%3A%2F%2Fpic31.nipic.com%2F20130801%2F11604791_100539834000_2.jpg', // 分享图标
            success: function () {
                // 设置成功
            }
        })
    });
</script>

</html>

