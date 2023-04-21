<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin;

use SimPay\SyliusSimPayPlugin\DependencyInjection\Compiler\OverrideChannelBasedPaymentMethodsResolverPass;
use SimPay\SyliusSimPayPlugin\DependencyInjection\Compiler\OverrideDefaultPaymentMethodResolverPass;
use SimPay\SyliusSimPayPlugin\DependencyInjection\Compiler\OverridePaymentMethodsResolverPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SimPaySyliusSimPayPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new OverrideChannelBasedPaymentMethodsResolverPass());
        $container->addCompilerPass(new OverridePaymentMethodsResolverPass());
        $container->addCompilerPass(new OverrideDefaultPaymentMethodResolverPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
