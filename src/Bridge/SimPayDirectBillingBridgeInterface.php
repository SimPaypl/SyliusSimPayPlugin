<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\Bridge;

use SimPay\SyliusSimPayPlugin\SimPay\DirectBilling\Notification;
use SimPay\SyliusSimPayPlugin\SimPay\DirectBilling\Transaction;

interface SimPayDirectBillingBridgeInterface
{
    public const TRANSACTION_DB_NEW_STATUS = 'transaction_db_new';
    public const TRANSACTION_DB_CONFIRMED_STATUS = 'transaction_db_confirmed';
    public const TRANSACTION_DB_REJECTED_STATUS = 'transaction_db_rejected';
    public const TRANSACTION_DB_CANCELED_STATUS = 'transaction_db_canceled';
    public const TRANSACTION_DB_PAYED_STATUS = 'transaction_db_payed';
    public const TRANSACTION_DB_GENERATE_ERROR_STATUS = 'transaction_db_generate_error';

    public const AMOUNT_TYPE_NET = 'net';
    public const AMOUNT_TYPE_GROSS = 'gross';

    public function setAuthorizationData(
        string $apiKey,
        string $apiPassword,
        string $serviceId,
        string $serviceApiKey
    ): void;

    public function setAmountType(string $amountType): void;

    public function createTransaction(): Transaction;

    public function consumeNotification(string $payload): Notification;
}
