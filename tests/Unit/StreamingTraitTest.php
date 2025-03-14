<?php

use OscarWeijman\AIClient\Traits\StreamingTrait;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;

// Create a test class that uses the StreamingTrait
class StreamingTraitTestClass {
    use StreamingTrait;
    
    public function testHandleStreamingResponse(Response $response, callable $callback, string $provider): void {
        $this->handleStreamingResponse($response, $callback, $provider);
    }
    
    public function testReadLine($stream): string {
        return $this->readLine($stream);
    }
}

test('StreamingTrait can read lines from a stream', function () {
    $testString = "Hello\nWorld\n";
    $stream = fopen('php://memory', 'r+');
    fwrite($stream, $testString);
    rewind($stream);
    
    $streamObj = new Stream($stream);
    
    $trait = new StreamingTraitTestClass();
    
    $line1 = $trait->testReadLine($streamObj);
    expect($line1)->toBe('Hello');
    
    $line2 = $trait->testReadLine($streamObj);
    expect($line2)->toBe('World');
});

test('StreamingTrait processes data chunks correctly', function () {
    // Create a stream with multiple data chunks
    $testData = implode("\n", [
        "data: " . json_encode(['choices' => [['delta' => ['content' => 'Hello']]]]),
        "",
        "data: " . json_encode(['choices' => [['delta' => ['content' => ' world']]]]),
        "",
        "data: [DONE]",
        ""
    ]);
    
    $stream = fopen('php://memory', 'r+');
    fwrite($stream, $testData);
    rewind($stream);
    
    // Create a mock response with our test stream
    $response = Mockery::mock(Response::class);
    $response->shouldReceive('getBody')->andReturn(new Stream($stream));
    
    // Create our test class and prepare to collect chunks
    $trait = new StreamingTraitTestClass();
    $chunks = [];
    
    $callback = function ($chunk) use (&$chunks) {
        $chunks[] = $chunk;
    };
    
    // Process the stream
    $trait->testHandleStreamingResponse($response, $callback, 'test-provider');
    
    // Verify the chunks were processed correctly
    expect($chunks)->toHaveCount(2);
    expect($chunks[0]['content'])->toBe('Hello');
    expect($chunks[1]['content'])->toBe(' world');
    expect($chunks[0]['provider'])->toBe('test-provider');
});