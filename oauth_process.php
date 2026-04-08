<?php
/**
 * Process Microsoft SSO Login
 * With proper authentication validation
 */
include_once('./config.php');
include_once('./constants.php');
include_once('./lib/Session.php');
include_once('./lib/DatabaseConnection.php');
include_once('./lib/Users.php');

@session_name(CATS_SESSION_NAME);
session_start();

// Azure AD Configuration
$clientId = '447bb81f-c63c-42ba-871d-2713a7244b46';
$allowedDomains = ['cloudfuze.com', 'exinent.com'];

$email = null;
$firstName = '';
$lastName = '';

// Process ID Token if provided
if (!isset($_POST['id_token'])) {
    header('Location: index.php?m=login&message=' . urlencode('No authentication token received'));
    exit;
}

$idToken = $_POST['id_token'];
$parts = explode('.', $idToken);

if (count($parts) !== 3) {
    header('Location: index.php?m=login&message=' . urlencode('Invalid token format'));
    exit;
}

// Decode header and payload
$header = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[0])), true);
$payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);

if (!$payload) {
    header('Location: index.php?m=login&message=' . urlencode('Could not decode token'));
    exit;
}

// Validate token claims
// 1. Check audience (aud) matches our client ID
if (!isset($payload['aud']) || $payload['aud'] !== $clientId) {
    header('Location: index.php?m=login&message=' . urlencode('Token audience mismatch'));
    exit;
}

// 2. Check token is not expired
if (isset($payload['exp']) && $payload['exp'] < time()) {
    header('Location: index.php?m=login&message=' . urlencode('Token has expired. Please try again.'));
    exit;
}

// 3. Check issuer is Microsoft
if (isset($payload['iss'])) {
    $validIssuers = [
        'https://login.microsoftonline.com/',
        'https://sts.windows.net/'
    ];
    $isValidIssuer = false;
    foreach ($validIssuers as $issuer) {
        if (strpos($payload['iss'], $issuer) === 0) {
            $isValidIssuer = true;
            break;
        }
    }
    if (!$isValidIssuer) {
        header('Location: index.php?m=login&message=' . urlencode('Invalid token issuer'));
        exit;
    }
}

// Extract email from token
if (!empty($payload['email'])) {
    $email = strtolower($payload['email']);
} elseif (!empty($payload['preferred_username'])) {
    $email = strtolower($payload['preferred_username']);
} elseif (!empty($payload['upn'])) {
    $email = strtolower($payload['upn']);
}

if (empty($email)) {
    header('Location: index.php?m=login&message=' . urlencode('No email found in Microsoft account'));
    exit;
}

// Validate email domain
$domain = substr($email, strpos($email, '@') + 1);
if (!in_array($domain, $allowedDomains)) {
    header('Location: index.php?m=login&message=' . urlencode('Access denied. Only ' . implode(' and ', $allowedDomains) . ' domains allowed.'));
    exit;
}

// Extract name
$firstName = isset($payload['given_name']) ? $payload['given_name'] : '';
$lastName = isset($payload['family_name']) ? $payload['family_name'] : '';

if (empty($firstName) && !empty($payload['name'])) {
    $nameParts = explode(' ', $payload['name'], 2);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
}

// Database connection
$db = DatabaseConnection::getInstance();

// Find existing user
$sql = sprintf("SELECT user_id, site_id, access_level, user_name FROM user WHERE email = %s LIMIT 1", $db->makeQueryString($email));
$rs = $db->query($sql);
$user = null;
if ($rs && mysqli_num_rows($rs) > 0) {
    $user = mysqli_fetch_assoc($rs);
}

if (!empty($user) && isset($user['user_id'])) {
    // Existing user - update name if needed
    $userID = $user['user_id'];
    $siteID = $user['site_id'];
    
    // Update first/last name from Microsoft
    $updateSql = sprintf(
        "UPDATE user SET first_name = %s, last_name = %s WHERE user_id = %d",
        $db->makeQueryString($firstName),
        $db->makeQueryString($lastName),
        $userID
    );
    $db->query($updateSql);
    
} else {
    // User does not exist in system - deny access
    // Only admin-approved users can login via Microsoft SSO
    header('Location: index.php?m=login&message=' . urlencode('Access denied. You are not authorized to use this application. Please contact your administrator to request access.'));
    exit;
}

// Create CATS session and login
if (!isset($_SESSION['CATS'])) {
    $_SESSION['CATS'] = new CATSSession();
}

$_SESSION['CATS']->ssoLogin($userID, $siteID);

// Verify login was successful
if (!$_SESSION['CATS']->isLoggedIn()) {
    header('Location: index.php?m=login&message=' . urlencode('Session login failed'));
    exit;
}

// Check user role and redirect accordingly
include_once('./lib/UserRoles.php');

$userRole = UserRoles::getUserRole($userID);

if ($userRole === UserRoles::ROLE_INTERVIEWER) {
    // Redirect interviewers to their portal
    header('Location: index.php?m=interviewer');
} else {
    // Redirect admins and recruiters to main dashboard
    header('Location: index.php?m=home');
}
exit;
