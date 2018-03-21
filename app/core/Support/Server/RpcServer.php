<?php
// +----------------------------------------------------------------------
// | RpcServer.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Core\Support\Server;

use App\Core\Support\Client\EurekaClient;
use Xin\Swoole\Rpc\Server;
use swoole_server;
use swoole_process;

class RpcServer extends Server
{
    public function beforeServerStart(swoole_server $server)
    {
        // 注册服务到Eureka
        EurekaClient::getInstance()->register();

        // 用于续约
        $process = new swoole_process(function (swoole_process $worker) use ($server) {
            while (true) {
                sleep(30);
                EurekaClient::getInstance()->heartbeat();
            }
        });

        $server->addProcess($process);
        parent::beforeServerStart($server);
    }
}