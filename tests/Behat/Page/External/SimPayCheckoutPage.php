<?php

declare(strict_types=1);

namespace Tests\SimPay\SyliusSimPayPlugin\Behat\Page\External;

use Behat\Mink\Exception\DriverException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\Mink\Session;
use FriendsOfBehat\PageObjectExtension\Page\Page;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use Payum\Core\Security\TokenInterface;
use SimPay\SyliusSimPayPlugin\Bridge\SimPayDirectBillingBridgeInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Tests\SimPay\SyliusSimPayPlugin\Behat\Service\Mocker\SimPayApiMocker;
use RuntimeException;

final class SimPayCheckoutPage extends Page implements SimPayCheckoutPageInterface
{
    public function __construct(
        Session $session,
        MinkParameters $minkParameters,
        private SimPayApiMocker $simPayApiMocker,
        private RepositoryInterface $securityTokenRepository,
    ) {
        parent::__construct($session, $minkParameters);
    }

    protected function getUrl(array $urlParameters = []): string
    {
        return 'https://api.simpay.pl/';
    }

    public function pay(): void
    {
        $captureToken = $this->findToken('after');
        $notifyToken = $this->findToken('notify');

        $payload = [
            'status' => SimPayDirectBillingBridgeInterface::TRANSACTION_DB_PAYED_STATUS
        ];

        $this->simPayApiMocker->mockApiSuccessfulVerifyTransaction(
            /**
             * @throws DriverException
             * @throws UnsupportedDriverActionException
             */
            function () use ($notifyToken, $payload, $captureToken): void {
                $this->getDriver()->getClient()->request('POST', $notifyToken->getTargetUrl(), $payload);
                $this->getDriver()->visit($captureToken->getTargetUrl());
            }
        );
    }

    public function failedPayment(): void
    {
        // TODO: Implement failedPayment() method.
    }

    private function findToken(string $type = 'capture'): TokenInterface
    {
        $tokens = array_reverse($this->securityTokenRepository->findAll());

        /** @var TokenInterface $token */
        foreach ($tokens as $token) {
            if (strpos($token->getTargetUrl(), $type)) {
                return $token;
            }
        }

        throw new RuntimeException(
            sprintf('Cannot find %s token, check if you are after proper checkout steps', $type)
        );
    }
}
