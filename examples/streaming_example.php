<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OscarWeijman\AIClient\AIClientFactory;

// Laad .env bestand als het bestaat
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

// Haal API key op uit omgevingsvariabelen of gebruik een standaardwaarde
$apiKey = $_ENV['OPENAI_API_KEY'] ?? 'jouw-api-key-hier';

// Maak een OpenAI client
$client = AIClientFactory::create('openai', $apiKey);

// Berichten voor de chat
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
try {
    echo "Verhaal wordt gegenereerd...\n";
    $client->streamingChatCompletion($messages, $callback);
    echo "\n\nVerhaal voltooid!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}