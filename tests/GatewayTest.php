<?php

namespace OmnipayTest\MobilExpress;

use Omnipay\Common\CreditCard;
use Omnipay\MobilExpress\Gateway;
use Omnipay\MobilExpress\Messages\AuthorizeResponse;
use Omnipay\MobilExpress\Messages\CompletePurchaseResponse;
use Omnipay\MobilExpress\Messages\PurchaseResponse;
use Omnipay\Tests\GatewayTestCase;


class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    public $gateway;

    /** @var array */
    public $options;

    public function setUp()
    {
        /** @var Gateway gateway */
        $this->gateway = new Gateway(null, $this->getHttpRequest());
        $this->gateway->setMerchantId('xxxx');
        $this->gateway->setTestMode(true);
        $this->gateway->setPassword('xxxx');
        $this->gateway->setPosId('xxxx');
    }

    public function testPurchase()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '34654634w343',
            'amount' => '500',
            'returnUrl' => "http://playground.io/examples/test.php",
            'installment' => 0,
            'paymentMethod' => '',
            'clientIp' => '129.168.2.1'
        ];

        /** @var PurchaseResponse $response */
        $response = $this->gateway->purchase($this->options)->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testCompletePurchase()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'transactionId' => '456u326j87344',
            'mobilexpressTransId' => '200094132',
            'result' => '3DSuccess',
            'totalAmount' => '500',
            'hash' => 'WZDx7ytilQtE7oatzPUYSnpju6M%3d'
        ];

        /** @var CompletePurchaseResponse $response */
        $response = $this->gateway->completePurchase($this->options)->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testCapture()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '6980090809',
            'amount' => '500',
            'installment' => 0,
            'paymentMethod' => '',
            'clientIp' => '129.168.2.1'
        ];

        /** @var AuthorizeResponse $response */
        $response = $this->gateway->capture($this->options)->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testAuthorize()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '6980090809',
            'amount' => '500',
            'installment' => 0,
            'paymentMethod' => '',
            'clientIp' => '129.168.2.1'
        ];

        /** @var AuthorizeResponse $response */
        $response = $this->gateway->authorize($this->options)->send();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @return CreditCard
     */
    private function getCardInfo(): CreditCard
    {
        $cardInfo = $this->getValidCard();
        $cardInfo['number'] = '4022774022774026';
        $cardInfo['expiryMonth'] = 12;
        $cardInfo['expiryYear'] = 2022;
        $cardInfo['cvv'] = '000';
        $card = new CreditCard($cardInfo);

        return $card;
    }
}