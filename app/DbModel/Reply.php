<?php

namespace App\DbModel;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $table = 'reply';
    protected $primaryKey = 'id';
    public    $timestamps = false;
    protected $guarded = [];//批量添加需要制定属性

    public static function insertReply($data)
    {
        self::insert([
            'content'=>$data,
        ]);
    }
}
