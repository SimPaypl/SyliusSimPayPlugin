
# SimPay SyliusSimPayPlugin
Integration of SimPay DirectBilling payments with Sylius application.

## Table of Content

* [Overview](#overview)
* [Installation](#installation)
* [Configuration](#configuration)
  * [Service](#service)
  * [Payment Method](#payment-method)
  * [Testing Mode](#testing-mode)
* [Contribution](#contribution)
* [Contact](#contact)

## Overview

The SyliusSimPayPlugin integrates [SimPay DirectBilling payments](https://www.simpay.pl/) with Sylius applications. 
After installing it you should be able to enable SimPay payment method (based on DirectBilling) in your web store.

## Why should I choose SimPay?

* Easy and fast integration – our plugin is inuitive to use, so you can handle the integration even without much skill.
* Instant earnings – we will send you profits from online payments every day.
* Safety and continuous technical support – we guarantee proper security for all payments. And in case of problems with the integration or operation of SimPay systems, we immediately respond with support.

## Installation

Firstly you should run the following command to install the plugin:

```bash
$ composer require simpay/sylius-simpay-plugin
```

After that, you should enable the plugin in your `config/bundles.php` file:

```php
return [
    SimPay\SyliusSimPayPlugin\SimPaySyliusSimPayPLugin::class => ['all' => true],
]
```

Then import routing in your `config/routes.yaml` file:

```yaml
simpay_sylius_simpay_plugin:
    resource: "@SimPaySyliusSimPayPlugin/config/routing.yml"
```

And finally, import configuration in your `config/packages/_sylius.yaml` file:

```yaml
imports:
    - { resource: "@SimPaySyliusSimPayPlugin/config/config.yml" }
```

Tada! You have installed the plugin. Now let's configure it.

## Configuration
To correctly configure the plugin, you have to visit [the SimPay website](https://www.simpay.pl/) and create an account.

### Service
Then you should create **a new DirectBilling Service**. During this process you will be asked to provide some data. The most important is the second step.

When you reach it, you should provide data like:
* Adres API,
* Adres Przekierowania (after success)
* Adres Przekierowania (after failure)

The first one should be the URL of your Sylius application with `/payment/simpay/notify` suffix. The last two should be the same as your Sylius application URL.

### Payment Method
After creating the service, you should go to your Sylius application and create a new **SimPay Payment Method**.
In **Gateway Configuration** you should provide four important values:
* API key
* API Password 
* Service ID
* Service API key

The first two you can find in your SimPay account on the API tab. `API key` is the `Klucz API` and `API Password` is the `Hasło API`.
The last two you can find in your SimPay account on the **Services Tab**.
`Service ID` is in the header of the **Service Details** (the number after `Szczegóły usługi ID`) and `Service API key` is in the `Klucz API` field.

The last option you can configure is `Amount Type` which can be `Net` or `Gross`. It depends on the type of your prices in Sylius.

***Important!*** Remember that SyliusSimPayPlugin works only with **PLN currency**, so the prices of your products should be in PLN.

### Testing Mode
When you are on the **Details Tab of the Service**, you should also **pay attention to the state of your testing mode**.

**Testing mode is enabled by default**. It means that you can test your payments without any costs.
When you want to start selling your products, you should change the state of this. Then you will be able to receive payments from your customers.

## Contribution

If you use Docker, setup local environment with the following command:

```bash
$ docker-compose up -d --build
```

Then enter the app container:

```bash
$ docker-compose exec app sh
```

And run the following command:

```bash
$ make init
```

## Contact

Do you have an issue with integration or want to learn more? Write to kontakt@simpay.pl
