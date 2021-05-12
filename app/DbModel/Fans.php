<?php

namespace App\DbModel;

use Illuminate\Database\Eloquent\Model;

class Fans extends Model
{
    protected $table = 'fans';
    protected $primaryKey = 'fid';
    public    $timestamps = false;
    protected $guarded = [];//批量添加需要制定属性
}
