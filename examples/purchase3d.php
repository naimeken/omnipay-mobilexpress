<?php

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('Examples\\', __DIR__);

use Omnipay\MobilExpress\Gateway;
use Examples\Helper;

$gateway = new Gateway();
$helper = new Helper();

try {
    $params = $helper->getPurchase3DParams();
    $response3d = $gateway->purchase($params)->send();

    $result = [
        'status' => $response3d->isSuccessful() ?: 0,
        'redirect' => $response3d->isRedirect() ?: 0,
        'redirectUrl' => $response3d->getRedirectUrl(),
        'redirectData' => $response3d->getRedirectData(),
        'redirectMethod' => $response3d->getRedirectMethod(),
        'mobileExpressTransId' => $response3d->getMobilExpressTransId(),
        'message' => $response3d->getMessage(),
        'requestParams' => $response3d->getServiceRequestParams(),
        'response' => $response3d->getData()
    ];

    print("<pre>" . print_r($result, true) . "</pre>");
} catch (Exception $e) {
    throw new \RuntimeException($e->getMessage());
}