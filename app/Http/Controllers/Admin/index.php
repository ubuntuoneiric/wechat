<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\model\WeChat;

class index extends Controller
{
    /**
     * 后台首页,其中展示天气柱状图
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin/index');
    }

    /**
     * 首页的天气显示
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function weather(Request $request)
    {
        if($request->ajax()){
            $city = request('city');
            /*缓存通用第一步,读缓存,但是这里有个多个缓存区分问题,所以稍微记一下这种模式*/
            $cache_name = "weather_".$city;//这是一种小逻辑,相当于二级...
            $data = Cache::get($cache_name);
            if(empty($data)){
                $data = $this->indexWeather($city);
                /*
                 * 以下是缓存到当天24点就是第二天凌晨的一种小方法
                 * 要获取的是当前时间到凌晨这段时间的时间戳,然后要获取当天凌晨的时间戳和当前的时间戳
                 * date('Y-m-d')===>获得当天默认零点的时间戳,然后date('Y-m-d')+24*60*60
                 */
                $time24 = strtotime(date('Y-m-d'))+86400;
                $second = $time24-time();
                Cache::put($cache_name,$data,$second);
            }

            echo $data;die;
        }

        return view('admin/weather');
    }

    /**
     * @param $city城市名称
     * @return false|mixed|string
     */
    private function indexWeather($city)
    {
        //获取天气状况
        $res = file_get_contents("http://api.k780.com/?app=weather.future&weaid={$city}&&appkey=43608&sign=10b9ad3a80620c860df86e043668cbe8&format=json");
        return $res;
    }
    //token
    public function cleartoken()
    {
        $data = WeChat::getToken();
        dd($data);
    }
}
