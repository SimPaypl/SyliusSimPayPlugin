<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\Resolver;

use SimPay\SyliusSimPayPlugin\Repository\PaymentMethodRepositoryInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentInterface as BasePaymentInterface;
use Sylius\Component\Payment\Resolver\PaymentMethodsResolverInterface;
use Webmozart\Assert\Assert;

final class ChannelBasedPaymentMethodsResolver implements PaymentMethodsResolverInterface
{
    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
    ) { }

    public function getSupportedMethods(BasePaymentInterface $subject): array
    {
        /** @var PaymentInterface $subject */
        Assert::isInstanceOf($subject, PaymentInterface::class);
        Assert::true($this->supports($subject), 'This payment method is not support by resolver');

        $channel = $subject->getOrder()->getChannel();

        if ($subject->getCurrencyCode() !== 'PLN') {
            return $this->paymentMethodRepository->findAllAvailableExceptThoseUsingSimPayGatewayForChannel($channel);
        }

        return $this->paymentMethodRepository->findEnabledForChannel($channel);
    }

    public function supports(BasePaymentInterface $subject): bool
    {
        return
            $subject instanceof PaymentInterface
            && null !== $subject->getOrder()
            && null !== $subject->getOrder()->getChannel()
        ;
    }
}
