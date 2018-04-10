<?php
/**
 * Created by PhpStorm.
 * User: wangwujun
 * Date: 2018/4/10
 * Time: 上午8:41
 */

namespace App\Service\Record;


use App\Service\Foundation\BaseService;

class DownLoadService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 下载文件
     */
    public function downLoadRecord()
    {
        //$url = "http://www.baidu.com/img/baidu_jgylogo3.gif";
        $url = "http://hxsd-backup.oss-cn-beijing.aliyuncs.com/recordAnalyze/2018/04/09/bei_jing/20180409182924-Outbound-E1Trunk1-3276-015038655347.wav";
        $save_dir = public_path('down/');
        //$filename = "test.gif";
        $filename = "a.wav";
        $res = $this->getFile($url, $save_dir, $filename, 1);
        dd($res);
    }

    public function getFile($url, $save_dir = '', $filename = '', $type = 0) {
        if (trim($url) == '') {
            return false;
        }
        if (trim($save_dir) == '') {
            $save_dir = './';
        }

        $save_dir = rtrim($save_dir, '/');
        $save_dir .= '/';

//        if (0 !== strrpos($save_dir, '/')) {
//            $save_dir.= '/';
//        }

        //创建保存目录
        if (!file_exists($save_dir) && !mkdir($save_dir, 0777, true)) {
            return false;
        }
        //获取远程文件所采用的方法
        if ($type) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $content = curl_exec($ch);
            curl_close($ch);
        } else {
            ob_start();
            readfile($url);
            $content = ob_get_contents();
            ob_end_clean();
        }
        $size = strlen($content);
        //文件大小
        $fp2 = @fopen($save_dir . $filename, 'a');
        fwrite($fp2, $content);
        fclose($fp2);
        unset($content, $url);
        return array(
            'file_name' => $filename,
            'save_path' => $save_dir . $filename
        );
    }
}