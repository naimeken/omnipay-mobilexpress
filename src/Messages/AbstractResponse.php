<?php
/**
 * MobileExpress Abstract Response
 */

namespace Omnipay\MobilExpress\Messages;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse implements RedirectResponseInterface
{
    /** @var array */
    public $serviceRequestParams;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        $parsedXML = @simplexml_load_string($this->getData());
        $content = json_decode(json_encode($parsedXML), true);
        $this->setData($content);
    }

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
     * @return string
     */
    public function getRedirectMethod(): string
    {
        return 'POST';
    }

    public function getRedirectData(): array
    {
        if ($this->isRedirect()) {
            /** @var CreditCard $card */
            $card = $this->request->getCard();

            return [
                'CardNumber' => $card->getNumber(),
                'CardLastYear' => $card->getExpiryYear(),
                'CardLastMonth' => $card->getExpiryMonth(),
                'CardCVV' => $card->getCvv(),
            ];
        }

        return [];
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

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }
}
