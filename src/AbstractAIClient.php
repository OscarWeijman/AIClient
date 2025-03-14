<?php

namespace OscarWeijman\AIClient;

use GuzzleHttp\Client as HttpClient;
use OscarWeijman\AIClient\Interfaces\AIClientInterface;

abstract class AbstractAIClient implements AIClientInterface
{
    protected HttpClient $httpClient;
    protected string $apiKey;
    protected string $baseUrl;
    
    /**
     * Constructor
     *
     * @param string $apiKey The API key for the service
     * @param array $options Additional options for the HTTP client
     */
    public function __construct(string $apiKey, array $options = [])
    {
        $this->apiKey = $apiKey;
        $this->httpClient = new HttpClient(array_merge([
            'base_uri' => $this->baseUrl,
            'headers' => $this->getDefaultHeaders(),
        ], $options));
    }
    
    /**
     * Get default headers for API requests
     *
     * @return array
     */
    abstract protected function getDefaultHeaders(): array;
    
    /**
     * Process the API response
     *
     * @param array $response The raw API response
     * @return array The processed response
     */
    abstract protected function processResponse(array $response): array;
}