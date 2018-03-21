<?php

namespace App\Tasks;

use App\Core\Support\Client\EurekaClient;

class TestTask extends Task
{

    public function mainAction()
    {
        $client = EurekaClient::getInstance();

        // dd($client->app('phalcon'));
        dd($client->apps());
    }

}

