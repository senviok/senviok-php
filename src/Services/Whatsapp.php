<?php
namespace Senviok\Services;

use Senviok\HttpClient;

class Whatsapp
{
    private HttpClient $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send a WhatsApp message using custom text or a pre-registered template.
     */
    public function send(array $payload): array
    {
        return $this->client->post('/v1/whatsapp', $payload);
    }
}
