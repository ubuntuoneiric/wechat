<?php

namespace App\DbModel;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channel';
    protected $primaryKey = 'id';
    public    $timestamps = false;
    protected $guarded = [];//批量添加需要制定属性

    /**
     * @param $channel关注渠道
     */
    public static function channelDecrement($channel)
    {
        self::where('c_cation',$channel)->decrement('num');;
    }

    /**
     * 查询所有
     * @return mixed 数据集
     */
    public static function channelAll()
    {
        $data = self::all();
        return $data;
    }
}
