<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\Resolver;

use SimPay\SyliusSimPayPlugin\Repository\PaymentMethodRepositoryInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Payment\Resolver\PaymentMethodsResolverInterface;

final class PaymentMethodsResolver implements PaymentMethodsResolverInterface
{
    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
    ) { }

    public function getSupportedMethods(PaymentInterface $subject): array
    {
        if ($subject->getCurrencyCode() !== 'PLN') {
            return $this->paymentMethodRepository->findAllAvailableExceptThoseUsingSimPayGateway();
        }

        return $this->paymentMethodRepository->findEnabled();
    }

    public function supports(PaymentInterface $subject): bool
    {
        return true;
    }
}
