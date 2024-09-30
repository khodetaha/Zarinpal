<?php

namespace Khodetaha\Zarinpal;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Request
{
    /** @var string */
    private $merchantId;

    /** @var int */
    private $amount;

    /** @var string */
    private $description;

    /** @var string */
    private $callbackUrl;

    /** @var string */
    private $mobile;

    /** @var string */
    private $email;

    /** @var Client */
    private $client;

    public function __construct(string $merchantId, int $amount)
    {
        $this->merchantId = $merchantId;
        $this->amount = $amount;
        $this->client = new Client();
    }

    public function send(): RequestResponse
    {
        $url = 'https://api.zarinpal.com/pg/v4/payment/request.json';

        $metadata = [];
        
        if ($this->mobile) {
            $metadata['mobile'] = $this->mobile;
        }
        
        if ($this->email) {
            $metadata['email'] = $this->email;
        }

        $data = [
            'merchant_id' => $this->merchantId,
            'currency' => $_ENV["ZARINPAL_CURRENCY"] ?: "IRT",
            'amount' => $this->amount,
            'description' => $this->description,
            'callback_url' => $this->callbackUrl,
            'metadata' => $metadata,
        ];

        try {
            // Send the request using Guzzle
            $response = $this->client->post($url, [
                'json' => $data,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);
    
            // Decode the response and convert it to an array
            return new RequestResponse(json_decode($response->getBody()->getContents(), true)); // Convert stdClass to array
        } catch (RequestException $e) {
            // Create a response object with error details
            $errorResponse = new RequestResponse([
                'Status' => 0, // Or any specific error code you want to set
                'Message' => "Error sending request: " . $e->getMessage(),
            ]);
            return $errorResponse; // Return the error response
        }
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function callbackUrl(string $callbackUrl): self
    {
        $this->callbackUrl = $callbackUrl;

        return $this;
    }

    public function mobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
