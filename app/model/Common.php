<?php
namespace App\model;

class Common
{
    /**无限级分类的其中一种,往上一级的数据中追加一个字段里边放入下一级的数据
     * @param $cate
     * @param int $pid
     * @param int $level
     * @return array
     */
    public static function infinite($data,$parent_id=0) //重新对数据进行排序
    {
        //先定义一个容器
        $arr = [];
        foreach ($data as $k=>$v) {
            if($v['parent_id']==$parent_id){
                $arr[$k] = $v;
                $arr[$k]['son'] = self::infinite($data,$v['id']);
            }
        }
        return $arr;
    }

    //分类递归排序的
    public static function cate_list($cate,$pid=0,$level=0) 
    {
        static $list = [];
        foreach ($cate as $v) {
            if($v['parent_id']==$pid){
                $v['level']=$level;
                $list[] = $v;
                self::cate_list($cate,$v['id'],$level+1);
            }
        }
        return $list;
    }
}
