<?php

namespace App\DbModel;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id';
    public    $timestamps = false;
    protected $guarded = [];//批量添加需要制定属性

    /**添加菜单
     * @param $data正确的数组
     */
    public static function insertMenu($data)
    {
        $res = self::insert($data);
        return $res;
    }
}
