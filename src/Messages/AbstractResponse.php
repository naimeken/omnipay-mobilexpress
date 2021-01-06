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
            return isset($this->data['BankMessage']) ? $this->data['BankMessage'] : $this->data['ErrorMessage'];
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
        return ($this->data['ResultCode'] === 'Success' || $this->data['ResultCode'] === 'ThreeDSecureURLCreated');
    }

    /**
     * @return boolean
     */
    public function isRedirect(): bool
    {
        if (isset($this->data['ResultCode']) && $this->data['ResultCode'] === 'ThreeDSecureURLCreated') {
            return true;
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getRedirectUrl(): ?string
    {
        if ($this->isRedirect()) {
            return $this->data['ThreeDRedirectURL'];
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getTransactionReference(): ?string
    {
        if ($this->isSuccessful()) {
            return isset($this->data['BankTransId']) ? $this->data['BankTransId'] : $this->request->getOrderId();
        }

        return null;
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
    public function getMobilExpressTransId(): ?string
    {
        return $this->data['MobilexpressTransId'] ?? null;
    }
}
