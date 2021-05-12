<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\model\WeChat;
use DB;
use Illuminate\Support\Facades\Cache;

class money extends Controller
{
	/**
	 * 红包授权页面
	 * @return [type] [description]
	 */
	public function auth()
	{
		WeChat::getOpenidDataByAuth();
		$list = Cache::get('list');
		$data = Cache::get('data');
		
		return view('money/auth',['list'=>$list,'data'=>$data]);
		
		
	}
	/**
	 * 红包算法
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function doAuth(Request $request)
	{
		$data = $request->all();
		$total=$data['total'];//红包总额 
		$num=$data['num'];// 分成8个红包，支持8人随机领取 
		Cache::put('data',$data,300);
		$min=0.01;//每个人最少能收到0.01元 

		$list = [];
		for ($i=1;$i<=$num;$i++) 
		{ 
			$safe_total=$total-($num-$i)*$min;//随机安全上限 
			$money=mt_rand($min*100,$safe_total*100)/100; 
			$total=$total-$money; 

			$list[$i-1] = '第'.$i.'个红包：'.$money.' 元'; 
		} 
		
		Cache::put('list',$list,300);
		return redirect('/money');
	}
}