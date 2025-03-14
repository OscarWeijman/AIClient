<?php

use OscarWeijman\AIClient\OpenAI\OpenAIClient;
use OscarWeijman\AIClient\Exceptions\AIClientException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

test('Client throws AIClientException on API error', function () {
    // Create a mock handler that returns an error
    $mock = new MockHandler([
        new RequestException('Error Communicating with Server', new Request('POST', 'completions')),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);
    
    // Create a reflection of the OpenAIClient to set the httpClient property
    $client = new OpenAIClient('fake-api-key');
    $reflection = new ReflectionClass($client);
    $property = $reflection->getProperty('httpClient');
    $property->setAccessible(true);
    $property->setValue($client, $httpClient);
    
    // The request should throw an AIClientException
    expect(fn() => $client->completion('Test prompt'))
        ->toThrow(AIClientException::class, 'OpenAI API error: Error Communicating with Server');
});

test('Client throws AIClientException on API rate limit', function () {
    // Create a mock handler that returns a rate limit error
    $mock = new MockHandler([
        new Response(429, [], json_encode([
            'error' => [
                'message' => 'Rate limit exceeded',
                'type' => 'rate_limit_error',
                'code' => 'rate_limit_exceeded'
            ]
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);
    
    // Create a reflection of the OpenAIClient to set the httpClient property
    $client = new OpenAIClient('fake-api-key');
    $reflection = new ReflectionClass($client);
    $property = $reflection->getProperty('httpClient');
    $property->setAccessible(true);
    $property->setValue($client, $httpClient);
    
    // The request should throw an AIClientException
    expect(fn() => $client->completion('Test prompt'))
        ->toThrow(AIClientException::class);
});

test('Client throws AIClientException on API authentication error', function () {
    // Create a mock handler that returns an authentication error
    $mock = new MockHandler([
        new Response(401, [], json_encode([
            'error' => [
                'message' => 'Invalid Authentication',
                'type' => 'invalid_request_error',
                'code' => 'invalid_api_key'
            ]
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);
    
    // Create a reflection of the OpenAIClient to set the httpClient property
    $client = new OpenAIClient('fake-api-key');
    $reflection = new ReflectionClass($client);
    $property = $reflection->getProperty('httpClient');
    $property->setAccessible(true);
    $property->setValue($client, $httpClient);
    
    // The request should throw an AIClientException
    expect(fn() => $client->completion('Test prompt'))
        ->toThrow(AIClientException::class);
});