<?php

declare(strict_types=1);

namespace Tests\SimPay\SyliusSimPayPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Shop\Checkout\CompletePageInterface;
use Sylius\Behat\Page\Shop\Order\ShowPageInterface;
use Tests\SimPay\SyliusSimPayPlugin\Behat\Page\External\SimPayCheckoutPageInterface;
use Tests\SimPay\SyliusSimPayPlugin\Behat\Service\Mocker\SimPayApiMocker;

final class SimPayContext implements Context
{
    public function __construct(
        private CompletePageInterface $summaryPage,
        private SimPayCheckoutPageInterface $simPayCheckoutPage,
        private ShowPageInterface $orderDetails,
        private SimPayApiMocker $simPayApiMocker,
    ) { }

    /**
     * @When I confirm my order with SimPay payment
     * @Given I have confirmed my order with SimPay payment
     */
    public function iConfirmMyOrderWithSimPayPayment(): void
    {
        $this->simPayApiMocker->mockApiSuccessfulVerifyTransaction(function (): void {
            $this->summaryPage->confirmOrder();
        });
    }

    /**
     * @When I sign in to SimPay and pay successfully
     */
    public function iSignInToSimPayAndPaySuccessfully(): void
    {
        $this->simPayApiMocker->mockApiSuccessfulVerifyTransaction(function (): void {
            $this->simPayCheckoutPage->pay();
        });
    }

    /**
     * @When I try to pay again SimPay payment
     */
    public function iTryToPayAgainSimPayPayment(): void
    {
        $this->simPayApiMocker->mockApiSuccessfulVerifyTransaction(function (): void {
            $this->orderDetails->pay();
        });
    }

    /**
     * @When I cancel my SimPay payment
     * @Given I have canceled SimPay payment
     */
    public function iFailedMySimPayPayment(): void
    {
        $this->simPayApiMocker->mockApiSuccessfulVerifyTransaction(function (): void {
            $this->simPayCheckoutPage->failedPayment();
        });
    }
}
