<?php
require_once __DIR__ . '/../config/config.php';

// Handle only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

try {
    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input');
    }

    if (!isset($data['gender'])) {
        throw new Exception('Gender is required');
    }

    // Build the prompt
    $prompt = buildJSONPrompt($data);

    // Call Gemini API
    $generatedNames = callGeminiAPI($prompt);

    // Parse JSON response directly
    $namesList = json_decode($generatedNames, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($namesList)) {
        throw new Exception('Invalid JSON response from API');
    }

    // Return success response
    echo json_encode([
        'success' => true,
        'names' => $namesList
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

/**
 * Build a prompt that directly requests JSON output
 */
function buildJSONPrompt($data) {
    $gender = $data['gender'] ?? 'neutral';
    $firstLetters = $data['firstLetters'] ?? [];
    $origin = $data['origin'] ?? '';
    $meaning = $data['meaning'] ?? '';
    $style = $data['style'] ?? '';
    $customName = trim($data['customName'] ?? '');
    $existingNames = $data['existingNames'] ?? []; // Track already generated names
    
    // Simple count: 8 for initial, 5 for more
    $nameCount = $data['count'] ?? DEFAULT_NAME_COUNT;

    $prompt = "Generate {$nameCount} UNIQUE baby names as a JSON array. Each name should be an object with these exact 4 fields:\n";
    $prompt .= "- name: the baby name\n";
    $prompt .= "- origin: cultural/linguistic origin\n";
    $prompt .= "- meaning: what the name means\n";
    $prompt .= "- description: detailed 2-3 sentence description including cultural significance, historical background, famous bearers, personality traits associated with the name, and why parents choose this name\n\n";

    // Add existing names to avoid duplicates
    if (!empty($existingNames)) {
        $prompt .= "IMPORTANT: Do NOT include these names that have already been generated: " . implode(', ', $existingNames) . "\n\n";
    }

    $prompt .= "Criteria:\n";
    
    if ($gender === 'boy') {
        $prompt .= "- Boy names only\n";
    } elseif ($gender === 'girl') {
        $prompt .= "- Girl names only\n";
    } elseif ($gender === 'neutral') {
        $prompt .= "- Gender-neutral names\n";
    } else {
        $prompt .= "- Both boy and girl names\n";
    }

    if (!empty($firstLetters)) {
        $prompt .= "- Starting with letters: " . implode(', ', $firstLetters) . "\n";
    }
    if (!empty($origin)) {
        $prompt .= "- Origin: " . $origin . "\n";
    }
    if (!empty($meaning)) {
        $prompt .= "- Related to meaning: " . $meaning . "\n";
    }
    if (!empty($style)) {
        $prompt .= "- Style: " . $style . "\n";
    }
    if (!empty($customName)) {
        $prompt .= "- Similar to: " . $customName . "\n";
    }

    $prompt .= "\nMake the descriptions rich and informative, including:\n";
    $prompt .= "- Historical or cultural significance\n";
    $prompt .= "- Famous people with this name\n";
    $prompt .= "- Personality traits often associated\n";
    $prompt .= "- Why modern parents choose this name\n";
    $prompt .= "- Any interesting facts or variations\n\n";
    $prompt .= "ENSURE ALL NAMES ARE COMPLETELY DIFFERENT from each other and from the excluded list.\n";
    $prompt .= "Return only valid JSON array, no other text.";

    return $prompt;
}

/**
 * Call Gemini API with JSON response format
 */
function callGeminiAPI($prompt) {
    $apiKey = GEMINI_API_KEY;
    $apiUrl = GEMINI_API_URL . '?key=' . $apiKey;

    if (empty($apiKey)) {
        throw new Exception(ERROR_NO_API_KEY);
    }

    $requestData = [
        'contents' => [
            ['parts' => [['text' => $prompt]]]
        ],
        'generationConfig' => [
            'temperature' => 0.7,
            'maxOutputTokens' => 1024,
            'responseMimeType' => 'application/json' // This ensures JSON output
        ]
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($requestData),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => true
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        throw new Exception('Network error: ' . $curlError);
    }

    if ($httpCode !== 200) {
        throw new Exception('API error: HTTP ' . $httpCode);
    }

    $decodedResponse = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid response from API');
    }

    return $decodedResponse['candidates'][0]['content']['parts'][0]['text'] ?? '';
}
?>
