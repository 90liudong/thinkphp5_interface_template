<?php
/**
 * Created by PhpStorm.
 * User: liudong
 * Date: 2018/11/28
 * Time: 9:54
 */
namespace app\common\lib;
use think\Cache;
class IAuth {
    /**
     * 设置密码
     * @param string $data
     * @return string
     */
    public static  function setPassword($data) {
        return md5($data.config('app.password_pre_halt'));
    }

    /**
     * 生成每次请求的sign ---》前端做的
     * @param array $data
     * @return string
     */
    public static function setSign($data = []) {
        // 1 按字段排序
        ksort($data);
        // 2拼接字符串数据  &
        $string = http_build_query($data);
        // 3通过aes来加密
        $string = (new Aes())->encrypt($string);

        return $string;
    }



    /**
     * 检查sign是否正常   ---》后端做的 （需要针对业务重写）
     * 检查header里的参数是否合法
     * @param array $data
     * @param $data
     * @return boolen
     */
    public static function checkSignPass($data) {
        $signArr = self::dcData($data['sign']);//解密sign后得到的数组
        $headerArr = self::dcData($data['hparams']);//解密header_param后得到的数组

        //判断手机类型
        if(!in_array($headerArr['app_type'], config('app.apptypes'))) {
            throw new ApiException('app_type不合法', 400);
        }

        //解密转换后的条件判定，自己加齐全（加密时候用了哪些参数就用哪些参数判断）
        if(
            !is_array($signArr) || !is_array($headerArr)
            || empty($signArr['did']) || $signArr['did'] != $headerArr['did']
            || empty($signArr['version']) || $signArr['version'] != $headerArr['version']
        ){
            return false;
        }

        if(!config('app_debug')) {
            //时间判断
            if ((time() - ceil($signArr['time'] / 1000)) > config('app.app_sign_time')) {
                return false;
            }
            //echo Cache::get($data['sign']);exit;
            // 唯一性判定
            if (Cache::get($data['sign'])) {
                return false;
            }
        }
        return true;
    }

//  前端需要加密入参和解密后端的出参  （入参有header和body   (sign授权码)）
//  后端需要加密出参和解密前端传的参数

    /**
     * 加密入参  ---》 前端做的
     * 加密出参  ---》 后端做的
     * @param array $data
     * @return string
     */
    public static function setEcData($data = []) {
        // 1 按字段排序
        ksort($data);
        // 2拼接字符串数据  &
        $string = http_build_query($data);
        // 3通过aes来加密
        $string = (new Aes())->encrypt($string);

        return $string;
    }

    /**
     * 解密入参  后端做的
     * @param array $data
     * @return string
     */
    public static function dcData($str = "") {
        // 1 按字段排序
        $string = (new Aes())->decrypt($str);
        // 将这样的diid=xx&app_type=3转换成数组
        parse_str($string, $arr);
        if(is_array($arr)){
            return $arr;
        }else{
            throw exception('参数错误',400);
        }
    }

    /**
     * 生成唯一性的token
     * @param string $phone
     * @return string
     */
    public  static function setAppLoginToken($phone=''){
        $str = md5(uniqid(md5(microtime(true)),true));
        $str = sha1($str.$phone);
        return $str;
    }
}