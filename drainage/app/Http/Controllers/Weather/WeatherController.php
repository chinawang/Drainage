<?php

namespace App\Http\Controllers\Weather;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeatherController extends Controller
{
    /**
     * WeatherController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showWeather()
    {
        $nowURL = $this->getNowURL();
        $nowWeather = $this->getNowWeather($nowURL);

        $dailyURL = $this->getDailyURL();
        $dailyWeather =$this->getDailyWeather($dailyURL);

        $param = ['nowWeather' => $nowWeather,'dailyWeather' => $dailyWeather];

        return $nowWeather;



        return view('weather.weather',$param);
    }

    public function getNowWeather($url)
    {
        // 1. 初始化
        $ch = curl_init();
        // 2. 设置选项，包括URL
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        if($output === FALSE ){
            echo "CURL Error:".curl_error($ch);
        }
        // 4. 释放curl句柄
        curl_close($ch);


        return $output;
    }

    public function getDailyWeather($url)
    {
        // 1. 初始化
        $ch = curl_init();
        // 2. 设置选项，包括URL
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        if($output === FALSE ){
            echo "CURL Error:".curl_error($ch);
        }
        // 4. 释放curl句柄
        curl_close($ch);


        return $output;
    }

    public function getNowURL()
    {
        // 心知天气接口调用凭据
        $key = 'rybl3izena2a0lug'; // 测试用 key，请更换成您自己的 Key
        $uid = 'U4954D65B6'; // 测试用 用户ID，请更换成您自己的用户ID
        // 参数
        $api = 'http://api.thinkpage.cn/v3/weather/now.json'; // 接口地址
        $location = 'zhengzhou'; // 城市名称。除拼音外，还可以使用 v3 id、汉语等形式
        // 获取当前时间戳，并构造验证参数字符串
        $keyname = "ts=".time()."&ttl=300&uid=".$uid;

        // 使用 HMAC-SHA1 方式，以 API 密钥（key）对上一步生成的参数字符串（raw）进行加密
        $sig = base64_encode(hash_hmac('sha1', $keyname, $key, true));

        // 将上一步生成的加密结果用 base64 编码，并做一个 urlencode，得到签名sig
        $signedkeyname = $keyname."&sig=".urlencode($sig);
        // 最终构造出可由前端或服务端进行调用的 url
        $url = $api."?location=".$location."&".$signedkeyname;

        return $url;
    }

    public function getDailyURL()
    {
        // 心知天气接口调用凭据
        $key = 'rybl3izena2a0lug'; // 测试用 key，请更换成您自己的 Key
        $uid = 'U4954D65B6'; // 测试用 用户ID，请更换成您自己的用户ID
        // 参数
        $api = 'http://api.thinkpage.cn/v3/weather/now.json'; // 接口地址
        $location = 'zhengzhou'; // 城市名称。除拼音外，还可以使用 v3 id、汉语等形式
        // 获取当前时间戳，并构造验证参数字符串
        $keyname = "ts=".time()."&ttl=300&uid=".$uid;

        // 使用 HMAC-SHA1 方式，以 API 密钥（key）对上一步生成的参数字符串（raw）进行加密
        $sig = base64_encode(hash_hmac('sha1', $keyname, $key, true));

        // 将上一步生成的加密结果用 base64 编码，并做一个 urlencode，得到签名sig
        $signedkeyname = $keyname."&sig=".urlencode($sig);

        // 开始日期。0=今天天气
        $start = 0;

        // 查询天数，1=只查一天
        $days = 3;

        // 最终构造出可由前端或服务端进行调用的 url
        $url = $api."?location=".$location."&".$signedkeyname."&start=".$start."&days=".$days;

        return $url;
    }
}
