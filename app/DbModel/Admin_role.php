<?php

namespace App\DbModel;

use Illuminate\Database\Eloquent\Model;

class Admin_role extends Model
{
    protected $table = 'admin_role';
    protected $primaryKey = 'id';
    public    $timestamps = false;
    protected $guarded = [];//批量添加需要制定属性
}