<?php
/**
 * Generate a job description draft via the configured AI provider
 * (Anthropic or OpenAI) from a job title and a few recruiter-supplied
 * keywords. Called via AJAX from the Add/Edit Job Order form's
 * "Generate with AI" button.
 */

/* Same working-directory fix as generateCompanyJobID.php — this endpoint is
 * fetched directly rather than routed through ajax.php, so relative includes
 * need to resolve from the project root. */
chdir(__DIR__ . '/..');

include_once('config.php');
include_once('constants.php');
include_once('lib/DatabaseConnection.php');
include_once('lib/Session.php');

session_name(CATS_SESSION_NAME);
session_start();
if (!isset($_SESSION['CATS']) || !$_SESSION['CATS']->isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

$siteID      = $_SESSION['CATS']->getSiteID();
$title       = isset($_POST['title']) ? trim($_POST['title']) : '';
$keywords    = isset($_POST['keywords']) ? trim($_POST['keywords']) : '';
$companyName = isset($_POST['companyName']) ? trim($_POST['companyName']) : '';

if ($title === '' && $keywords === '') {
    echo json_encode(['success' => false, 'error' => 'Enter a job title or a few keywords first.']);
    exit;
}

/* Look up the active AI provider and its key from the `settings` key-value
 * table (set via Settings > Administration > AI Integration), falling back
 * to the provider's environment variable. */
function getAiSettingValue($db, $siteID, $settingKey) {
    $rs = $db->getAllAssoc(sprintf(
        "SELECT value FROM settings WHERE setting = %s AND site_id = %s LIMIT 1",
        $db->makeQueryString($settingKey),
        $db->makeQueryInteger($siteID)
    ));

    return (!empty($rs) && !empty($rs[0]['value'])) ? $rs[0]['value'] : '';
}

$db = DatabaseConnection::getInstance();

$provider = getAiSettingValue($db, $siteID, 'aiProvider');
if ($provider !== 'openai') {
    $provider = 'anthropic';
}

if ($provider === 'openai') {
    $apiKey = getAiSettingValue($db, $siteID, 'openaiApiKey');
    if (empty($apiKey)) {
        $apiKey = getenv('OPENAI_API_KEY');
    }
    $providerLabel = 'an OpenAI';
} else {
    $apiKey = getAiSettingValue($db, $siteID, 'anthropicApiKey');
    if (empty($apiKey)) {
        $apiKey = getenv('ANTHROPIC_API_KEY');
    }
    $providerLabel = 'an Anthropic';
}

if (empty($apiKey)) {
    echo json_encode([
        'success' => false,
        'notConfigured' => true,
        'error' => "AI generation is not configured yet. Add {$providerLabel} API key under Settings > Administration > AI Integration."
    ]);
    exit;
}

$promptParts = [];
if ($title !== '')       $promptParts[] = "Job title: {$title}";
if ($companyName !== '') $promptParts[] = "Company: {$companyName}";
if ($keywords !== '')    $promptParts[] = "Keywords/requirements: {$keywords}";

$userPrompt = "Write a professional job description for an internal applicant tracking system, based on:\n\n"
    . implode("\n", $promptParts)
    . "\n\nFormat the response as HTML suitable for a rich-text editor: a short intro paragraph, then "
    . "<h3>Responsibilities</h3> and <h3>Requirements</h3> sections each with a <ul> bullet list. "
    . "Do not include a job title heading, salary, or company boilerplate/legal text. "
    . "Return only the HTML fragment, no markdown code fences, no commentary before or after.";

if ($provider === 'openai') {
    $requestUrl = 'https://api.openai.com/v1/chat/completions';
    $requestBody = json_encode([
        'model' => 'gpt-4o-mini',
        'max_tokens' => 1024,
        'messages' => [
            ['role' => 'user', 'content' => $userPrompt]
        ]
    ]);
    $requestHeaders = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ];
} else {
    $requestUrl = 'https://api.anthropic.com/v1/messages';
    $requestBody = json_encode([
        'model' => 'claude-sonnet-5',
        'max_tokens' => 1024,
        'messages' => [
            ['role' => 'user', 'content' => $userPrompt]
        ]
    ]);
    $requestHeaders = [
        'Content-Type: application/json',
        'x-api-key: ' . $apiKey,
        'anthropic-version: 2023-06-01'
    ];
}

$ch = curl_init($requestUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $requestBody,
    CURLOPT_HTTPHEADER => $requestHeaders,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 30
]);

$response = curl_exec($ch);
$curlError = curl_error($ch);
$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    echo json_encode(['success' => false, 'error' => 'Could not reach the AI service: ' . $curlError]);
    exit;
}

$responseData = json_decode($response, true);

if ($httpStatus !== 200 || !is_array($responseData)) {
    $apiErrorMessage = isset($responseData['error']['message']) ? $responseData['error']['message'] : 'Unexpected response from AI service.';
    echo json_encode(['success' => false, 'error' => $apiErrorMessage]);
    exit;
}

$generatedText = '';
if ($provider === 'openai') {
    if (!empty($responseData['choices']) && is_array($responseData['choices'])) {
        $generatedText = isset($responseData['choices'][0]['message']['content'])
            ? $responseData['choices'][0]['message']['content']
            : '';
    }
} else {
    if (!empty($responseData['content']) && is_array($responseData['content'])) {
        foreach ($responseData['content'] as $block) {
            if (isset($block['type']) && $block['type'] === 'text') {
                $generatedText .= $block['text'];
            }
        }
    }
}

if ($generatedText === '') {
    echo json_encode(['success' => false, 'error' => 'The AI service returned an empty response.']);
    exit;
}

echo json_encode([
    'success' => true,
    'description' => $generatedText
]);
?>
