<?php

namespace OscarWeijman\AIClient\Interfaces;

interface AIClientInterface
{
    /**
     * Send a completion request to the AI service
     *
     * @param string $prompt The prompt to send to the AI
     * @param array $options Additional options for the request
     * @return array The response from the AI service
     */
    public function completion(string $prompt, array $options = []): array;
    
    /**
     * Send a chat completion request to the AI service
     *
     * @param array $messages Array of message objects with role and content
     * @param array $options Additional options for the request
     * @return array The response from the AI service
     */
    public function chatCompletion(array $messages, array $options = []): array;
}