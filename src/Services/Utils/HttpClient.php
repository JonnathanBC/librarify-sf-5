<?php

namespace App\Services\Utils;

use App\Services\Utils\HttpClientInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface as SymfonyHttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class HttpClient implements HttpClientInterface
{
    public function __construct(
        private SymfonyHttpClientInterface $httpClient
    ) {
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        return $this->httpClient->request($method, $url, $options);
    }
}
