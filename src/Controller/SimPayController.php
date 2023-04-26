<?php

declare(strict_types=1);

namespace SimPay\SyliusSimPayPlugin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SimPayController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        return $this->redirectToRoute('payum_notify_do', [
            'request' => $request,
            'payum_token' => $request->request->get('control'),
        ], Response::HTTP_PERMANENTLY_REDIRECT);
    }
}
