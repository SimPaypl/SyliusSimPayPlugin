<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class SimPayGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults(
            [
                'payum.factory_name' => 'simpay',
                'payum.factory_title' => 'SimPay',
            ]
        );

        if (false === (bool)$config['payum.api']) {
            $config['payum.default_options'] = [
                'simpay_api_key' => '',
                'simpay_api_password' => '',
                'simpay_service_id' => '',
                'simpay_service_api_key' => '',
            ];

            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = [
                'simpay_api_key',
                'simpay_api_password',
                'simpay_service_id',
                'simpay_service_api_key',
            ];

            $config['payum.api'] = static function (ArrayObject $config): array {
                $config->validateNotEmpty($config['payum.required_options']);

                return [
                    'simpay_api_key' => $config['simpay_api_key'],
                    'simpay_api_password' => $config['simpay_api_password'],
                    'simpay_service_id' => $config['simpay_service_id'],
                    'simpay_service_api_key' => $config['simpay_service_api_key'],
                ];
            };
        }
    }
}
