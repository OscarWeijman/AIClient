<?php

use OscarWeijman\AIClient\AIClientFactory;
use OscarWeijman\AIClient\OpenAI\OpenAIClient;
use OscarWeijman\AIClient\DeepSeek\DeepSeekClient;
use OscarWeijman\AIClient\Exceptions\AIClientException;

test('Factory can create OpenAI client', function () {
    $client = AIClientFactory::create('openai', 'fake-api-key');
    expect($client)->toBeInstanceOf(OpenAIClient::class);
});

test('Factory can create DeepSeek client', function () {
    $client = AIClientFactory::create('deepseek', 'fake-api-key');
    expect($client)->toBeInstanceOf(DeepSeekClient::class);
});

test('Factory throws exception for unsupported provider', function () {
    expect(fn() => AIClientFactory::create('unsupported', 'fake-api-key'))
        ->toThrow(AIClientException::class, 'Unsupported AI provider: unsupported');
});