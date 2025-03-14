<?php

use OscarWeijman\AIClient\AIClientFactory;
use OscarWeijman\AIClient\Interfaces\AIClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

test('OpenAI client implements streamingChatCompletion method', function () {
    $client = AIClientFactory::create('openai', 'fake-api-key');
    expect($client)->toBeInstanceOf(AIClientInterface::class);
    $reflection = new ReflectionClass($client);
    expect($reflection->hasMethod('streamingChatCompletion'))->toBeTrue();
});

test('DeepSeek client implements streamingChatCompletion method', function () {
    $client = AIClientFactory::create('deepseek', 'fake-api-key');
    expect($client)->toBeInstanceOf(AIClientInterface::class);
    $reflection = new ReflectionClass($client);
    expect($reflection->hasMethod('streamingChatCompletion'))->toBeTrue();
});

test('OpenAI streamingChatCompletion makes correct API request', function () {
    // Create mock responses for streaming
    $responses = [
        new Response(200, [], "data: " . json_encode(['choices' => [['delta' => ['content' => 'Hello']]]]) . "\n\n"),
        new Response(200, [], "data: " . json_encode(['choices' => [['delta' => ['content' => ' world']]]]) . "\n\n"),
        new Response(200, [], "data: [DONE]\n\n"),
    ];
    
    $mock = new MockHandler($responses);
    $handlerStack = HandlerStack::create($mock);
    
    // Create a container to collect request data
    $container = [];
    $history = Middleware::history($container);
    $handlerStack->push($history);
    
    // Create a custom HTTP client with the mock handler
    $httpClient = new Client(['handler' => $handlerStack]);
    
    // Create a reflection of the OpenAI client to inject our mock HTTP client
    $clientClass = new ReflectionClass('OscarWeijman\AIClient\OpenAI\OpenAIClient');
    $client = $clientClass->newInstanceWithoutConstructor();
    
    // Set the HTTP client property
    $httpClientProperty = $clientClass->getProperty('httpClient');
    $httpClientProperty->setAccessible(true);
    $httpClientProperty->setValue($client, $httpClient);
    
    // Set the API key property
    $apiKeyProperty = $clientClass->getProperty('apiKey');
    $apiKeyProperty->setAccessible(true);
    $apiKeyProperty->setValue($client, 'fake-api-key');
    
    // Set the base URL property
    $baseUrlProperty = $clientClass->getProperty('baseUrl');
    $baseUrlProperty->setAccessible(true);
    $baseUrlProperty->setValue($client, 'https://api.openai.com/v1/');
    
    // Prepare test data
    $messages = [
        ['role' => 'user', 'content' => 'Say hello world'],
    ];
    
    // Collect the chunks
    $receivedChunks = [];
    $callback = function ($chunk) use (&$receivedChunks) {
        $receivedChunks[] = $chunk;
    };
    
    // Call the streaming method
    $client->streamingChatCompletion($messages, $callback);
    
    // Verify the request was made correctly
    expect($container)->toHaveCount(1);
    expect($container[0]['request']->getMethod())->toBe('POST');
    expect($container[0]['request']->getUri()->getPath())->toBe('chat/completions');
    
    // Verify the request body contains stream=true
    $requestBody = json_decode($container[0]['request']->getBody()->getContents(), true);
    expect($requestBody)->toHaveKey('stream');
    expect($requestBody['stream'])->toBeTrue();
});