<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DbModel\User;
use App\DbModel\Media;
use App\DbModel\Channel;
use App\DbModel\Reply;
use App\DbModel\Love;
use App\DbModel\Question;
use App\DbModel\User_answer;
use App\model\WeChat as wc;
use DB;
use Illuminate\Support\Facades\Cache;

class wechat extends Controller
{
    protected $nameArr = array('刘德华','郭富城','黎明','张学友','贾伟玲');
	/**
     * [index 微信开发主业务流程]
     * @return [type] [接入时有返回值,别的时间貌似没有]
     */
    public function index()
    {
        /*微信接入以及验证*/
        wc::insert();
        /*接受微信发送过来的xml数据,并且写入text文件记录下来*/
        $xml = file_get_contents("php://input");
        file_put_contents("xml.txt",$xml);
        /*将接受过来的xml数据转化成对象*/
        $xmlObj = simplexml_load_string($xml);

        /*用户关注或取消关注处理或者自定义菜单的点击时间*/
        if($xmlObj->MsgType == "event" && $xmlObj->Event == "subscribe"){
            /*设置首次关注的回复*/
            $this->doSubscribe($xmlObj);
            /*设置消息回复类型*/
            $this->doReply($xmlObj);
        }elseif($xmlObj->MsgType == "event" && $xmlObj->Event == "unsubscribe"){
            $this->doUnSubscribe($xmlObj);
        }elseif($xmlObj->MsgType == "event" && $xmlObj->Event == "CLICK"){
            if($xmlObj->EventKey == "123"){
                /*从题库随机获取题目*/
                $this->question($xmlObj);
            }elseif($xmlObj->EventKey == "109"){
                /*查看我的成绩*/
                $this->look($xmlObj);
            }elseif($xmlObj->EventKey == "200"){
                /*查人品*/
                $msg = "查人品请输入:人品#姓名";
                wc::replyTxt($xmlObj,$msg);
            }elseif($xmlObj->EventKey == "307"){
                $this->seelove($xmlObj);
            }
        }
        /*用户留言处理*/
        if($xmlObj->MsgType == "text"){
            $this->doText($xmlObj);
        }
    }
     /*================================================================================================================================================================*/
    /**
     * 处理用户留言
     * @param  [type] $xmlObj [微信服务器发送的xml数据对象]
     * @return [type]         [description]
     */
    public function doText($xmlObj)
    {
        //用户留言处理
        $content = trim($xmlObj->Content);
        //判断
        if(mb_strpos($content,"天气") !== false){

            $msg = wc::getWeather($content);
            wc::replyTxt($xmlObj,$msg);

        }elseif($content == "新闻"){

            $title = "今日头条";
            $description = "百度.com,百度.com";
            $picurl = "https://www.baidu.com/img/bd_logo1.png";
            $url = "www.baidu.com";
            wc::getImageText($xmlObj,$title,$description,$picurl,$url);

        }elseif(mb_strpos($content,"建议") !== false){

            User::updateContentByOpenid($xmlObj->FromUserName,$content);
            $msg = "建议已收到";
            wc::replyTxt($xmlObj,$msg);

        }elseif($content == "1"){

            $msg = implode(',',$this->nameArr);
            wc::replyTxt($xmlObj,$msg);

        }elseif($content == "2"){

            $rand = array_rand($this->nameArr);
            $msg = $this->nameArr[$rand];
            wc::replyTxt($xmlObj,$msg);

        }elseif($content == "斗图"){

            $media_id = Media::selectRandImage();
            wc::replyImage($xmlObj,$media_id);

        }elseif($content == "A"){
            /*用户答题回复处理*/
            $this->answer($xmlObj,"A");
        }elseif($content == "B"){
            /*用户答题回复处理*/
            $this->answer($xmlObj,"B");
        }elseif(mb_strpos($content,"人品") !== false){
            /*回复人品值*/
            $length = mb_strpos($content,'#');
            $name = mb_substr($content,$length+1);
            $person = wc::getCharacter($content);
            $msg = "姓名:".$name."\n"."人品:".$person."\n"."您的人品很棒奥!";
            wc::replyTxt($xmlObj,$msg);
        }else{

            $msg = "已收到,欢迎来到乐柠";
            wc::replyTxt($xmlObj,$msg);

        }
    }
    /**
     * 处理用户关注
     * @param  [type] $xmlObj [微信发送的xml数据对象]
     * @return [type]         [description]
     */
    public function doSubscribe($xmlObj)
    {
        /*获取用户信息*/
        $user = wc::getUser($xmlObj);
        /*通过二维码关注增加数量*/
        $channel = 1;//通过其他渠道关注的用户默认是1
        if($xmlObj->EventKey != ""){
            $channel = substr($xmlObj->EventKey,8);//获取二维码的标识
        }
        DB::table('channel')->where('c_cation',$channel)->increment('num');//递增关注数
        //添加用户信息进用户表
        $openid = $user['openid'];
        $res = DB::table('user')->where('open_id',$openid)->first();
        if(empty($res)){
                 DB::table('user')->insert([
                'name'=>$user['nickname'],
                'open_id'=>$user['openid'],
                'sex'=>$user['sex'],
                'channel'=>$channel,
                'is_subscribe'=>$user['subscribe'],
                'create_time'=>time()
            ]);
        }else{
            DB::table('user')->where('open_id',$openid)->update(['is_subscribe'=>1,'channel'=>$channel]);
        }
    }

