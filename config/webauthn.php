<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Webauthn challenge length
    |--------------------------------------------------------------------------
    |
    | Length of the random string used in the challenge request.
    |
    */

    'challenge_length' => 32,

    /*
    |--------------------------------------------------------------------------
    | Webauthn timeout (milliseconds)
    |--------------------------------------------------------------------------
    |
    | Time that the caller is willing to wait for the call to complete.
    |
    */

    'timeout' => 60000,

    /*
    |--------------------------------------------------------------------------
    | Webauthn extension client input
    |--------------------------------------------------------------------------
    |
    | Optional authentication extension.
    | See https://www.w3.org/TR/webauthn/#client-extension-input
    |
    */

    'extensions' => [],

    /*
    |--------------------------------------------------------------------------
    | Webauthn icon
    |--------------------------------------------------------------------------
    |
    | Url which resolves to an image associated with the entity.
    | See https://www.w3.org/TR/webauthn/#dom-publickeycredentialentity-icon
    |
    */

    'icon' => null,

    /*
    |--------------------------------------------------------------------------
    | Webauthn Attestation Conveyance
    |--------------------------------------------------------------------------
    |
    | This parameter specify the preference regarding the attestation conveyance
    | during credential generation.
    | See https://www.w3.org/TR/webauthn/#attestation-convey
    |
    */

    'attestation_conveyance' => \Webauthn\PublicKeyCredentialCreationOptions::ATTESTATION_CONVEYANCE_PREFERENCE_NONE,

    /*
    |--------------------------------------------------------------------------
    | Google Safetynet ApiKey
    |--------------------------------------------------------------------------
    |
    | Api key to use Google Safetynet.
    | See https://developer.android.com/training/safetynet/attestation
    |
    */

    'google_safetynet_api_key' => '',

    /*
    |--------------------------------------------------------------------------
    | Webauthn Public Key Credential Parameters
    |--------------------------------------------------------------------------
    |
    | List of allowed Cryptographic Algorithm Identifier.
    | See https://www.w3.org/TR/webauthn/#alg-identifier
    |
    */

    'public_key_credential_parameters' => [
        \Cose\Algorithms::COSE_ALGORITHM_ES256,
        \Cose\Algorithms::COSE_ALGORITHM_RS256,
    ],

    /*
    |--------------------------------------------------------------------------
    | Webauthn Authenticator Selection Criteria
    |--------------------------------------------------------------------------
    |
    | Requirement for the creation operation.
    | See https://www.w3.org/TR/webauthn/#authenticatorSelection
    |
    */

    'authenticator_selection_criteria' => [

        /*
        | See https://www.w3.org/TR/webauthn/#attachment
        */
        'attachment_mode' => \Webauthn\AuthenticatorSelectionCriteria::AUTHENTICATOR_ATTACHMENT_NO_PREFERENCE,

        'require_resident_key' => false,

        /*
        | See https://www.w3.org/TR/webauthn/#userVerificationRequirement
        */
        'user_verification' => \Webauthn\AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_PREFERRED,
    ],

];
