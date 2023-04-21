<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\SimPay;

final class SimPayServiceAuthorization
{
    public function __construct(
        public int $serviceId,
        public string $serviceApiKey,
    ) { }
}
