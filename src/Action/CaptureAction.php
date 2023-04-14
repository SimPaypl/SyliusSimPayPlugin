<?php

declare(strict_types=1);

namespace EightLines\SyliusSimPayPlugin\Action;

use EightLines\SyliusSimPayPlugin\Bridge\SimPayDirectBillingBridgeInterface;
use EightLines\SyliusSimPayPlugin\Exception\SimPayException;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Payum\Core\Security\TokenInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class CaptureAction implements ActionInterface, ApiAwareInterface, GenericTokenFactoryAwareInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    private ?GenericTokenFactoryInterface $tokenFactory;

    public function __construct(
        private SimPayDirectBillingBridgeInterface $simPayDirectBillingBridge,
    ) { }

    public function setApi($api): void
    {
        if (false === is_array($api)) {
            throw new UnsupportedApiException('Not supported. Expected to be set as array.');
        }

        $this->simPayDirectBillingBridge->setAuthorizationData(
            $api['simpay_api_key'],
            $api['simpay_api_password'],
            (int) $api['simpay_service_id'],
            $api['simpay_service_api_key'],
        );
    }

    /**
     * @throws SimPayException
     */
    public function execute($request): void
    {
        /** @var Capture $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = $request->getModel();

        /** @var OrderInterface $order */
        $order = $request->getFirstModel()->getOrder();

        /** @var TokenInterface $token */
        $token = $request->getToken();

        $notifyToken = $this->tokenFactory->createNotifyToken($token->getGatewayName(), $token->getDetails());

        $transaction = $this->simPayDirectBillingBridge->createTransaction()
            ->setAmount($order->getTotal() / 100)
            ->setControl($notifyToken->getHash())
            ->setDescription($order->getNumber())
            ->setAfterUrl($token->getAfterUrl());

        $result = $transaction->make();

        if (null !== $result) {
            $model['transactionId'] = $result['transactionId'];

            $request->setModel($model);

            throw new HttpRedirect($result['redirectUrl']);
        }

        throw new SimPayException('Unable to create transaction.');
    }

    public function setGenericTokenFactory(GenericTokenFactoryInterface $genericTokenFactory = null): void
    {
        $this->tokenFactory = $genericTokenFactory;
    }

    public function supports($request): bool
    {
        return
            $request instanceof Capture
            && $request->getModel() instanceof ArrayObject;
    }
}
