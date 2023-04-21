<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\Resolver;

use Sylius\Component\Payment\Exception\UnresolvedDefaultPaymentMethodException;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentMethodInterface;
use Sylius\Component\Payment\Resolver\DefaultPaymentMethodResolverInterface;
use Webmozart\Assert\Assert;

final class DefaultPaymentMethodResolver implements DefaultPaymentMethodResolverInterface
{
    public function __construct(
        private ChannelBasedPaymentMethodsResolver $channelBasedPaymentMethodsResolver,
    ) { }

    /**
     * @throws UnresolvedDefaultPaymentMethodException
     */
    public function getDefaultPaymentMethod(PaymentInterface $payment): PaymentMethodInterface
    {
        $paymentMethods = $this->channelBasedPaymentMethodsResolver->getSupportedMethods($payment);

        if (empty($paymentMethods)) {
            throw new UnresolvedDefaultPaymentMethodException();
        }

        Assert::isInstanceOf($paymentMethods[0], PaymentMethodInterface::class);

        return $paymentMethods[0];
    }
}
