@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/rbac/rbac.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>天气</span>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        天气
                    </div>
                    <div class="panel-body custom-panel-body" id="content-weather">
                        <div class="op_weather4_twoicon_container_div">


                            <div class="op_weather4_twoicon" style="height: 404px;">


                                <a class="op_weather4_twoicon_today OP_LOG_LINK" target="_blank"
                                   href="http://www.baidu.com/link?url=ERASCsq1p6D-GMvZQnPIY55u8glI97P3A-jcplHwZX2mOCpCrsKD77L0_zDV4VSIMF4NP_digBPFYYOQ7VpPRa"
                                   data-click="{&quot;url&quot;:&quot;http://www.weather.com.cn/weather/101180801.shtml&quot;}"
                                   weath-bg="daytime" weath-eff="{&quot;halo&quot;:1}">
                                    <p class="op_weather4_twoicon_date">


                                        04月14日 周五 农历三月十八&nbsp;
                                    </p>
                                    <p class="op_weather4_twoicon_date op_weather4_twoicon_pm25">实时空气质量:
                                        <em style="background:#afdb00"><b>90</b>&nbsp;良</em>
                                    </p>


                                    <div class="op_weather4_twoicon_shishi">
                                        <div class="op_weather4_twoicon_icon"></div>

                                        <div class="op_weather4_twoicon_shishi_info">
                                            <span class="op_weather4_twoicon_shishi_title">23</span>
                                            <span class="op_weather4_twoicon_shishi_data">
                                <i class="op_weather4_twoicon_shishi_sup">℃</i>
                                <i class="op_weather4_twoicon_shishi_sub">多云(实时)</i>
                            </span>
                                        </div>


                                    </div>


                                    <p class="op_weather4_twoicon_temp">14 ~ 23℃</p>
                                    <p class="op_weather4_twoicon_weath" title="">

                                        多云转晴

                                    </p>
                                    <p class="op_weather4_twoicon_wind">微风</p>


                                </a>


                                <a class="op_weather4_twoicon_day OP_LOG_LINK" style="left:188px" target="_blank"
                                   href="https://www.baidu.com/link?url=MF3sqBo_GUHkmjthxIk0iOlTWa2ExFF9SNwyZr_BWSHP31Xg8jojeKrW6GgEsBxyi8xW9iQ9eiRivAlgwR9PqK&amp;wd=&amp;eqid=e8698119000336110000000258f054a2"
                                   data-click="{&quot;url&quot;:&quot;http://www.weather.com.cn/weather/101180801.shtml#7d&quot;}"
                                   weath-bg="daytime" weath-eff="{&quot;halo&quot;:1}">


                                    <div class="op_weather4_twoicon_hover"></div>
                                    <div class="op_weather4_twoicon_split"></div>
                                    <p class="op_weather4_twoicon_date">


                                        周六
                                    </p>


                                    <p class="op_weather4_twoicon_date_day">04月15日</p>

                                    <div class="op_weather4_twoicon_icon"></div>

                                    <p class="op_weather4_twoicon_temp">13 ~ 27℃</p>
                                    <p class="op_weather4_twoicon_weath" title="">

                                        多云转中雨

                                    </p>
                                    <p class="op_weather4_twoicon_wind">微风</p>


                                </a>


                                <a class="op_weather4_twoicon_day OP_LOG_LINK" style="left:276px" target="_blank"
                                   href="http://www.baidu.com/link?url=MF3sqBo_GUHkmjthxIk0iOlTWa2ExFF9SNwyZr_BWSHP31Xg8jojeKrW6GgEsBxyi8xW9iQ9eiRivAlgwR9PqK"
                                   data-click="{&quot;url&quot;:&quot;http://www.weather.com.cn/weather/101180801.shtml#7d&quot;}"
                                   weath-bg="cloudy" weath-eff="{&quot;rain&quot;:80}">


                                    <div class="op_weather4_twoicon_hover"></div>
                                    <div class="op_weather4_twoicon_split"></div>
                                    <p class="op_weather4_twoicon_date">


                                        周日
                                    </p>


                                    <p class="op_weather4_twoicon_date_day">04月16日</p>

                                    <div class="op_weather4_twoicon_icon"></div>

                                    <p class="op_weather4_twoicon_temp">13 ~ 17℃</p>
                                    <p class="op_weather4_twoicon_weath" title="">

                                        中雨转晴

                                    </p>
                                    <p class="op_weather4_twoicon_wind">微风</p>


                                </a>


                                <a class="op_weather4_twoicon_day OP_LOG_LINK" style="left:364px" target="_blank"
                                   href="http://www.baidu.com/link?url=MF3sqBo_GUHkmjthxIk0iOlTWa2ExFF9SNwyZr_BWSHP31Xg8jojeKrW6GgEsBxyi8xW9iQ9eiRivAlgwR9PqK"
                                   data-click="{&quot;url&quot;:&quot;http://www.weather.com.cn/weather/101180801.shtml#7d&quot;}"
                                   weath-bg="daytime" weath-eff="{&quot;halo&quot;:1}">


                                    <div class="op_weather4_twoicon_hover"></div>
                                    <div class="op_weather4_twoicon_split"></div>
                                    <p class="op_weather4_twoicon_date">


                                        周一
                                    </p>


                                    <p class="op_weather4_twoicon_date_day">04月17日</p>

                                    <div class="op_weather4_twoicon_icon"></div>

                                    <p class="op_weather4_twoicon_temp">14 ~ 21℃</p>
                                    <p class="op_weather4_twoicon_weath" title="">

                                        晴

                                    </p>
                                    <p class="op_weather4_twoicon_wind">微风</p>


                                </a>


                                <a class="op_weather4_twoicon_day OP_LOG_LINK" style="left:452px" target="_blank"
                                   href="http://www.baidu.com/link?url=MF3sqBo_GUHkmjthxIk0iOlTWa2ExFF9SNwyZr_BWSHP31Xg8jojeKrW6GgEsBxyi8xW9iQ9eiRivAlgwR9PqK"
                                   data-click="{&quot;url&quot;:&quot;http://www.weather.com.cn/weather/101180801.shtml#7d&quot;}"
                                   weath-bg="daytime" weath-eff="{&quot;halo&quot;:1}">


                                    <div class="op_weather4_twoicon_hover"></div>
                                    <div class="op_weather4_twoicon_split"></div>
                                    <p class="op_weather4_twoicon_date">


                                        周二
                                    </p>


                                    <p class="op_weather4_twoicon_date_day">04月18日</p>

                                    <div class="op_weather4_twoicon_icon"></div>

                                    <p class="op_weather4_twoicon_temp">14 ~ 28℃</p>
                                    <p class="op_weather4_twoicon_weath" title="">

                                        晴转阴

                                    </p>
                                    <p class="op_weather4_twoicon_wind">微风</p>


                                </a>


                                <div bg-name="daytime" class="op_weather4_twoicon_bg"
                                     style="background-image: -webkit-linear-gradient(top, rgb(13, 104, 188), rgb(114, 173, 224)); height: 404px;">
                                </div>


                                <div bg-name="cloudy" class="op_weather4_twoicon_bg"
                                     style="display: none; background-image: -webkit-linear-gradient(top, rgb(72, 86, 99), rgb(161, 184, 202)); height: 404px;">
                                </div>
                                <div class="sjs" id="sjs0"
                                     style="overflow: hidden; position: relative; width: 538px; height: 250px; z-index: 10;">
                                    <div id="sjs0-default"
                                         style="z-index: 2; height: 250px; width: 538px; position: absolute; top: 0px; left: 0px;"></div>
                                    <canvas id="sjs0-eff-halo" height="250" width="538"
                                            style="z-index: 1; position: absolute; top: 0px; left: 0px; display: block; opacity: 1;"></canvas>
                                    <canvas id="sjs0-eff-rain" height="250" width="538"
                                            style="z-index: 1; position: absolute; top: 0px; left: 0px; display: none;"></canvas>
                                    <div id="sjs0-light"
                                         style="z-index: 5; height: 250px; width: 538px; position: absolute; top: 0px; left: 0px; opacity: 0; background-color: rgb(255, 255, 255);"></div>
                                </div>
                            </div>


                            <style type="text/css">
                                .op_weather4_twoicon {
                                    height: 282px;
                                }

                                .op_weather4_twoicon_bg {
                                    height: 404px;
                                }

                                .op_weather4_xiala {
                                    width: 100%;
                                    position: absolute;
                                    z-index: 5;
                                    top: 251px;
                                    font-family: '微软雅黑', '黑体', Arial;
                                }

                                .op_weather4_xltab ul li {
                                    width: 78px;
                                    list-style: none;
                                    float: left;
                                    background: rgba(255, 255, 255, 0.2);
                                    filter: progid:DXImageTransform.Microsoft.gradient(GradientType=1, startColorstr='#20ffffff', endColorstr='#20ffffff');
                                    text-align: center;
                                    line-height: 26px;
                                    margin-right: 1px;
                                    font-size: 12px;
                                    cursor: pointer;
                                    display: block;
                                }

                                .op_weather4_xltab ul li.op_weather4_xlactive {
                                    height: 27px;
                                    background: rgba(255, 255, 255, 0.1);
                                    filter: progid:DXImageTransform.Microsoft.gradient(GradientType=1, startColorstr=#10ffffff, endColorstr=#10ffffff);
                                }

                                .op_weather4_xlcon {
                                    background: rgba(255, 255, 255, 0.1);
                                    filter: progid:DXImageTransform.Microsoft.gradient(GradientType=1, startColorstr=#10ffffff, endColorstr=#10ffffff);
                                    height: 114px;
                                }

                                .op_weather4_xlleft {
                                    float: left;
                                    height: 86px;
                                    width: 35px;
                                    font-size: 10px;
                                    color: #fff;
                                    position: absolute;
                                    top: 0px;
                                    left: 0px;
                                    -webkit-transform: scale(0.8);
                                    -moz-transform: scale(0.8);
                                    -o-transform: scale(0.8);
                                }

                                .op_weather4_xlleft ul li {
                                    padding-top: 4px;
                                    padding-top: 2px \9;
                                    list-style: none;
                                    text-align: right;
                                }

                                .op_weather4_xltimeul {
                                    clear: both;
                                    font-size: 12px;
                                    padding-left: 42px;
                                    padding-top: 2px \9;
                                }

                                .op_weather4_xltimeul ul {
                                    width: 520px;
                                }

                                .op_weather4_xltimeul ul li {
                                    float: left;
                                    list-style: none;
                                    width: 56px;
                                    text-align: left;
                                    color: #fff;
                                }

                                .op_weather4_xltimeul-s ul li {
                                    float: left;
                                    list-style: none;
                                    width: 56px;
                                    text-align: left;
                                    color: #fff;
                                }

                                .op_weather4_xltimeul-s ul {
                                    width: 520px;
                                }

                                .op_weather4_yuandian {
                                    background: url('//www.baidu.com/aladdin/img/new_weath/ico.png') no-repeat;
                                }

                                .op_weather4_xllefts {
                                    font-size: 10px;
                                    -webkit-transform: scale(0.8);
                                    -moz-transform: scale(0.8);
                                    -o-transform: scale(0.8);
                                    position: absolute;
                                    top: -14px;;
                                    left: -11px;
                                    color: #fff;
                                    width: 40px;
                                    text-align: center;
                                }

                                .op_weather4_xlqyyc {
                                    float: left;
                                    width: 40px;
                                    height: 106px;
                                    position: absolute;
                                    top: 0;
                                    cursor: pointer;
                                    z-index: 9;
                                }

                                .op_weather4_xltip {
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    z-index: 10;
                                    font-size: 12px;
                                }

                                .op_weather4_xlico {
                                    position: absolute;
                                    width: 10px;
                                    height: 10px;
                                    border-color: transparent transparent white transparent;
                                    border-style: solid;
                                    border-width: 0 5px 5px 5px;
                                    width: 0;
                                    height: 0;
                                    top: 1px;
                                    z-index: 2;
                                }

                                .op_weather4_xlcont {
                                    background: #fff;
                                    margin-top: 5px;
                                    color: #555;
                                    padding: 10px;
                                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                                }

                                .op_weather4_jsl {
                                    width: 20px;
                                    height: 114px;
                                    float: left;
                                }

                                .op_weather4_jsm {
                                    width: 498px;
                                    height: 84px;
                                    float: left;
                                    margin-top: 15px;
                                    font-size: 12px;
                                }

                                .op_weather4_jsm ul li {
                                    width: 55px;
                                    float: left;
                                    background: url('//www.baidu.com/aladdin/img/new_weath/icobg.png') no-repeat right 0;
                                }

                                .op_weather4_jsm span {
                                    display: block;
                                    text-align: center;
                                }

                                .op_weather4_jsr {
                                    width: 20px;
                                    height: 114px;
                                    float: left;
                                }

                                .op_weather4_jsm ul li .op_weather4_jsico {
                                    height: 24px;
                                    padding: 13px 0 13px 18px;
                                }

                                .op_weather4_jsiconborw0, .op_weather4_jsiconborw1, .op_weather4_jsiconborw2, .op_weather4_jsiconborw3, .op_weather4_jsiconborw4, .op_weather4_jsiconborw5, .op_weather4_jsiconborw6, .op_weather4_jsiconborw7, .op_weather4_jsiconborw8, .op_weather4_jsiconborw9, .op_weather4_jsiconborw10 {
                                    width: 18px;
                                    height: 23px;
                                    background: url('//www.baidu.com/aladdin/img/new_weath/icowater.png') no-repeat;
                                }

                                .op_weather4_jsiconborw0 {
                                    background-position: 0 0px;
                                }

                                .op_weather4_jsiconborw1 {
                                    background-position: 0 -33px;
                                }

                                .op_weather4_jsiconborw2 {
                                    background-position: 0 -66px;
                                }

                                .op_weather4_jsiconborw3 {
                                    background-position: 0 -99px;
                                }

                                .op_weather4_jsiconborw4 {
                                    background-position: 0 -132px;
                                }

                                .op_weather4_jsiconborw5 {
                                    background-position: 0 -165px;
                                }

                                .op_weather4_jsiconborw6 {
                                    background-position: 0 -198px;
                                }

                                .op_weather4_jsiconborw7 {
                                    background-position: 0 -231px;
                                }

                                .op_weather4_jsiconborw8 {
                                    background-position: 0 -264px;
                                }

                                .op_weather4_jsiconborw9 {
                                    background-position: 0 -297px;
                                }

                                .op_weather4_jsiconborw10 {
                                    background-position: 0 -330px;
                                }

                                .op_weather4_xlzs {
                                    width: 240px;
                                    float: left;
                                    margin-left: 17px;
                                    border-bottom: 1px solid #ddd;
                                    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                                    line-height: 32px;
                                    height: 32px;
                                    cursor: pointer;
                                }

                                .op_weather4_xlzstit {
                                    font-size: 16px;
                                }

                                .op_weather4_xlzstitdes {
                                    font-size: 12px;
                                }

                                .op_weather4_xlbornone {
                                    border-bottom: 0;
                                }

                                .op_weather4_xltop {
                                    padding-top: 8px;
                                    clear: both;
                                }

                                .op_weather4_xltiptitle {
                                    font-size: 14px;
                                    font-weight: bold;
                                    line-height: 24px;
                                }

                                .op_weather4_xltipcontent {
                                    line-height: 20px;
                                }

                                .op_weather4_xlopen {
                                    width: 100%;
                                    height: 29px;
                                    background: rgba(0, 0, 0, 0.1);
                                    filter: progid:DXImageTransform.Microsoft.gradient(GradientType=1, startColorstr=#10000000, endColorstr=#10000000);
                                    cursor: pointer;
                                    text-align: center;
                                    z-index: 6;
                                    padding-top: 3px;
                                }

                                .op_weather4_tipbtn {
                                    display: inline-block;
                                    padding: 2px 0;
                                    cursor: pointer;
                                }

                                .op_weather4_tipbtn span {
                                    display: inline-block;
                                }

                                .op_weather4_icobottom {
                                    background: url('//www.baidu.com/aladdin/img/new_weath/ico.png') 0 -188px no-repeat;
                                    width: 27px;
                                    height: 11px;
                                    display: inline-block;
                                }

                                .op_weather4_xlclose {
                                    height: 32px;
                                    cursor: pointer;
                                    text-align: center;
                                    z-index: 6;
                                }

                                .op_weather4_xlclose .op_weather4_icobottom {
                                    background: url('//www.baidu.com/aladdin/img/new_weath/ico.png') 0 -212px no-repeat;
                                }

                                .op_weather4_xlCanvascon1, .op_weather4_xlCanvascon2 {
                                    position: relative;
                                    margin-left: 36px;
                                }

                                .op_weather4_jslNovalue {
                                    background: none;
                                    padding-top: 4px;
                                }

                                .op_weather4_xltab ul .op_weather4_temperature {
                                    width: 106px;
                                }
                            </style>


                            <div class="op_weather4_xiala">
                                <div class="op_weather4_xltabcontent" style="display: block;">
                                    <a href="http://www.baidu.com/link?url=zegxCRkhkEY8cMQ3Fq3jBid_Q0n4pXiptRZqyZSXmRac2yOih3NnI_cidmSHUYsTk1hB9vcX20cn460eqR48Z0YcOqBHiolHvchZ_RRbtX3"
                                       target="_blank" style="color:#ffffff; text-decoration: none;">
                                        <div class="op_weather4_xlcon" style="">
                                            <div class="c-row">
                                                <div class="op_weather4_xlleft">
                                                    <ul class="op_weather4_xlleftfswd">
                                                        <li>33</li>
                                                        <li>27</li>
                                                        <li>21</li>
                                                        <li>15</li>
                                                        <li>9</li>
                                                    </ul>
                                                </div>
                                                <div style="float:left;">
                                                    <div class="op_weather4_xlCanvascon1">
                                                        <canvas class="op_weather4_xlCanvas1" width="480"
                                                                height="86"></canvas>
                                                        <div class="op_weather4_xlqyyc" style="left: 0px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 14px; top: 36.5px; background-position: 0px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; left: 0px; top: 25px;">23°C
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 56px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 14px; top: 42.5px; background-position: 0px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; left: 0px; top: 31px;">21°C
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 112px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 41px; background-position: 0px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; left: 0px; top: 28px;">22°C
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 168px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 53px; background-position: 0px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; left: 0px; top: 40px;">18°C
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 224px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 14px; top: 51.5px; background-position: 0px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; left: 0px; top: 40px;">18°C
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 280px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 59px; background-position: 0px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; left: 0px; top: 46px;">16°C
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 336px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 64px; background-position: 0px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; left: 0px; top: 51px;">14°C
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 392px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 59px; background-position: 0px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; left: 0px; top: 46px;">16°C
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 448px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 32px; background-position: 0px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; left: 0px; top: 19px;">25°C
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="op_weather4_xltimeul op_weather4_xltimeul-s">
                                                <ul>
                                                    <li class="op_weather4_xltimeulscal">现在</li>
                                                    <li class="op_weather4_xltimeulscal">14点</li>
                                                    <li class="op_weather4_xltimeulscal">17点</li>
                                                    <li class="op_weather4_xltimeulscal">20点</li>
                                                    <li class="op_weather4_xltimeulscal">23点</li>
                                                    <li class="op_weather4_xltimeulscal">02点</li>
                                                    <li class="op_weather4_xltimeulscal">05点</li>
                                                    <li class="op_weather4_xltimeulscal">08点</li>
                                                    <li class="op_weather4_xltimeulscal">11点</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="op_weather4_xlcon" style="display: none;">
                                            <div class="c-row">
                                                <div class="op_weather4_xlleft">
                                                    <ul class="op_weather4_xlleftflfx">
                                                        <li>8</li>
                                                        <li>6</li>
                                                        <li>4</li>
                                                        <li>2</li>
                                                        <li>0</li>
                                                    </ul>
                                                </div>
                                                <div style="float:left;">
                                                    <div class="op_weather4_xlCanvascon2">
                                                        <canvas class="op_weather4_xlCanvas2" width="480"
                                                                height="86"></canvas>
                                                        <div class="op_weather4_xlqyyc" style="left: 0px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 61px; background-position: 0px -127px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; width: 42px; left: 0px; top: 48px;"></div>
                                                            <div class="op_weather4_xltip"
                                                                 style="width: 100px; top: 108px; left: -30px; z-index: 1000; display: none;">
                                                                <span class="op_weather4_xlico"
                                                                      style="left: 45px;"></span>
                                                                <div class="op_weather4_xlcont"><p>风向：西南风</p>
                                                                    <p>风力：2级</p></div>
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 56px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 79px; background-position: 2px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; width: 42px; left: 0px; top: 66px;"></div>
                                                            <div class="op_weather4_xltip"
                                                                 style="width: 100px; top: 108px; left: -30px; z-index: 1000; display: none;">
                                                                <span class="op_weather4_xlico"
                                                                      style="left: 45px;"></span>
                                                                <div class="op_weather4_xlcont"><p>风向：无</p>
                                                                    <p>风力：微风</p></div>
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 112px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 79px; background-position: 2px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; width: 42px; left: 0px; top: 66px;"></div>
                                                            <div class="op_weather4_xltip"
                                                                 style="width: 100px; top: 108px; left: -30px; z-index: 1000; display: none;">
                                                                <span class="op_weather4_xlico"
                                                                      style="left: 45px;"></span>
                                                                <div class="op_weather4_xlcont"><p>风向：无</p>
                                                                    <p>风力：微风</p></div>
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 168px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 79px; background-position: 2px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; width: 42px; left: 0px; top: 66px;"></div>
                                                            <div class="op_weather4_xltip"
                                                                 style="width: 100px; top: 108px; left: -30px; z-index: 1000; display: none;">
                                                                <span class="op_weather4_xlico"
                                                                      style="left: 45px;"></span>
                                                                <div class="op_weather4_xlcont"><p>风向：无</p>
                                                                    <p>风力：微风</p></div>
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 224px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 79px; background-position: 2px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; width: 42px; left: 0px; top: 66px;"></div>
                                                            <div class="op_weather4_xltip"
                                                                 style="width: 100px; top: 108px; left: -30px; z-index: 1000; display: none;">
                                                                <span class="op_weather4_xlico"
                                                                      style="left: 45px;"></span>
                                                                <div class="op_weather4_xlcont"><p>风向：无</p>
                                                                    <p>风力：微风</p></div>
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 280px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 79px; background-position: 2px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; width: 42px; left: 0px; top: 66px;"></div>
                                                            <div class="op_weather4_xltip"
                                                                 style="width: 100px; top: 108px; left: -30px; z-index: 1000; display: none;">
                                                                <span class="op_weather4_xlico"
                                                                      style="left: 45px;"></span>
                                                                <div class="op_weather4_xlcont"><p>风向：无</p>
                                                                    <p>风力：微风</p></div>
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 336px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 79px; background-position: 2px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; width: 42px; left: 0px; top: 66px;"></div>
                                                            <div class="op_weather4_xltip"
                                                                 style="width: 100px; top: 108px; left: -30px; z-index: 1000; display: none;">
                                                                <span class="op_weather4_xlico"
                                                                      style="left: 45px;"></span>
                                                                <div class="op_weather4_xlcont"><p>风向：无</p>
                                                                    <p>风力：微风</p></div>
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 392px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 79px; background-position: 2px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; width: 42px; left: 0px; top: 66px;"></div>
                                                            <div class="op_weather4_xltip"
                                                                 style="width: 100px; top: 108px; left: -30px; z-index: 1000; display: none;">
                                                                <span class="op_weather4_xlico"
                                                                      style="left: 45px;"></span>
                                                                <div class="op_weather4_xlcont"><p>风向：无</p>
                                                                    <p>风力：微风</p></div>
                                                            </div>
                                                        </div>
                                                        <div class="op_weather4_xlqyyc"
                                                             style="left: 448px; width: 56px;">
                                                            <div class="op_weather4_yuandian"
                                                                 style="position: absolute; width: 14px; height: 14px; left: 13px; top: 79px; background-position: 2px 3px;"></div>
                                                            <div class="op_weather4_xllefts"
                                                                 style="position: absolute; width: 42px; left: 0px; top: 66px;"></div>
                                                            <div class="op_weather4_xltip"
                                                                 style="width: 100px; top: 108px; left: -30px; z-index: 1000; display: none;">
                                                                <span class="op_weather4_xlico"
                                                                      style="left: 45px;"></span>
                                                                <div class="op_weather4_xlcont"><p>风向：无</p>
                                                                    <p>风力：微风</p></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="op_weather4_xltimeul">
                                                <ul>
                                                    <li>现在</li>
                                                    <li>14点</li>
                                                    <li>17点</li>
                                                    <li>20点</li>
                                                    <li>23点</li>
                                                    <li>02点</li>
                                                    <li>05点</li>
                                                    <li>08点</li>
                                                    <li>11点</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="op_weather4_xlcon" style="display:none;">
                                            <div class="op_weather4_jsl"></div>
                                            <div class="op_weather4_jsm">
                                                <ul class="op_weather4_jslul">
                                                    <li><span>现在</span>
                                                        <div class="op_weather4_jsico"><span
                                                                    class="op_weather4_jsiconborw0"></span></div>
                                                        <span class="op_weather4_jsml">0mm</span></li>
                                                    <li><span>14点</span>
                                                        <div class="op_weather4_jsico"><span
                                                                    class="op_weather4_jsiconborw0"></span></div>
                                                        <span class="op_weather4_jsml">0mm</span></li>
                                                    <li><span>17点</span>
                                                        <div class="op_weather4_jsico"><span
                                                                    class="op_weather4_jsiconborw0"></span></div>
                                                        <span class="op_weather4_jsml">0mm</span></li>
                                                    <li><span>20点</span>
                                                        <div class="op_weather4_jsico"><span
                                                                    class="op_weather4_jsiconborw0"></span></div>
                                                        <span class="op_weather4_jsml">0mm</span></li>
                                                    <li><span>23点</span>
                                                        <div class="op_weather4_jsico"><span
                                                                    class="op_weather4_jsiconborw0"></span></div>
                                                        <span class="op_weather4_jsml">0mm</span></li>
                                                    <li><span>02点</span>
                                                        <div class="op_weather4_jsico"><span
                                                                    class="op_weather4_jsiconborw0"></span></div>
                                                        <span class="op_weather4_jsml">0mm</span></li>
                                                    <li><span>05点</span>
                                                        <div class="op_weather4_jsico"><span
                                                                    class="op_weather4_jsiconborw0"></span></div>
                                                        <span class="op_weather4_jsml">0mm</span></li>
                                                    <li><span>08点</span>
                                                        <div class="op_weather4_jsico"><span
                                                                    class="op_weather4_jsiconborw0"></span></div>
                                                        <span class="op_weather4_jsml">0mm</span></li>
                                                    <li><span>11点</span>
                                                        <div class="op_weather4_jsico"><span
                                                                    class="op_weather4_jsiconborw0"></span></div>
                                                        <span class="op_weather4_jsml">0mm</span></li>
                                                </ul>
                                            </div>
                                            <div class="op_weather4_jsr"></div>
                                        </div>
                                        <div class="op_weather4_xlcon" style="display:none;">
                                            <ul class="op_weather4_xltop c-clearfix">

                                            </ul>
                                        </div>
                                    </a>
                                    <div class="op_weather4_xltab c-clearfix">
                                        <ul>
                                            <li class="op_weather4_xlactive OP_LOG_BTN op_weather4_temperature"
                                                data-click="{fm:'beha'}"><span
                                                        class="op_weather4_xlfilter">24小时温度</span></li>
                                            <li class=" OP_LOG_BTN" data-click="{fm:'beha'}"><span
                                                        class="op_weather4_xlfilter">风力风向</span></li>
                                            <li class=" OP_LOG_BTN" data-click="{fm:'beha'}"><span
                                                        class="op_weather4_xlfilter">降水量</span></li>
                                            <li class=" OP_LOG_BTN" data-click="{fm:'beha'}"><span
                                                        class="op_weather4_xlfilter">相关指数</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="op_weather4_xlopen OP_LOG_BTN" data-click="{fm:'beha'}"
                                     style="display:none;">
                                    <div class="op_weather4_xlfilter">
                <span class="op_weather4_tipbtn"><span>查看今天详细数据</span>
                    <i class="op_weather4_icobottom"></i>
                </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

    {{--<script>(function (T, h, i, n, k, P, a, g, e) {--}}
    {{--g = function () {--}}
    {{--P = h.createElement(i);--}}
    {{--a = h.getElementsByTagName(i)[0];--}}
    {{--P.src = k;--}}
    {{--P.charset = "utf-8";--}}
    {{--P.async = 1;--}}
    {{--a.parentNode.insertBefore(P, a)--}}
    {{--};--}}
    {{--T["ThinkPageWeatherWidgetObject"] = n;--}}
    {{--T[n] || (T[n] = function () {--}}
    {{--(T[n].q = T[n].q || []).push(arguments)--}}
    {{--});--}}
    {{--T[n].l = +new Date();--}}
    {{--if (T.attachEvent) {--}}
    {{--T.attachEvent("onload", g)--}}
    {{--} else {--}}
    {{--T.addEventListener("load", g, false)--}}
    {{--}--}}
    {{--}(window, document, "script", "tpwidget", "//widget.seniverse.com/widget/chameleon.js"))--}}
    {{--</script>--}}
    {{--<script>--}}
    {{--tpwidget("init", {--}}
    {{--"flavor": "bubble",--}}
    {{--"location": "WW0V9QP93VS8",--}}
    {{--"geolocation": "disabled",--}}
    {{--"position": "top-left",--}}
    {{--"margin": "200px 300px",--}}
    {{--"language": "zh-chs",--}}
    {{--"unit": "c",--}}
    {{--"theme": "chameleon",--}}
    {{--"uid": "U4954D65B6",--}}
    {{--"hash": "53726de14b08605c08ed73005e8c9109"--}}
    {{--});--}}
    {{--tpwidget("show");--}}
    {{--</script>--}}



    {{--<script type="text/javascript">--}}

        {{--var url = "{!! $weatherURL !!}";--}}

        {{--$.ajax({--}}
            {{--url: url,--}}
            {{--type: 'GET',--}}
            {{--dataType: 'JSONP',--}}
            {{--success: function (data) {--}}
                {{--var obj = document.getElementById('content-weather');--}}
                {{--var weather = data.results[0]--}}
                {{--var text = [];--}}
                {{--text.push("Location: " + weather.location.name);--}}
                {{--text.push("Weather: " + weather.now.text);--}}
                {{--text.push("Temperature: " + weather.now.temperature);--}}

                {{--obj.innerText = text.join("\n")--}}
            {{--}--}}
        {{--});--}}

    {{--</script>--}}

@endsection
