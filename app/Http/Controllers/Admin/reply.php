<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DbModel\Reply as rm;
use App\model\WeChat;
use App\DbModel\Media;

class reply extends Controller
{
    /**消息回复视图
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        return view('reply/add');
    }

    /**回复方式添加处理
     * @param Request $request
     */
    public function doadd(Request $request)
    {
        $data = $request->all();
        unset($data['s']);
        $file = $request->file('content');
        if($data['type'] == 2){
           $path = WeChat::upload($file);//相对路径
            /*同步一下微信,获取一下media_id*/
            $address = public_path("storage/".$path);
            $str = WeChat::getMediaID($address,"image","1");
            $arr = json_decode($str,true);
            $media_id = $arr['media_id'];
            $data = ['type'=>$data['type'],'path'=>$path,'media_id'=>$media_id];
            Media::insertMediaByReply("关注",$path,"image","1",$media_id);
        }
        $json = json_encode($data);
        rm::insertReply($json);
        echo "成功";
    }

    /**素材的ajax展示
     * @param Request $request
     */
    public function medialist(Request $request)
    {
        $data = Media::selectMedia();
        echo json_encode($data);
    }
}
