<?php
namespace Senviok\Services;

use Senviok\HttpClient;

class Emails
{
    private HttpClient $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send a transactional or template-based email.
     */
    public function send(array $payload): array
    {
        return $this->client->post('/v1/emails', $payload);
    }

    /**
     * List paginated received emails for the workspace inbox.
     */
    public function listInbox(array $query = []): array
    {
        return $this->client->get('/v1/inbox', $query);
    }

    /**
     * Retrieve details of a parsed incoming email and temporary presigned attachment URLs.
     */
    public function getInbox(string $id): array
    {
        return $this->client->get("/v1/inbox/{$id}");
    }

    /**
     * Toggle read/unread status for an inbound email.
     */
    public function markInboxRead(string $id, bool $isRead = true): array
    {
        return $this->client->patch("/v1/inbox/{$id}/read", ['isRead' => $isRead]);
    }

    /**
     * Archive/Delete a received email.
     */
    public function deleteInbox(string $id): array
    {
        return $this->client->delete("/v1/inbox/{$id}");
    }

    /**
     * Reply directly to a received email thread.
     */
    public function replyInbox(string $id, array $payload): array
    {
        return $this->client->post("/v1/inbox/{$id}/reply", $payload);
    }
}
