<?php

namespace OmnipayTest\MobilExpress\Messages;

use Omnipay\MobilExpress\Messages\CompletePurchaseRequest;

class CompletePurchaseRequestTest extends MobilExpressTestCase
{
    /**
     * @var CompletePurchaseRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getCompletePurchaseParams());
    }

    public function testEndpoint(): void
    {
        self::assertSame('https://test.mobilexpress.com.tr/checkout/v7/FastCheckoutService.asmx/FinishPaymentProcessWithCard',
            $this->request->getEndpoint());
    }

    public function testTransactionId(): void
    {
        self::assertArrayHasKey('TransactionId', $this->request->getData());

        self::assertSame('107134669907', $this->request->getTransactionId());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('21007MowI12120', $response->getTransactionReference());
        self::assertSame('00', $response->getCode());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('CompletePurchaseFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertNull($response->getTransactionReference());
        self::assertSame('RefTransactionNotFound', $response->getCode());
    }
}