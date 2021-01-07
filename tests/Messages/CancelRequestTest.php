<?php

namespace OmnipayTest\MobilExpress\Messages;

use Omnipay\MobilExpress\Messages\CancelRequest;

class CancelRequestTest extends MobilExpressTestCase
{
    /**
     * @var CancelRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new CancelRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getCancelParams());
    }

    public function testEndpoint(): void
    {
        self::assertSame('https://test.mobilexpress.com.tr/checkout/v7/FastCheckoutService.asmx/ProcessPaymentWithCard',
            $this->request->getEndpoint());
    }

    public function testTransactionId(): void
    {
        self::assertArrayHasKey('TransactionId', $this->request->getData());

        $this->request->setOrderId('107134669903');

        self::assertSame('107134669903', $this->request->getOrderId());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('CancelSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame(5, $this->request->getInstallment());
        self::assertSame('21007L6UH18359', $response->getTransactionReference());
        self::assertSame('00', $response->getCode());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('CancelFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertNull($response->getTransactionReference());
        self::assertSame('RefTransactionNotFound', $response->getCode());
        self::assertSame('İlgili ödeme kaydı bulunamadı',
            $response->getMessage());
    }
}