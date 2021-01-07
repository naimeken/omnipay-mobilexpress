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
            $errorMessage = empty($this->data['ErrorMessage']) ? '' : $this->data['ErrorMessage'];
            return $this->data['BankMessage'] ?? $errorMessage ?? $this->data['ResultCode'];
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->data['BankReturnCode'] ?? $this->data['ResultCode'] ?? null;
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
        return isset($this->data['ResultCode']) && $this->data['ResultCode'] === 'ThreeDSecureURLCreated';
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
            return $this->data['BankTransId'] ?? $this->request->getOrderId();
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
