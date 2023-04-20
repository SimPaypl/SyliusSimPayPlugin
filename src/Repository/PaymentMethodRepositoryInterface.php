<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\Repository;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Core\Repository\PaymentMethodRepositoryInterface as BasePaymentMethodRepositoryInterface;

interface PaymentMethodRepositoryInterface extends BasePaymentMethodRepositoryInterface
{
    /**
     * @return PaymentMethod[]
     */
    public function findEnabled(): array;

    /**
     * @return PaymentMethod[]
     */
    public function findAllAvailableExceptThoseUsingSimPayGateway(): array;

    /**
     * @return PaymentMethod[]
     */
    public function findAllAvailableExceptThoseUsingSimPayGatewayForChannel(ChannelInterface $channel): array;
}
