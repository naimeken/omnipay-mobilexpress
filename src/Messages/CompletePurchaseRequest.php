<?php
/**
 * MobilExpress Complete Purchase Request
 */

namespace Omnipay\MobilExpress\Messages;

use Exception;

class CompletePurchaseRequest extends AbstractRequest
{
    private const THREE3D_SUCCESS = '3DSuccess';

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getData()
    {
        if ($this->getResult() !== self::THREE3D_SUCCESS) {
            throw new \RuntimeException('3D verification error. Reason: ' . $this->getResult());
        }

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
