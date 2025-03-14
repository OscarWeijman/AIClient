<?php

namespace OscarWeijman\AIClient\Traits;

use Psr\Http\Message\ResponseInterface;

trait StreamingTrait
{
    /**
     * Handle streaming response from API
     *
     * @param ResponseInterface $response The streaming response
     * @param callable $callback Function to call for each chunk
     * @param string $provider The provider name
     * @return void
     */
    protected function handleStreamingResponse(ResponseInterface $response, callable $callback, string $provider): void
    {
        $stream = $response->getBody();
        
        while (!$stream->eof()) {
            $line = $this->readLine($stream);
            
            if (empty($line)) {
                continue;
            }
            
            if ($line === "data: [DONE]") {
                break;
            }
            
            if (strpos($line, 'data: ') === 0) {
                $data = substr($line, 6);
                $chunk = json_decode($data, true);
                
                if (json_last_error() === JSON_ERROR_NONE && isset($chunk['choices'][0]['delta']['content'])) {
                    $content = $chunk['choices'][0]['delta']['content'];
                    $callback([
                        'provider' => $provider,
                        'content' => $content,
                        'raw_chunk' => $chunk,
                    ]);
                }
            }
        }
    }
    
    /**
     * Read a line from the stream
     *
     * @param $stream
     * @return string
     */
    protected function readLine($stream): string
    {
        $buffer = '';
        while (!$stream->eof()) {
            $byte = $stream->read(1);
            if ($byte === "\n") {
                break;
            }
            $buffer .= $byte;
        }
        return trim($buffer);
    }
}