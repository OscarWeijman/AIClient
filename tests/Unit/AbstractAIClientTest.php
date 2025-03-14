<?php

use OscarWeijman\AIClient\AbstractAIClient;
use OscarWeijman\AIClient\Interfaces\AIClientInterface;
use GuzzleHttp\Client as HttpClient;

// Create a concrete implementation of AbstractAIClient for testing
class ConcreteAIClient extends AbstractAIClient
{
    protected string $baseUrl = 'https://api.example.com/';
    
    protected function getDefaultHeaders(): array
    {
        return [
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type' => 'application/json',
        ];
    }
    
    protected function processResponse(array $response): array
    {
        return [
            'provider' => 'test',
            'content' => $response['content'] ?? null,
            'raw_response' => $response,
            'finish_reason' => $response['finish_reason'] ?? null,
            'model' => $response['model'] ?? null,
            'usage' => $response['usage'] ?? null,
        ];
    }
    
    public function completion(string $prompt, array $options = []): array
    {
        return $this->processResponse([
            'content' => 'Test completion response',
            'finish_reason' => 'stop',
            'model' => 'test-model',
            'usage' => ['total_tokens' => 10],
        ]);
    }
    
    public function chatCompletion(array $messages, array $options = []): array
    {
        return $this->processResponse([
            'content' => 'Test chat response',
            'finish_reason' => 'stop',
            'model' => 'test-model',
            'usage' => ['total_tokens' => 15],
        ]);
    }
    
    // Expose protected methods for testing
    public function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }
    
    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}

test('AbstractAIClient implements AIClientInterface', function () {
    $client = new ConcreteAIClient('test-api-key');
    expect($client)->toBeInstanceOf(AIClientInterface::class);
});

test('AbstractAIClient constructor sets API key', function () {
    $client = new ConcreteAIClient('test-api-key');
    expect($client->getApiKey())->toBe('test-api-key');
});

test('AbstractAIClient constructor creates HTTP client with correct base URI', function () {
    $client = new ConcreteAIClient('test-api-key');
    $httpClient = $client->getHttpClient();
    
    // Get the configuration from the HTTP client
    $reflection = new ReflectionObject($httpClient);
    $property = $reflection->getProperty('config');
    $property->setAccessible(true);
    $config = $property->getValue($httpClient);
    
    expect((string)$config['base_uri'])->toBe('https://api.example.com/');
});

test('AbstractAIClient constructor sets default headers', function () {
    $client = new ConcreteAIClient('test-api-key');
    $httpClient = $client->getHttpClient();
    
    // Get the configuration from the HTTP client
    $reflection = new ReflectionObject($httpClient);
    $property = $reflection->getProperty('config');
    $property->setAccessible(true);
    $config = $property->getValue($httpClient);
    
    expect($config['headers'])->toHaveKey('Authorization');
    expect($config['headers']['Authorization'])->toBe('Bearer test-api-key');
    expect($config['headers'])->toHaveKey('Content-Type');
    expect($config['headers']['Content-Type'])->toBe('application/json');
});

test('AbstractAIClient constructor accepts additional options', function () {
    $client = new ConcreteAIClient('test-api-key', [
        'timeout' => 30,
        'verify' => false,
    ]);
    $httpClient = $client->getHttpClient();
    
    // Get the configuration from the HTTP client
    $reflection = new ReflectionObject($httpClient);
    $property = $reflection->getProperty('config');
    $property->setAccessible(true);
    $config = $property->getValue($httpClient);
    
    expect($config)->toHaveKey('timeout');
    expect($config['timeout'])->toBe(30);
    expect($config)->toHaveKey('verify');
    expect($config['verify'])->toBeFalse();
});