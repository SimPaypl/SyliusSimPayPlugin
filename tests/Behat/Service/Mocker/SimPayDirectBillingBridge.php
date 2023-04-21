<?php

declare(strict_types=1);

namespace Tests\SimPay\SyliusSimPayPlugin\Behat\Service\Mocker;

use SimPay\SyliusSimPayPlugin\SimPay\DirectBilling\Notification;
use SimPay\SyliusSimPayPlugin\SimPay\DirectBilling\Transaction;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class SimPayDirectBillingBridge
{

    public function __construct(
        private ContainerInterface $container
    ) { }

    public function setAuthorizationData(
        string $apiKey,
        string $apiPassword,
        int $serviceId,
        string $serviceApiKey
    ): void
    {
        $this->container->get('simpay.sylius_simpay_plugin.bridge.simpay_directbilling_bridge')->setAuthorizationData(
            $apiKey,
            $apiPassword,
            $serviceId,
            $serviceApiKey
        );
    }

    public function setAmountType(string $amountType): void
    {
        $this->container->get('simpay.sylius_simpay_plugin.bridge.simpay_directbilling_bridge')->setAmountType($amountType);
    }

    public function createTransaction(): Transaction
    {
        return $this->container->get('simpay.sylius_simpay_plugin.bridge.simpay_directbilling_bridge')->createTransaction();
    }

    public function consumeNotification(string $payload): Notification
    {
        return $this->container->get('simpay.sylius_simpay_plugin.bridge.simpay_directbilling_bridge')->consumeNotification($payload);
    }
}
