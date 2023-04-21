<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\DependencyInjection\Compiler;

use SimPay\SyliusSimPayPlugin\Resolver\ChannelBasedPaymentMethodsResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class OverrideChannelBasedPaymentMethodsResolverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container->getDefinition('sylius.payment_methods_resolver.channel_based')
            ->setClass(ChannelBasedPaymentMethodsResolver::class)
            ->setArgument(0, new Reference('sylius.repository.payment_method'));
    }
}
