<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\SimPay\DirectBilling;

use SimPay\SyliusSimPayPlugin\Bridge\SimPayDirectBillingBridgeInterface;
use SimPay\SyliusSimPayPlugin\SimPay\SimPayHttpClient;
use SimPay\SyliusSimPayPlugin\SimPay\SimPayServiceAuthorization;
use Webmozart\Assert\Assert;

final class Transaction
{
    private ?float $amount;

    private ?string $description;

    private ?string $control;

    private ?string $afterUrl;

    public function __construct(
        private SimPayHttpClient $simPayHttpClient,
        private SimPayServiceAuthorization $serviceAuthorization,
        private string $amountType = 'gross',
    ) { }

    public function setAmount(float $amount): Transaction
    {
        $this->amount = $amount;

        return $this;
    }

    public function setDescription(string $description): Transaction
    {
        $this->description = $description;

        return $this;
    }

    public function setControl(string $control): Transaction
    {
        $this->control = $control;

        return $this;
    }

    public function setAfterUrl(string $afterUrl): Transaction
    {
        $this->afterUrl = $afterUrl;

        return $this;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function make(): ?array
    {
        Assert::notNull($this->amount, 'Amount is required');
        Assert::notNull($this->amountType, 'Amount type is required');
        Assert::notNull($this->afterUrl, 'After URL is required');
        Assert::notNull($this->control, 'Control is required');
        Assert::true(
            $this->amountType === SimPayDirectBillingBridgeInterface::AMOUNT_TYPE_GROSS || $this->amountType === SimPayDirectBillingBridgeInterface::AMOUNT_TYPE_NET,
            'Amount type is invalid'
        );

        $payload = [
            'amount' => $this->amount,
            'amountType' => $this->amountType,
            'description' => $this->description ?? '',
            'control' => $this->control,
            'returns' => [
                'success' => $this->afterUrl,
                'failure' => $this->afterUrl,
            ],
        ];

        $payload['signature'] = $this->getSignature($payload);

        return $this->simPayHttpClient->saveTransaction(
            $this->serviceAuthorization->serviceId,
            $payload
        );
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function getSignature(array $payload): string
    {
        $payload['success'] = $payload['returns']['success'];
        $payload['failure'] = $payload['returns']['failure'];
        $payload['hashKey'] = $this->serviceAuthorization->serviceApiKey;

        unset($payload['returns']);

        return hash('sha256', implode('|', $payload));
    }
}
