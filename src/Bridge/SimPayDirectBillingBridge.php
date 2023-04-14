<?php

declare(strict_types=1);

namespace EightLines\SyliusSimPayPlugin\Bridge;

use EightLines\SyliusSimPayPlugin\SimPay\DirectBilling\DirectBilling;
use EightLines\SyliusSimPayPlugin\SimPay\DirectBilling\Exception\NotificationException;
use EightLines\SyliusSimPayPlugin\SimPay\DirectBilling\Notification;
use EightLines\SyliusSimPayPlugin\SimPay\DirectBilling\Transaction;
use EightLines\SyliusSimPayPlugin\SimPay\SimPayAuthorization;
use EightLines\SyliusSimPayPlugin\SimPay\SimPayServiceAuthorization;
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
