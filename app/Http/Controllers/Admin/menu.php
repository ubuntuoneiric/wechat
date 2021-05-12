<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DbModel\Menu as mm;
use App\model\WeChat;
use App\model\Common;

class menu extends Controller
{
    //自定义菜单添加视图
    public function add()
    {
        $data = mm::where('parent_id',0)->get()->toArray();
        return view('menu/add',['data'=>$data]);
    }

    /**自定义菜单的添加处理
     * @param Request $request
     */
    public function doadd(Request $request)
    {
        
        $data = $request->all();
        $this->addmenu($data);
    }

    /**菜单的信息展示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list()
    {
        $data = mm::get()->toArray();
        $data = Common::cate_list($data,$pid=0,$level=0);
        return view('menu/list',['data'=>$data]);
    }

    /**一键生成微信菜单
     * @param Request $request
     */
    public function createMenu()
    {
        $data = mm::get()->toArray();
        $data = Common::infinite($data,$parent_id=0);//用递归处理数组目的是往一级数据中塞入二级数据,为了方便二次循环处理数据
        $kv = ['click'=>'key','view'=>'url'];//设置一个数组.可以用于固定的替换.

        $postData = [];
        foreach($data as $k=>$v){
            if(empty($v['content'])){
                /*有子菜单,这里添加的时候规定准备用二级菜单的不需要添加类型和内容*/
                $postData['button'][$k]['name'] = $v['name'];
                /*我们把二级菜单的数据压入一级数据的son字段中了*/
                foreach($v['son'] as $key=>$value)
                {
                    $postData['button'][$k]['sub_button'][] = [
                        'type'=>$value['type'],
                        'name'=>$value['name'],
                        $kv[$value['type']]=>$value['content']
                    ];
                }
            }else{
                /*没有子菜单*/
                $postData['button'][] = [
                    'type'=>$v['type'],
                    'name'=>$v['name'],
                    $kv[$v['type']]=>$v['content']
                ];
            }

        }

        $res = WeChat::createMenu($postData);
        dd($res);
    }

    /**
     * 添加菜单的方法
     */
    public function addmenu($data)
    {
        $menu = [];
        $menu['parent_id'] = $data['parent_id'];
        $menu['name'] = $data['name'];
        $menu['type'] = $data['type'];
        $menu['content'] = $data['content'];
        $menu['create_time'] = time();
        mm::insertMenu($menu);
        WeChat::success("/admin/menulist");
    }
}
