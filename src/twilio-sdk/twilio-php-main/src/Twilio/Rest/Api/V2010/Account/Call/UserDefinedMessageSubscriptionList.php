<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Api
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Twilio\Rest\Api\V2010\Account\Call;

use Twilio\Exceptions\TwilioException;
use Twilio\ListResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;


class UserDefinedMessageSubscriptionList extends ListResource
    {
    /**
     * Construct the UserDefinedMessageSubscriptionList
     *
     * @param Version $version Version that contains the resource
     * @param string $accountSid The SID of the [Account](https://www.twilio.com/docs/iam/api/account) that subscribed to the User Defined Messages.
     * @param string $callSid The SID of the [Call](https://www.twilio.com/docs/voice/api/call-resource) the User Defined Messages subscription is associated with. This refers to the Call SID that is producing the user defined messages.
     */
    public function __construct(
        Version $version,
        string $accountSid,
        string $callSid
    ) {
        parent::__construct($version);

        // Path Solution
        $this->solution = [
        'accountSid' =>
            $accountSid,
        
        'callSid' =>
            $callSid,
        
        ];

        $this->uri = '/Accounts/' . \rawurlencode($accountSid)
        .'/Calls/' . \rawurlencode($callSid)
        .'/UserDefinedMessageSubscriptions.json';
    }

    /**
     * Create the UserDefinedMessageSubscriptionInstance
     *
     * @param string $callback The URL we should call using the `method` to send user defined events to your application. URLs must contain a valid hostname (underscores are not permitted).
     * @param array|Options $options Optional Arguments
     * @return UserDefinedMessageSubscriptionInstance Created UserDefinedMessageSubscriptionInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create(string $callback, array $options = []): UserDefinedMessageSubscriptionInstance
    {

        $options = new Values($options);

        $data = Values::of([
            'Callback' =>
                $callback,
            'IdempotencyKey' =>
                $options['idempotencyKey'],
            'Method' =>
                $options['method'],
        ]);

        $headers = Values::of(['Content-Type' => 'application/x-www-form-urlencoded' ]);
        $payload = $this->version->create('POST', $this->uri, [], $data, $headers);

        return new UserDefinedMessageSubscriptionInstance(
            $this->version,
            $payload,
            $this->solution['accountSid'],
            $this->solution['callSid']
        );
    }


    /**
     * Constructs a UserDefinedMessageSubscriptionContext
     *
     * @param string $sid The SID that uniquely identifies this User Defined Message Subscription.
     */
    public function getContext(
        string $sid
        
    ): UserDefinedMessageSubscriptionContext
    {
        return new UserDefinedMessageSubscriptionContext(
            $this->version,
            $this->solution['accountSid'],
            $this->solution['callSid'],
            $sid
        );
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        return '[Twilio.Api.V2010.UserDefinedMessageSubscriptionList]';
    }
}
