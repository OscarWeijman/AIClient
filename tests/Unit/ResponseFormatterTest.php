<?php

use OscarWeijman\AIClient\OpenAI\OpenAIClient;
use OscarWeijman\AIClient\DeepSeek\DeepSeekClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

// Test om te controleren of de response formatters correct werken
test('OpenAI response formatter correctly processes completion response', function () {
    // Create a mock OpenAI client
    $client = new OpenAIClient('fake-api-key');
    
    // Create a reflection to access the protected method
    $reflection = new ReflectionClass($client);
    $method = $reflection->getMethod('processResponse');
    $method->setAccessible(true);
    
    // Test data
    $rawResponse = [
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
    
    // Process the response
    $result = $method->invoke($client, $rawResponse);
    
    // Check the result
    expect($result)->toBeArray();
    expect($result['provider'])->toBe('openai');
    expect($result['content'])->toBe('This is a test response');
    expect($result['finish_reason'])->toBe('length');
    expect($result['model'])->toBe('gpt-3.5-turbo-instruct');
    expect($result['usage'])->toBe($rawResponse['usage']);
    expect($result['raw_response'])->toBe($rawResponse);
});

test('OpenAI response formatter correctly processes chat completion response', function () {
    // Create a mock OpenAI client
    $client = new OpenAIClient('fake-api-key');
    
    // Create a reflection to access the protected method
    $reflection = new ReflectionClass($client);
    $method = $reflection->getMethod('processResponse');
    $method->setAccessible(true);
    
    // Test data
    $rawResponse = [
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
    
    // Process the response
    $result = $method->invoke($client, $rawResponse);
    
    // Check the result
    expect($result)->toBeArray();
    expect($result['provider'])->toBe('openai');
    expect($result['content'])->toBe('This is a test chat response');
    expect($result['finish_reason'])->toBe('stop');
    expect($result['model'])->toBe('gpt-3.5-turbo');
    expect($result['usage'])->toBe($rawResponse['usage']);
    expect($result['raw_response'])->toBe($rawResponse);
});

test('DeepSeek response formatter correctly processes completion response', function () {
    // Create a mock DeepSeek client
    $client = new DeepSeekClient('fake-api-key');
    
    // Create a reflection to access the protected method
    $reflection = new ReflectionClass($client);
    $method = $reflection->getMethod('processResponse');
    $method->setAccessible(true);
    
    // Test data
    $rawResponse = [
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
    
    // Process the response
    $result = $method->invoke($client, $rawResponse);
    
    // Check the result
    expect($result)->toBeArray();
    expect($result['provider'])->toBe('deepseek');
    expect($result['content'])->toBe('This is a DeepSeek test response');
    expect($result['finish_reason'])->toBe('length');
    expect($result['model'])->toBe('deepseek-coder');
    expect($result['usage'])->toBe($rawResponse['usage']);
    expect($result['raw_response'])->toBe($rawResponse);
});

test('DeepSeek response formatter correctly processes chat completion response', function () {
    // Create a mock DeepSeek client
    $client = new DeepSeekClient('fake-api-key');
    
    // Create a reflection to access the protected method
    $reflection = new ReflectionClass($client);
    $method = $reflection->getMethod('processResponse');
    $method->setAccessible(true);
    
    // Test data
    $rawResponse = [
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
    
    // Process the response
    $result = $method->invoke($client, $rawResponse);
    
    // Check the result
    expect($result)->toBeArray();
    expect($result['provider'])->toBe('deepseek');
    expect($result['content'])->toBe('This is a DeepSeek chat response');
    expect($result['finish_reason'])->toBe('stop');
    expect($result['model'])->toBe('deepseek-chat');
    expect($result['usage'])->toBe($rawResponse['usage']);
    expect($result['raw_response'])->toBe($rawResponse);
});