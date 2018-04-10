<?php
/**
 * Created by PhpStorm.
 * User: wangwujun
 * Date: 2018/4/10
 * Time: 上午10:34
 */

namespace App\Service\Record;


use App\Repository\Record\AsrRep;
use App\Service\Foundation\BaseService;
use Illuminate\Support\Facades\Request;

class AsrService extends BaseService
{
    protected $asrRep;

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

    public function __construct(
        AsrRep $asrRep
    )
    {
        parent::__construct();
        $this->asrRep = $asrRep;
    }

    /**
     * asr回调处理
     * @param array $params
     */
    public function asrCallBack(array $callBackInfo)
    {
        //file_put_contents(public_path('ten_asr_call_back.json'), json_encode($callBackInfo));

        // TODO 后期直接改成接收回调数据
        //$callBackInfo = json_decode(file_get_contents('http://asr.hxsd.com/ten_asr_call_back.json'), true);

        $data = $this->dataFormat($callBackInfo);

        // 存储回调识别信息
        $this->setAsrRequest($data);

        // 回调响应成功后必须返回此格式
        return [
            'code' => 0,
            'message' => '成功',
        ];
    }

    /**
     * 对话处理
     * @param $info 对话的JSON对象
     * @return 格式化好的一通电话对话
     * 对话中每句话以\n分隔，每句话遵守以下格式（其中_实际为空格）
     * [开始时间,结束时间,声道编号]__对话文本
     * 示例：
     * [0:1.330,0:4.120,0]  喂你好崔勇
     * 开始时间与结束时间m:s.ms 即 分钟:秒:毫秒，阿里云开始时间和结束时间为毫秒，因此需要做一次计算 m * 60 * 1000 + s.ms * 1000
     * 腾讯云与阿里云识别结果相比，还缺少以下内容，可以暂时统一补0
     * emotion_value 情绪值
     * silence_duration 静音时间
     * speech_rate 语速
     */
    protected function dataFormat(array $info)
    {
        if(! $info) return [];

        $result = $analyzeInfo = $u = $m = [];
        $text = _isset($info, 'text');
        // 过滤空格
        $text = explode("\n", $text);

        foreach($text as $key => $value){
            // 区分角色与文本
            $vc = explode("]  ", $value);
            $v1 = trim($vc[0], '[');
            $v2 = $vc[1];

            // 区分开始和结束时间
            $tc = explode(",", $v1);
            $tb = explode(":", $tc[0]);
            $te = explode(":", $tc[1]);
            $channel = $tc[2];

            $u = [];
            // 计算时间（毫秒）
            $u['channel_id'] = $channel;
            $u['begin_time'] = round($tb[0] * 60 * 1000 + $tb[1] * 1000);
            $u['end_time'] = round($te[0] * 60 * 1000 + $te[1] * 1000);
            $u['emotion_value'] = 0;
            $u['silence_duration'] = 0;
            $u['speech_rate'] = 0;
            $u['text'] = $v2;

            $analyzeInfo[] = $u;
        }
        $jsonData = $info;
        unset($jsonData['text']);

        $result = [
            'requestId' => $info['requestId'] ?? '',
            'oss_path' => $info['audioUrl'] ?? '',
            'analyze_info' => json_encode($analyzeInfo),
            'json_data' => json_encode($jsonData),
        ];

        return $result;
    }

    protected function setAsrRequest(array $data)
    {
        // 处理数据
        if(! $data) return [];

        return $this->asrRep->saveAsrRequest([$data]);
    }
}