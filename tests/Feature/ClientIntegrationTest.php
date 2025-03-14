<?php

use OscarWeijman\AIClient\AIClientFactory;
use OscarWeijman\AIClient\Interfaces\AIClientInterface;

test('Factory creates clients that implement AIClientInterface', function () {
    $openaiClient = AIClientFactory::create('openai', 'fake-api-key');
    $deepseekClient = AIClientFactory::create('deepseek', 'fake-api-key');
    
    expect($openaiClient)->toBeInstanceOf(AIClientInterface::class);
    expect($deepseekClient)->toBeInstanceOf(AIClientInterface::class);
});

test('Clients have consistent response format', function () {
    // Create mock clients with predefined responses
    $openaiClient = getMockOpenAIClient();
    $deepseekClient = getMockDeepSeekClient();
    
    // Test completion
    $openaiResult = $openaiClient->completion('Test prompt');
    $deepseekResult = $deepseekClient->completion('Test prompt');
    
    // Both should have the same response structure
    expect($openaiResult)->toHaveKeys(['provider', 'content', 'raw_response', 'finish_reason', 'model', 'usage']);
    expect($deepseekResult)->toHaveKeys(['provider', 'content', 'raw_response', 'finish_reason', 'model', 'usage']);
    
    // Test chat completion
    $messages = [['role' => 'user', 'content' => 'Hello']];
    $openaiChatResult = $openaiClient->chatCompletion($messages);
    $deepseekChatResult = $deepseekClient->chatCompletion($messages);
    
    // Both should have the same response structure
    expect($openaiChatResult)->toHaveKeys(['provider', 'content', 'raw_response', 'finish_reason', 'model', 'usage']);
    expect($deepseekChatResult)->toHaveKeys(['provider', 'content', 'raw_response', 'finish_reason', 'model', 'usage']);
});

// Helper function to create a mock OpenAI client
function getMockOpenAIClient() {
    $mockClient = Mockery::mock('OscarWeijman\AIClient\OpenAI\OpenAIClient');
    
    $mockClient->shouldReceive('completion')
        ->andReturn([
            'provider' => 'openai',
            'content' => 'OpenAI test response',
            'raw_response' => ['choices' => [['text' => 'OpenAI test response']]],
            'finish_reason' => 'stop',
            'model' => 'gpt-3.5-turbo',
            'usage' => ['total_tokens' => 10],
        ]);
        
    $mockClient->shouldReceive('chatCompletion')
        ->andReturn([
            'provider' => 'openai',
            'content' => 'OpenAI chat response',
            'raw_response' => ['choices' => [['message' => ['content' => 'OpenAI chat response']]]],
            'finish_reason' => 'stop',
            'model' => 'gpt-3.5-turbo',
            'usage' => ['total_tokens' => 15],
        ]);
        
    return $mockClient;
}

// Helper function to create a mock DeepSeek client
function getMockDeepSeekClient() {
    $mockClient = Mockery::mock('OscarWeijman\AIClient\DeepSeek\DeepSeekClient');
    
    $mockClient->shouldReceive('completion')
        ->andReturn([
            'provider' => 'deepseek',
            'content' => 'DeepSeek test response',
            'raw_response' => ['choices' => [['text' => 'DeepSeek test response']]],
            'finish_reason' => 'stop',
            'model' => 'deepseek-coder',
            'usage' => ['total_tokens' => 10],
        ]);
        
    $mockClient->shouldReceive('chatCompletion')
        ->andReturn([
            'provider' => 'deepseek',
            'content' => 'DeepSeek chat response',
            'raw_response' => ['choices' => [['message' => ['content' => 'DeepSeek chat response']]]],
            'finish_reason' => 'stop',
            'model' => 'deepseek-chat',
            'usage' => ['total_tokens' => 15],
        ]);
        
    return $mockClient;
}