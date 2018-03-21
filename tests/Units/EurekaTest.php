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
use Tests\UnitTestCase;

/**
 * Class UnitTest
 */
class EurekaTest extends UnitTestCase
{
    public function testBaseCase()
    {
        $apps = EurekaClient::getInstance()->apps();
        $this->assertTrue(isset($apps['applications']['application']));
    }
}
