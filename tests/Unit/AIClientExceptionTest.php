<?php

use OscarWeijman\AIClient\Exceptions\AIClientException;

test('AIClientException can be instantiated with message', function () {
    $exception = new AIClientException('Test exception message');
    expect($exception)->toBeInstanceOf(AIClientException::class);
    expect($exception->getMessage())->toBe('Test exception message');
});

test('AIClientException can be instantiated with message and code', function () {
    $exception = new AIClientException('Test exception message', 123);
    expect($exception)->toBeInstanceOf(AIClientException::class);
    expect($exception->getMessage())->toBe('Test exception message');
    expect($exception->getCode())->toBe(123);
});

test('AIClientException can be instantiated with message, code and previous exception', function () {
    $previousException = new Exception('Previous exception');
    $exception = new AIClientException('Test exception message', 123, $previousException);
    
    expect($exception)->toBeInstanceOf(AIClientException::class);
    expect($exception->getMessage())->toBe('Test exception message');
    expect($exception->getCode())->toBe(123);
    expect($exception->getPrevious())->toBe($previousException);
});