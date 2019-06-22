<?php
/**
 * Created by PhpStorm.
 * User: edz
 * Date: 2019/6/22
 * Time: 9:41 PM
 */
namespace Fjb\CustomPack\Url;

use GuzzleHttp\Client;

class Scanner
{
    /**
     * @var array 一个由url组成的数组
     */
    protected $urls;
    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * Scanner constructor.
     * @param array $urls 一个要扫描的url数组
     */
    public function __construct(array $urls)
    {
        $this->urls = $urls;
        $this->httpClient = new Client();
    }

    /**
     * @return array
     */
    public function getInvalidUrls()
    {
        $invalidUrls = [];
        foreach ($this->urls as $url){
            try{
                $statusCode = $this->getStatusCodeForUrl($url);
            }catch (\Exception $e){
                $statusCode = 500;
            }
            if($statusCode >= 400){
                array_push($invalidUrls,[
                    'url'      => $url,
                    'status'   => $statusCode,
                ]);
            }
        }
        return $invalidUrls;
    }

    /**
     * 获取指定url的http状态码
     * @param $url
     * @return int
     */
    public function getStatusCodeForUrl($url)
    {
        $httpResponse = $this->httpClient->options($url);
        return $httpResponse->getStatusCode();
    }

}