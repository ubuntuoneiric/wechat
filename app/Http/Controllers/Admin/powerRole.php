<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DbModel\Role;
use App\DbModel\Admin;
use App\DbModel\Admin_role;
use DB;

class powerRole extends Controller
{
	/*角色添加视图页面*/
	public function roleadd()
	{
		return view('powerRole/roleadd');
	}
	/**角色添加处理
	 * @param  Request
	 * @return [type]
	 */
	public function doroleadd(Request $request)
	{
		$data = $request->all();
		$res = DB::table('role')->insert($data);
		if($res){
			return redirect('admin/rolelist');
		}
	}
	/**角色展示 和 分配视图页面
	 * @return [type]
	 */
	public function rolelist()
	{
		$data = Role::get()->toArray();
		/*处理关系表,展示每个角色的详情*/
		$admin = Admin_role::get()->groupBy('role_id')->toArray();
		dd($admin);
		return view('powerRole/rolelist',['data'=>$data]);
	}
	/**
	 * 分配角色的视图
	 * @param  Request
	 * @return [type]
	 */
	public function roledistribution(Request $request)
	{
		$id = $request->input('id');
		$role = Role::where('role_id',$id)->first()->toArray();
		$admin = Admin::get()->toArray();
		return view('powerRole/roledistribution',['role'=>$role,'data'=>$admin]);
	}
	/**
	 * 分配角色的处理
	 * @param  Request
	 * @return [type]
	 */
	public function dodistribution(Request $request)
	{
		$data = $request->all();
        $openid = explode(",",$data['openid']);
        $arr = [];
        foreach($openid as $v){
            $arr[] = ['admin_id'=>$v,'role_id'=>$data['role']];
        }
        DB::table('admin_role')->insert($arr);
        echo json_encode(['font'=>'添加角色成功']);
	}
	public function poweradd()
	{
		return view('powerRole/poweradd');
	}
}