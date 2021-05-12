<?php

namespace App\DbModel;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    protected $table = 'relation';
    protected $primaryKey = 'rid';
    public    $timestamps = false;
    protected $guarded = [];//批量添加需要制定属性

}
