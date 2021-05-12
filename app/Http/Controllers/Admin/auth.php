<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\WeChat;
use DB;

class auth extends Controller
{
    //绑定openid页面
    public function authadmin()
    {
        $openid = WeChat::getOpenidByAuth();

        return view('auth/auth');
    }
    //处理
    public function doauth(Request $request)
    {
        $data = $request->all();
        $res = DB::table('admin')->where('name',$data['name'])->where('pwd',md5($data['pwd']))->first();
        if(!empty($res)){
            if(empty($res->openid)){
                DB::table('admin')->where('name',$data['name'])->update(['openid'=>session('openid')]);
                echo "绑定成功";
            }else{
                DB::table('admin')->where('name',$data['name'])->update(['openid'=>session('openid')]);
                echo "<script>alert('该账号已经绑定过,确定修改吗?')</script>";
                echo "修改成功";
            }
        }else{
            echo "账号密码错误";
        }
    }
}
