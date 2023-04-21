<?php

declare(strict_types=1);

namespace Tests\SimPay\SyliusSimPayPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Test\Services\DefaultChannelFactoryInterface;

final class ChannelContext implements Context
{
    public function __construct(
        private DefaultChannelFactoryInterface $unitedStatesChannelFactory,
        private SharedStorageInterface $sharedStorage
    ) { }

    /**
     * @Given the store operates on a single channel in "United States" but with :currencyCode currency
     */
    public function storeOperatesOnASingleChannelInUnitedStatesButWithSpecifiedCurrency(string $currencyCode): void
    {
        $defaultData = $this->unitedStatesChannelFactory->create(null, null, $currencyCode);

        $this->sharedStorage->setClipboard($defaultData);
        $this->sharedStorage->set('channel', $defaultData['channel']);
    }
}
