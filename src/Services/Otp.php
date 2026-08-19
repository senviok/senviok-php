<?php
namespace Senviok\Services;

use Senviok\HttpClient;

class Otp
{
    private HttpClient $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Generate and dispatch a secure verification PIN over SMS or Email.
     */
    public function send(array $payload): array
    {
        return $this->client->post('/v1/otp/send', $payload);
    }

    /**
     * Validate an OTP verification PIN.
     */
    public function verify(array $payload): array
    {
        return $this->client->post('/v1/otp/verify', $payload);
    }

    /**
     * Resend an active OTP PIN without generating a new one if still valid.
     */
    public function resend(array $payload): array
    {
        return $this->client->post('/v1/otp/resend', $payload);
    }
}
