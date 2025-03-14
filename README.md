# AI Client

Een moderne PHP client voor AI API's zoals OpenAI en DeepSeek.

[![Tests](https://github.com/OscarWeijman/AIClient/actions/workflows/tests.yml/badge.svg)](https://github.com/OscarWeijman/AIClient/actions/workflows/tests.yml)
[![Static Analysis](https://github.com/OscarWeijman/AIClient/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/OscarWeijman/AIClient/actions/workflows/static-analysis.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

## Installatie

```bash
composer require oscarweijman/ai-client
```

## Gebruik

### Client initialiseren

```php
use OscarWeijman\AIClient\AIClientFactory;

// Maak een OpenAI client
$openaiClient = AIClientFactory::create('openai', 'jouw-api-key');

// Maak een DeepSeek client
$deepseekClient = AIClientFactory::create('deepseek', 'jouw-api-key');
```

### Text Completion

```php
$result = $openaiClient->completion('Wat is de hoofdstad van Nederland?', [
    'max_tokens' => 100,
    'temperature' => 0.7,
]);

echo $result['content'];
```

### Chat Completion

```php
$result = $openaiClient->chatCompletion([
    ['role' => 'system', 'content' => 'Je bent een behulpzame assistent.'],
    ['role' => 'user', 'content' => 'Wat is de hoofdstad van Nederland?'],
], [
    'max_tokens' => 100,
    'temperature' => 0.7,
]);

echo $result['content'];
```

### Streaming Chat Completion (v1.1.0+)

```php
$messages = [
    ['role' => 'system', 'content' => 'Je bent een behulpzame assistent.'],
    ['role' => 'user', 'content' => 'Schrijf een kort verhaal over een robot die leert programmeren.'],
];

// Callback functie die wordt aangeroepen voor elk stukje van de streaming response
$callback = function ($chunk) {
    echo $chunk['content'];
    flush(); // Zorg ervoor dat de output direct wordt weergegeven
};

// Voer de streaming chat completion uit
$openaiClient->streamingChatCompletion($messages, $callback, [
    'model' => 'gpt-3.5-turbo',
    'temperature' => 0.7,
]);
```

## Response formaat

Alle API responses worden gestandaardiseerd naar het volgende formaat:

```php
[
    'provider' => 'openai', // of 'deepseek'
    'content' => 'De inhoud van het antwoord',
    'raw_response' => [], // De originele API response
    'finish_reason' => 'stop', // De reden waarom de generatie is gestopt
    'model' => 'gpt-3.5-turbo', // Het gebruikte model
    'usage' => [
        'prompt_tokens' => 10,
        'completion_tokens' => 20,
        'total_tokens' => 30,
    ],
]
```

## Error Handling

De bibliotheek gooit `AIClientException` bij fouten:

```php
use OscarWeijman\AIClient\Exceptions\AIClientException;

try {
    $result = $client->chatCompletion($messages);
} catch (AIClientException $e) {
    echo "Error: " . $e->getMessage();
}
```

## Tests uitvoeren

### Unit en Feature tests

```bash
composer test
```

Of met de Pest CLI:

```bash
./vendor/bin/pest --exclude-group=integration
```

### Integratie tests met echte API's

1. Kopieer het `.env.example` bestand naar `.env`
2. Vul je API keys in
3. Zet `ENABLE_API_TESTS=true`
4. Voer de tests uit:

```bash
composer test-integration
```

Of met de Pest CLI:

```bash
./vendor/bin/pest tests/Integration
```

## Statische analyse

```bash
composer analyse
```

## GitHub Actions

Dit project gebruikt GitHub Actions voor automatische tests en statische analyse:

- **tests.yml**: Voert unit en feature tests uit op verschillende PHP versies
- **integration-tests.yml**: Voert integratie tests uit met echte API's (handmatig te triggeren)
- **static-analysis.yml**: Voert PHPStan analyse uit

## Licentie

Dit project is gelicenseerd onder de MIT licentie - zie het [LICENSE](LICENSE) bestand voor details.