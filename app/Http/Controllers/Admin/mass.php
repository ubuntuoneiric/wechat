<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DbModel\User;
use App\DbModel\Fans;
use App\model\WeChat;
use DB;

class mass extends Controller
{
    //群发消息设置
    public function add()
    {
        $data = Fans::get()->toArray();
        $label = DB::table('label')->get();
        return view('mass/add',['label'=>$label,'data'=>$data]);
    }
    //群发消息处理
    public function doadd(Request $request)
    {
        $data = $request->all();
        $content = $data['content'];
        
        if($data['type'] == 1){//标签发送
            $postData = [
                'filter'=>['is_to_all'=>false, 'tag_id'=>$data['label']],
                'text'=>['content'=>$content],
                'msgtype'=>'text'
            ];
            $res = WeChat::massMessage($postData);
            if($res['errcode'] == 0){
                echo "发送成功";
            }else{
                echo "发送失败";
            }
        }elseif($data['type'] == 2){//全部发送
            $postData = [
                'filter'=>['is_to_all'=>true, 'tag_id'=>''],
                'text'=>['content'=>$content],
                'msgtype'=>'text'
            ];
            $res = WeChat::massMessage($postData);
            if($res['errcode'] == 0){
                echo "发送成功";
            }else{
                echo "发送失败";
            }
        }else{                  //根据openid批量
            $postData = [
                'touser'=>$data['check'],
                'text'=>['content'=>$content],
                'msgtype'=>'text'
            ];
            $res = WeChat::massMessageOpenid($postData);
            if($res['errcode'] == 0){
                echo "发送成功";
            }else{
                echo "发送失败";
            }
        }
    }
}
