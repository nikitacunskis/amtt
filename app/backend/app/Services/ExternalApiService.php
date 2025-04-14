<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExternalApiService
{
    protected string $baseUrl;

    public function setBaseUrl(string $url): void
    {
        $this->baseUrl = rtrim($url, '/');
    }

    public function get(string $endpoint, array $params = []): array
    {
        $response = Http::baseUrl($this->baseUrl)->get($endpoint, $params);

        if ($response->failed()) {
            throw new \RuntimeException('External API call failed: ' . $response->body());
        }

        return $response->json();
    }

    public function post(string $endpoint, array $data = []): array
    {
        $response = Http::baseUrl($this->baseUrl)->post($endpoint, $data);

        if ($response->failed()) {
            throw new \RuntimeException('External API POST request failed: ' . $response->body());
        }

        return $response->json();
    }

    public function put(string $endpoint, array $data = []): array
    {
        $response = Http::baseUrl($this->baseUrl)->put($endpoint, $data);

        if ($response->failed()) {
            throw new \RuntimeException('External API PUT request failed: ' . $response->body());
        }

        return $response->json();
    }

    public function patch(string $endpoint, array $data = []): array
    {
        $response = Http::baseUrl($this->baseUrl)->patch($endpoint, $data);

        if ($response->failed()) {
            throw new \RuntimeException('External API PATCH request failed: ' . $response->body());
        }

        return $response->json();
    }

    public function delete(string $endpoint, array $data = []): array
    {
        $response = Http::baseUrl($this->baseUrl)->delete($endpoint, $data);

        if ($response->failed()) {
            throw new \RuntimeException('External API DELETE request failed: ' . $response->body());
        }

        return $response->json();
    }
}