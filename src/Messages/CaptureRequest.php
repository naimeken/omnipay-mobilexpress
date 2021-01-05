<?php
/**
 * MobilExpress Capture Request
 */

namespace Omnipay\MobilExpress\Messages;

use Omnipay\Common\Exception\InvalidRequestException;

class CaptureRequest extends AbstractRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData(): array
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
        return 'postauth';
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
        return 'ProcessPaymentWithCard';
    }

    /**
     * @param $data
     * @return AuthorizeResponse
     */
    protected function createResponse($data): AuthorizeResponse
    {
        $response = new AuthorizeResponse($this, $data);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }
}

