<?php

declare(strict_types=1);

namespace Tests\SimPay\SyliusSimPayPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use SimPay\SyliusSimPayPlugin\Bridge\SimPayDirectBillingBridgeInterface;
use SimPay\SyliusSimPayPlugin\SimPayGatewayFactory;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Repository\PaymentMethodRepositoryInterface;

final class SimPayContext implements Context
{
    public function __construct(
        private SharedStorageInterface $sharedStorage,
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
        private ExampleFactoryInterface $paymentMethodExampleFactory,
        private EntityManagerInterface $entityManager
    ) { }

    /**
     * @Given the store has (also) a payment method :paymentMethodName with a code :paymentMethodCode and SimPay Checkout Gateway
     */
    public function theStoreHasAPaymentMethodWithACodeAndSimPayCheckoutGateway(
        string $paymentMethodName,
        string $paymentMethodCode
    ): void
    {
        $paymentMethod = $this->createPaymentMethod($paymentMethodName, $paymentMethodCode);

        $paymentMethod->getGatewayConfig()->setConfig([
            'simpay_api_key' => 'api_key',
            'simpay_api_password' => 'api_password',
            'simpey_service_id' => 123,
            'simpay_service_api_key' => 'service_api_key',
            'simpay_amount_type' => SimPayDirectBillingBridgeInterface::AMOUNT_TYPE_GROSS,
        ]);

        $this->entityManager->flush();
    }

    private function createPaymentMethod(
        string $name,
        string $code,
    ): PaymentMethodInterface
    {
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $this->paymentMethodExampleFactory->create([
            'name' => $name,
            'code' => $code,
            'description' => '',
            'enabled' => true,
            'gatewayName' => SimPayGatewayFactory::FACTORY_TITLE,
            'gatewayFactory' => SimPayGatewayFactory::FACTORY_NAME,
            'channels' => [$this->sharedStorage->get('channel')],
        ]);

        $this->sharedStorage->set('payment_method', $paymentMethod);
        $this->paymentMethodRepository->add($paymentMethod);

        return $paymentMethod;
    }
}
