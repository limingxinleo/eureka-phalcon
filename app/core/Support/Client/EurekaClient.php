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
        'Accept' => 'application/json',
        'Content-Type' => 'application/xml'
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
            'headers' => $this->headers
        ]);
    }

    protected function handleResponse(ResponseInterface $response)
    {
        $str = $response->getBody()->getContents();
        $xml = json_decode($str, true);
        return $xml;
    }

    public function apps()
    {
        $route = '/eureka/v2/apps';
        $response = $this->client->get($route);
        return $this->handleResponse($response);
    }

    public function register()
    {
        $route = '/eureka/v2/apps/' . $this->config->instance;
        $xml = file_get_contents(APP_PATH . '/config/eureka/instance.xml');
        $response = $this->client->post($route, [
            'body' => $xml
        ]);

        return $this->handleResponse($response);
    }
}