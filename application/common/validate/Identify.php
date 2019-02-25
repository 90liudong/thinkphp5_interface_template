<?php
/**
 * Created by PhpStorm.
 * User: liudong
 * Date: 2018/11/28
 * Time: 16:36
 */

namespace app\common\validate;

use think\Validate;
class Identify extends Validate {

    protected $rule = [
        'tel' => 'require|number|length:11',
    ];
}