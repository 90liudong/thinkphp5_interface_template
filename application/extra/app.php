<?php
/**
 * Created by PhpStorm.
 * User: liudong
 * Date: 2018/11/28
 * Time: 10:44
 */


return [
    'password_pre_halt' => '_#sing_ty',// 密码加密盐
    'aeskey' => 'sgg45747ss223455',//aes 密钥 , 服务端和客户端必须保持一致
    'apptypes' => [
        'ios',
        'android',
    ],
    'app_sign_time' => 100,// sign失效时间
    'app_sign_cache_time' => 20,// sign 缓存失效时间
    'node_time'=>300000,//短信验证码失效时间
    'token_time_out'=>1,//token过期时间
];