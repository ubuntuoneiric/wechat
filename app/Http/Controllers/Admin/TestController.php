<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\WeChat;
use App\DbModel\Media;
use App\model\Curl;

class TestController extends Controller
{
/**
 * 微信支付二维码=====测试
 * @return [type] [description]
 */
public function index()
{      
    //调用微信接口
    //订单号
    $out_trade_no = "10a".date("YmdHi").rand(1000,9999);
    $appid = 'wxd5af665b240b75d4';  //公众号id 服务号id
    $mch_id = '1500086022';  //商户平台id
    $nonce_str = $this->createNonceStr();
    $notify_url = "http://ym.day900.com/login"; //支付成功后异步通知地址
    $spbill_create_ip = $_SERVER['REMOTE_ADDR'];
    $total_fee = 1; //钱 单位是分
    $trade_type = "NATIVE";
    $body = "ym测试";

    $signArr = [
        'appid'=>$appid,  
        'body'=>$body,
        'mch_id'=>$mch_id,
        'nonce_str'=>$nonce_str,
        'notify_url'=>$notify_url,
        'out_trade_no'=>$out_trade_no,
        'spbill_create_ip'=>$spbill_create_ip,
        'total_fee'=>$total_fee,
        'trade_type'=>$trade_type,
    ];

    //生成签名 
    //签名步骤一：按字典序排序参数
    ksort($signArr);
    $string = $this->ToUrlParams($signArr);

    //签名步骤二：在string后加入KEY
    $string = $string . "&key="."7c4a8d09ca3762af61e59520943AB26O";
    //签名步骤三：MD5加密或者HMAC-SHA256
    $string = md5($string);
    //签名步骤四：所有字符转为大写
    $sign = strtoupper($string);
    //组装xml数据
    $xml = '<xml>
       <appid>'.$appid.'</appid>
       <body>'.$body.'</body>
       <mch_id>'.$mch_id.'</mch_id>
       <nonce_str>'.$nonce_str.'</nonce_str>
       <notify_url>'.$notify_url.'</notify_url>
       <out_trade_no>'.$out_trade_no.'</out_trade_no>
       <spbill_create_ip>'.$spbill_create_ip.'</spbill_create_ip>
       <total_fee>'.$total_fee.'</total_fee>
       <trade_type>'.$trade_type.'</trade_type>
       <sign>'.$sign.'</sign>
    </xml>';

    //微信支付地址
    $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
    //发送post请求 发送xml数据
    $res = Curl::http_post_xml($url,$xml);
    //var_dump($res);die;
    $resObj = simplexml_load_string($res);
    if($resObj->return_code == 'SUCCESS'){
        $code_url = $resObj->code_url;
        echo $code_url;
    }

    return view('test/test',['url'=>$code_url]);
}

private function ToUrlParams($signArr)
{
    $buff = "";
    foreach ($signArr as $k => $v)
    {
        if($k != "sign" && $v != "" && !is_array($v)){
            $buff .= $k . "=" . $v . "&";
        }
    }
    
    $buff = trim($buff, "&");
    return $buff;
}

private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }
}	