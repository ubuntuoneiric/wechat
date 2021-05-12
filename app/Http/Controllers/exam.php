<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\model\WeChat;
use App\DbModel\Fans;
use DB;
use Illuminate\Support\Facades\Cache;

class exam extends Controller
{
	
	public function auth()
	{
		$data = WeChat::getOpenidDataByAuth();
		$openid = $data['openid'];
		$res = DB::table('admin')->where('openid',$openid)->first();
		if(!empty($res)){
			echo "<script>alert('登录成功,正在跳转...');location.href='/lovecreate';</script>";
		}else{
			echo "登录失败!您未进行账号绑定";die;
		}
	}

	public function add()
	{
		$fans = Fans::get()->toArray();
		return view('love/add',['fans'=>$fans]);
	}

	public function doadd(Request $request)
	{
		$data = $request->all();
		if(empty($data['is_name'])){
			echo "匿名未选";die;
		}
		$is_name = $data['is_name'];
		foreach ($data['check'] as $k => $v) {
			
			$openid = $v;
			$content = $data[$v];

			$sess = session('openidData');

			$res = DB::table('love')->insert([
				'openid'=>$openid,
				'is_name'=>$is_name,
				'content'=>$content,
				'name'=>$sess['nickname']
			]);
			 $postData = [
                'touser' => $v,
                'template_id' => '73J4LDLLxDbdXOd1e6y3NOZQ1YVLKWSv_SOoYjGhHek',
                "url" => "http://www.baidu.com",
                'data' => [
                    'name' => ['value' => $sess['nickname'], 'color' => '#173177'],
                    'content' => ['value' => $content, 'color' => '#173177'],
                  
                ],
            ];
            $data = WeChat::sendMessageByTemplate($postData);
           
		}

		echo "<script>alert('表白成功');</script>";
	}

}