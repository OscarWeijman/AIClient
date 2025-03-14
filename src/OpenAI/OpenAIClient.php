<?php

namespace OscarWeijman\AIClient\OpenAI;

use GuzzleHttp\Exception\GuzzleException;
use OscarWeijman\AIClient\AbstractAIClient;
use OscarWeijman\AIClient\Exceptions\AIClientException;
use OscarWeijman\AIClient\Traits\StreamingTrait;
use Psr\Http\Message\ResponseInterface;

class OpenAIClient extends AbstractAIClient
{
    use StreamingTrait;
    
    protected string $baseUrl = 'https://api.openai.com/v1/';
    
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
            'model' => $options['model'] ?? 'gpt-3.5-turbo-instruct',
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
            throw new AIClientException("OpenAI API error: {$e->getMessage()}", $e->getCode(), $e);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function chatCompletion(array $messages, array $options = []): array
    {
        $payload = array_merge([
            'model' => $options['model'] ?? 'gpt-3.5-turbo',
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
            throw new AIClientException("OpenAI API error: {$e->getMessage()}", $e->getCode(), $e);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function streamingChatCompletion(array $messages, callable $callback, array $options = []): void
    {
        $payload = array_merge([
            'model' => $options['model'] ?? 'gpt-3.5-turbo',
            'messages' => $messages,
            'max_tokens' => $options['max_tokens'] ?? 150,
            'temperature' => $options['temperature'] ?? 0.7,
            'stream' => true,
        ], $options);
        
        try {
            $response = $this->httpClient->post('chat/completions', [
                'json' => $payload,
                'stream' => true,
            ]);
            
            $this->handleStreamingResponse($response, $callback, 'openai');
        } catch (GuzzleException $e) {
            throw new AIClientException("OpenAI API streaming error: {$e->getMessage()}", $e->getCode(), $e);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    protected function processResponse(array $response): array
    {
        // Process and standardize the OpenAI response format
        return [
            'provider' => 'openai',
            'raw_response' => $response,
            'content' => $response['choices'][0]['message']['content'] ?? $response['choices'][0]['text'] ?? null,
            'finish_reason' => $response['choices'][0]['finish_reason'] ?? null,
            'model' => $response['model'] ?? null,
            'usage' => $response['usage'] ?? null,
        ];
    }
}