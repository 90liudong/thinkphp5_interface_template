<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

\think\Route::resource('api/:ver/index','api/:ver.index');
\think\Route::resource('api/:ver/login','api/:ver.login');

//短信验证码相关
Route::resource('api/:ver/identify', 'api/:ver.identify');
Route::resource('api/:ver/user', 'api/:ver.user');