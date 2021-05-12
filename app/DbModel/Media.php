<?php

namespace App\DbModel;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';
    protected $primaryKey = 'id';
    public    $timestamps = false;
    protected $guarded = [];//批量添加需要制定属性

    /**随机获取一条素材数据
     * @return mixed返回素材的微信id
     * @author Mr cui
     */
    public static function selectRandImage()
    {
        $data = self::where('m_type', "image")->inRandomOrder()->first()->toArray();
        return $data['media_id'];
    }

    /**添加素材封装
     * @param $name
     * @param $path
     * @param $type
     * @param $format
     * @param $media_id
     */
    public static function insertMediaByReply($name,$path,$type,$format,$media_id)
    {
        self::insert([
            'm_name'=>$name,
            'm_path'=>$path,
            'm_type'=>$type,
            'm_format'=>$format,
            'media_id'=>$media_id,
            'create_time'=>time()
        ]);
    }
    /*查询图片所有*/
    public static function selectMedia()
    {
        $data = self::where('m_type','image')->get()->toArray();
        return $data;
    }
}
