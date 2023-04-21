<?php

declare(strict_types=1);

namespace Tests\SimPay\SyliusSimPayPlugin\Behat\Page\External;

use FriendsOfBehat\PageObjectExtension\Page\PageInterface;

interface SimPayCheckoutPageInterface extends PageInterface
{
    public function pay(): void;

    public function failedPayment(): void;
}
