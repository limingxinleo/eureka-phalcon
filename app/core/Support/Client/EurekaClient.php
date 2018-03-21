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

    protected $url;

    protected $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/xml'
    ];

    public function __construct()
    {
        $this->config = di('config')->eureka;
        $this->url = env('APP_URL');

        $baseUri = $this->config->baseUri;
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

    protected function getInstanceXml()
    {
        $path = di('config')->application->configDir;
        $xml = file_get_contents($path . 'eureka/instance.xml');
        $appName = $this->config->appName;

        $xml = str_replace('{{APP_NAME}}', $appName, $xml);
        $xml = str_replace('{{APP_URL}}', $this->url, $xml);
        return $xml;
    }

    public function apps()
    {
        $route = '/eureka/v2/apps';
        $response = $this->client->get($route);
        return $this->handleResponse($response);
    }

    public function app($appName)
    {
        $route = '/eureka/v2/apps/' . $appName;
        $response = $this->client->get($route);
        return $this->handleResponse($response);
    }

    public function register()
    {
        $route = '/eureka/v2/apps/' . $this->config->appName;
        $response = $this->client->post($route, [
            'body' => $this->getInstanceXml()
        ]);

        return $this->handleResponse($response);
    }

    public function heartbeat()
    {
        $appName = $this->config->appName;
        $url = $this->url;
        $route = sprintf('/eureka/v2/apps/%s/%s', $appName, $url);
        $response = $this->client->put($route);

        return $this->handleResponse($response);
    }
}