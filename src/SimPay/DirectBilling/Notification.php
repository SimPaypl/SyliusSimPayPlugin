<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\SimPay\DirectBilling;

use SimPay\SyliusSimPayPlugin\SimPay\DirectBilling\Exception\NotificationException;
use SimPay\SyliusSimPayPlugin\SimPay\SimPayServiceAuthorization;
use JsonException;

final class Notification
{
    public array $payload;

    private SimPayServiceAuthorization $serviceAuthorization;

    /**
     * @throws JsonException
     * @throws NotificationException
     */
    public function __construct(
        SimPayServiceAuthorization $serviceAuthorization,
        string $payload = null,
    ) {
        $this->serviceAuthorization = $serviceAuthorization;
        $this->payload = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);

        $this->validate();
    }

    /**
     * @throws NotificationException
     */
    private function validate(): void
    {
        if (!isset($this->payload['signature'])) {
            throw new NotificationException('Signature param not found');
        }

        if ($this->getSignature() === $this->payload['signature']) {
            return;
        }

        throw new NotificationException('Bad signature');
    }

    private function getSignature(): string
    {
        $payload = [
            $this->payload['id'],
            $this->payload['service_id'],
            $this->payload['status'],
            $this->payload['values']['net'],
            $this->payload['values']['gross'],
            $this->payload['values']['partner'],
            $this->payload['returns']['complete'],
            $this->payload['returns']['failure'],
            $this->payload['control'],
        ];

        if (isset($this->payload['number_from'])) {
            $payload[] = $this->payload['number_from'];
        }

        if (isset($this->payload['provider'])) {
            $payload[] = $this->payload['provider'];
        }

        $payload[] = $this->serviceAuthorization->serviceApiKey;

        return hash('sha256', implode('|', $payload));
    }

    public function getStatus(): string
    {
        return $this->payload['status'];
    }
}
