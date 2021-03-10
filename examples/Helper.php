<?php

namespace Examples;


class Helper
{

    /**
     * @return array
     * @throws \Exception
     */
    public function getPurchaseParams(): array
    {
        $params = [
            'card' => $this->getValidCard(),
            'orderId' => uniqid(),
            'amount' => '500',
            'installment' => 0,
            'paymentMethod' => '',
            'clientIp' => '129.168.2.1'
        ];

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     */
    public function getRefundParams(): array
    {
        $params = [
            'orderId' => '5ff71395dad9e',
            'amount' => '200',
            'installment' => 2
        ];

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     */
    public function getVoidParams(): array
    {
        $params = [
            'orderId' => '5ff71395dad9e',
            'amount' => '330',
            'installment' => 2
        ];

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getAuthorizeParams(): array
    {
        $params = [
            'card' => $this->getValidCard(),
            'orderId' => uniqid(),
            'amount' => '330',
            'installment' => 2,
            'paymentMethod' => '',
            'clientIp' => '129.168.2.1'
        ];

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getCaptureParams(): array
    {
        $params = [
            'card' => $this->getValidCard(),
            'orderId' => '5ff71395dad9e',
            'amount' => '330',
            'installment' => 2,
            'paymentMethod' => '',
            'clientIp' => '129.168.2.1'
        ];

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getPurchase3DParams(): array
    {
        $params = [
            'card' => $this->getValidCard(),
            'orderId' => uniqid(),
            'amount' => '500',
            'returnUrl' => 'http://playground.io/examples/3dVerification.php',
            'installment' => 0,
            'paymentMethod' => '3d',
            'clientIp' => '129.168.2.1'
        ];
        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getCompletePurchaseParams(): array
    {
        $params = [
            'transactionId' => '5ff71149a988f',
            'mobilexpressTransId' => '200094805',
            'result' => '3DSuccess'
        ];

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     */
    private function getDefaultOptions(): array
    {
        return [
            'testMode' => true,
            'merchantId' => 'xxxx', //You can take it from mobilexpress.
            'password' => 'xxxx',//You can take it from mobilexpress
            'posId' => 'xxxx'//You can take it from mobilexpress
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    private function provideMergedParams(array $params): array
    {
        $params = array_merge($this->getDefaultOptions(), $params);
        return $params;
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getValidCard(): array
    {
        return [
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4022774022774026',
            'expiryMonth' => 12,
            'expiryYear' => 2022,
            'cvv' => '000',
            'billingAddress1' => '123 Billing St',
            'billingAddress2' => 'Billsville',
            'billingCity' => 'Billstown',
            'billingPostcode' => '12345',
            'billingState' => 'CA',
            'billingCountry' => 'US',
            'billingPhone' => '(555) 123-4567',
            'shippingAddress1' => '123 Shipping St',
            'shippingAddress2' => 'Shipsville',
            'shippingCity' => 'Shipstown',
            'shippingPostcode' => '54321',
            'shippingState' => 'NY',
            'shippingCountry' => 'US',
            'shippingPhone' => '(555) 987-6543',
        ];
    }
}

