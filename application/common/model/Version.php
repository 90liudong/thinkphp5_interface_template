<?php
/**
 * Created by PhpStorm.
 * User: liudong
 * Date: 2018/11/29
 * Time: 9:41
 */
namespace app\common\model;
class Version extends Base{
    public function getLastNormalVersionByAppType($app_type){
        $data = [
            'status' => 1,
            'app_type' => $app_type,
        ];

        $order = [
            'id' => 'desc',
        ];

        return $this->where($data)
            ->order($order)
            ->limit(1)
            ->select();
    }
}