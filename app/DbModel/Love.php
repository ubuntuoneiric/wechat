<?php

namespace App\DbModel;

use Illuminate\Database\Eloquent\Model;

class Love extends Model
{
    protected $table = 'love';
    protected $primaryKey = 'id';
    public    $timestamps = false;
    protected $guarded = [];//批量添加需要制定属性


}