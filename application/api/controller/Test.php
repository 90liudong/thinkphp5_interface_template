<?php
/**
 * Created by PhpStorm.
 * User: liudong
 * Date: 2018/11/28
 * Time: 10:06
 */
namespace app\api\controller;

use app\common\lib\Aes;
use app\common\lib\IAuth;
use app\common\lib\SendCode;
use app\common\lib\Time;
use think\Controller;

class Test extends Controller {
    //获取手机验证码
//    function getCode(){
//        $code = (new SendCode())->sendSMS('15914905652');
//        return $code;
//    }
    //获取token
    function token(){
        $str = IAuth::setAppLoginToken();
        echo $str;
    }
    //仿前端生成唯一的sign
    function qdcreatesign(){
        $data = [
            'did'=>'666666sdfgfvcvgghfghhfkasjfhfanf',//用户的设备号
            'version'=>1,//用户app版本
            'time'=> Time::getMillisecond()//13位时间戳
        ];

        $sign = IAuth::setSign($data);
        return $sign;
    }
    //仿前端将header里的参数加密
    function qdheader(){
        $header = [
            'version'=>1,
            'app_type'=>'android',
            'did'=>'666666sdfgfvcvgghfghhfkasjfhfanf',
            'model'=>'xiaomi',
            'access_user_token'=>'wA5SYKdpE4iamWRCvTAGJ8d0KSNNIYh8az8v5BlfKavljxaacoSEcHKkQh0YaD8H'
        ];

        $header_params = IAuth::setEcData($header);
//        tw+lrKIxsAF+oHcdkEAMJBzeIaEp+mQwkc9bTkRHUZWQBZtZXT9SxMXXKlJVTPANNYidk0Kok5yuMkjTYf2/5ZY2YNSIYfxuSrY2fGuyeUM=
        return $header_params;
    }

    //仿前端将body里的参数加密
    function qdbody(){
        $body = [
            'id'=>1,
            'name'=>'liudong',
            'tel'=>'13430128182',
        ];

        $body_params = IAuth::setEcData($body);
        return $body_params;
    }



    //加密测试
    function test(){
        $data = input('post.');
        $data = IAuth::dcData($data);
        halt($data);
    }



    // 前端加密
    function jiami(){
//        $str = "id=1&ms=1234";
//        $aes_str = (new Aes())->encrypt($str);
//        echo  $aes_str;

        $headers = request()->header();
        $str = IAuth::setEcData($headers);
        echo $str;
    }

    //前端解密
    function jiemi($str){
//        $str = "WR0FN6gywq/k6Q+LynTqrg==";
//
//        $aes_str = (new Aes())->decrypt($str);
//        echo  $aes_str;
        $arr = IAuth::dcData($str);
        return ok($arr);
    }
}