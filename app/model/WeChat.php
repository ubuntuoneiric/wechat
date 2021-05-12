<?php

namespace App\model;

use Illuminate\Support\Facades\Cache;
use App\model\Curl;

class WeChat
{
    /*此类为微信核心类==>引用方法存放*/
    const appid = "wx660e9a0142586c1e";//测试号id
    const secret = "7324668add83e49db73fca371f953539";//测试号密码
    /**
     * 微信接入自己服务器的验证方法.
     * @return [type] [没有返回值,按要求在页面直接输出$echostr.]
     */
    public static function insert()
    {
        $echostr = request("echostr");
        if(!empty($echostr) && WeChat::checkSign()){
            echo $echostr;die;
        }
    }

    /**
     * 微信接入页面,验证token,就是自己的签名.
     * @return [bool] [true:成功,false:失败]
     */
    private static function checkSign()
    {
        /*
         * 1）将token、timestamp、nonce三个参数进行字典序排序 (就是由a-z进行sort数组函数排序)
         * 2）将三个参数字符串拼接成一个字符串进行sha1加密
         * 3）开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
         */
        $signature = $_GET["signature"];
        $timestamp =$_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $tmpArr = array("token",$timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $signature == $tmpStr ){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 获取全局唯一access_token令牌.
     * @return [string] [获取的access_token令牌本体.]
     */
    public static function getToken()
    {
        //定义一些需要的变量,逻辑判断
        $access_token = Cache::get('access_token');
        //小逻辑判断
        if(empty($access_token)){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::appid."&secret=".self::secret;
            $data = file_get_contents($url);
            $data = json_decode($data,true);
            $access_token = $data["access_token"];
            //框架缓存,存值
            Cache::put('access_token',$access_token,7200);//2个小时过期
        }
        file_put_contents("access_token.txt",$access_token);

        return $access_token;
    }
    /**
     * 回复文本消息
     * @param  [type] $xmlObj [微信端发送过来的xml转化成的对象]
     * @param  [type] $msg    [回复的xml数据]
     * @return [type]         [要回复的xml数据]
     */
    public static function replyTxt($xmlObj,$msg)
    {
        $txt = "<xml>
              <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
              <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
              <CreateTime>".time()."</CreateTime>
              <MsgType><![CDATA[text]]></MsgType>
              <Content><![CDATA[".$msg."]]></Content>
            </xml>";
        echo $txt;
    }

    /**
     * 获取天气的有关信息
     * @param $text信息
     * @return string 天气的信息
     */
    public static function getWeather($text)
    {
        $length = mb_strpos($text,'天气');
        $city = "北京";
        if($length>0){
            $city = mb_substr($text,0,$length);
        }
        //获取天气状况
        $res = file_get_contents("http://api.k780.com/?app=weather.future&weaid={$city}&&appkey=43608&sign=10b9ad3a80620c860df86e043668cbe8&format=json");
        $res = json_decode($res,true);
        $msg ="";
        foreach($res['result'] as $v){
            $msg .= $v['days'].$v['citynm'].$v['temperature'].$v['weather'].$v['wind']."\r\n";
        }
        return $msg;
    }
    /**
     * [getCharacter description]获得人品值的算法
     * @param  [type] $text [description]
     * @return [type]       [description]
     */
    public static function getCharacter($text)
    {
        $length = mb_strpos($text,'#');
        $name = mb_substr($text,$length+1);
        $name = base64_encode($name);
        $arr = str_split($name,1);
        $person = 0;
        foreach ($arr as $k => $v) {
            $person += ord($v);
        }
        return $person%100;
    }
    /**
     * 自动回复图文信息
     * @param  [type] $xmlObj      [description]
     * @param  [type] $title       [标题]
     * @param  [type] $description [描述]
     * @param  [type] $picurl      [图片地址]
     * @param  [type] $url         [跳转地址]
     * @return [type]              [description]
     */
    public static function getImageText($xmlObj,$title,$description,$picurl,$url)
    {
        echo "<xml>
              <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
              <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
              <CreateTime>".time()."</CreateTime>
              <MsgType><![CDATA[news]]></MsgType>
              <ArticleCount>1</ArticleCount>
              <Articles>
                <item>
                  <Title><![CDATA[".$title."]]></Title>
                  <Description><![CDATA[".$description."]]></Description>
                  <PicUrl><![CDATA[".$picurl."]]></PicUrl>
                  <Url><![CDATA[".$url."]]></Url>
                </item>
              </Articles>
          </xml>";
    }
    /**
     * 获取用户信息.
     * @param  [type] $xmlObj [用户发送的xml数据的对象格式.]
     * @return [type]         [description]
     */
    public static function getUser($xmlObj)
    {
        //定义一些需要的变量
        $access_token = WeChat::getToken();
        $openid = $xmlObj->FromUserName;//用户的openid,唯一的
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $user = file_get_contents($url);
        $user = json_decode($user,true);

        return $user;
    }
    /**
     * 回复图片
     * @param  [type] $xmlObj   [description]
     * @param  [type] $media_id [微信素材库的media_id]
     * @return [type]           [description]
     */
    public static function replyImage($xmlObj,$media_id)
    {
        echo "<xml>
                  <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
                  <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
                  <CreateTime>".time()."</CreateTime>
                  <MsgType><![CDATA[image]]></MsgType>
                  <Image>
                    <MediaId><![CDATA[".$media_id."]]></MediaId>
                  </Image>
            </xml>";
    }

    /**微信素材上传获取mediaID
     * @param $path
     * @param $type
     * @param $sign1代表临时2代表永久
     * @return bool|string
     */
    public static function getMediaID($path,$type,$sign)
    {
        $access_token = WeChat::getToken();
        //接口地址
        if($sign == "1"){
            $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$access_token}&type={$type}";
        }else{
            $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type={$type}";
        }
        //因为是用curl传文件路径,一般路径中都有符号,所以需要用函数给其进行特殊处理.
        $imgPath = new \CURLFile($path);
        $postData['media'] = $imgPath;
        //发送请求
        $res = Curl::curlPost($url,$postData);
        return $res;
    }
    /*
     * 删除微信的永久素材
     */
    public static function deleteMedia($postData)
    {
        //定义一些变量
        $access_token = WeChat::getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/material/del_material?access_token={$access_token}";
        $data['media_id'] = $postData;
        $data = json_encode($data);
        $res = Curl::curlPost($url,$data);
        return $res;
    }
    /**
     * 获取微信渠道的带参数的二维码
     * @param  [type] $id [description]这个是自己定的渠道标识
     * @return [type]     [description]二维码的地址
     */
    public static function getCode($id)
    {
        $access_token = WeChat::getToken();
        //创建参数二维码接口
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$access_token}";

        //参数
        //xxx渠道         111
        $postData = '{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id":'.$id.'}}}';
        //发请求
        //调接口 拿到票据ticket
        $data = Curl::curlPost($url,$postData);

        $data = json_decode($data,true);
        $ticket = $data['ticket'];
        //通过ticket换取二维码
        $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;
        //保存图片到本地
        $filename = "qrcode".md5(time().rand(1000,9999)).".jpg";
        copy($url,"qrcode/".$filename);
        //$img = file_get_contents($url);
        //file_put_contents("qrcode/".$filename.".jpg",$img);
        return $filename;
    }

