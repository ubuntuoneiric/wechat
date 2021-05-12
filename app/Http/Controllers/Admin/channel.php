<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DbModel\Channel as cm;
use App\model\WeChat;
use DB;

class channel extends Controller
{
    //添加
    public function add()
    {
        return view('channel/add');
    }
    //添加处理
    public function doadd(Request $request)
    {
        $data = $request->all();
        $path = WeChat::getCode($data['c_cation']);
        DB::table('channel')->insert([
            'c_name'=>$data['c_name'],
            'c_cation'=>$data['c_cation'],
            'qrcode'=>$path,
            'c_format'=>$data['c_format'],
            'create_time'=>time(),
        ]);
        WeChat::success("/admin/channellist");
    }
    //信息展示
    public function list()
    {
        $data = DB::table('channel')->get();
        return view('channel/list',['data'=>$data]);
    }
    //展示柱状图
    public function show()
    {
        $data = cm::channelAll();
        $nameStr = '';
        $numStr = '';
        foreach ($data as $v){
            $nameStr .= "'".$v['c_name']."'".",";
        }
        foreach ($data as $v){
            $numStr .= $v['num'].",";
        }
        $nameStr = rtrim($nameStr,',');
        $numStr = rtrim($numStr,',');
        
        return view('channel/show',['name'=>$nameStr,'num'=>$numStr]);
    }
}
