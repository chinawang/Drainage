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

        $nowResult = json_decode($nowWeather,true);
        $dailyResult = json_decode($dailyWeather,true);

        $updatetime = $nowResult['results'][0]['last_update'];

        $nowResult['results'][0]['last_update'] = date('H:i',strtotime($updatetime));

        $dailyDate1 = $dailyResult['results'][0]['daily'][0]['date'];
        $dailyResult['results'][0]['daily'][0]['date'] = date('n',strtotime($dailyDate1))."月".date('d',strtotime($dailyDate1))."日";

        $dailyDate2 = $dailyResult['results'][0]['daily'][1]['date'];
        $dailyResult['results'][0]['daily'][1]['date'] = date('n',strtotime($dailyDate2))."月".date('d',strtotime($dailyDate2))."日";

        $dailyDate3 = $dailyResult['results'][0]['daily'][2]['date'];
        $dailyResult['results'][0]['daily'][2]['date'] = date('n',strtotime($dailyDate3))."月".date('d',strtotime($dailyDate3))."日";

        $backgroundStyle = null;

        switch ($nowResult['results'][0]['now']['code'])
        {
            case 0:
                $backgroundStyle = "linear-gradient(#2869e9,#79bfff)";
                break;
            case 1:
                $backgroundStyle = "linear-gradient(#2869e9,#79bfff)";
                break;
            case 2:
                $backgroundStyle = "linear-gradient(#2869e9,#79bfff)";
                break;
            case 3:
                $backgroundStyle = "linear-gradient(#2869e9,#79bfff)";
                break;
            case 4:
                $backgroundStyle = "linear-gradient(#6f7c85,#919b9f)";
                break;
            case 5:
                $backgroundStyle = "linear-gradient(#6f7c85,#919b9f)";
                break;
            case 6:
                $backgroundStyle = "linear-gradient(#6f7c85,#919b9f)";
                break;
            case 7:
                $backgroundStyle = "linear-gradient(#6f7c85,#919b9f)";
                break;
            case 8:
                $backgroundStyle = "linear-gradient(#6f7c85,#919b9f)";
                break;
            case 9:
                $backgroundStyle = "linear-gradient(#6f7c85,#919b9f)";
                break;
            case 10:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 10:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 11:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 12:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 13:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 14:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 15:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 16:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 17:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 18:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 19:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 20:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 21:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 22:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 23:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 24:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 25:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 34:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 35:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 36:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            case 37:
                $backgroundStyle = "linear-gradient(#1a242f,#7d939b)";
                break;
            default:
                $backgroundStyle = "linear-gradient(#2869e9,#79bfff)";
        }

        $param = ['nowWeather' => $nowResult['results'][0],'dailyWeather' => $dailyResult['results'][0],'backgroundStyle' => $backgroundStyle];

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
        $api = 'http://api.thinkpage.cn/v3/weather/daily.json'; // 接口地址
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
        $days = 5;

        // 最终构造出可由前端或服务端进行调用的 url
        $url = $api."?location=".$location."&".$signedkeyname."&start=".$start."&days=".$days;

        return $url;
    }

}
