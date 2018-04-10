<?php
/**
 * Created by PhpStorm.
 * User: wangwujun
 * Date: 2018/4/10
 * Time: 上午8:44
 */

namespace App\Http\Controllers\Record;


use App\Http\Controllers\Controller;
use App\Service\Exceptions\ApiExceptions;
use App\Service\Exceptions\Message;
use App\Service\Record\DownLoadService;
use Illuminate\Http\Request;

class DownLoadController extends Controller
{
    public function __construct()
    {

    }

    /**
     * 下载文件
     * @param Request $request
     * @param DownLoadService $downLoadService
     * @return array|mixed
     */
    public function downLoadRecord(Request $request, DownLoadService $downLoadService)
    {
        try {
            return Message::success($downLoadService->downLoadRecord(requestData($request)));
        } catch (\Exception $exception) {
            return ApiExceptions::handle($exception);
        }
    }
}