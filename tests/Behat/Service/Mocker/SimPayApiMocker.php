<?php

declare(strict_types=1);

namespace Tests\SimPay\SyliusSimPayPlugin\Behat\Service\Mocker;

use SimPay\SyliusSimPayPlugin\Bridge\SimPayDirectBillingBridgeInterface;
use Sylius\Behat\Service\Mocker\MockerInterface;

final class SimPayApiMocker
{
    public function __construct(
        private MockerInterface $mocker
    ) { }

    public function mockApiSuccessfulVerifyTransaction(callable $action): void
    {
        $mockService = $this->mocker->mockService(
            'simpay.sylius_simpay_plugin.bridge.simpay_directbilling_bridge',
            SimPayDirectBillingBridgeInterface::class
        );

        $mockService
            ->shouldReceive('setAuthorizationData');

        $mockService
            ->shouldReceive('setAmountType');

        $action();

        $this->mocker->unmockService('simpay.sylius_simpay_plugin.bridge.simpay_directbilling_bridge');
    }
}
