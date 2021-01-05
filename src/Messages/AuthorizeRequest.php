<?php
/**
 * MobilExpress Authorize Request
 */

namespace Omnipay\MobilExpress\Messages;

class AuthorizeRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return [];
    }


    /**
     * @return string
     */
    public function getProcessType(): string
    {
        return 'preauth';
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

