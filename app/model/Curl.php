<?php
namespace App\model;

class Curl
{
    /**
     * curl的get请求
     * @param  [type] $url [请求地址]
     * @return [type]      [返回请求内容]
     */
    public static function curlGet($url)
    {
        //1初始化
        $ch = curl_init();
        //2设置
        curl_setopt($ch,CURLOPT_URL,$url); //访问地址
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //返回格式
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false); // 对认证证书来源的检查,对https的网址访问时多加的一个参数
        //3执行
        $content = curl_exec($ch);
        //4关闭
        curl_close($ch);
        return $content;
    }
    /**
     * curl发送post请求
     * @param  [type] $url      [请求地址]
     * @param  [type] $postData [携带参数]
     * @return [type]           [请求的返回值]
     */
    public static function curlPost($url,$postData)
    {
        //1初始化
        $ch = curl_init();
        //2设置
        curl_setopt($ch,CURLOPT_URL,$url); //访问地址
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //返回格式
        curl_setopt($ch,CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postData); // Post提交的数据包
        //请求网址是https
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false); // 对认证证书来源的检查
        //3执行
        $content = curl_exec($ch);
        //4关闭
        curl_close($ch);
        return $content;
    }
    /**
     * 用post的方式发送xml的原始数据.
     * @param  [type] $url 请求地址
     * @param  [type] $xml xml数据
     * @return [type]      http返回值
     * @author Mr Cui <[<haiwanlvzhu@163.com>]>
     */
    public static function http_post_xml($url, $xml)
    {   
        /*定义content-type为xml,注意是数组,这是模拟http协议的header头*/
        $header[] = "Content-type: text/xml";
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);//比正常的post请求多出的参数设置
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $xml);
        //执行命令 如果访问https网址，需要设置为false
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);//这个是重点。
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            print curl_error($ch);
        }
        curl_close($ch);

        return $response;
    }
}
