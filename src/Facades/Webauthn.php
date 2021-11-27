<?php

namespace Pschocke\LaravelWebauthn\Facades;

use Illuminate\Support\Facades\Facade;
use Pschocke\LaravelWebauthn\Contracts\WebauthnCredentiable;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRequestOptions;

/**
 * @method static PublicKeyCredentialCreationOptions getRegisterData(\Illuminate\Contracts\Auth\Authenticatable $user)
 * @method static \Pschocke\LaravelWebauthn\Models\WebauthnCredential doRegister(WebauthnCredentiable $model, PublicKeyCredentialCreationOptions $publicKey, string $data, string $keyName)
 * @method static PublicKeyCredentialRequestOptions getAuthenticateData(WebauthnCredentiable $model)
 * @method static bool doAuthenticate(\Illuminate\Contracts\Auth\Authenticatable $user, PublicKeyCredentialRequestOptions $publicKey, string $data)
 * @method static void forceAuthenticate()
 * @method static bool check()
 * @method static bool enabled(\Illuminate\Contracts\Auth\Authenticatable $user)
 * @method static bool canRegister(\Illuminate\Contracts\Auth\Authenticatable $user)
 *
 * @see \Pschocke\LaravelWebauthn\Webauthn
 */
class Webauthn extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Pschocke\LaravelWebauthn\Services\Webauthn::class;
    }
}
