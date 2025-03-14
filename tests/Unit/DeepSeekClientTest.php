<?php

use OscarWeijman\AIClient\DeepSeek\DeepSeekClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

test('DeepSeek client can be instantiated', function () {
    $client = new DeepSeekClient('fake-api-key');
    expect($client)->toBeInstanceOf(DeepSeekClient::class);
});

test('DeepSeek client can make completion request', function () {
    // Create a mock response
    $mockResponse = [
        'id' => 'cmpl-456',
        'object' => 'text_completion',
        'created' => 1589478378,
        'model' => 'deepseek-coder',
        'choices' => [
            [
                'text' => 'This is a DeepSeek test response',
                'index' => 0,
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
    
    // Create a reflection of the DeepSeekClient to set the httpClient property
    $client = new DeepSeekClient('fake-api-key');
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
    expect($result['provider'])->toBe('deepseek');
    expect($result['content'])->toBe('This is a DeepSeek test response');
});

test('DeepSeek client can make chat completion request', function () {
    // Create a mock response
    $mockResponse = [
        'id' => 'chatcmpl-456',
        'object' => 'chat.completion',
        'created' => 1589478378,
        'model' => 'deepseek-chat',
        'choices' => [
            [
                'message' => [
                    'role' => 'assistant',
                    'content' => 'This is a DeepSeek chat response',
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
    
    // Create a reflection of the DeepSeekClient to set the httpClient property
    $client = new DeepSeekClient('fake-api-key');
    $reflection = new ReflectionClass($client);
    $property = $reflection->getProperty('httpClient');
    $property->setAccessible(true);
    $property->setValue($client, $httpClient);
    
    // Make the request
    $messages = [
        ['role' => 'user', 'content' => 'Hello, DeepSeek!']
    ];
    $result = $client->chatCompletion($messages);
    
    // Check the request
    expect($container)->toHaveCount(1);
    $request = $container[0]['request'];
    $body = json_decode((string) $request->getBody(), true);
    expect($body['messages'][0]['content'])->toBe('Hello, DeepSeek!');
    
    // Check the response
    expect($result)->toBeArray();
    expect($result['provider'])->toBe('deepseek');
    expect($result['content'])->toBe('This is a DeepSeek chat response');
});