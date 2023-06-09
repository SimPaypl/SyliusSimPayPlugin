<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\Action;

use SimPay\SyliusSimPayPlugin\Bridge\SimPayDirectBillingBridgeInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;

final class ConvertPaymentAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    public function execute($request): void
    {
        /** @var Convert $request */
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        $details['amount'] = $payment->getTotalAmount() / 100;
        $details['description'] = $payment->getDescription();
        $details['status'] = SimPayDirectBillingBridgeInterface::TRANSACTION_DB_NEW_STATUS;

        $request->setResult((array) $details);
    }

    public function supports($request): bool
    {
        return
            $request instanceof Convert
            && $request->getSource() instanceof PaymentInterface
            && 'array' === $request->getTo();
    }
}
