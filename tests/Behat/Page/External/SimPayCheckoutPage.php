<?php

declare(strict_types=1);

namespace Tests\SimPay\SyliusSimPayPlugin\Behat\Page\External;

use FriendsOfBehat\PageObjectExtension\Page\Page;

final class SimPayCheckoutPage extends Page implements SimPayCheckoutPageInterface
{
    protected function getUrl(array $urlParameters = []): string
    {
        // TODO: Implement getUrl() method.
    }

    public function pay(): void
    {
        // TODO: Implement pay() method.
    }

    public function failedPayment(): void
    {
        // TODO: Implement failedPayment() method.
    }
}
