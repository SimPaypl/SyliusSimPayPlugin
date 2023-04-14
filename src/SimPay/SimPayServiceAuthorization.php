<?php

declare(strict_types=1);

namespace EightLines\SyliusSimPayPlugin\SimPay;

final class SimPayServiceAuthorization
{
    public function __construct(
        public int $serviceId,
        public string $serviceApiKey,
    ) { }
}
