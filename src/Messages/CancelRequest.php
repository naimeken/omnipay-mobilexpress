<?php
/**
 * MobilExpress Cancel Request
 */

namespace Omnipay\MobilExpress\Messages;

use Exception;

class CancelRequest extends AbstractRequest
{
    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getData(): array
    {
        $data = $this->getRefundRequestParams();
        $this->setRequestParams($data);

        return $data;
    }

    /**
     * @return string
     */
    public function getProcessType(): string
    {
        return 'cancel';
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
        return 'ProcessPaymentWithCard';
    }

    /**
     * @param $data
     * @return CancelResponse
     */
    protected function createResponse($data): CancelResponse
    {
        $response = new CancelResponse($this, $data);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }
}
