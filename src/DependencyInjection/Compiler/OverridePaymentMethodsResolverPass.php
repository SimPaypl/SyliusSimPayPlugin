<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use SimPay\SyliusSimPayPlugin\Resolver\PaymentMethodsResolver;
use Symfony\Component\DependencyInjection\Reference;

final class OverridePaymentMethodsResolverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container->getDefinition('sylius.payment_methods_resolver')
            ->setClass(PaymentMethodsResolver::class)
            ->setArgument(0, new Reference('sylius.repository.payment_method'));

        $container->getDefinition('sylius.payment_methods_resolver.default')
            ->setClass(PaymentMethodsResolver::class)
            ->setArgument(0, new Reference('sylius.repository.payment_method'));
    }
}
