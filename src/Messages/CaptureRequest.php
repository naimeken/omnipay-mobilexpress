<?php
/**
 * MobilExpress Capture Request
 */

namespace Omnipay\MobilExpress\Messages;

class CaptureRequest extends AbstractRequest
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
        return 'postauth';
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

