<?php
/**
 * Created by PhpStorm.
 * User: liudong
 * Date: 2018/11/27
 * Time: 21:09
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\Aes;
use app\common\lib\IAuth;
use app\common\model\User;
use think\Controller;
use app\common\lib\SendCode;
class Login extends Common {

    public function index(){
        echo 123456;
    }

    public function save(){
        if (!request()->isPost()){
            return failed("您没有权限",[],403);
        }
        $param = input('post.');
        if (empty($param['tel'])){
            return failed("手机号不合法",[],404);
        }
        if (empty($param['code'])){
            return failed("手机短信验证码不合法",[],404);
        }
        //手机号和验证码都传了的话，用validate校验
        $validate = validate('Identify');
        if(!$validate->check(input('param.'))) {
            return show(config('code.error'), $validate->getError(), [], 403);
        }
        //再校验验证码
        if(SendCode::getInstance()->checkSmsIdentify($param['tel']) != $param['code'] ) {
            return failed("手机短信验证码错误",[],404);
        }
        $token = IAuth::setAppLoginToken($param['tel']);

        // 根据手机号查询是否存在用户，存在的话更新token,不存在的话注册新用户
        $user = User::get(['tel'=>$param['tel']]);
        if (!empty($user)){
            $data = [
                'token'=>$token,
                'time_out'=>strtotime("+".config('app.token_time_out')."days"),
            ];
            model('User')->save($data,['id'=>$user['id']]);
            $id = $user['id'];
        }else{
            //第一次登陆  那么必须有密码
            if(!isset($param['password'])){
                return failed('请输入密码');
            }
            $data = [
                'token'=>$token,
                'time_out'=>strtotime("+".config('app.token_time_out')."days"),
                'user_name'=>$param['tel'],
                'tel'=>$param['tel'],
                'password'=>md5($param['password'].config('app.password_pre_halt')),

            ];
            // halt($data);
            //插入数据
            $id = model('User')->add($data);
        }

        if ($id){
            $res = [
                'token'=>(new Aes())->encrypt($token."||".$id)
            ];

            return ok($res);
        }else{
            return failed('失败了');
        }
    }
}