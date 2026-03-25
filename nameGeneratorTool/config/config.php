<?php
// Configuration file for Baby Name Generator

// API Configuration
define('GEMINI_API_KEY', 'AIzaSyB0M85wn-zYuUCbfPckoy4r4yXanzdrn7M'); // Replace with your actual API key
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent');
// Application Settings
define('APP_NAME', 'Baby Name Generator');
define('APP_VERSION', '1.0.0');
define('DEBUG_MODE', true);

// Default Settings
define('DEFAULT_NAME_COUNT', 8);
define('MORE_NAME_COUNT', 5);
define('MAX_GENERATED_NAMES', 50);

// Error Messages
define('ERROR_NO_API_KEY', 'Please set your Gemini API key');
define('ERROR_API_FAILED', 'Failed to generate names. Please try again.');
define('ERROR_INVALID_REQUEST', 'Invalid request parameters');

return [
    'api_key' => GEMINI_API_KEY,
    'api_url' => GEMINI_API_URL,
    'app_name' => APP_NAME,
    'debug' => DEBUG_MODE
];
?>
