<?php
/**
 * Created by PhpStorm.
 * User: liudong
 * Date: 2018/11/27
 * Time: 21:09
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\IAuth;
class Index extends Common{

    public function index(){

    }

    public function save(){
        $data = input('post.');
        $data = IAuth::dcData($data['body_params']);
        halt($data);
    }

    /**
     * 客户端初始化接口
     * 1、检测APP是否需要升级
     */
    public function init() {
        // app_type 去ent_version 查询
        $version = model('Version')->getLastNormalVersionByAppType($this->headers['app_type']);

        if(empty($version)) {
            return new ApiException('error', 404);
        }

        if($version->version > $this->headers['version']) {
            $version->is_update = $version->is_force == 1 ? 2 : 1;
        }else {
            $version->is_update = 0;  // 0 不更新 ， 1需要更新, 2强制更新
        }

        // 记录用户的基本信息 用于统计
        $actives = [
            'version' => $this->headers['version'],
            'app_type' => $this->headers['app_type'],
            'did' => $this->headers['did'],
            'model'=>$this->headers['model'],
        ];
        try {
            model('AppActive')->add($actives);
        }catch (\Exception $e) {
            // todo
            //Log::write();
        }

        return show(config('code.success'), 'OK', $version, 200);
    }
}