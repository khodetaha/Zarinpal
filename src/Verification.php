<?php

namespace Khodetaha\Zarinpal;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Verification
{
    /** @var string */
    private $merchantId;

    /** @var int */
    private $amount;

    /** @var string */
    private $authority;

    /** @var Client */
    private $client;

    public function __construct(string $merchantId, int $amount)
    {
        $this->merchantId = $merchantId;
        $this->amount = $amount;
        $this->client = new Client();
    }

    public function send(): VerificationResponse
    {
        $url = 'https://api.zarinpal.com/pg/v4/payment/verify.json';

        $data = [
            'merchant_id' => $this->merchantId,
            'amount' => $this->amount,
            'authority' => $this->authority,
        ];

        try {
            $response = $this->client->post($url, [
                'json' => $data, // Send data as JSON
                'headers' => [
                    'Accept' => 'application/json', // Accept JSON response
                ],
            ]);

            $responseBody = json_decode($response->getBody(), true); // Decode JSON response
            return new VerificationResponse($responseBody);
        } catch (RequestException $e) {
            // Handle the exception as needed (log it, rethrow, etc.)
            throw new \Exception("HTTP request failed: " . $e->getMessage());
        }
    }

    public function authority(string $authority): self
    {
        $this->authority = $authority;

        return $this;
    }
}
