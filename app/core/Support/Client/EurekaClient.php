<?php
// +----------------------------------------------------------------------
// | EurekaClient.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Core\Support\Client;

use App\Common\Enums\ErrorCode;
use App\Common\Exceptions\BizException;
use GuzzleHttp\Client;
use Xin\Traits\Common\InstanceTrait;
use Psr\Http\Message\ResponseInterface;

class EurekaClient
{
    use InstanceTrait;

    protected $client;

    protected $config;

    protected $headers = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json'
    ];

    public function __construct()
    {
        $this->config = di('config')->eureka;

        $baseUri = di('config')->eureka->baseUri;
        if (empty($baseUri)) {
            throw new BizException(ErrorCode::$ENUM_EUREKA_CONFIG_INVALID);
        }

        $this->client = new Client([
            'base_uri' => $baseUri,
        ]);
    }

    protected function handleResponse(ResponseInterface $response)
    {
        $str = $response->getBody()->getContents();
        return $str;
        $xml = simplexml_load_string($str);
        return $xml;
    }

    public function apps()
    {
        $route = '/eureka/v2/apps';
        $response = $this->client->get($route, [
            'headers' => $this->headers
        ]);
        return $this->handleResponse($response);
    }

    public function register()
    {
        $route = '/eureka/v2/apps/' . $this->config->instance;
        $response = $this->client->post($route, [
            'json' => [
                'instance' => [
                    'hostName' => '127.0.0.1',
                    'app' => 'xxx',
                    'vipAddress' => 'xxx',
                    'secureVipAddress' => 'xxx',
                    'ipAddr' => 'ss',
                    'status' => 'UP',
                    'port' => '80',
                    'securePort' => '443',
                ],
            ],
        ]);

        return $this->handleResponse($response);
    }
}