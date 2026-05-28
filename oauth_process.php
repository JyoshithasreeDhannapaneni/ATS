<?php
ob_start();
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

$clientId = MICROSOFT_SSO_CLIENT_ID;
$allowedDomains = ['cloudfuze.com', 'exinent.com'];

$debugLog = './oauth_debug.log';
function oauthLog($msg) {
    global $debugLog;
    file_put_contents($debugLog, date('Y-m-d H:i:s') . ' - ' . $msg . "\n", FILE_APPEND);
}
oauthLog('=== OAuth process started ===');

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
if (!isset($payload['aud']) || $payload['aud'] !== $clientId) {
    header('Location: index.php?m=login&message=' . urlencode('Token audience mismatch'));
    exit;
}

if (isset($payload['exp']) && $payload['exp'] < time()) {
    header('Location: index.php?m=login&message=' . urlencode('Token has expired. Please try again.'));
    exit;
}

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

oauthLog('Email extracted: ' . $email);
oauthLog('Name: ' . $firstName . ' ' . $lastName);

// Database connection - uses DatabaseConnection which works with PostgreSQL
$db = DatabaseConnection::getInstance();
oauthLog('DB connection obtained');

// Find existing user
$sql = sprintf(
    "SELECT user_id, site_id, access_level, user_name FROM `user` WHERE email = %s LIMIT 1",
    $db->makeQueryString($email)
);
oauthLog('User lookup SQL: ' . $sql);

try {
    $rs = $db->getAllAssoc($sql);
    oauthLog('User lookup result: ' . json_encode($rs));
} catch (Exception $e) {
    oauthLog('User lookup ERROR: ' . $e->getMessage());
    header('Location: index.php?m=login&message=' . urlencode('Database error: ' . $e->getMessage()));
    exit;
}

if (!empty($rs)) {
    $user = $rs[0];
    $userID = $user['user_id'];
    $siteID = $user['site_id'];

    // Update first/last name from Microsoft
    $updateSql = sprintf(
        "UPDATE `user` SET first_name = %s, last_name = %s WHERE user_id = %d",
        $db->makeQueryString($firstName),
        $db->makeQueryString($lastName),
        $userID
    );
    $db->query($updateSql);

} else {
    // Create new user
    $siteID = 1;
    $username = preg_replace('/[^a-z0-9_]/', '', strtolower(str_replace('@', '_', $email)));
    $password = md5(uniqid(rand(), true));

    // Ensure unique username
    $checkSql = sprintf(
        "SELECT user_id FROM `user` WHERE user_name = %s",
        $db->makeQueryString($username)
    );
    $checkRs = $db->getAllAssoc($checkSql);
    if (!empty($checkRs)) {
        $username = $username . '_' . time();
    }

    $sql = sprintf(
        "INSERT INTO `user` (site_id, user_name, email, password, first_name, last_name, access_level, can_change_password, is_test_user)
         VALUES (%d, %s, %s, %s, %s, %s, 400, 1, 0)",
        $siteID,
        $db->makeQueryString($username),
        $db->makeQueryString($email),
        $db->makeQueryString($password),
        $db->makeQueryString($firstName),
        $db->makeQueryString($lastName)
    );

    $db->query($sql);
    $userID = $db->getLastInsertID();

    if (!$userID) {
        header('Location: index.php?m=login&message=' . urlencode('Failed to create account'));
        exit;
    }
}

oauthLog('User resolved: userID=' . $userID . ' siteID=' . $siteID);

// Create CATS session and login
if (!isset($_SESSION['CATS'])) {
    $_SESSION['CATS'] = new CATSSession();
    oauthLog('Created new CATSSession');
} else {
    oauthLog('Using existing CATSSession');
}

try {
    $_SESSION['CATS']->ssoLogin($userID, $siteID);
    oauthLog('ssoLogin() called successfully');
} catch (Exception $e) {
    oauthLog('ssoLogin() ERROR: ' . $e->getMessage());
    header('Location: index.php?m=login&message=' . urlencode('Login error: ' . $e->getMessage()));
    exit;
}

// Verify login was successful
$loggedIn = $_SESSION['CATS']->isLoggedIn();
oauthLog('isLoggedIn: ' . ($loggedIn ? 'YES' : 'NO'));

if (!$loggedIn) {
    oauthLog('Login verification FAILED');
    header('Location: index.php?m=login&message=' . urlencode('Session login failed. Please try again.'));
    exit;
}

oauthLog('Session ID: ' . session_id());
oauthLog('Login SUCCESSFUL - redirecting to home');

// Check user role and redirect accordingly
include_once('./lib/UserRoles.php');

$userRole = UserRoles::getUserRole($userID);
oauthLog('User role: ' . $userRole);

if ($userRole === UserRoles::ROLE_INTERVIEWER) {
    header('Location: index.php?m=interviewer');
} else {
    header('Location: index.php?m=home');
}
exit;
