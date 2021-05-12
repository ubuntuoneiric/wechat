<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class question extends Controller
{
    //添加
    public function add()
    {
        return view('question/add');
    }
    //添加处理
    public function doadd(Request $request)
    {
        $data = $request->all();
        $res = DB::table('question')->insert($data);
        dd($res);
    }
}
