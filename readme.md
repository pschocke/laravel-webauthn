Webauthn adapter for Laravel
============================

LaravelWebauthn is an adapter to use Webauthn in Laravel. It provides you with all the tools you need to build 2fa login or authorisation.

It is highly inspired and copies a lot of code from the [laravel-webauthn package by asbiin](https://github.com/asbiin/laravel-webauthn), but comes without routes, controllers and middlewares to give you maximum flexibilities. It is basically the extracted core functionality.

If you want a ready to go implementation to just drop into your application, be sure to check his repository out.

# Installation

You may use Composer to install this package into your Laravel project:

``` bash
composer require pschocke/laravel-webauthn
```

You don't need to add this package to your service providers.

## Support

This package supports Laravel 5.8 and newer, and has been tested with php 7.2 and newer versions.

It's based on [asbiin/laravel-webauthn](https://github.com/asbiin/laravel-webauthn), which in turn is based on [web-auth/webauthn-framework](https://github.com/web-auth/webauthn-framework).


## Important

Your browser will refuse to negotiate a relay to your security device without the following:

- domain (localhost and 127.0.0.1 will be rejected by `webauthn.js`)
- an SSL/TLS certificate trusted by your browser (self-signed is okay)
- connected HTTPS on port 443 (ports other than 443 will be rejected)

## Configuration

You can publish the LaravelWebauthn configuration in a file named `config/webauthn.php`, and resources.
Just run this artisan command:

```sh
php artisan vendor:publish --provider="Pschocke\LaravelWebauthn\LaravelWebauthnServiceProvider"
```

This publishes the config file to `/config/webauthn.php` and a new migration.

After that, run your migrations
```sh
php artisan migrate
```


### initial configuration

Webauthn is typically used with the User model, but to give you the ability to use it with another model, you need to implement the `WebauthnCredentiable` interface on the model that is used for authentication:

```php
class User extends Authenticatable implements Pschocke\LaravelWebauthn\Contracts\WebauthnCredentiable {}
```
## Usage

The following examples just show ONE way to register and authenticate. There are a lot of other ways to make webauthn authorisation work.

### Registration of new Webauthn device

To use a webauthn device (e.g. touchID, Yubikey, Windows Hello, etc...) to authenticate a user, you first need to register the device and connect it to the user.

The registration is initiated by javascript and validated on the server side. First, generate a public key and give it to the javascript:

```php
$publicKey = Pschocke\LaravelWebauthn\Facades\Webauthn::getRegisterData($user);
```
Be sure to keep the public key present for the validation part, e.g. in session or in your livewire component.

```html
  <!-- load javascript part -->
  <script src="{!! secure_asset('vendor/pschocke/webauthn-laravel/webauthn.js') !!}"></script>
...
    <!-- button click to run registration -->
    <button onclick="register()">
        Register new Device
    </button>

  <!-- form to send datas to -->
  <form method="POST" id="form">
    @csrf
    <input type="hidden" name="register" id="register" />
    <input type="hidden" name="name" id="name" />
  </form>
...
  <!-- script part to run the sign part -->
  <script>
    var publicKey = {!! json_encode($publicKey) !!};

    var webauthn = new WebAuthn();

    register = function() {
        webauthn.register(
          publicKey,
          function (datas) {
            document.getElementById('register').value = JSON.stringify(datas);
            document.getElementById('form').submit();
          }
        );
    }
  </script>
```

And on submit, validate the response and attach it to the user:

```php
Pschocke\LaravelWebauthn\Facades\Webauthn::doRegister(
    $user,
    $publicKey,
    $submittedData,
    $nameOfTheKey
);
```
This method will throw an exception if it encounters corrupted data. If it runs without error, the key has been registered and you can notify the user about its success.

## Authenticate

After a user has registered a webauthn device, you can check if a given device is registered to a given user:

First, you need to generate a public key and send it to your javascript:

```php
$publicKey = Pschocke\LaravelWebauthn\Facades\Webauthn::getAuthenticateData($user);
```
Be sure to keep the public key present for the validation part, e.g. in session or in your livewire component.


```html
  <!-- load javascript part -->
  <script src="{!! secure_asset('vendor/pschocke/webauthn-laravel/webauthn.js') !!}"></script>
...
    <!-- button click to run registration -->
    <button onclick="register()">
        Register new Device
    </button>
  <!-- form to send datas to -->
  <form method="POST" action="" id="form">
    @csrf
    <input type="hidden" name="data" id="data" />
  </form>
...
  <!-- script part to run the sign part -->
  <script>
    var publicKey = {!! json_encode($publicKey) !!};

    var webauthn = new WebAuthn();

    webauthn.sign(
      publicKey,
      function (datas) {
        document.getElementById("data").value = JSON.stringify(datas),
        document.getElementById("form").submit();
      }
    );
  </script>
```

Finally you need to validate the publickey response:

```php
$result = Webauthn::doAuthenticate(
    $request->user(),
    $publicKey,
    $request->input( 'data')
);
```

This method will throw an exception if it encounters corrupted data.

If result is true, your user has been checked successfully and you are free to log him in/authorize it for a part of your application, etc.


# License
Licensed under the MIT License. [View license](/LICENSE).

A lot of the code was written by [Alexis Saettler](https://github.com/asbiin)
