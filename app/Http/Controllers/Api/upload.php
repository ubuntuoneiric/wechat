<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class upload extends Controller
{
	/**
	 * [formData "http://haiwanlvzhu.cn/api/formData"]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function formData(Request $request)
	{
		$file = $request->file('img');
		$path = $file->store('uploads');
		$path = "http://haiwanlvzhu.cn/storage/".$path;
		return json_encode(['code'=>1,'msg'=>'ok','path'=>$path]);
	}
	/**
	 * [binaryString "http://haiwanlvzhu.cn/api/binaryString"]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function binaryString(Request $request)
	{
		/*这种方式可以获取原始二进制数据*/
		$data = file_get_contents("php://input");
		/*把原始数据流写入图片中,因为图片和文件都是一些原始的数据流 0101010101010*/
		file_put_contents("./storage/uploads/binaryString.jpg", $data);
		$path = "http://haiwanlvzhu.cn/storage/uploads/binaryString.jpg";
		return json_encode(['code'=>1,'msg'=>'ok','path'=>$path]);
	}
	/**
	 * [base64 "http://haiwanlvzhu.cn/api/base64"]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function base64(Request $request)
	{
		$data = $request->post('img');
		$lenght = strpos($data,',');
		$data = substr($data, $lenght+1);

		$path = base64_decode($data);
		file_put_contents("./storage/uploads/base64.jpg", $path);
		$path = "http://haiwanlvzhu.cn/storage/uploads/base64.jpg";
		return json_encode(['code'=>1,'msg'=>'ok','path'=>$path]);
	}
}