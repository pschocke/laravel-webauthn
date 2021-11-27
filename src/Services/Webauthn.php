<?php

namespace Pschocke\LaravelWebauthn\Services;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Session\Session;
use Pschocke\LaravelWebauthn\Contracts\WebauthnCredentiable;
use Pschocke\LaravelWebauthn\Models\WebauthnCredential;
use Pschocke\LaravelWebauthn\Services\Webauthn\PublicKeyCredentialCreationOptionsFactory;
use Pschocke\LaravelWebauthn\Services\Webauthn\PublicKeyCredentialRequestOptionsFactory;
use Pschocke\LaravelWebauthn\Services\Webauthn\PublicKeyCredentialValidator;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRequestOptions;

class Webauthn extends WebauthnRepository
{
    /**
     * Laravel application.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Configuratoin repository.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Session manager.
     *
     * @var \Illuminate\Contracts\Session\Session
     */
    protected $session;

    /**
     * Event dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Create a new instance of Webauthn.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @param  \Illuminate\Contracts\Session\Session  $session
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     */
    public function __construct(Application $app, Config $config, Session $session, Dispatcher $events)
    {
        $this->app = $app;
        $this->config = $config;
        $this->session = $session;
        $this->events = $events;
    }

    /**
     * Get datas to register a new key.
     *
     * @param  User  $user
     * @return PublicKeyCredentialCreationOptions
     */
    public function getRegisterData(User $user): PublicKeyCredentialCreationOptions
    {
        $publicKey = $this->app->make(PublicKeyCredentialCreationOptionsFactory::class)
            ->create($user);

//        $this->events->dispatch(new WebauthnRegisterData($user, $publicKey));

        return $publicKey;
    }

    /**
     * Register a new key.
     *
     * @param  WebauthnCredentiable  $user
     * @param  PublicKeyCredentialCreationOptions  $publicKey
     * @param  string  $data
     * @param  string  $keyName
     * @return WebauthnCredential
     */
    public function doRegister(WebauthnCredentiable $user, PublicKeyCredentialCreationOptions $publicKey, string $data, string $keyName): WebauthnCredential
    {
        $publicKeyCredentialSource = $this->app->make(PublicKeyCredentialValidator::class)
            ->validate($publicKey, $data);

        $webauthnKey = $this->create($user, $keyName, $publicKeyCredentialSource);

//        $this->events->dispatch(new WebauthnRegister($webauthnKey));

        return $webauthnKey;
    }

    /**
     * Get datas to authenticate a user.
     *
     * @param  WebauthnCredentiable  $model
     * @return PublicKeyCredentialRequestOptions
     */
    public function getAuthenticateData(WebauthnCredentiable $model): PublicKeyCredentialRequestOptions
    {
        $publicKey = $this->app->make(PublicKeyCredentialRequestOptionsFactory::class)
            ->create($model);

//        $this->events->dispatch(new WebauthnLoginData($user, $publicKey));

        return $publicKey;
    }

    /**
     * Authenticate a user.
     *
     * @param  WebauthnCredentiable  $model
     * @param  PublicKeyCredentialRequestOptions  $publicKey
     * @param  string  $data
     * @return bool
     */
    public function doAuthenticate(WebauthnCredentiable $model, PublicKeyCredentialRequestOptions $publicKey, string $data): bool
    {
        return $this->app->make(PublicKeyCredentialValidator::class)
            ->check($model, $publicKey, $data);
    }

    /**
     * Test if the user has one webauthn key set or more.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return bool
     */
    public function enabled(): bool
    {
        return (bool) $this->config->get('webauthn.enable', true);
    }
}
