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
        return ['CardNum', 'LastYear', 'LastMonth', 'CVV'];
    }

    /**
     * @return string
     */
    public function getProcessName(): string
    {
        return 'FinishPaymentProcessWithCard';
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
