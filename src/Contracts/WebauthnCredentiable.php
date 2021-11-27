<?php

namespace Pschocke\LaravelWebauthn\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface WebauthnCredentiable
{
    public function webauthnCredentials(): HasMany;

    public function getKey();
}
