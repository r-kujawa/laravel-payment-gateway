<?php

namespace rkujawa\LaravelPaymentGateway\Providers\Authorize;

use Curl\Curl;

/**
 * @method AuthorizeResponse createCustomerPaymentProfileRequest(array $array)
 * @method AuthorizeResponse createCustomerProfileRequest(array $array)
 * @method AuthorizeResponse deleteCustomerPaymentProfileRequest(array $array)
 * @method AuthorizeResponse deleteCustomerProfileRequest(array $array)
 * @method AuthorizeResponse getCustomerPaymentProfileRequest(array $array)
 * @method AuthorizeResponse getCustomerProfileIdsRequest(array $array)
 * @method AuthorizeResponse getCustomerProfileRequest(array $array)
 * @method AuthorizeResponse getUnsettledTransactionListRequest(array $array)
 * @method AuthorizeResponse updateCustomerPaymentProfileRequest(array $array)
 * @method AuthorizeResponse updateCustomerProfileRequest(array $array)
 * @method AuthorizeResponse getSettledBatchListRequest(array $array)
 * @method AuthorizeResponse getTransactionDetailsRequest(array $array)
 * @method AuthorizeResponse getTransactionListRequest(array $array)
 */
class AuthorizeClient
{
    private $curl;
    private $endpoint;
    private $requestParameters;

    public function __construct($endpoint)
    {
        //or maybe get it from conf
        $this->endpoint = $endpoint;
        $this->requestParameters = [];
        $this->initCurl();
    }

    private function initCurl()
    {
        $this->curl = new Curl();
        $this->curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $this->curl->setOpt(CURLOPT_HEADER, false);
        $this->curl->setHeader('Content-Type', 'text/json');
    }

    public function __call($funcName, $args)
    {
        $parameters = [
            $funcName => array_merge($this->getAuthenticationParams(), $args[0])
        ];

        $this->requestParameters = json_encode($parameters);

        return new AuthorizeResponse($this->curl->post($this->endpoint, $this->requestParams));
    }

    private function getAuthenticationParams()
    {
        //we can get those values from payment.php, check if those are set, if not, throw an exception
        return [
            'merchantAuthentication' => [
                'name'           => $this->login,
                'transactionKey' => $this->transactionKey,
            ]
        ];
    }

    public function getRequestParams()
    {
        return $this->requestParameters;
    }
}
