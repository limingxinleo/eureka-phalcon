<?php
// +----------------------------------------------------------------------
// | RpcServer.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Core\Support\Server;

use App\Utils\Log;
use Xin\Swoole\Rpc\Server;
use swoole_server;
use swoole_process;

class RpcServer extends Server
{
    public function beforeServerStart(swoole_server $server)
    {
        $process = new swoole_process(function (swoole_process $worker) use ($server) {
            while (true) {
                sleep(1);
                echo "xx" . PHP_EOL;
            }
        });

        $server->addProcess($process);
        parent::beforeServerStart($server);
    }
}