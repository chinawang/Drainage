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
        $url = $this->getWeatherURL();
        $param = ['weatherURL' => $url];

//        return $url;
        $weatherURL = parse_url($url);
        return $weatherURL;
        $weatherData = file_get_contents($weatherURL);
        return $weatherData;

        return view('weather.weather',$param);
    }

    public function getWeatherURL()
    {
        // 心知天气接口调用凭据
        $key = 'rybl3izena2a0lug'; // 测试用 key，请更换成您自己的 Key
        $uid = 'U4954D65B6'; // 测试用 用户ID，请更换成您自己的用户ID
        // 参数
        $api = 'http://api.thinkpage.cn/v3/weather/now.json'; // 接口地址
        $location = '郑州'; // 城市名称。除拼音外，还可以使用 v3 id、汉语等形式
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
}
