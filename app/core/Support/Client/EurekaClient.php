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
use App\Utils\Redis;
use GuzzleHttp\Client;
use limx\Support\Arr;
use Phalcon\Text;
use Xin\Traits\Common\InstanceTrait;
use Psr\Http\Message\ResponseInterface;

class EurekaClient
{
    use InstanceTrait;

    protected $client;

    protected $config;

    protected $url;

    protected $port;

    protected $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/xml'
    ];

    public function __construct()
    {
        $this->config = di('config')->eureka;
        $this->url = env('APP_URL');
        $this->port = env('APP_PORT');

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
        $xml = str_replace('{{APP_PORT}}', $this->port, $xml);
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

    public function getBaseUriByServiceName($serviceName)
    {
        $redisKey = sprintf($this->config->cacheKeyPrefix, Text::lower($serviceName));
        return Redis::sRandMember($redisKey);
    }

    public function cacheServices()
    {
        $apps = Arr::get($this->apps(), 'applications', []);
        if (!isset($apps['application'])) {
            // 不存在服务
            return;
        }

        $apps = $apps['application'];
        if (isset($apps['name'])) {
            $apps = [$apps];
        }

        foreach ($apps as $app) {
            $this->cacheSingleService($app['instance'], $app['name']);
        }
    }

    protected function cacheSingleService($services, $name)
    {
        $redisKey = sprintf($this->config->cacheKeyPrefix, Text::lower($name));
        if (isset($services['app'])) {
            $services = [$services];
        }

        foreach ($services as $service) {
            // 只存在一个实例
            $port = $service['port']['$'];
            if ($port != 80) {
                $item = 'http://' . $service['ipAddr'] . ':' . $port . '/';
            } else {
                $item = 'http://' . $service['ipAddr'] . '/';
            }
            Redis::sadd($redisKey, $item);
            Redis::expire($redisKey, 60);
        }
    }
}