<?php
/**
 * Created by PhpStorm.
 * User: liudong
 * Date: 2018/11/27
 * Time: 21:09
 */
namespace app\api\controller\v1;

use app\api\controller\AuthBase;
use app\common\lib\IAuth;
class User extends AuthBase {

    public function index(){

    }

    public function save(){
        $data = input('post.');
        $data['body_params'] = IAuth::dcData($data['body_params']);
        halt($data);
    }
}