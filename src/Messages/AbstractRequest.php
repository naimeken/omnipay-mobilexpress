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
    use BaseSoapService;

    /** @var array */
    protected $requestParams;

    /** @var array */
    protected $endpoints = [
        'test' => 'https://test.mobilexpress.com.tr/checkout/v7/FastCheckoutService.asmx?WSDL',
        'prod' => 'https://www.mobilexpress.com.tr/checkout/v7/FastCheckoutService.asmx?WSDL'
    ];

    /** @var string */
    private const PAYMENT_METHOD_3D = '3d';

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->endpoints["test"] : $this->endpoints["prod"];
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
     * @return int
     */
    public function getPosId(): int
    {
        return $this->getParameter('posId') ?? 0;
    }

    /**
     * @param int $value
     * @return AbstractRequest
     */
    public function setPosId(int $value): AbstractRequest
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
            $response = $this->makeRequestToService($this->getEndpoint(), $this->getProcessName(), $data);
            $method = $this->getProcessName() . 'Result';
            $stdClass = property_exists($response, $method) ? (array)$response->$method : (array)$response;
            $result = json_decode(json_encode($stdClass, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

            return $this->createResponse($result);
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
            'POSID' => $this->getPosId(),
            'TotalAmount' => $this->getAmount(),
            'InstallmentCount' => $this->getInstallment(),
            'UseLoyaltyPoints' => false,
            'Request3D' => $this->getPaymentMethod() === self::PAYMENT_METHOD_3D ?? false,
            'ReturnURL' => $this->getPaymentMethod() === self::PAYMENT_METHOD_3D ? $this->getReturnUrl() : '',
            'ClientIP' => $this->getClientIp(),
            'POSConfiguration' => ''
        ];
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
            'function' => $this->getProcessName(),
            'data' => $this->requestParams,
            'method' => $this->getHttpMethod()
        ];
    }
}
