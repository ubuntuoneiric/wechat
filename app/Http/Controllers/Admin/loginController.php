<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\model\WeChat;
use App\DbModel\Admin;
use DB;

class loginController extends Controller
{
    /**
     * 登录视图页面&&页面分享(要分享哪个页面就在该页面写这些功能性代码)
     * @return [type] [description]
     */
    public function login()
    {
        $appid = "wx660e9a0142586c1e";
        $jsapiTicket = WeChat::getJsApiTicket();
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = WeChat::createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $appid,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );

        return view('admin/login',['signPackage'=>$signPackage]);
    }
    //登录验证
    public function dologin(Request $request)
    {
        $data = $request->all();
        $res = DB::table('admin')->where('name',$data['name'])->where('pwd',md5($data['pwd']))->first();
        if(empty($res->openid)){
            echo "<script>alert('账号未绑定微信请前去绑定');location.href='/admin/login';</script>";
        }
        if(empty($res)){
            echo "<script>alert('账号密码错误');location.href='/admin/login';</script>";
        }else{
            if(empty($data['code'])){
                echo "<script>alert('请填写验证码');location.href='/admin/login';</script>";
            }else{
                $code = Cache::get($res->openid);
                if($data['code'] == $code){
                    /*写入一些需要的日志数据*/
                    $log = [
                        'name'=>$data['name'],
                        'last_ip'=>$_SERVER['REMOTE_ADDR'],
                        'last_login_time'=>time(),
                        'openid'=>$res->openid,
                    ];
                    DB::table('admin_log')->insert($log);
                    session(['adminname'=>$data['name']]);
                    echo "<script>alert('成功');location.href='/admin';</script>";
                }else{
                    echo "<script>alert('验证码错误');location.href='/admin/login';</script>";
                }
            }
        }
    }
    //登录退出
    public function loginout(Request $request)
    {
        $request->session()->forget('adminname','adminid');
        return redirect('/admin/login');
    }
    //生成验证码
    public function createCode(Request $request)
    {
        $data = $request->all();
        $res = DB::table('admin')->where('name',$data['name'])->where('pwd',md5($data['pwd']))->first();
        if(empty($res->openid)){
            echo json_encode(['font'=>'账号未绑定微信请前往绑定','code'=>3]);die;
        };
        if(empty($res)){
            echo json_encode(['font'=>'账号密码错误','code'=>2]);die;
        }else {
            /*生成验证码*/
            $code = rand(1000, 9999);
            Cache::put($res->openid,$code,180);
            $log = DB::table('admin_log')->where('openid',$res->openid)->orderBy('last_login_time','desc')->first();
            /*调用微信模板接口发送验证码*/
            $postData = [
                'touser' => $res->openid,
                'template_id' => 'ARbEbHf9i3_GHT4DCAV2_wlaM1BjbgjK908f2bsc0bE',
                "url" => "http://www.baidu.com",
                'data' => [
                    'name' => ['value' => $data['name'], 'color' => '#173177'],
                    'code' => ['value' => $code, 'color' => '#173177'],
                    'last'=>['value' =>date('Y-m-d H:i:s',$log->last_login_time),'color' => '#173177'],
                    'ip'=>['value'=>$log->last_ip,'color'=>'#e11408'],
                ],
            ];
            $data = WeChat::sendMessageByTemplate($postData);
            if($data['errmsg'] == "ok"){
                echo json_encode(['font'=>'验证码发送成功','code'=>1]);
            }else{
                echo json_encode(['font'=>'验证码发送失败','code'=>2]);
            }
        }
    }

    //点击扫码登录进入的方法
    public function scanlogin()
    {
        //扫取二维码之后跳转的页面
        $url = "http://haiwanlvzhu.cn/admin/phonescan?log=".time();
        return view('admin/scanlogin',['url'=>$url,'log'=>time()]);
    }
    //扫码之后的跳转页面
    public function phonescan(Request $request)
    {
        //获取唯一标识
        $time = $request->input('log');
        //二维码过期
        if(time()-$time>120){
            echo "二维码过期";die;
        }
        //获取该用户的openid
        $openid = WeChat::getOpenidByAuth();
        //判断用户是否绑定
        $res = DB::table('admin')->where('openid',$openid)->first();
        if(empty($res)){
            echo "绑定账号不正确或者未绑定账号请返回绑定";die;
        }
        //存储起来作为验证用
        Cache::put($time."scan",$openid,300);

        echo "登录成功,请前去PC端查看";
    }
    //检测用户是否扫码的方法
    public function checkscan(Request $request)
    {
        $log = $request->input('log');
        $openid = Cache::get($log."scan");
        /*抛错的思想*/
        $res = Admin::where('openid',$openid)->first()->toArray();
        if(!$res){
            echo json_encode(['msg'=>'绑定账号有问题','code'=>1]);die;
        }
        session(['adminname'=>$res['name']]);
        if($openid){
            echo json_encode(['msg'=>'已扫码','code'=>1]);
        }
    }
}
