<?php
/**
 * Created by PhpStorm.
 * User: liudong
 * Date: 2018/11/27
 * Time: 21:08
 */
namespace app\api\controller;

use app\common\lib\Aes;
use app\common\lib\exception\ApiException;
use app\common\lib\IAuth;
use think\Controller;

/**
 * 登录权限控制基础类库，需要登录的接口都要继承它
 * Class AuthBase
 * @package app\api\controller
 */
class AuthBase extends Common {
    public $user = [];
    /**
     * 初始化
     */
    function _initialize()
    {
        parent::_initialize();
        if(!$this->isLogin()){
            throw  new ApiException("请登录",401);
        }

    }

    /*判断用户是否登录*/
    public function isLogin(){
        $header = IAuth::dcData($this->headers['hparams']);
        if (empty($header['access_user_token'])){
            return false;
        }

        $accessUserToken = (new Aes())->decrypt($header['access_user_token']);
        if (empty($accessUserToken)){
            return false;
        }
        if(!preg_match('/||/',$accessUserToken)){
            return false;
        }
        list($token,$id) = explode("||",$accessUserToken);
//        halt($header);
        //根据token获取用户信息
        $user = model('User')->get(['token'=>$token]);

        if(!$user || $user->status != 1){
            return false;
        }
        if(time() > $user->time_out){
            return false;
        }
        //验证通过 更改token过期时间
        try {
            model('User')->save(['time_out'=>strtotime("+".config('app.token_time_out')."days")],['id'=>$user['id']]);
        }catch (\Exception $e) {
            return failed($e->getMessage());
        }
        $this->user = $user;
        return true;
    }



}