<?php
namespace Senviok\Services;

use Senviok\HttpClient;

class Suppressions
{
    private HttpClient $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Add an email address to the suppression list.
     */
    public function create(array $payload): array
    {
        return $this->client->post('/v1/suppressions', $payload);
    }

    /**
     * List all suppressions.
     */
    public function list(): array
    {
        return $this->client->get('/v1/suppressions');
    }

    /**
     * Remove an email from the suppression list.
     */
    public function delete(string $id): array
    {
        return $this->client->delete("/v1/suppressions/{$id}");
    }
}
