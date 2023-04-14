<?php

declare(strict_types=1);

namespace EightLines\SyliusSimPayPlugin\SimPay\DirectBilling;

use EightLines\SyliusSimPayPlugin\SimPay\DirectBilling\Exception\NotificationException;
use EightLines\SyliusSimPayPlugin\SimPay\SimPayAuthorization;
use EightLines\SyliusSimPayPlugin\SimPay\SimPayHttpClient;
use EightLines\SyliusSimPayPlugin\SimPay\SimPayServiceAuthorization;
use JsonException;

final class DirectBilling
{
    private SimPayHttpClient $simPayHttpClient;

    public function __construct(
        private SimPayAuthorization $authorization,
        private SimPayServiceAuthorization $serviceAuthorization,
    ) {
        $this->simPayHttpClient = new SimPayHttpClient($this->authorization);
    }

    public function createTransaction(): Transaction
    {
        return new Transaction(
            $this->simPayHttpClient,
            $this->serviceAuthorization,
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
