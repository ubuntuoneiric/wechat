<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Common extends Controller
{
	/**
	 * 清除session的方法
	 * @param  Request $request [地址上?data=你要删除的session]
	 * @return [type]           [description]
	 */
	public function clearSession(Request $request)
	{
		$data = $request->get('data');
		$request->session()->forget("openidData");
		var_dump(session($data));
		return "清除成功!";
	}
	/**
	 * 清除缓存同上
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function clearCache(Request $request)
	{
		$data = $request->get('data');
		Cache::forget($data);
		var_dump(Cache::get($data));
		echo "清除成功";
	}
}