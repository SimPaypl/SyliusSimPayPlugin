<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\Bridge;

use SimPay\SyliusSimPayPlugin\SimPay\DirectBilling\DirectBilling;
use SimPay\SyliusSimPayPlugin\SimPay\DirectBilling\Exception\NotificationException;
use SimPay\SyliusSimPayPlugin\SimPay\DirectBilling\Notification;
use SimPay\SyliusSimPayPlugin\SimPay\DirectBilling\Transaction;
use SimPay\SyliusSimPayPlugin\SimPay\SimPayAuthorization;
use SimPay\SyliusSimPayPlugin\SimPay\SimPayServiceAuthorization;
use JsonException;
use Webmozart\Assert\Assert;

final class SimPayDirectBillingBridge implements SimPayDirectBillingBridgeInterface
{
    private ?DirectBilling $directBilling;

    public function setAuthorizationData(
        string $apiKey,
        string $apiPassword,
        int $serviceId,
        string $serviceApiKey
    ): void
    {
        $this->directBilling = new DirectBilling(
            new SimPayAuthorization($apiKey, $apiPassword),
            new SimPayServiceAuthorization($serviceId, $serviceApiKey),
        );
    }

    public function setAmountType(string $amountType): void
    {
        Assert::isInstanceOf(
            $this->directBilling,
            DirectBilling::class,
            'You must set authorization data before setting amount type.'
        );

        $this->directBilling->setAmountType($amountType);
    }

    public function createTransaction(): Transaction
    {
        Assert::isInstanceOf(
            $this->directBilling,
            DirectBilling::class,
            'You must set authorization data before creating transaction.'
        );

        return $this->directBilling->createTransaction();
    }

    /**
     * @throws JsonException
     * @throws NotificationException
     */
    public function consumeNotification(string $payload): Notification
    {
        Assert::isInstanceOf(
            $this->directBilling,
            DirectBilling::class,
            'You must set authorization data before creating transaction.'
        );

        return $this->directBilling->consumeNotification($payload);
    }
}
