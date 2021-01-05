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
        return [];
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
        // TODO: Implement getProcessName() method.
    }
}
