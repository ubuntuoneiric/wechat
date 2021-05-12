<?php

namespace App\DbModel;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    public    $timestamps = false;
    protected $guarded = [];//批量添加需要制定属性

    /*
     * 根据openid查询
     * */
    public static function selectByOpenid($openid)
    {
        $data = self::where('open_id',$openid)->first()->toArray();
        return $data;
    }

    /**根据openid进行content修改,用户修改和添加建议
     * @param $openid
     * @param $data
     */
    public static function updateContentByOpenid($openid,$data)
    {
        $content = mb_substr($data,2);
        self::where('open_id',$openid)->update(['content'=>$content]);
    }
}
