<?php

namespace Pschocke\LaravelWebauthn\Services\Webauthn;

use Illuminate\Contracts\Config\Repository as Config;
use Pschocke\LaravelWebauthn\Contracts\CredentialRepositoryInterface;

abstract class AbstractFactory
{
    /**
     * The config repository instance.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Public Key Credential Source Repository.
     *
     * @var CredentialRepositoryInterface
     */
    protected $repository;

    public function __construct(Config $config, CredentialRepositoryInterface $repository)
    {
        $this->config = $config;
        // Credential Repository
        $this->repository = $repository;
    }
}
