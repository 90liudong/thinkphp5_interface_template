<?php
/**
 * Created by PhpStorm.
 * User: liudong
 * Date: 2018/11/28
 * Time: 10:14
 */
namespace app\common\lib;
class Time {

    public static function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }

}