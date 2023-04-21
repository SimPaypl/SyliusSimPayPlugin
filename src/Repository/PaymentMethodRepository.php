<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\Repository;

use SimPay\SyliusSimPayPlugin\SimPayGatewayFactory;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\PaymentMethodRepository as BasePaymentMethodRepository;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\PaymentMethod;

final class PaymentMethodRepository extends BasePaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    /**
     * @return PaymentMethod[]
     */
    public function findEnabled(): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('o.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return PaymentMethod[]
     */
    public function findAllAvailableExceptThoseUsingSimPayGateway(): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.gatewayConfig', 'g')
            ->where('o.enabled = :enabled')
            ->andWhere('g.factoryName != :factoryName')
            ->setParameter('enabled', true)
            ->setParameter('factoryName', SimPayGatewayFactory::FACTORY_NAME)
            ->orderBy('o.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return PaymentMethod[]
     */
    public function findAllAvailableExceptThoseUsingSimPayGatewayForChannel(ChannelInterface $channel): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.gatewayConfig', 'g')
            ->where('o.enabled = :enabled')
            ->andWhere(':channel MEMBER OF o.channels')
            ->andWhere('g.factoryName != :factoryName')
            ->setParameter('enabled', true)
            ->setParameter('channel', $channel)
            ->setParameter('factoryName', SimPayGatewayFactory::FACTORY_NAME)
            ->orderBy('o.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
