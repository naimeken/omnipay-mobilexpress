<?php
/**
 * MobilExpress Complete Purchase Request
 */

namespace Omnipay\MobilExpress\Messages;

use Exception;

class CompletePurchaseRequest extends AbstractRequest
{
    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getData()
    {
        $data = $this->getCompletePurchaseRequestParams();
        $this->setRequestParams($data);

        return $data;
    }

    /**
     * @return string
     */
    public function getProcessType(): string
    {
        return 'sales';
    }

    /**
     * @return array
     */
    public function getSensitiveData(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getProcessName(): string
    {
        return 'FinishPaymentProcessWithCard';
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->getParameter('result');
    }

    /**
     * @param string $value
     * @return CompletePurchaseRequest
     */
    public function setResult(string $value): CompletePurchaseRequest
    {
        return $this->setParameter('result', $value);
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->getParameter('hash');
    }

    /**
     * @param string $value
     * @return CompletePurchaseRequest
     */
    public function setHash(string $value): CompletePurchaseRequest
    {
        return $this->setParameter('hash', $value);
    }

    /**
     * @return string
     */
    public function getTotalAmount(): string
    {
        return $this->getParameter('totalAmount');
    }

    /**
     * @param string $value
     * @return CompletePurchaseRequest
     */
    public function setTotalAmount(string $value): CompletePurchaseRequest
    {
        return $this->setParameter('totalAmount', $value);
    }

    /**
     * @param $data
     * @return CompletePurchaseResponse
     */
    protected function createResponse($data): CompletePurchaseResponse
    {
        $response = new CompletePurchaseResponse($this, $data);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }
}
