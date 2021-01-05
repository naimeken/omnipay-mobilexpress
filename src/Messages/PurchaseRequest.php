<?php
/**
 * MobilExpress Purchase Request
 */

namespace Omnipay\MobilExpress\Messages;

use Exception;

class PurchaseRequest extends AbstractRequest
{
    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getData()
    {
        $data = $this->getSalesRequestParams();
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
        return ['CardNum','LastYear','LastMonth','CVV'];
    }

    /**
     * @return string
     */
    public function getProcessName(): string
    {
        return 'ProcessPaymentWithCard';
    }

    /**
     * @param $data
     * @return PurchaseResponse
     */
    protected function createResponse($data): PurchaseResponse
    {
        $response = new PurchaseResponse($this, $data);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }
}
