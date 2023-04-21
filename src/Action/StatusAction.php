<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\Action;

use ArrayAccess;
use SimPay\SyliusSimPayPlugin\Bridge\SimPayDirectBillingBridgeInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\Request\GetStatusInterface;

final class StatusAction implements ActionInterface
{
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

    public function execute($request): void
    {
        /** @var $request GetStatusInterface */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());
        $transactionId = $model['transactionId'] ?? null;
        $transactionStatus = $model['transactionStatus'] ?? null;

        if ((null === $transactionStatus || SimPayDirectBillingBridgeInterface::TRANSACTION_DB_NEW_STATUS === $transactionStatus) && null !== $transactionId) {
            $request->markNew();

        } elseif (SimPayDirectBillingBridgeInterface::TRANSACTION_DB_CONFIRMED_STATUS === $transactionStatus) {
            $request->markPending();

        } elseif (SimPayDirectBillingBridgeInterface::TRANSACTION_DB_REJECTED_STATUS === $transactionStatus) {
            $request->markFailed();

        } elseif (SimPayDirectBillingBridgeInterface::TRANSACTION_DB_CANCELED_STATUS === $transactionStatus) {
            $request->markCanceled();

        } elseif (SimPayDirectBillingBridgeInterface::TRANSACTION_DB_PAYED_STATUS === $transactionStatus) {
            $request->markCaptured();

        } elseif (SimPayDirectBillingBridgeInterface::TRANSACTION_DB_GENERATE_ERROR_STATUS === $transactionStatus) {
            $request->markFailed();

        } else {
            $request->markUnknown();
        }
    }

    public function supports($request): bool
    {
        return
            $request instanceof GetStatusInterface
            && $request->getModel() instanceof ArrayAccess;
    }
}
