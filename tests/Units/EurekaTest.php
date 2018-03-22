<?php
// +----------------------------------------------------------------------
// | 基础测试类 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace Tests\Units;

use App\Core\Support\Client\EurekaClient;
use GuzzleHttp\Client;
use Tests\HttpTestCase;
use Tests\UnitTestCase;

/**
 * Class UnitTest
 */
class EurekaTest extends HttpTestCase
{
    public function testBaseCase()
    {
        $apps = EurekaClient::getInstance()->apps();
        $this->assertTrue(isset($apps['applications']['application']));
    }

    public function testPhalconService()
    {
        $client = EurekaClient::getInstance();
        $baseUri = $client->getBaseUriByServiceName('phalcon');

        $httpClient = new Client([
            'base_uri' => $baseUri
        ]);
        $res = $httpClient->post('/');
        $json = json_decode($res->getBody()->getContents(), true);

        $this->assertTrue($json['success']);
    }
}
