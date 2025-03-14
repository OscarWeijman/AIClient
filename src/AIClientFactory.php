<?php

namespace OscarWeijman\AIClient;

use OscarWeijman\AIClient\Exceptions\AIClientException;
use OscarWeijman\AIClient\Interfaces\AIClientInterface;
use OscarWeijman\AIClient\OpenAI\OpenAIClient;
use OscarWeijman\AIClient\DeepSeek\DeepSeekClient;

class AIClientFactory
{
    /**
     * Create an AI client instance
     *
     * @param string $provider The AI provider (openai, deepseek)
     * @param string $apiKey The API key for the service
     * @param array $options Additional options for the client
     * @return AIClientInterface
     * @throws AIClientException
     */
    public static function create(string $provider, string $apiKey, array $options = []): AIClientInterface
    {
        return match (strtolower($provider)) {
            'openai' => new OpenAIClient($apiKey, $options),
            'deepseek' => new DeepSeekClient($apiKey, $options),
            default => throw new AIClientException("Unsupported AI provider: {$provider}"),
        };
    }
}