<?php
/**
 * Created by PhpStorm.
 * User: wangwujun
 * Date: 2018/4/10
 * Time: 下午1:48
 */
namespace App\Repository\Record;

use App\Model\Record\AsrRequest;
use App\Repository\Foundation\BaseRep;

class AsrRep extends BaseRep
{
    protected $asrRequest;

    public function __construct(
        AsrRequest $asrRequest
    )
    {
        parent::__construct();
        $this->asrRequest = $asrRequest;
    }

    /**
     * 保存识别结果
     * @param array $data
     * @return string
     */
    public function saveAsrRequest(array $data)
    {
        return $this->asrRequest->insertUpdateBatch($data);
    }

    /**
     * 获取识别结果
     * @param array $condition
     * @return array
     */
    public function getAsrRequest(array $condition)
    {
        $requestId = $condition['requestId'] ?? [];
        if(! $requestId) return [];

        return $this->asrRequest
                ->whereIn('requestId', $requestId)
                ->get()
                ->toArray();
    }

}