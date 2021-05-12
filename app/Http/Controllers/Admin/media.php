<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\WeChat;
use DB;

class media extends Controller
{
    //添加
    public function add()
    {
        return view('media/add');
    }
    //添加处理
    public function doadd(Request $request)
    {
        //接受表单传值
        $data = $request->all();
        $type = $data['m_type'];
        $name = $data['m_name'];
        $format = $data['m_format'];
        $sign = $data['m_format'];
        $file = $request->file('m_path');
        //自定义文件名
        $filename = time().rand(1000,9999).".".$file->getClientOriginalExtension();
        $path = $request->file('m_path')->storeAs('media',$filename);
        $img = storage_path('/app/public/'.$path);//图片的绝对路径
        $str = WeChat::getMediaID($img,$type,$sign);
        $arr = json_decode($str,true);
        $media_id = $arr['media_id'];//获取素材的media_id
        DB::table('media')->insert([
            'm_name'=>$name,
            'm_type'=>$type,
            'm_path'=>$path,
            'media_id'=>$media_id,
            'm_format'=>$format,
            'create_time'=>time(),
        ]);
        return redirect('/admin/medialist');
    }
    //素材展示
    public function list()
    {
        $data = DB::table('media')->paginate(2);
        return view('media/list',['data'=>$data]);
    }
    //删除永久素材
    public function deletemedia(Request $request)
    {
        $data = $request->all();
        $postData = $data['media_id'];
        $res = WeChat::deleteMedia($postData);
        $res = json_decode($res,true);
        if($res['errcode'] == 0){
            DB::table('media')->where('media_id',$postData)->delete();
        }
        echo "<script>alert('删除成功');location.href='/admin/medialist'</script>";
    }
}
