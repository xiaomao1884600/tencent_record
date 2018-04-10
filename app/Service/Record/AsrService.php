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

    protected $callBackInfo = '{
    "code": "0",
    "requestId": "266085380",
    "appid": "1255612177",
    "projectid": "1091843",
    "text": "[0:1.330,0:4.120,0]  喂你好崔勇\n[0:4.120,0:6.270,1]  嗯嗯啊\n[0:6.270,0:8.730,0]  你是崔勇吗\n[0:8.730,0:10.820,1]  你在哪里啊\n[0:10.820,0:12.490,0]  呃我是火星时代流量\n[0:12.490,0:13.340,1]  嗯\n[0:13.340,0:18.905,0]  天天在火星时代咨询不涉及方面客场现在有这方面学计划吗现在\n[0:18.905,0:26.360,1]  又打错了啊不是说过对对对对我哦\n[0:26.360,0:28.715,0]  就是没咨询过是吧\n[0:28.715,0:36.837,1]  嗯嗯行还有其他你们老师微信了恶性还是稍微简单嗯再见啊好了",
    "audioUrl": "http://hxsd-backup.oss-cn-beijing.aliyuncs.com/recordAnalyze/2018/04/09/bei_jing/20180409182924-Outbound-E1Trunk1-3276-015038655347.wav",
    "audioTime": "37.924900",
    "message": "成功"
}
';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * asr回调处理
     * @param array $params
     */
    public function asrCallBack(array $callBackInfo)
    {
        $content = json_encode($callBackInfo) . "\n";
        file_put_contents(public_path('ten_asr_call_back.json'), $content);
        return [];
        $this->callBackInfo = file_get_contents(public_path('ten_asr_call_back.json'));
        //return ['success' => 'true'];
        dd($this->callBackInfo);
        $callBackInfo = json_decode($this->callBackInfo, true);
        $analyzeInfo = $this->dataFormat($callBackInfo);
        dd($analyzeInfo);
    }

    protected function dataFormat(array $info)
    {
        $analyzeInfo = [];
        $text = trim(_isset($info, 'text'), '\"""');

        $text = explode("\r\n", $text);

        dd($info, $text);
    }
}