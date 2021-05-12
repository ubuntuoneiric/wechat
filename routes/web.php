<?php

Route::any('/wechat','wechat@index');//微信前端主控制器
Route::get('/admin','Admin\index@index');//后台首页
Route::get('/admin/getfanslist','wechat@getFansList');//重新获取粉丝
Route::get('/cleartoken','Admin\index@cleartoken');//清除token
Route::get('/clearSession','Common@clearSession');//清除session
Route::get('/clearCache','Common@clearCache');//清除cache
Route::middleware(['checkadmin'])->group(function () {});//登录中间件

Route::get('/admin/weather','Admin\index@weather');//首页天气
Route::get('/admin/login','Admin\loginController@login');//登录视图
Route::post('/admin/dologin','Admin\loginController@dologin');//登录验证
Route::get('/admin/loginout','Admin\loginController@loginout');//登录退出

Route::get('/admin/mediaadd','Admin\media@add');//素材添加
Route::post('/admin/mediadoadd','Admin\media@doadd');//素材添加处理
Route::get('/admin/medialist','Admin\media@list');//素材展示
Route::any('/admin/deletemedia','Admin\media@deletemedia');//删除永久素材

Route::get('/admin/channeladd','Admin\channel@add');//渠道添加
Route::post('/admin/channeldoadd','Admin\channel@doadd');//渠道添加处理
Route::get('/admin/channellist','Admin\channel@list');//渠道展示
Route::get('/admin/channelshow','Admin\channel@show');//渠道展示

Route::get('/admin/replyadd','Admin\reply@add');//回复添加
Route::post('/admin/replydoadd','Admin\reply@doadd');//添加处理
Route::any('/admin/mediareplylist','Admin\reply@medialist');//素材ajax展示

Route::get('/admin/menuadd','Admin\menu@add');//自定义菜单添加
Route::post('/admin/menudoadd','Admin\menu@doadd');//自定义菜单添加处理
Route::get('/admin/menulist','Admin\menu@list');//自定义菜单列表
Route::get('/admin/createMenu','Admin\menu@createMenu');//一键生成菜单

Route::get('/admin/labeladd','Admin\label@add');//标签添加
Route::post('/admin/labeldoadd','Admin\label@doadd');//标签添加处理
Route::get('/admin/labellist','Admin\label@list');//标签展示
Route::get('/admin/fanslist','Admin\label@fanslist');//粉丝列表
Route::get('/admin/fansDistribution','Admin\label@fansDistribution');//粉丝分配标签
Route::post('/admin/createLabelUser','Admin\label@createLabelUser');//粉丝分配标签处理添加
Route::get('/admin/relationlist','Admin\label@relationlist');//标签的分配完成列表
Route::get('/admin/massadd','Admin\mass@add');//群发消息设置
Route::post('/admin/massdoadd','Admin\mass@doadd');//群发消息设置处理

Route::get('/sendMessage','wechat@sendMessage');//自动群发消息
Route::post('/admin/createCode','Admin\loginController@createCode');//获取验证码

Route::get('/admin/questionadd','Admin\question@add');//添加题目
Route::post('/admin/questiondoadd','Admin\question@doadd');//添加题目处理

Route::get('/admin/authlogin','Admin\auth@login');//网页授权跳转到微信处理页面
Route::get('/admin/auth','Admin\auth@auth');//网页授权跳转到微信处理页面
Route::any('/admin/authadmin','Admin\auth@authadmin');//绑定账号页面
Route::post('/admin/doauth','Admin\auth@doauth');//处理绑定账号页面

Route::get('/admin/scanlogin','Admin\loginController@scanlogin');//点击扫码进入的方法
Route::get('/admin/phonescan','Admin\loginController@phonescan');//扫码之后进入的地址
Route::get('/admin/checkscan','Admin\loginController@checkscan');//判断用户是否扫码

Route::get('/admin/wxpay','Admin\TestController@index');//微信支付测试

Route::get('/admin/poweradd','Admin\powerRole@poweradd');//角色权限分配
Route::get('/admin/roleadd','Admin\powerRole@roleadd');//角色添加
Route::post('/admin/doroleadd','Admin\powerRole@doroleadd');//角色添加处理
Route::get('/admin/rolelist','Admin\powerRole@rolelist');//角色展示
Route::get('/admin/roledistribution','Admin\powerRole@roledistribution');//角色分配
Route::post('/admin/dodistribution','Admin\powerRole@dodistribution');//角色分配

Route::get('/admin/wish','Admin\wish@auth');//许愿墙授权页面
Route::post('/admin/dowish','Admin\wish@doauth');//许愿接受内容
Route::any('/admin/wishlist','Admin\wish@wishlist');//许愿墙备用

Route::get('/money','money@auth');//红包的授权页面,可以在测试号中访问的.
Route::post('/domoney','money@doAuth');//红包的授权页面

Route::get('/loveadd','exam@auth');//表白授权登录
Route::get('/lovecreate','exam@add');//表白添加
Route::post('/lovedoadd','exam@doadd');//表白添加

//api接口
Route::any('/api/formData','Api\upload@formData');
Route::any('/api/binaryString','Api\upload@binaryString');
Route::any('/api/base64','Api\upload@base64');

Route::get('/', function(){ 
	return view('sign');
});