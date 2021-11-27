<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Pschocke\LaravelWebauthn\Models\WebauthnCredential;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasWebauthn
{
    public function webauthnCredentials(): HasMany
    {
        return $this->hasMany(WebauthnCredential::class, 'credentiable_id');
    }
}
