<?php
namespace Senviok\Services;

use Senviok\HttpClient;

class Webhooks
{
    private HttpClient $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new webhook subscription.
     */
    public function create(array $payload): array
    {
        return $this->client->post('/v1/webhooks', $payload);
    }

    /**
     * List all webhooks.
     */
    public function list(): array
    {
        return $this->client->get('/v1/webhooks');
    }

    /**
     * Get delivery logs for a webhook.
     */
    public function getLogs(string $id): array
    {
        return $this->client->get("/v1/webhooks/{$id}/deliveries");
    }

    /**
     * Delete a webhook subscription.
     */
    public function delete(string $id): array
    {
        return $this->client->delete("/v1/webhooks/{$id}");
    }

    /**
     * Verify an incoming webhook's HMAC-SHA256 signature.
     */
    public function verifySignature(string $rawBody, string $signatureHeader, string $secret): bool
    {
        $computed = hash_hmac('sha256', $rawBody, $secret);
        return hash_equals($computed, $signatureHeader);
    }
}
