<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\SimPay\DirectBilling;

use SimPay\SyliusSimPayPlugin\SimPay\DirectBilling\Exception\NotificationException;
use SimPay\SyliusSimPayPlugin\SimPay\SimPayAuthorization;
use SimPay\SyliusSimPayPlugin\SimPay\SimPayHttpClient;
use SimPay\SyliusSimPayPlugin\SimPay\SimPayServiceAuthorization;
use JsonException;

final class DirectBilling
{
    private SimPayHttpClient $simPayHttpClient;

    private ?string $amountType;

    public function __construct(
        private SimPayAuthorization $authorization,
        private SimPayServiceAuthorization $serviceAuthorization,
    ) {
        $this->simPayHttpClient = new SimPayHttpClient($this->authorization);
    }

    public function setAmountType(string $amountType): void
    {
        $this->amountType = $amountType;
    }

    public function createTransaction(): Transaction
    {
        return new Transaction(
            $this->simPayHttpClient,
            $this->serviceAuthorization,
            $this->amountType,
        );
    }

    /**
     * @throws JsonException
     * @throws NotificationException
     */
    public function consumeNotification(string $payload): Notification
    {
        return new Notification(
            $this->serviceAuthorization,
            $payload
        );
    }
}
