<?php

namespace App\DbModel;

use Illuminate\Database\Eloquent\Model;

class User_answer extends Model
{
    protected $table = 'user_answer';
    protected $primaryKey = 'id';
    public    $timestamps = false;
    protected $guarded = [];//批量添加需要制定属性
}
