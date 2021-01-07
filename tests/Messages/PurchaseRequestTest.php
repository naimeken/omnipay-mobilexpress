<?php

namespace OmnipayTest\MobilExpress\Messages;

use Omnipay\MobilExpress\Messages\PurchaseRequest;

class PurchaseRequestTest extends MobilExpressTestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getPurchaseParams());
    }

    public function testEndpoint(): void
    {
        self::assertSame('https://test.mobilexpress.com.tr/checkout/v7/FastCheckoutService.asmx/ProcessPaymentWithCard',
            $this->request->getEndpoint());
    }

    public function testTransactionId(): void
    {
        self::assertArrayHasKey('TransactionId', $this->request->getData());

        $this->request->setOrderId('107134669902');

        self::assertSame('107134669902', $this->request->getOrderId());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame(5, $this->request->getInstallment());
        self::assertSame('21007LrwE16300', $response->getTransactionReference());
        self::assertSame('00', $response->getCode());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertNull($response->getTransactionReference());
        self::assertSame('DUPLICATE_TRANS_ID', $response->getCode());
        self::assertSame('TransactionId benzersiz olmalıdır.',
            $response->getMessage());
    }
}