<?php
/**
 * MobilExpress Abstract Request
 */

namespace Omnipay\MobilExpress\Messages;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\MobilExpress\Mask;
use Omnipay\MobilExpress\RequestInterface;
use Omnipay\Common\Exception\InvalidRequestException;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest implements RequestInterface
{

    /** @var array */
    protected $requestParams;

    /** @var array */
    protected $endpoints = [
        'test' => 'https://test.mobilexpress.com.tr/checkout/v7/FastCheckoutService.asmx',
        'prod' => 'https://www.mobilexpress.com.tr/checkout/v7/FastCheckoutService.asmx'
    ];

    /** @var string */
    private const PAYMENT_METHOD_3D = '3d';

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return ($this->getTestMode() ? $this->endpoints["test"] : $this->endpoints["prod"]) . '/' . $this->getProcessName();
    }

    /**
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setMerchantId(string $value): AbstractRequest
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->getParameter('password');
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setPassword(string $value): AbstractRequest
    {
        return $this->setParameter('password', $value);
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->getParameter('orderId');
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setOrderId(string $value): AbstractRequest
    {
        return $this->setParameter('orderId', $value);
    }

    /**
     * @return string|null
     */
    public function getReferenceTransactionId(): ?string
    {
        return $this->getParameter('referenceTransactionId');
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setReferenceTransactionId(string $value): AbstractRequest
    {
        return $this->setParameter('referenceTransactionId', $value);
    }

    /**
     * @return int
     */
    public function getInstallment(): int
    {
        return $this->getParameter('installment') ?? 0;
    }

    /**
     * @param int $value
     * @return AbstractRequest
     */
    public function setInstallment(int $value): AbstractRequest
    {
        return $this->setParameter('installment', $value);
    }

    /**
     * @return string
     */
    public function getPosId(): string
    {
        return $this->getParameter('posId') ?? '0';
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setMobilExpressTransId(string $value): AbstractRequest
    {
        return $this->setParameter('mobilexpressTransId', $value);
    }

    /**
     * @return string
     */
    public function getMobilExpressTransId(): string
    {
        return $this->getParameter('mobilexpressTransId');
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setPosId(string $value): AbstractRequest
    {
        return $this->setParameter('posId', $value);
    }

    /**
     * @param mixed $data
     * @return ResponseInterface|AbstractResponse
     * @throws InvalidResponseException
     */
    public function sendData($data)
    {
        try {
            foreach ($data as $key => $value) {
                if (is_bool($value)) {
                    $data[$key] = ($value) ? 'true' : 'false';
                }
            }

            $httpRequest = $this->httpClient->request(
                $this->getHttpMethod(),
                $this->getEndpoint(),
                ['Content-Type' => 'application/x-www-form-urlencoded'],
                http_build_query($data)
            );

            $response = (string)$httpRequest->getBody()->getContents();

            return $this->createResponse($response);
        } catch (\Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    protected function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * @return array
     * @throws InvalidRequestException
     */
    protected function getSalesRequestParams(): array
    {
        return [
            'ProcessType' => $this->getProcessType(),
            'MerchantKey' => $this->getMerchantId(),
            'APIpassword' => $this->getPassword(),
            'TransactionId' => $this->getOrderId(),
            'CardNum' => $this->getCard()->getNumber(),
            'LastYear' => $this->getCard()->getExpiryYear(),
            'LastMonth' => $this->getCard()->getExpiryMonth(),
            'CVV' => $this->getCard()->getCvv(),
            'POSID' => (int)$this->getPosId(),
            'TotalAmount' => $this->getAmount(),
            'InstallmentCount' => $this->getInstallment(),
            'UseLoyaltyPoints' => false,
            'Request3D' => $this->getPaymentMethod() === self::PAYMENT_METHOD_3D ?? false,
            'ReturnURL' => $this->getPaymentMethod() === self::PAYMENT_METHOD_3D ? $this->getReturnUrl() : '',
            'ClientIP' => $this->getClientIp(),
            'ClientUserAgent' => '',
            'ExtCampaignInfo' => '',
            'CustomerID' => '',
            'Email' => '',
            'CustomerName' => '',
            'CustomerPhone' => '',
            'CardHolder' => '',
            'POSConfiguration' => '',
        ];
    }

    /**
     * @return array
     */
    protected function getCompletePurchaseRequestParams(): array
    {
        return [
            'MerchantKey' => $this->getMerchantId(),
            'APIpassword' => $this->getPassword(),
            'TransactionId' => $this->getTransactionId(),
            'MobilexpressTransId' => $this->getMobilExpressTransId(),
            'ClientIP' => '',
            'ClientUserAgent' => ''
        ];
    }

    protected function getRefundRequestParams(): array
    {
        $data = [
            'ProcessType' => $this->getProcessType(),
            'MerchantKey' => $this->getMerchantId(),
            'APIpassword' => $this->getPassword(),
            'TransactionId' => $this->getOrderId(),
            'CardNum' => '',
            'LastYear' => 0,
            'LastMonth' => 0,
            'CVV' => '',
            'POSID' => (int)$this->getPosId(),
            'TotalAmount' => $this->getAmount(),
            'InstallmentCount' => $this->getInstallment(),
            'UseLoyaltyPoints' => false,
            'Request3D' => false,
            'ReturnURL' => '',
            'ClientIP' => '',
            'ClientUserAgent' => '',
            'ExtCampaignInfo' => '',
            'CustomerID' => '',
            'Email' => '',
            'CustomerName' => '',
            'CustomerPhone' => '',
            'CardHolder' => '',
            'POSConfiguration' => '',
        ];
        // Required if bank is Vakıfbank
        $referenceTransactionId = $this->getReferenceTransactionId();
        if (!empty($referenceTransactionId)){
            $extCampaignInfo = implode(':', ['BankReferenceNo', $referenceTransactionId]);
            $data['ExtCampaignInfo'] = $extCampaignInfo;
        }
        return $data;
    }


    /**
     * @param array $data
     */
    protected function setRequestParams(array $data): void
    {
        array_walk_recursive($data, [$this, 'updateValue']);
        $this->requestParams = $data;
    }

    /**
     * @param string $data
     * @param string $key
     */
    protected function updateValue(string &$data, string $key): void
    {
        $sensitiveData = $this->getSensitiveData();

        if (\in_array($key, $sensitiveData, true)) {
            $data = Mask::mask($data);
        }

    }

    /**
     * @return array
     */
    protected function getRequestParams(): array
    {
        return [
            'url' => $this->getEndPoint(),
            'type' => $this->getProcessType(),
            'data' => $this->requestParams,
            'method' => $this->getHttpMethod()
        ];
    }
}
