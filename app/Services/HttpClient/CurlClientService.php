<?php

namespace App\Services\HttpClient;

//http://docs.guzzlephp.org/en/stable/
//http://guzzle-cn.readthedocs.io/zh_CN/latest/index.html
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

use Validator;
use Log;

class CurlClientService
{
    protected $httpclient;
    protected $validateserv;
    public function __construct(
        Client $guzzleHttpClient
    ) {
        $this->httpclient      = $guzzleHttpClient;
        $this->connect_timeout = 10;
    }
    /**
        $result =  $this->doRequestJsonToArray(
            $media_source_host.'/api/unlimited_roster'
            ,['form_params'=>$form_params]
            ,"POST"
            ,false
            );
     */
    public function doRequestJsonToArray($uri = '', $params = [], $method = 'GET', $sslverify = true, $exceptions = false)
    {
        $rtn = null;
        try {
            $requestData = $this->doRequestBase($uri, $params, $method, $sslverify, $exceptions);
            if (!empty($requestData)) {
                $json_str = $requestData->getBody()->getContents();
                $rtn = $this->resolveJsonToArray($json_str);
                $rtn['status_code'] = $requestData->getStatusCode();
            }
        } catch (ClientException $e) {
            Log::error("Message : " . $e->getMessage());
            Log::error("Filename : " . $e->getFile());
            Log::error("CodeLine : " . $e->getLine());
        } catch (RequestException $e) {
            Log::error("Message : " . $e->getMessage());
            Log::error("Filename : " . $e->getFile());
            Log::error("CodeLine : " . $e->getLine());
        }
        return $rtn;
    }
    public function doRequestBodyContent($uri = '', $params = [], $method = 'GET', $sslverify = true, $exceptions = false)
    {
        $rtn = null;
        try {
            $requestData = $this->doRequestBase($uri, $params, $method, $sslverify, $exceptions);
            $rtn = empty($requestData) ? $rtn : $requestData->getBody()->getContents();
        } catch (ClientException $e) {
            Log::error("Message : " . $e->getMessage());
            Log::error("Filename : " . $e->getFile());
            Log::error("CodeLine : " . $e->getLine());
        } catch (RequestException $e) {
            Log::error("Message : " . $e->getMessage());
            Log::error("Filename : " . $e->getFile());
            Log::error("CodeLine : " . $e->getLine());
        }
        return $rtn;
    }
    /*
        確認是否為json格式
        *是 =>false
        *否 =>json_decode array
    */
    public function resolveJsonToArray($json_str = "")
    {
        $rtn = false;
        /**驗證資料**/
        $validdata = [];
        $validdata["json_str"] = $json_str;
        $validator_data = [
            'json_str' => 'json',
        ];
        $validator = Validator::make($validdata, $validator_data);
        if ($validator->fails() == true) {
            Log::error("json_decode 錯誤 : ");
            Log::error("json_str : " . $json_str);
        } else {
            $rtn = json_decode($json_str, true);
        }
        return $rtn;
    }
    /**
     * [doRequestBase description]
     * @param  string  $uri        [URI]
     * @param  array   $params     [參數]
     * @param  string  $method     [方式]
     * @param  boolean $sslverify  [是否忽略SSL]
     * @param  boolean $exceptions [是否忽略非 http code 2XX]
     * @return [type]              [description]
     */
    public function doRequestBase($uri = '', $params = [], $method = 'GET', $sslverify = true, $exceptions = false)
    {
        $rtn = null;
        try {
            $options = [
                'exceptions' => $exceptions,
                'verify' => $sslverify,
                'connect_timeout' => $this->connect_timeout,
            ];
            //POST
            if (array_key_exists("form_params", $params)) {
                $options["form_params"] = data_get($params, "form_params");
            }
            if (array_key_exists("body", $params)) {
                $options["body"] = data_get($params, "body");
            }
            //GET
            if (array_key_exists("query", $params)) {
                $options["query"] = data_get($params, "query");
            }
            //POST
            if (array_key_exists("json", $params)) {
                $options["json"] = data_get($params, "json");
            }
            //POST
            if (array_key_exists("multipart", $params)) {
                $options["multipart"] = data_get($params, "multipart");
            }
            if (array_key_exists("headers", $params)) {
                $options["headers"] = data_get($params, "headers");
            }
            if (array_key_exists("auth", $params)) {
                $options["auth"] = data_get($params, "auth");
            }
            //遇到 HTTP 404 500 之類的都能抓 contain
            if (array_key_exists("http_errors", $params)) {
                $options["http_errors"] = data_get($params, "http_errors");
            }

            $rtn = $this->httpclient->request($method, $uri, $options);
            //參考 找到對應 vendor
            // $this->httpclient->requestAsync();
            // $rtn = $res->getBody()->getContents();
        } catch (ClientException $e) {
            Log::error("uri : " . $uri);
            Log::error("params : " . print_r($params, true));
            Log::error("method : " . $method);
            Log::error("Message : " . $e->getMessage());
            Log::error("Filename : " . $e->getFile());
            Log::error("CodeLine : " . $e->getLine());
        } catch (RequestException $e) {
            Log::error("uri : " . $uri);
            Log::error("params : " . print_r($params, true));
            Log::error("method : " . $method);
            Log::error("Message : " . $e->getMessage());
            Log::error("Filename : " . $e->getFile());
            Log::error("CodeLine : " . $e->getLine());
        }
        return $rtn;
    }
}
