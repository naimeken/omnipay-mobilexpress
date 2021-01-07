<?php

namespace OmnipayTest\MobilExpress\Messages;

use Omnipay\MobilExpress\Messages\AuthorizeRequest;

class AuthorizeRequestTest extends MobilExpressTestCase
{
    /**
     * @var AuthorizeRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getAuthorizeParams());
    }

    public function testEndpoint(): void
    {
        self::assertSame('https://test.mobilexpress.com.tr/checkout/v7/FastCheckoutService.asmx/ProcessPaymentWithCard',
            $this->request->getEndpoint());
    }

    public function testTransactionId(): void
    {
        $data = $this->request->getData();
        self::assertArrayHasKey('TransactionId', $this->request->getData());

        $this->request->setOrderId('1071346699');

        self::assertSame('1071346699', $this->request->getOrderId());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame(2, $this->request->getInstallment());
        self::assertSame('21007LKgI12904', $response->getTransactionReference());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('AuthorizeFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertNull($response->getTransactionReference());
        self::assertSame('InvalidInstallment', $response->getCode());
        self::assertEmpty($response->getMessage());
    }
}