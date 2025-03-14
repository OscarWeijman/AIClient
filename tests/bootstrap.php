<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Laad .env bestand als het bestaat
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
    
    // Optioneel: valideer dat bepaalde omgevingsvariabelen bestaan
    // $dotenv->required(['OPENAI_API_KEY', 'DEEPSEEK_API_KEY']);
}