    /*
     * 成功跳转
     */
    public static function success($url)
    {
        echo "<script>alert('成功!');location.href='".$url."'</script>";
    }

    /**文件上传的封装方法
     * @param $file表单上传的文件对象数据集
     * @returnmixed返回一个storage链接的相对路径
     */
    public static function upload($file)
    {
        $filename = md5(time().rand(1000,9999)).".".$file->getClientOriginalExtension();
        $path = $file->storeAs('media',$filename);
        return $path;
    }

    /**生成微信菜单的方法封装
     * @param $arr按规定的数组
     * @return bool|string
     */
    public static function createMenu($arr)
    {
        $access_token = self::getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
        $postData = json_encode($arr,JSON_UNESCAPED_UNICODE);
        $res = Curl::curlPost($url,$postData);
        return $res;
    }

    /**标签同步到微信
     * @param $postData需要的参数{   "tag" : {     "name" : "广东"//标签名   } }
     * @return bool|string微信的返回值{   "tag":{ "id":134,//标签id "name":"广东"   } }
     */
    public static function createLabel($postData)
    {
        $postData = json_encode($postData);
        $access_token = self::getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/tags/create?access_token={$access_token}";
        $res = Curl::curlPost($url,$postData);
        $res = json_decode($res,true);
        return $res;
    }

    /**获取用户列表,不传值默认是从头开始拉取数据
     * @return bool|string{
                            "total":2,
                            "count":2,
                            "data":{
                            "openid":["OPENID1","OPENID2"]},
                            "next_openid":"NEXT_OPENID"
                          }
     */
    public static function getUserList()
    {
        $access_token = self::getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token={$access_token}&next_openid=";
        $res = Curl::curlGet($url);
        $res = json_decode($res,true);
        return $res;
    }
   /**
    * 自动获取用户信息,根据openID
    * @param  [type] $openid [用户唯一标识]
    * @return [type]         [description]
    */
    public static function eachGetUser($openid)
    {
        //定义一些需要的变量
        $access_token = WeChat::getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $user = file_get_contents($url);
        $user = json_decode($user,true);

        return $user;
    }

