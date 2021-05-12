<?php

namespace App\DbModel;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'role_id';
    public    $timestamps = false;
    protected $guarded = [];//批量添加需要制定属性
}