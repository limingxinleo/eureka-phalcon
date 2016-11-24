<?php

namespace MyApp\Controllers\Test;

class AliController extends ControllerBase
{

    public function indexAction()
    {
        $alipay = di('config')->application->libraryDir . 'alipay/AopSdk.php';
        require_once $alipay;
        $c = new \AopClient();
        $c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $c->appId = env("ALIPAY_APPID");
        $c->rsaPrivateKey = env('ALIPAY_PRIKEY');
        $c->format = "json";
        $c->alipayrsaPublicKey = env('ALIPAY_PUBKEY');
        $req = new \AlipayTradeWapPayRequest();
        $data['out_trade_no'] = time();
        $data['total_amount'] = 0.01;
        $data['subject'] = 'test';
        $data['seller_id'] = env('ALIPAY_SELLERID');
        $data['product_code'] = 'QUICK_WAP_PAY';
        $bizContent = json_encode($data);
        //$bizContent = '{"out_trade_no":"' . time() . '","total_amount":0.01,"subject":"test","seller_id":"qianrong2016@163.com","product_code":"QUICK_WAP_PAY"}';
        $req->setBizContent($bizContent);

        $form = $c->pageExecute($req);
        echo $form;
    }

}

