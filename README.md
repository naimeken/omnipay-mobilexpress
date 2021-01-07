# omnipay-mobilexpress


Mobilexpress (Akbank, Alternatif Bank, Anadolu Bank, Deniz Bank, Finans Bank, Garanti, ING, Iyzico, İş Bankası, OdeaBank, Paratika, PayU, Şekerbank, Halkbank, ZiraatBank, TEB, VakıfBank, Türkiye Finans, YKB sanal pos) gateway for Omnipay payment processing library

<a href="https://github.com/thephpleague/omnipay">Omnipay</a> is a framework agnostic, multi-gateway payment
processing library for PHP 7.3+. This package implements Gvp Online Payment Gateway support for Omnipay.

* You have to contact the Mobilexpress for the document.
* You can check mobilexpress service from https://test.mobilexpress.com.tr/checkout/v7/FastCheckoutService.asmx
* You can check test cards from https://test.mobilexpress.com.tr/icerik/id/1247/Test-Kartlar?v=1

## Requirement

* PHP >= 7.3.x,
* [Omnipay V.3](https://github.com/thephpleague/omnipay) repository,
* PHPUnit to run tests

## Autoload

You have to install omnipay V.3

```bash
composer require league/omnipay:^3
```

Then you have to install omnipay-mobilexpress package:

```bash
composer require alegra/omnipay-mobilexpress
```

> `payment-mobilexpress` follows the PSR-4 convention names for its classes, which means you can easily integrate `payment-mobilexpress` classes loading in your own autoloader.

## Basic Usage

- You can use /examples folder to execute examples. This folder is exists here only to show you examples, it is not for production usage.
- First in /examples folder:

```bash
composer install
```

**Authorize Example**

- You can check authorize.php file in /examples folder.

```php
<?php

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('Examples\\', __DIR__);

use Omnipay\MobilExpress\Gateway;
use Examples\Helper;

$gateway = new Gateway();
$helper = new Helper();

try {
    $params = $helper->getAuthorizeParams();
    $response = $gateway->authorize($params)->send();

    $result = [
        'status' => $response->isSuccessful() ?: 0,
        'redirect' => $response->isRedirect() ?: 0,
        'bankTransId' => $response->getTransactionReference(),
        'mobileExpressTransId' => $response->getMobilExpressTransId(),
        'message' => $response->getMessage(),
        'requestParams' => $response->getServiceRequestParams(),
        'response' => $response->getData()
    ];

    print("<pre>" . print_r($result, true) . "</pre>");
} catch (Exception $e) {
    throw new \RuntimeException($e->getMessage());
}
```

**Capture Example**

- You can check capture.php file in /examples folder.

```php
<?php

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('Examples\\', __DIR__);

use Omnipay\MobilExpress\Gateway;
use Examples\Helper;

$gateway = new Gateway();
$helper = new Helper();

try {
    $params = $helper->getCaptureParams();
    $response = $gateway->capture($params)->send();

    $result = [
        'status' => $response->isSuccessful() ?: 0,
        'redirect' => $response->isRedirect() ?: 0,
        'bankTransId' => $response->getTransactionReference(),
        'mobileExpressTransId' => $response->getMobilExpressTransId(),
        'message' => $response->getMessage(),
        'requestParams' => $response->getServiceRequestParams(),
        'response' => $response->getData()
    ];

    print("<pre>" . print_r($result, true) . "</pre>");
} catch (Exception $e) {
    throw new \RuntimeException($e->getMessage());
}
```

**Purchase Example**

- You can check purchase.php file in /examples folder.

```php
<?php

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('Examples\\', __DIR__);

use Omnipay\MobilExpress\Gateway;
use Examples\Helper;

$gateway = new Gateway();
$helper = new Helper();

try {
    $params = $helper->getPurchaseParams();
    $response = $gateway->purchase($params)->send();

    $result = [
        'status' => $response->isSuccessful() ?: 0,
        'redirect' => $response->isRedirect() ?: 0,
        'bankTransId' => $response->getTransactionReference(),
        'mobileExpressTransId' => $response->getMobilExpressTransId(),
        'message' => $response->getMessage(),
        'requestParams' => $response->getServiceRequestParams(),
        'response' => $response->getData()
    ];

    print("<pre>" . print_r($result, true) . "</pre>");
} catch (Exception $e) {
    throw new \RuntimeException($e->getMessage());
}
```

**Purchase 3d Example**

- You can check purchase3d.php file in /examples folder.

```php
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
```

**Complete Purchase Example**

- You can check completePurchase.php file in /examples folder.
- Request parameters are created from the data you receive as a result of the 3d payment request.
- You can check 3dVerification.php. It is 3DForm.

```php
<?php

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('Examples\\', __DIR__);

use Omnipay\MobilExpress\Gateway;
use Examples\Helper;

$gateway = new Gateway();
$helper = new Helper();

try {
    $params = $helper->getCompletePurchaseParams();
    $response = $gateway->completePurchase($params)->send();

    $result = [
        'status' => $response->isSuccessful() ?: 0,
        'bankTransId' => $response->getTransactionReference(),
        'mobileExpressTransId' => $response->getMobilExpressTransId(),
        'message' => $response->getMessage(),
        'requestParams' => $response->getServiceRequestParams(),
        'response' => $response->getData()
    ];

    print("<pre>" . print_r($result, true) . "</pre>");
} catch (Exception $e) {
    throw new \RuntimeException($e->getMessage());
}
```

**Refund Example**

- You can check refund.php file in /examples folder.

```php
<?php

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('Examples\\', __DIR__);

use Omnipay\MobilExpress\Gateway;
use Examples\Helper;

$gateway = new Gateway();
$helper = new Helper();

try {
    $params = $helper->getRefundParams();
    $response = $gateway->refund($params)->send();

    $result = [
        'status' => $response->isSuccessful() ?: 0,
        'redirect' => $response->isRedirect() ?: 0,
        'message' => $response->getMessage(),
        'requestParams' => $response->getServiceRequestParams(),
        'response' => $response->getData()
    ];

    print("<pre>" . print_r($result, true) . "</pre>");
} catch (Exception $e) {
    throw new \RuntimeException($e->getMessage());
}
```

**Cancel Example**

- You can check refund.php file in /examples folder.

```php
<?php

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('Examples\\', __DIR__);

use Omnipay\MobilExpress\Gateway;
use Examples\Helper;

$gateway = new Gateway();
$helper = new Helper();

try {
    $params = $helper->getVoidParams();
    $response = $gateway->void($params)->send();

    $result = [
        'status' => $response->isSuccessful() ?: 0,
        'redirect' => $response->isRedirect() ?: 0,
        'message' => $response->getMessage(),
        'requestParams' => $response->getServiceRequestParams(),
        'response' => $response->getData()
    ];

    print("<pre>" . print_r($result, true) . "</pre>");
} catch (Exception $e) {
    throw new \RuntimeException($e->getMessage());
}
```
requestParams:

> System send request to mobilexpress api. It shows request information.
>

## Licensing

[GNU General Public Licence v3.0](LICENSE)

    For the full copyright and license information, please view the LICENSE
    file that was distributed with this source code.