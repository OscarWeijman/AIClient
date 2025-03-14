<?php

use OscarWeijman\AIClient\OpenAI\OpenAIClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

test('OpenAI client can be instantiated', function () {
    $client = new OpenAIClient('fake-api-key');
    expect($client)->toBeInstanceOf(OpenAIClient::class);
});

test('OpenAI client can make completion request', function () {
    // Create a mock response
    $mockResponse = [
        'id' => 'cmpl-123',
        'object' => 'text_completion',
        'created' => 1589478378,
        'model' => 'gpt-3.5-turbo-instruct',
        'choices' => [
            [
                'text' => 'This is a test response',
                'index' => 0,
                'logprobs' => null,
                'finish_reason' => 'length',
            ],
        ],
        'usage' => [
            'prompt_tokens' => 5,
            'completion_tokens' => 7,
            'total_tokens' => 12,
        ],
    ];

    // Create a mock handler
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], json_encode($mockResponse)),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $container = [];
    $history = Middleware::history($container);
    $handlerStack->push($history);
    
    $httpClient = new Client(['handler' => $handlerStack]);
    
    // Create a reflection of the OpenAIClient to set the httpClient property
    $client = new OpenAIClient('fake-api-key');
    $reflection = new ReflectionClass($client);
    $property = $reflection->getProperty('httpClient');
    $property->setAccessible(true);
    $property->setValue($client, $httpClient);
    
    // Make the request
    $result = $client->completion('Test prompt');
    
    // Check the request
    expect($container)->toHaveCount(1);
    $request = $container[0]['request'];
    expect((string) $request->getBody())->toContain('Test prompt');
    
    // Check the response
    expect($result)->toBeArray();
    expect($result['provider'])->toBe('openai');
    expect($result['content'])->toBe('This is a test response');
});

test('OpenAI client can make chat completion request', function () {
    // Create a mock response
    $mockResponse = [
        'id' => 'chatcmpl-123',
        'object' => 'chat.completion',
        'created' => 1589478378,
        'model' => 'gpt-3.5-turbo',
        'choices' => [
            [
                'message' => [
                    'role' => 'assistant',
                    'content' => 'This is a test chat response',
                ],
                'index' => 0,
                'finish_reason' => 'stop',
            ],
        ],
        'usage' => [
            'prompt_tokens' => 10,
            'completion_tokens' => 15,
            'total_tokens' => 25,
        ],
    ];

    // Create a mock handler
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], json_encode($mockResponse)),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $container = [];
    $history = Middleware::history($container);
    $handlerStack->push($history);
    
    $httpClient = new Client(['handler' => $handlerStack]);
    
    // Create a reflection of the OpenAIClient to set the httpClient property
    $client = new OpenAIClient('fake-api-key');
    $reflection = new ReflectionClass($client);
    $property = $reflection->getProperty('httpClient');
    $property->setAccessible(true);
    $property->setValue($client, $httpClient);
    
    // Make the request
    $messages = [
        ['role' => 'user', 'content' => 'Hello, AI!']
    ];
    $result = $client->chatCompletion($messages);
    
    // Check the request
    expect($container)->toHaveCount(1);
    $request = $container[0]['request'];
    $body = json_decode((string) $request->getBody(), true);
    expect($body['messages'][0]['content'])->toBe('Hello, AI!');
    
    // Check the response
    expect($result)->toBeArray();
    expect($result['provider'])->toBe('openai');
    expect($result['content'])->toBe('This is a test chat response');
});