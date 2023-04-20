<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\DependencyInjection\Compiler;

use SimPay\SyliusSimPayPlugin\Resolver\DefaultPaymentMethodResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class OverrideDefaultPaymentMethodResolverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container->getDefinition('sylius.payment_method_resolver.default')
            ->setClass(DefaultPaymentMethodResolver::class)
            ->setArgument(0, new Reference('sylius.payment_methods_resolver.channel_based'));
    }
}
