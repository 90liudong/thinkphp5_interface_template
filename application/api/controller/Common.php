<?php
/**
 * Created by PhpStorm.
 * User: liudong
 * Date: 2018/11/27
 * Time: 21:08
 */
namespace app\api\controller;

use app\common\lib\exception\ApiException;
use think\Controller;
use app\common\lib\IAuth;
use think\Cache;
class Common extends Controller{
    public $headers = [];

    function _initialize(){
        $this->checkRequestAuth();
    }

    /**
     * 检查每次app请求的数据是否合法
     */
    public function checkRequestAuth() {
        // 首先需要获取headers
        $headers = request()->header();
//        halt($headers);
        // todo

        // sign 加密需要 客户端工程师 ， 解密：服务端工程师
        // 1 headers body 仿照sign 做参数的加解密
        // 2
        // 3

        // 基础参数校验
        if(empty($headers['sign'])) {
            throw new ApiException('sign不存在', 400);
        }

        // 需要sign
        if(!IAuth::checkSignPass($headers)) {
            throw new ApiException('授权码sign失败', 401);
        }

        Cache::set($headers['sign'], 1, config('app.app_sign_cache_time'));

        // 1、文件  2、mysql 3、redis
        $this->headers = $headers;
    }
}