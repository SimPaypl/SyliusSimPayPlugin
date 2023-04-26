<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\Form\Type;

use SimPay\SyliusSimPayPlugin\Bridge\SimPayDirectBillingBridgeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class SimPayGatewayConfigurationType extends AbstractType
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) { }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'app_payment_notify',
                UrlType::class,
                [
                    'label' => 'simpay.sylius_simpay_plugin.app_payment_notify',
                    'required' => false,
                    'mapped' => false,
                    'disabled' => true,
                    'data' => $this->urlGenerator->generate('simpay_sylius_simpay_plugin_simpay_payment_notify', [], UrlGeneratorInterface::ABSOLUTE_URL),
                ]
            )
            ->add(
                'simpay_api_key',
                TextType::class,
                [
                    'label' => 'simpay.sylius_simpay_plugin.api_key',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'simpay.sylius_simpay_plugin.gateway_configuration.api_key.not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'simpay_api_password',
                TextType::class,
                [
                    'label' => 'simpay.sylius_simpay_plugin.api_password',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'simpay.sylius_simpay_plugin.gateway_configuration.api_password.not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'simpay_service_id',
                TextType::class,
                [
                    'label' => 'simpay.sylius_simpay_plugin.service_id',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'simpay.sylius_simpay_plugin.gateway_configuration.service_id.not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'simpay_service_api_key',
                TextType::class,
                [
                    'label' => 'simpay.sylius_simpay_plugin.service_api_key',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'simpay.sylius_simpay_plugin.gateway_configuration.service_api_key.not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'simpay_amount_type',
                ChoiceType::class,
                [
                    'label' => 'simpay.sylius_simpay_plugin.amount_type.label',
                    'choices' => [
                        'simpay.sylius_simpay_plugin.amount_type.choices.net' => SimPayDirectBillingBridgeInterface::AMOUNT_TYPE_NET,
                        'simpay.sylius_simpay_plugin.amount_type.choices.gross' => SimPayDirectBillingBridgeInterface::AMOUNT_TYPE_GROSS,
                    ],
                ]
            )
        ;
    }
}
