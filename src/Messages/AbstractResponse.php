<?php
/**
 * MobileExpress Abstract Response
 */

namespace Omnipay\MobilExpress\Messages;

use Omnipay\Common\Message\RedirectResponseInterface;

abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse implements RedirectResponseInterface
{
    /** @var array */
    public $serviceRequestParams;

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        if (!$this->isSuccessful()) {
            return $this->data['BankMessage'];
        }

        return null;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->data['BankReturnCode'];
    }

    /**
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        return $this->data['ResultCode'] === 'Success';
    }

    /**
     * @return string
     */
    public function getTransactionReference(): string
    {
        return $this->data['MobilexpressTransId'];
    }

    /**
     * @return array
     */
    public function getServiceRequestParams(): array
    {
        return $this->serviceRequestParams;
    }

    /**
     * @param array $serviceRequestParams
     */
    public function setServiceRequestParams(array $serviceRequestParams): void
    {
        $this->serviceRequestParams = $serviceRequestParams;
    }

    /**
     * @return string|null
     */
    public function getBankTransId(): ?string
    {
        if ($this->isSuccessful()) {
            return $this->data['BankTransId'];
        }

        return null;
    }
}
