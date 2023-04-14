<?php

declare(strict_types=1);

namespace EightLines\SyliusSimPayPlugin\SimPay;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

final class SimPayHttpClient
{
    private Client $client;

    public function __construct(SimPayAuthorization $authorization)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.simpay.pl',
            'headers' => $authorization->getHeaders(),
        ]);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<string, mixed>|null
     *
     * @throws JsonException
     * @throws GuzzleException
     */
    public function request(string $method, string $uri, array $payload = []): ?array
    {
        $response = $this->client->request($method, $uri, [
            ('GET' === $method ? 'query' : 'json') => $payload,
            'allow_redirects' => false,
        ]);

        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<string, mixed>|null
     */
    public function saveTransaction(int $serviceId, array $payload): ?array
    {
        try {
            $response = $this->request('POST', sprintf('/directbilling/%d/transactions', $serviceId), $payload);

            if (false === $response['success']) {
                return null;
            }

            return $response['data'] ?? null;

        } catch (JsonException | GuzzleException $e) {
            return null;
        }
    }
}