    /**
     * 取消关注
     * @param $xmlObj
     * @return mixed
     */
    public function doUnSubscribe($xmlObj)
    {
        $user = wc::getUser($xmlObj);
        $openid = $user['openid'];
        $data = User::selectByOpenid($openid);
        if($data['channel'] != 1){
            Channel::channelDecrement($data['channel']);
        }
        DB::table('user')->where('open_id',$user['openid'])->update(['is_subscribe'=>2]);
    }

    /**
     * 从消息回复表获取首次关注回复的消息类型及内容
     * @param  [type] $xmlObj [微信发送的xml数据]
     * @return [type]         [description]
     */
    public function doReply($xmlObj)
    {
        $data = Reply::orderBy('id','desc')->first()->toArray();
        $content = json_decode($data['content'],true);
        /*其中1代表文本回复2代表图片回复*/
        if($content['type'] == 1){
            $msg = $content['content'];
            wc::replyTxt($xmlObj,$msg);
        }else{
            $media_id = $content['media_id'];
            wc::replyImage($xmlObj,$media_id);
        }
    }

    /**
     * 脚本程序:用于更新粉丝用户信息
     * @return [type] [没有返回值]
     */
    public function getFansList()
    {
        echo "注意:不要随便用奥!用之前先清理数据库,用完一次再die一下";die;
        $data = wc::getUserList();
        $openidList = $data['data']['openid'];
        foreach($openidList as $v){
            $res = DB::table('fans')->where('openid',$v)->first();
            $user = wc::eachGetUser($v);
            if(empty($res)){
                DB::table('fans')->insert([
                    'openid'=>$user['openid'],
                    'nickname'=>$user['nickname'],
                    'city'=>$user['city'],
                    'subscribe_time'=>$user['subscribe_time'],
                    'headimgurl'=>$user['headimgurl'],
                    'sex'=>$user['sex']
                ]);
            }
        }
        echo "ok";
    }

    /**
     * Linux系统的自动任务测试
     * @return [type] [自动调用]
     */
    public function sendMessage()
    {
        $content = "http://haiwanlvzhu.cn/money"."前往领取红包===>";
        $postData = [
            'filter'=>['is_to_all'=>true, 'tag_id'=>''],
            'text'=>['content'=>$content],
            'msgtype'=>'text'
        ];
        $res = wc::massMessage($postData);
        if($res['errcode'] == 0){
            file_put_contents("/data/wwwroot/default/job.txt","自动写入成功 \n",FILE_APPEND);
        }else{
            file_put_contents("/data/wwwroot/default/job.txt","自动写入失败，错误号:".$res['errcode']."。 \n",FILE_APPEND);
        }
    }

    /**
     * 点击答题的点击处理
     * @param $xmlObj
     */
    public function question($xmlObj)
    {
        $user = wc::getUser($xmlObj);
        $data = Question::inRandomOrder()->first()->toArray();
        Cache::put('question',$data,3600);
        $res = User_answer::where('qid',$data['id'])->where('openid',$user['openid'])->first();
        if(empty($res)){
            DB::table('user_answer')->insert([
                'qid'=>$data['id'],
                'openid'=>$user['openid'],
            ]);
        }
        $msg = $data['title']."\n"."A:".$data['A']."B:".$data['B'];
        wc::replyTxt($xmlObj,$msg);
    }

    /**
     * 用户答题
     * @param $xmlObj
     */
    public function answer($xmlObj,$AB)
    {
        $user = wc::getUser($xmlObj);
        $question = Cache::get('question');;//题库里问题的数据

        $res = DB::table('user_answer')->where('openid',$user['openid'])->orderBy('id','desc')->first();
        if(empty($res->answer)&&empty($res->is_right)){
            $ii = 0;
            if($question['right'] == $AB){
                $ii = 1;
                $msg = "恭喜你回答正确!";
                wc::replyTxt($xmlObj,$msg);
            }else{
                $ii = 2;
                $msg = "很遗憾回答错误!";
                wc::replyTxt($xmlObj,$msg);
            }
            DB::table('user_answer')->where('openid',$user['openid'])->where('qid',$question['id'])->update([
                'answer'=>$AB,
                'is_right'=>$ii,
            ]);

        }else{
            $msg = "该题您已经回答过了!";
            wc::replyTxt($xmlObj,$msg);
        }
    }

    /**
     * 查看成绩
     * @param $xmlObj
     */
    public function look($xmlObj)
    {
        $data = DB::table('user_answer')->get()->toArray();
        $user = wc::getUser($xmlObj);
        $name = $user['nickname'];
        if($user['sex'] == 1){
            $sex = "先生";
        }else{
            $sex = "女士";
        }
        $count1 = DB::table('user_answer')->where(['is_right'=>"1",'openid'=>$user['openid']])->count();
        $count2 = DB::table('user_answer')->where(['is_right'=>"2",'openid'=>$user['openid']])->count();
        $msg = "您好".$name.$sex."!您共答对:".$count1."道题。答错".$count2."道题。";
        wc::replyTxt($xmlObj,$msg);
    }

    public function seelove($xmlObj)
    {
        $openid = $xmlObj->FromUserName;
        $data = Love::where('openid',$openid)->get()->toArray();
        if(empty($data)){
            $msg = "对不起,您还没有被表白.";
            wc::replyTxt($xmlObj,$msg);
        }
       
        $msg = '';
        foreach ($data as $k => $v) {
            if($v['is_name'] == 1){
                $msg .= "昵称:".$v['name']."||||||表白内容:".$v['content']."\n";
            }else{
                $msg .= "表白内容:".$v['content']."\n";
            }   
        }
        wc::replyTxt($xmlObj,$msg);        
    }
}