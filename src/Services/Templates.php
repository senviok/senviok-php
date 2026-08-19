<?php
namespace Senviok\Services;

use Senviok\HttpClient;

class Templates
{
    private HttpClient $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new HTML template.
     */
    public function create(array $payload): array
    {
        return $this->client->post('/v1/templates', $payload);
    }

    /**
     * List all HTML templates.
     */
    public function list(): array
    {
        return $this->client->get('/v1/templates');
    }

    /**
     * Get a single template.
     */
    public function get(string $id): array
    {
        return $this->client->get("/v1/templates/{$id}");
    }

    /**
     * Update an HTML template.
     */
    public function update(string $id, array $payload): array
    {
        return $this->client->put("/v1/templates/{$id}", $payload);
    }

    /**
     * Delete an HTML template.
     */
    public function delete(string $id): array
    {
        return $this->client->delete("/v1/templates/{$id}");
    }
}
