<?php

namespace OmnipayTest\MobilExpress\Messages;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class MobilExpressTestCase extends TestCase
{
    protected function getAuthorizeParams(): array
    {
        $cardParams = $this->getCardParams();
        $params = [
            'card' => $cardParams,
            'orderId' => '1071346699',
            'amount' => '330',
            'returnUrl' => "http://playground.io/examples/test.php",
            'installment' => 2,
            'paymentMethod' => '',
            'clientIp' => '129.168.2.1'
        ];

        return $this->provideMergedParams($params);
    }

    protected function getCaptureParams(): array
    {
        $cardParams = $this->getCardParams();
        $params = [
            'card' => $cardParams,
            'orderId' => '1071346699',
            'amount' => '330',
            'installment' => 2,
            'paymentMethod' => '',
            'clientIp' => '129.168.2.1'
        ];

        return $this->provideMergedParams($params);
    }

    protected function getPurchaseParams(): array
    {
        $cardParams = $this->getCardParams();
        $params = [
            'card' => $cardParams,
            'orderId' => '107134669902',
            'amount' => '500',
            'returnUrl' => "http://playground.io/examples/test.php",
            'installment' => 5,
            'paymentMethod' => '',
            'clientIp' => '129.168.2.1'
        ];

        return $this->provideMergedParams($params);
    }

    protected function getCompletePurchaseParams(): array
    {
        $params = [
            'transactionId' => '107134669907',
            'mobilexpressTransId' => '200094667',
            'result' => '3DSuccess'
        ];

        return $this->provideMergedParams($params);
    }

    protected function getRefundParams(): array
    {
        $params = [
            'orderId' => '107134669902',
            'amount' => '200',
            'installment' => 5
        ];

        return $this->provideMergedParams($params);
    }

    protected function getCancelParams(): array
    {
        $params = [
            'orderId' => '107134669903',
            'amount' => '500',
            'installment' => 5
        ];

        return $this->provideMergedParams($params);
    }

    private function getDefaultOptions(): array
    {
        return [
            'testMode' => true,
            'merchantId' => 'xxx',
            'password' => 'xxx',
            'posId' => 0,
        ];
    }

    private function provideMergedParams($params): array
    {
        $params = array_merge($params, $this->getDefaultOptions());
        return $params;
    }

    private function getCardParams(): CreditCard
    {
        $cardInfo = $this->getValidCard();
        $cardInfo['number'] = '4022774022774026';
        $cardInfo['expiryMonth'] = 12;
        $cardInfo['expiryYear'] = 2023;
        $cardInfo['cvv'] = "000";
        return new CreditCard($cardInfo);
    }
}