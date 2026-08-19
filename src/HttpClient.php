<?php
namespace Senviok;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Senviok\Exceptions\ApiException;

class HttpClient
{
    private Client $client;

    public function __construct(string $apiKey, string $baseUrl)
    {
        $this->client = new Client([
            'base_uri' => rtrim($baseUrl, '/') . '/',
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    public function request(string $method, string $path, array $options = []): array
    {
        try {
            $response = $this->client->request($method, ltrim($path, '/'), $options);
            $body = (string)$response->getBody();
            return json_decode($body, true) ?? [];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 500;
            $bodyContent = $response ? (string)$response->getBody() : '';
            $decodedBody = json_decode($bodyContent, true) ?? [];
            
            $message = $decodedBody['Message'] ?? $decodedBody['error'] ?? $e->getMessage();
            throw new ApiException($message, $statusCode, $decodedBody);
        }
    }

    public function get(string $path, array $query = []): array
    {
        return $this->request('GET', $path, ['query' => $query]);
    }

    public function post(string $path, array $body = []): array
    {
        return $this->request('POST', $path, ['json' => $body]);
    }

    public function put(string $path, array $body = []): array
    {
        return $this->request('PUT', $path, ['json' => $body]);
    }

    public function patch(string $path, array $body = []): array
    {
        return $this->request('PATCH', $path, ['json' => $body]);
    }

    public function delete(string $path): array
    {
        return $this->request('DELETE', $path);
    }
}
