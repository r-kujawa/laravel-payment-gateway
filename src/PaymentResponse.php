<?php

namespace rkujawa\LaravelPaymentGateway;

use rkujawa\LaravelPaymentGateway\Contracts\PaymentResponder;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
use rkujawa\LaravelPaymentGateway\Traits\PaymentResponses;
use rkujawa\LaravelPaymentGateway\Traits\SimulateAttributes;
use RuntimeException;

abstract class PaymentResponse implements PaymentResponder
{
    use SimulateAttributes, PaymentResponses;

    /**
     * Statuses in this array are considered successful.
     *
     * @var array
     */
    protected $successStatuses = [
        PaymentStatus::AUTHORIZED,
        PaymentStatus::APPROVED,
        PaymentStatus::PARTIALLY_APPROVED,
        PaymentStatus::PENDING,
        PaymentStatus::PROCESSING_ASYNC,
    ];

    /**
     * Additional responses supported by the application.
     *
     * @var array
     */
    protected $responses = [];

    /**
     * Default responses supported by the application.
     *
     * @var array
     */
    protected $requiredResponses = [
        'getWallet' => 'walletDetails',
        'getPaymentMethod' => 'paymentMethodDetails',
        'tokenizePaymentMethod' => 'tokenizationDetails',
        'updatePaymentMethod' => 'updatedPaymentMethodDetails',
        'removePaymentMethod' => 'extractionDetails',
        'authorize' => 'authorizationDetails',
        'capture' => 'captureDetails',
        'authorizeAndCapture' => 'authorizationAndCaptureDetails',
        'void' => 'voidDetails',
        'refund' => 'refundDetails',
    ];

    /**
     * The provider's raw response.
     *
     * @var mixed
     */
    protected $rawResponse;

    /**
     * Additional data needed to format the response.
     *
     * @var mixed|null
     */
    protected $additionalData;

    /**
     * The request method that returned this response.
     *
     * @var string
     */
    protected $requestMethod;

    /**
     * The provider that the $request was made towards.
     *
     * @var \rkujawa\LaravelPaymentGateway\Models\PaymentProvider
     */
    public $provider;

    /**
     * The merchant that was used to make the $request.
     *
     * @var \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant
     */
    public $merchant;

    /**
     * The expected formatted data based on the $request.
     *
     * @var 
     */
    private $data;

    /**
     * @param mixed $response
     * @param mixed|null $additionalData
     */
    public function __construct($response, $additionalData = null)
    {
        $this->rawResponse = $response;
        $this->additionalData = $additionalData;
    }

    /**
     * Configure the response based on the request.
     *
     * @param string $requestMethod
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentProvider $provider
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant $merchant
     * @return void
     */
    public function configure($requestMethod, PaymentProvider $provider, PaymentMerchant $merchant)
    {
        $this->requestMethod = $requestMethod;
        $this->provider = $provider;
        $this->merchant = $merchant;
    }

    /**
     * Get the provider's raw response.
     * 
     * @return array
     */
    public function getRaw()
    {
        return $this->rawResponse;
    }

    /**
     * Verify whether the request should be considered successful.
     * 
     * @return bool
     */
    public function isSuccessful()
    {
        return in_array($this->getStatusCode(), $this->successStatuses);
    }

    /**
     * Verify whether the request should be considered a failure.
     *
     * @return bool
     */
    public function isNotSuccessful()
    {
        return ! $this->isSuccessful();
    }

    /**
     * Determines the status code based on the request's raw response.
     *
     * @return int
     */
    public abstract function getStatusCode();

    /**
     * Get a string representation of the response's status.
     *
     * @return string
     */
    public function getStatus()
    {
        return PaymentStatus::get($this->getStatusCode()) ?? 'Unknown';
    }

    /**
     * Get a description of the response's status.
     *
     * @return string
     */
    public function getMessage()
    {
        return PaymentStatus::getMessage($this->getStatusCode()) ?? '';
    }

    /**
     * Get the formatted data based on the request that was made.
     *
     * @return array|mixed
     * 
     * @throws \RuntimeException
     */
    public function getData()
    {
        if (! isset($this->data)) {
            if (is_null($callback = $this->getDataCallback())) {
                throw new RuntimeException('Data is not set.');
            }
            
            $this->data = $this->{$callback}();
        }

        return $this->data;
    }

    /**
     * Get the callback method that should be used to get the data.
     *
     * @return string|null
     */
    private function getDataCallback()
    {
        return array_merge($this->requiredResponses, $this->responses)[$this->requestMethod] ?? null;
    }
}
