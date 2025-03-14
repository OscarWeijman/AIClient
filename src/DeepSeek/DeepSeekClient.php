<?php

namespace OscarWeijman\AIClient\DeepSeek;

use GuzzleHttp\Exception\GuzzleException;
use OscarWeijman\AIClient\AbstractAIClient;
use OscarWeijman\AIClient\Exceptions\AIClientException;

class DeepSeekClient extends AbstractAIClient
{
    protected string $baseUrl = 'https://api.deepseek.com/v1/';
    
    /**
     * {@inheritdoc}
     */
    protected function getDefaultHeaders(): array
    {
        return [
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type' => 'application/json',
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function completion(string $prompt, array $options = []): array
    {
        $payload = array_merge([
            'model' => $options['model'] ?? 'deepseek-coder',
            'prompt' => $prompt,
            'max_tokens' => $options['max_tokens'] ?? 150,
            'temperature' => $options['temperature'] ?? 0.7,
        ], $options);
        
        try {
            $response = $this->httpClient->post('completions', [
                'json' => $payload
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            return $this->processResponse($result);
        } catch (GuzzleException $e) {
            throw new AIClientException("DeepSeek API error: {$e->getMessage()}", $e->getCode(), $e);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function chatCompletion(array $messages, array $options = []): array
    {
        $payload = array_merge([
            'model' => $options['model'] ?? 'deepseek-chat',
            'messages' => $messages,
            'max_tokens' => $options['max_tokens'] ?? 150,
            'temperature' => $options['temperature'] ?? 0.7,
        ], $options);
        
        try {
            $response = $this->httpClient->post('chat/completions', [
                'json' => $payload
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            return $this->processResponse($result);
        } catch (GuzzleException $e) {
            throw new AIClientException("DeepSeek API error: {$e->getMessage()}", $e->getCode(), $e);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    protected function processResponse(array $response): array
    {
        // Process and standardize the DeepSeek response format
        return [
            'provider' => 'deepseek',
            'raw_response' => $response,
            'content' => $response['choices'][0]['message']['content'] ?? $response['choices'][0]['text'] ?? null,
            'finish_reason' => $response['choices'][0]['finish_reason'] ?? null,
            'model' => $response['model'] ?? null,
            'usage' => $response['usage'] ?? null,
        ];
    }
}