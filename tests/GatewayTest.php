<?php

namespace OmnipayTest\MobilExpress;

use Omnipay\Common\CreditCard;
use Omnipay\MobilExpress\Gateway;
use Omnipay\MobilExpress\Messages\AuthorizeRequest;
use Omnipay\MobilExpress\Messages\CancelRequest;
use Omnipay\MobilExpress\Messages\CaptureRequest;
use Omnipay\MobilExpress\Messages\CompletePurchaseRequest;
use Omnipay\MobilExpress\Messages\PurchaseRequest;
use Omnipay\MobilExpress\Messages\RefundRequest;
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
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        /*$this->gateway->setMerchantId('xxxx');
        $this->gateway->setTestMode(true);
        $this->gateway->setPassword('xxxx');
        $this->gateway->setPosId(9999);*/
    }

    public function testCompletePurchase()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'transactionId' => '107134669907',
            'mobilexpressTransId' => '200094667',
            'result' => '3DSuccess'
        ];

        /** @var CompletePurchaseRequest $request */
        $request = $this->gateway->completePurchase($this->options);

        self::assertInstanceOf(CompletePurchaseRequest::class, $request);
        self::assertSame('107134669907', $request->getTransactionId());
    }

    public function testPurchase()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '107134669907',
            'amount' => '300',
            'returnUrl' => "http://playground.io/examples/test.php",
            'installment' => 0,
            'paymentMethod' => '3d',
            'clientIp' => '129.168.2.1'
        ];

        /** @var PurchaseRequest $request */
        $request = $this->gateway->purchase($this->options);

        self::assertInstanceOf(PurchaseRequest::class, $request);
        self::assertSame('107134669907', $request->getOrderId());
    }


    public function testCapture()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '1071346699',
            'amount' => '330',
            'installment' => 2,
            'paymentMethod' => '',
            'clientIp' => '129.168.2.1'
        ];

        /** @var CaptureRequest $request */
        $request = $this->gateway->capture($this->options);

        self::assertInstanceOf(CaptureRequest::class, $request);
        self::assertSame('1071346699', $request->getOrderId());
    }

    public function testAuthorize()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '1071346699',
            'amount' => '330',
            'installment' => 2,
            'paymentMethod' => '',
            'clientIp' => '129.168.2.1'
        ];

        /** @var AuthorizeRequest $request */
        $request = $this->gateway->authorize($this->options);

        self::assertInstanceOf(AuthorizeRequest::class, $request);
        self::assertSame('1071346699', $request->getOrderId());
    }

    public function testRefund(): void
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '107134669902',
            'amount' => '200',
            'installment' => 5
        ];
        /** @var RefundRequest $request */
        $request = $this->gateway->refund($this->options);

        self::assertInstanceOf(RefundRequest::class, $request);
        self::assertSame('107134669902', $request->getOrderId());
    }


    public function testCancel(): void
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '107134669903',
            'amount' => '500',
            'installment' => 5
        ];
        /** @var CancelRequest $request */
        $request = $this->gateway->void($this->options);

        self::assertInstanceOf(CancelRequest::class, $request);
        self::assertSame('107134669903', $request->getOrderId());
    }

    /**
     * @return CreditCard
     */
    private function getCardInfo(): CreditCard
    {
        $cardInfo = $this->getValidCard();
        $cardInfo['number'] = '4022774022774026';
        $cardInfo['expiryMonth'] = 12;
        $cardInfo['expiryYear'] = 2023;
        $cardInfo['cvv'] = '000';
        return new CreditCard($cardInfo);
    }
}