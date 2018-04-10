<?php
/**
 * Created by PhpStorm.
 * User: wangwujun
 * Date: 2018/4/9
 * Time: 下午4:27
 */
namespace App\Http\Controllers\Record;

use App\Http\Controllers\Controller;
use App\Service\Exceptions\ApiExceptions;
use App\Service\Exceptions\Message;
use App\Service\Record\AsrService;
use App\Service\Record\RecordService;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function __construct()
    {

    }

    /**
     * 呼出
     * @param Request $request
     * @param RecordService $recordService
     * @return array|mixed
     */
    public function setCallOut(Request $request, RecordService $recordService)
    {
        try {
            return Message::success($recordService->setCallOut(requestData($request)));
        } catch (\Exception $exception) {
            return ApiExceptions::handle($exception);
        }
    }

    /**
     * ASR识别回调
     * @param Request $request
     * @param AsrService $asrService
     * @return array|mixed
     */
    public function asrCallBack(Request $request, AsrService $asrService)
    {
        try {
            return Message::success($asrService->asrCallBack(requestData($request)));
        } catch (\Exception $exception) {
            return ApiExceptions::handle($exception);
        }
    }

}