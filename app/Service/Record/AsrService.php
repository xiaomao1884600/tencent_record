<?php
/**
 * Created by PhpStorm.
 * User: wangwujun
 * Date: 2018/4/10
 * Time: 上午10:34
 */

namespace App\Service\Record;


use App\Service\Foundation\BaseService;

class AsrService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * asr回调处理
     * @param array $params
     */
    public function asrCallBack(array $params)
    {
        $content = json_encode($params) . "\n";
        file_put_contents(public_path('ten_asr_call_back.json'), $content, FILE_APPEND);

        return ['success' => 'true'];
    }
}