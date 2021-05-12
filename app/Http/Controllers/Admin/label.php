<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DbModel\Label as lm;
use App\DbModel\Fans;
use App\DbModel\User;
use App\DbModel\Relation;
use App\model\WeChat;
use DB;

class label extends Controller
{
    //添加视图
    public function add()
    {
        return view('label/add');
    }
    //添加处理
    public function doadd(Request $request)
    {
        $data = $request->input();
        /*同步微信的测试号*/
        $postData = ["tag"=>["name"=>$data['name']]];
        $res = WeChat::createLabel($postData);
        /*数据入库*/
        $data['sign'] = $res['tag']['id'];
        lm::insertLabel($data);
        WeChat::success("/admin/labellist");
    }
    //标签列表
    public function list()
    {
        $data = lm::get()->toArray();
        return view('label/list',['data'=>$data]);
    }
    /*粉丝列表,获取详细的用户信息*/
    public function fanslist()
    {
        $data = Fans::get()->toArray();
        return view('label/fanslist',['data'=>$data]);
    }
    /*分配用户的方法*/
    public function fansDistribution(Request $request)
    {
        $id = $request->get("id");
        $label = lm::where('lid',$id)->first()->toArray();//标签数据
        $data = Fans::get()->toArray();//用户数据
        return view('label/fansDistribution',['label'=>$label,'data'=>$data]);
    }

    /**处理用户标签的批量添加
     * @param Request $request
     */
    public function createLabelUser(Request $request)
    {
        $data = $request->all();
        $openid = explode(",",$data['openid']);
        /*同步微信*/
        $postData = [];
        $postData['tagid'] = $data['label'];
        $postData['openid_list'] = $openid;
        $res = WeChat::createRelation($postData);
        /*数据入库*/
        if($res['errmsg'] == "ok"){
            $arr = [];
            foreach($openid as $v){
                $arr[] = ['uid'=>$v,'lid'=>$data['label']];
            }
            Relation::insert($arr);
        }

        echo json_encode(['font'=>"添加成功"]);
    }

    /**关联列表
     * @return 
     */
    public function relationlist()
    {
        $data = DB::table('relation')->get();
        return view('label/relationlist',['data'=>$data]);
    }
}
