<?php
/**
 * Created by PhpStorm.
 * User: wangwujun
 * Date: 2018/4/9
 * Time: 下午4:53
 */

namespace App\Service\Foundation;


class HttpRequest
{
    protected $method = 'GET';

    public function requestCurl($method = 'GET', $config, $queryParams, $headers = [], $timeout = 600)
    {
        $this->initConfig($config);
        $this->method = $method;

        $requestUrl = $queryParams['requestUrl'] ?? '';

        if(! $requestUrl){
            throw new \Exception('requestUrl be required !');
        };
        $body = $queryParams['body'] ?? [];
        $result = $this->curl($requestUrl, $method, $body, $headers, $timeout);
        $responseInfo = $result['tmpInfo'];
        return $responseInfo;
    }

    public function initConfig($config)
    {

        return $this;
    }

    public function curl($url, $method, $params = [], $header = [], $timeout = 600)
    {

        $res = [];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($header)) {
            // 增加header信息需要将header数组信息拼接成冒号拼接
            foreach ($header as $k => $v) {
                $headers[] = $k . ":" . $v;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true); //当需要通过curl_getinfo来获取发出请求的header信息时,该选项需要设置为true
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        switch ($method) {
            case 'GET':
                if (!empty($params)) {
                    $url = $url . (strpos($url, '?') ? '&' : '?') . (is_array($params) ? http_build_query($params) : $params);
                }
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
                break;
            case 'POST':
                if (class_exists('\CURLFile')) {
                    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
                } else if (defined('CURLOPT_SAFE_UPLOAD')) {
                    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
                }
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
                break;
        }
        $res['url'] = $url;
        $beginTime = microtime(true);
        $res['tmpInfo'] = curl_exec($ch);
        $res['data'] = $params;

        $res['execTime'] = microtime(true) - $beginTime;
        if (curl_errno($ch)) { //curl报错
            $res['error_code'] = curl_errno($ch);
            $res['error_msg'] = curl_error($ch);
        } else {
            $res['getInfo'] = curl_getinfo($ch);
        }
        curl_close($ch); //关闭会话
        return $res;
    }
}