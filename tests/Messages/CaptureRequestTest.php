<?php

namespace OmnipayTest\MobilExpress\Messages;

use Omnipay\MobilExpress\Messages\CaptureRequest;

class CaptureRequestTest extends MobilExpressTestCase
{
    /**
     * @var CaptureRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getCaptureParams());
    }

    public function testEndpoint(): void
    {
        self::assertSame('https://test.mobilexpress.com.tr/checkout/v7/FastCheckoutService.asmx/ProcessPaymentWithCard',
            $this->request->getEndpoint());
    }

    public function testTransactionId(): void
    {
        self::assertArrayHasKey('TransactionId', $this->request->getData());

        $this->request->setOrderId('1071346699');

        self::assertSame('1071346699', $this->request->getOrderId());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame(2, $this->request->getInstallment());
        self::assertSame('21007LioD15166', $response->getTransactionReference());
        self::assertSame('00', $response->getCode());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('CaptureFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertNull($response->getTransactionReference());
        self::assertSame('DUPLICATE_TRANS_ID', $response->getCode());
        self::assertSame('TransactionId benzersiz olmalıdır.',
            $response->getMessage());
    }
}