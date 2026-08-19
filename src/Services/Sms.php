<?php
namespace Senviok\Services;

use Senviok\HttpClient;

class Sms
{
    private HttpClient $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send a transactional SMS message or verification alert.
     */
    public function send(array $payload): array
    {
        return $this->client->post('/v1/sms', $payload);
    }
}
