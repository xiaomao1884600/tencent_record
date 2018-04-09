<?php
/**
 * Created by PhpStorm.
 * User: wangwujun
 * Date: 2018/4/9
 * Time: 下午4:34
 */
namespace App\Service\Record;

use App\Service\Foundation\BaseService;
use App\Service\Foundation\HttpRequest;

class RecordService extends BaseService
{

    protected $config = [
        'id' => 'hxsd',
    ];

    protected $call_out_url = 'http://HOST/201511v3/callCancel?id=hxsd';

    protected $httpRequest;

    protected $method = 'GET';

    public function __construct(
        HttpRequest $httpRequest
    )
    {
        parent::__construct();
        $this->httpRequest = $httpRequest;
    }

    public function setCallOut(array $params)
    {
        $params['requestUrl'] = $this->call_out_url;

        $result = $this->httpRequest->requestCurl($this->method, [], $params);
        dd($result);
    }
}