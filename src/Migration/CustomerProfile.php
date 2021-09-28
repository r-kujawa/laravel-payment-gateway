<?php

namespace rkujawa\LaravelPaymentGateway\Migration;

use App\Models\Payment\AuthorizeCustomerProfile;
use rkujawa\LaravelPaymentGateway\Models\TemporaryPaymentMethod;
use JohnConde\Authnet\AuthnetApiFactory;
use Log;

class CustomerProfile
{
    protected $authorizeCustomerProfile;
    protected $owner;

    protected $temporaryPaymentMethods;

    const STATUS = [
        'success' => 1,
        'failed' => 2,
        'busy' => 3
    ];

    const AUTHORIZE_ERROR = [
        'not_found' => 'E00040',
        'busy' => 'E00053'
    ];

    public function __construct(AuthorizeCustomerProfile $authorizeCustomerProfile)
    {
        $this->authorizeCustomerProfile = $authorizeCustomerProfile;
    }

    private function fetch()
    {
        $this->owner = $this->authorizeCustomerProfile->order->getOwnerClient();
        if (is_null($this->owner)) {
            $this->dismiss(AuthorizeCustomerProfile::DISMISS['no_client']);

            return self::STATUS['success'];
        }

        if ($this->authorizeCustomerProfile->paymentProfile->isEmpty()) {
            $this->dismiss(AuthorizeCustomerProfile::DISMISS['empty']);

            return self::STATUS['success'];
        }

        if ($this->shouldMakeRequest()) {
            $request = AuthnetApiFactory::getJsonApiHandler(
                production() ? \env('AUTHNET_INCFILE_ID') : \env('LOCAL_AUTHNET_INCFILE_ID'),
                production() ? \env('AUTHNET_INCFILE_SECRET') : \env('LOCAL_AUTHNET_INCFILE_SECRET'),
                production() ? AuthnetApiFactory::USE_PRODUCTION_SERVER : AuthnetApiFactory::USE_DEVELOPMENT_SERVER
            );

            $response = $request->getCustomerProfileRequest([
                'customerProfileId' => $this->authorizeCustomerProfile->customer_profile_token,
                'unmaskExpirationDate' => true
            ]);

            if ($response->isSuccessful()) {
                $paymentProfiles = collect(property_exists($response->profile, 'paymentProfiles') ? $response->profile->paymentProfiles : null);

                if ($paymentProfiles->isNotEmpty()) {
                    $this->temporaryPaymentMethods = collect();
    
                    $paymentProfiles->each(function ($paymentProfile) {
                        $update = array_merge(
                            [
                                'last_digits' => substr($paymentProfile->payment->creditCard->cardNumber, -4),
                                'expiration_date' => $paymentProfile->payment->creditCard->expirationDate,
                                'type' => $paymentProfile->payment->creditCard->cardType,
                            ],
                            property_exists($paymentProfile, 'billTo') && property_exists($paymentProfile->billTo, 'firstName') ? ['first_name' => $paymentProfile->billTo->firstName] : [],
                            property_exists($paymentProfile, 'billTo') && property_exists($paymentProfile->billTo, 'lastName') ? ['last_name' => $paymentProfile->billTo->lastName] : []
                        );

                        $temporaryPaymentMethod = TemporaryPaymentMethod::updateOrCreate(
                            [
                                'customer_profile_token' => $this->authorizeCustomerProfile->customer_profile_token,
                                'payment_profile_token' => $paymentProfile->customerPaymentProfileId
                            ],
                            $update
                        );

                        $authorizePaymentProfile = $this->authorizeCustomerProfile->paymentProfile->firstWhere('payment_profile_token', $paymentProfile->customerPaymentProfileId);
                        if (!is_null($authorizePaymentProfile) && $authorizePaymentProfile->created_at->lessThan($temporaryPaymentMethod->created_at)) {
                            $temporaryPaymentMethod->update([
                                'created_at' => $authorizePaymentProfile->created_at
                            ]);
                        }

                        $this->temporaryPaymentMethods->push($temporaryPaymentMethod);
                    });
                } else {
                    $this->dismiss(AuthorizeCustomerProfile::DISMISS['no_payment_profiles'], true);

                    return self::STATUS['success'];
                }
            } elseif ($response->getErrorCode() === self::AUTHORIZE_ERROR['not_found']) {
                $this->dismiss(AuthorizeCustomerProfile::DISMISS['not_found'], true);

                return self::STATUS['success'];
            } elseif ($response->getErrorCode() === self::AUTHORIZE_ERROR['busy']) {
                return self::STATUS['busy'];
            } else {
                Log::debug('Failed to migrate customer profile, Authorize.Net Error', [
                    'error' => [
                        'code' => $response->getErrorCode(),
                        'message' => $response->getErrorMessage(),
                        'text' => $response->getErrorText()
                    ],
                    'authorizeCustomerProfile' => $this->authorizeCustomerProfile->toArray()
                ]);

                $this->dismiss(AuthorizeCustomerProfile::DISMISS['failed']);

                return self::STATUS['failed'];
            }
        }

        $this->temporaryPaymentMethods->each(function ($temporaryPaymentMethod) {
            $related = $this->authorizeCustomerProfile->paymentProfile->firstWhere('payment_profile_token', $temporaryPaymentMethod->payment_profile_token);
            if (!is_null($related)) {
                $temporaryPaymentMethod->relations()->updateOrCreate(
                    [
                        'order_id' => $this->authorizeCustomerProfile->order->getParent()->transnum,
                        'owner_id' => $this->owner->id,
                    ],
                    [
                        'is_primary' => $related->priority === 1
                    ]
                );
            }
        });

        $this->dismiss(AuthorizeCustomerProfile::DISMISS['success']);

        return self::STATUS['success'];
    }

    private function dismiss(int $status, bool $allRelated = false)
    {
        if ($allRelated) {
            AuthorizeCustomerProfile::where('customer_profile_token', $this->authorizeCustomerProfile->customer_profile_token)
                ->update([
                    'dismiss' => $status
                ]);
        } else {
            $this->authorizeCustomerProfile->update([
                'dismiss' => $status
            ]);
        }
    }

    private function shouldMakeRequest()
    {
        $existingTemporaryPaymentMethods = TemporaryPaymentMethod::where('customer_profile_token', $this->authorizeCustomerProfile->customer_profile_token)->get();

        if ($existingTemporaryPaymentMethods->isEmpty()) {
            return true;
        }

        $mostRecentExistingTemporaryPaymentMethod = $existingTemporaryPaymentMethods->sortByDesc('updated_at')->first();
        $mostRecentAuthorizePaymentProfile = $this->authorizeCustomerProfile->paymentProfile->sortByDesc('updated_at')->first();

        if ($mostRecentExistingTemporaryPaymentMethod->updated_at->lessThan($mostRecentAuthorizePaymentProfile->updated_at)) {
            return true;
        }

        $this->temporaryPaymentMethods = $existingTemporaryPaymentMethods;

        return false;
    }

    public static function migrate(AuthorizeCustomerProfile $authorizeCustomerProfile)
    {
        return (new self($authorizeCustomerProfile))->fetch();
    }
}
