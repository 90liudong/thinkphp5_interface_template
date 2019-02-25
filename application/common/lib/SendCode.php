<?php
/**
 * Created by PhpStorm.
 * User: liudong
 * Date: 2018/11/28
 * Time: 10:14
 */
namespace app\common\lib;

//单例模式
use think\Cache;

class SendCode {

    /**
     * 静态变量保存全局的实例
     * @var null
     */
    private static $_instance = null;
    /**
     * 私有的构造方法
     */
    private function __construct(){

    }

    /**
     * 静态方法 单例模式统一入口
     */
    public static function getInstance(){
        if(is_null(self::$_instance)){
            self::$_instance = new self();
        }

        return self::$_instance;
    }


    /**
     * （创蓝）
     * 发送短信验证码
     * @param $tel
     * @return int
     */
    public function sendSMS($tel){
        $data['account'] = 'N153515_N2621621';
        $data['password'] = 'INbx5ptdRX99fd';
        $code = mt_rand(100000,999999);
        $data['phone'] = $tel;
        $data['msg'] = "【图桑医疗科技】您的验证码是：".$code;
        $url = 'http://smsbj1.253.com/msg/send/json';//POST指向的链接
        try {
            $json_data = $this->postJsonData($url, $data);
            $array = json_decode($json_data,true);
        }catch (\Exception $e) {
            // 记录日志
            Log::write("set-----发送短信验证码错误".$e->getMessage());
            return false;
        }
//        halt($array);
        if($array['code']!="0"){
            return false;
        }else{
            //存入缓存
            Cache::set($tel,$code,config('app.node_time'));
            return true;
        }

    }

    public function postJsonData($url, $data){
        $data = json_encode($data);
        $ch = curl_init();
        $timeout = 300;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
        $handles = curl_exec($ch);
        curl_close($ch);
        return $handles;
    }

    //    校验短信验证码
    /**
     * 根据手机号码查询验证码是否正常
     * @param int $phone
     */
    public function checkSmsIdentify($tel = 0) {
        if(!$tel) {
            return false;
        }
        return Cache::get($tel);
    }
}