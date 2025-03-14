<?php

use OscarWeijman\AIClient\AIClientFactory;

/**
 * @group integration
 */
test('OpenAI API integration test', function () {
    $apiKey = $_ENV['OPENAI_API_KEY'] ?? null;
    
    if (!$apiKey || ($_ENV['ENABLE_API_TESTS'] ?? 'false') !== 'true') {
        $this->markTestSkipped('Skipping OpenAI API test: API key not set or tests not enabled');
    }
    
    $client = AIClientFactory::create('openai', $apiKey);
    
    // Test completion
    $result = $client->completion('Say hello', ['max_tokens' => 10]);
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('provider');
    expect($result['provider'])->toBe('openai');
    expect($result)->toHaveKey('content');
    expect($result['content'])->not->toBeEmpty();
});

/**
 * @group integration
 */
test('DeepSeek API integration test', function () {
    $apiKey = $_ENV['DEEPSEEK_API_KEY'] ?? null;
    
    if (!$apiKey || ($_ENV['ENABLE_API_TESTS'] ?? 'false') !== 'true') {
        $this->markTestSkipped('Skipping DeepSeek API test: API key not set or tests not enabled');
    }
    
    $client = AIClientFactory::create('deepseek', $apiKey);
    
    // Test chat completion
    $result = $client->chatCompletion([
        ['role' => 'user', 'content' => 'Say hello']
    ], ['max_tokens' => 10]);
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('provider');
    expect($result['provider'])->toBe('deepseek');
    expect($result)->toHaveKey('content');
    expect($result['content'])->not->toBeEmpty();
});