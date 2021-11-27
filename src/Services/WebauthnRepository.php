<?php

namespace Pschocke\LaravelWebauthn\Services;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Pschocke\LaravelWebauthn\Contracts\WebauthnCredentiable;
use Pschocke\LaravelWebauthn\Models\WebauthnCredential;
use Webauthn\PublicKeyCredentialSource;

abstract class WebauthnRepository
{
    /**
     * Create a new key.
     *
     * @param  WebauthnCredentiable  $user
     * @param  string  $keyName
     * @param  PublicKeyCredentialSource  $publicKeyCredentialSource
     * @return WebauthnCredential
     */
    public function create(WebauthnCredentiable $model, string $keyName, PublicKeyCredentialSource $publicKeyCredentialSource)
    {
        /** @var WebauthnCredential $webauthnKey */
        $webauthnKey = $model->webauthnCredentials()->make([
            'name' => $keyName,
        ]);

        $webauthnKey->publicKeyCredentialSource = $publicKeyCredentialSource;
        $webauthnKey->save();

        return $webauthnKey;
    }

    /**
     * Detect if user has a key.
     *
     * @param  WebauthnCredentiable  $model
     * @return bool
     */
    public function hasKey(WebauthnCredentiable $model): bool
    {
        return $model->webauthnCredentials()->count() > 0;
    }
}
