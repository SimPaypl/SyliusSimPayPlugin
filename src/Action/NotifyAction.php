<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\Action;

use SimPay\SyliusSimPayPlugin\Bridge\SimPayDirectBillingBridgeInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\Notify;
use Sylius\Component\Core\Model\PaymentInterface;
use Webmozart\Assert\Assert;

final class NotifyAction implements ActionInterface, ApiAwareInterface
{
    use GatewayAwareTrait;

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
        /** @var Notify $request */
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getFirstModel();

        Assert::isInstanceOf(
            $payment,
            PaymentInterface::class,
            'Payment must be instance of PaymentInterface'
        );

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $payload = trim(file_get_contents('php://input'));
        $result = $this->simPayDirectBillingBridge->consumeNotification($payload);

        if (PaymentInterface::STATE_COMPLETED !== $payment->getState()) {
            $model['transactionStatus'] = $result->getStatus();

            $request->setModel($model);
        }

        throw new HttpResponse('OK', 200);
    }

    public function supports($request): bool
    {
        return
            $request instanceof Notify
            && $request->getModel() instanceof ArrayObject;
    }
}
