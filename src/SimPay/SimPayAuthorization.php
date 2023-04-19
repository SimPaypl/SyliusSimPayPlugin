<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\SimPay;

final class SimPayAuthorization
{
    public function __construct(
        private string $apiKey,
        private string $apiPassword,
        private string $lang = 'en'
    ) { }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return [
            'X-SIM-KEY' => $this->apiKey,
            'X-SIM-PASSWORD' => $this->apiPassword,
            'X-SIM-LANG' => $this->lang,
            'X-SIM-VERSION' => '2.1.1',
            'X-SIM-PLATFORM' => 'PHP',
        ];
    }
}
