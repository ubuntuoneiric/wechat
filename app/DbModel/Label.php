<?php

namespace App\DbModel;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $table = 'label';
    protected $primaryKey = 'lid';
    public    $timestamps = false;
    protected $guarded = [];//批量添加需要制定属性

    /**标准添加
     * @param $data要添加的数据
     */
    public static function insertLabel($data)
    {
        self::create($data);
    }
}