    /**同步微信的标签和用户的关联
     * @param $postData
     * @return bool|mixed|string
     */
    public static function createRelation($postData)
    {
        $postData = json_encode($postData);
        $access_token = WeChat::getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token={$access_token}";
        $res = Curl::curlPost($url,$postData);
        $res = json_decode($res,true,JSON_UNESCAPED_UNICODE);
        return $res;
    }

    /**群发消息发送
     * @param $postData
     * @return bool|mixed|string
     */
    public static function massMessage($postData)
    {
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $access_token = self::getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token={$access_token}";
        $res = Curl::curlPost($url,$postData);
        $res = json_decode($res,true,JSON_UNESCAPED_UNICODE);
        return $res;
    }
    /**群发消息发送根据openid批量
     * @param $postData
     * @return bool|mixed|string
     */
    public static function massMessageOpenid($postData)
    {
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $access_token = self::getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token={$access_token}";
        $res = Curl::curlPost($url,$postData);
        $res = json_decode($res,true,JSON_UNESCAPED_UNICODE);
        return $res;
    }
    /**发送模板消息
     * @param $postData
     * @return bool|mixed|string
     */
    public static function sendMessageByTemplate($postData)
    {
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $access_token = self::getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$access_token}";
        $res = Curl::curlPost($url,$postData);
        $res = json_decode($res,true,JSON_UNESCAPED_UNICODE);
        return $res;
    }

    /**
     * 通过微信网页授权的方式获取用户openid和用户信息
     * 返回值为用户信息数据,并且会把openid存入session中
     * @return string 只返回用户的openID
     */
    public static function getOpenidByAuth()
    {
        /*先去取一下session中的存值*/
        $openid = session('openid');
        if(!empty($openid)){
            //如果session中已经有openid,这里不做任何操作.
        }
        /*然后再取一下code,就是授权页面返回的code值*/
        $code = request()->input('code');
        if(empty($code)){
            //处理url地址然后跳过去
            $host = $_SERVER['HTTP_HOST'];//域名
            $path = $_SERVER['REQUEST_URI'];//路由
            $redirect = urlencode("http://".$host.$path);//参数中带有url地址时,专用处理用的.
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::appid."&redirect_uri={$redirect}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            header('location:'.$url);die;
        }else{
            //根据code参数获取token
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".self::appid."&secret=".self::secret."&code={$code}&grant_type=authorization_code";
            $data = Curl::curlGet($url);
            $data = json_decode($data,true);
            $openid = $data['openid'];
            $access_token = $data['access_token'];
            //非静默方式,获取用户详细信息
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
            $data = Curl::curlGet($url);
            $data = json_decode($data,true);//用户信息中包括openid

            session(['openid'=>$openid]);
            return $openid;
        }
    }

    /**
     * 通过微信网页授权的方式获取用户openid和用户信息
     * 返回值为用户信息数据,并且会把openid存入session中
     * @return array 返回用户的消息集合.
     */
    public static function getOpenidDataByAuth()
    {
        /*先去取一下session中的存值*/
        $openid = session('openidData');
        if(!empty($openid)){
            //如果session中已经有openid,这里不做任何操作.
            return $openid;
        }
        /*然后再取一下code,就是授权页面返回的code值*/
        $code = request()->input('code');
        if(empty($code)){
            //处理url地址然后跳过去
            $host = $_SERVER['HTTP_HOST'];//域名
            $path = $_SERVER['REQUEST_URI'];//路由
            $redirect = urlencode("http://".$host.$path);//参数中带有url地址时,专用处理用的.
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::appid."&redirect_uri={$redirect}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            header('location:'.$url);die;
        }else{
            //根据code参数获取token
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".self::appid."&secret=".self::secret."&code={$code}&grant_type=authorization_code";
            $data = Curl::curlGet($url);
            $data = json_decode($data,true);
            $openid = $data['openid'];
            $access_token = $data['access_token'];
            //非静默方式,获取用户详细信息
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
            $data = Curl::curlGet($url);
            $data = json_decode($data,true);//用户信息中包括openid

            session(['openidData'=>$data]);
            return $data;
        }
    }

    /**
     * 获取jsapi_ticket
     * @return mixed
     */
    public static function getJsApiTicket()
    {
        //定义一些需要的变量,逻辑判断
        $ticket = Cache::get('ticket');
        //小逻辑判断
        if(empty($ticket)){
            $access_token = self::getToken();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
            $data = file_get_contents($url);
            $data = json_decode($data,true);
            $ticket = $data["ticket"];
            //框架缓存,存值
            Cache::put('ticket',$ticket,7200);//2个小时过期
        }
        file_put_contents("ticket.txt",$ticket);

        return $ticket;
    }

    /**
     * 获取随机字符串
     * @param int $length
     * @return string
     */
    public static function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}