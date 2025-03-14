<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OscarWeijman\AIClient\AIClientFactory;

// Replace with your actual API keys
$openaiApiKey = 'your-openai-api-key';
$deepseekApiKey = 'your-deepseek-api-key';

// Example with OpenAI
try {
    $openaiClient = AIClientFactory::create('openai', $openaiApiKey);
    
    // Text completion example
    $completionResult = $openaiClient->completion(
        'Write a short poem about programming',
        ['max_tokens' => 100]
    );
    
    echo "OpenAI Completion Result:\n";
    echo $completionResult['content'] . "\n\n";
    
    // Chat completion example
    $chatResult = $openaiClient->chatCompletion([
        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
        ['role' => 'user', 'content' => 'Explain what PHP is in one sentence.']
    ]);
    
    echo "OpenAI Chat Result:\n";
    echo $chatResult['content'] . "\n\n";
    
} catch (Exception $e) {
    echo "OpenAI Error: " . $e->getMessage() . "\n";
}

// Example with DeepSeek
try {
    $deepseekClient = AIClientFactory::create('deepseek', $deepseekApiKey);
    
    // Text completion example
    $completionResult = $deepseekClient->completion(
        'Write a function to calculate fibonacci numbers in PHP',
        ['max_tokens' => 150]
    );
    
    echo "DeepSeek Completion Result:\n";
    echo $completionResult['content'] . "\n\n";
    
    // Chat completion example
    $chatResult = $deepseekClient->chatCompletion([
        ['role' => 'system', 'content' => 'You are a coding assistant.'],
        ['role' => 'user', 'content' => 'How do I handle exceptions in PHP?']
    ]);
    
    echo "DeepSeek Chat Result:\n";
    echo $chatResult['content'] . "\n\n";
    
} catch (Exception $e) {
    echo "DeepSeek Error: " . $e->getMessage() . "\n";
}