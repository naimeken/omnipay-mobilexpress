<?php
/**
 * MobilExpress Refund Request
 */

namespace Omnipay\MobilExpress\Messages;

use Exception;

class RefundRequest extends AbstractRequest
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
        return 'refund';
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
        // TODO: Implement getProcessName() method.
    }
}
