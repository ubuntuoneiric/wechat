<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\WeChat;
use App\DbModel\Wish as wc;
use DB;

class wish extends Controller
{
	/**
	 * [auth description]许愿墙授权页面
	 * @return [type] [description]
	 */
	public function auth()
	{
		WeChat::getOpenidDataByAuth();
		$data = wc::get()->toArray();
		return view('wish/auth',['data'=>$data]);
	}
	public function doauth(Request $request)
	{
		$data = $request->all();
		$content = $data['content'];

		$data = session('openidData');
		$res = DB::table('wish')->insert([
			'name'=>$data['nickname'],
			'openid'=>$data['openid'],
			'header'=>$data['headimgurl'],
			'content'=>$content,
		]);
		if($res){
			return redirect('/admin/wish');
		}
	}
	public function wishlist()
	{
		$data = wc::get()->toArray();
		echo json_encode($data);
	}
}