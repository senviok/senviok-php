<?php
namespace Senviok\Services;

use Senviok\HttpClient;

class Domains
{
    private HttpClient $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Register a new custom sending domain.
     */
    public function create(array $payload): array
    {
        return $this->client->post('/v1/domains', $payload);
    }

    /**
     * List registered custom domains.
     */
    public function list(): array
    {
        return $this->client->get('/v1/domains');
    }

    /**
     * Get DKIM records for a domain.
     */
    public function getDkim(string $id): array
    {
        return $this->client->get("/v1/domains/{$id}/dkim");
    }

    /**
     * Trigger verification of domain records propagation.
     */
    public function verify(string $id): array
    {
        return $this->client->post("/v1/domains/{$id}/verify");
    }

    /**
     * Delete a registered custom domain.
     */
    public function delete(string $id): array
    {
        return $this->client->delete("/v1/domains/{$id}");
    }
}
