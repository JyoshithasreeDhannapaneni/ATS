<?php
/**
 * Neutara ATS - Org Chart Module
 */

class OrgChartUI extends UserInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->_authenticationRequired = true;
        $this->_moduleDirectory = 'orgchart';
        $this->_moduleName      = 'orgchart';
        $this->_moduleTabText   = 'Org Chart';
        $this->_subTabs         = array();
    }

    public function handleRequest()
    {
        $action = $this->getAction();

        /* Every action below can edit/disable another user's login or read
         * their account record -- none of that is safe at ordinary access
         * levels. Match the rest of the app's convention (Settings module
         * gates user add/edit/disable at ACCESS_LEVEL_SA) rather than
         * leaving user management reachable by any logged-in account. */
        $mutatingActions = array('updateEmployee', 'deleteEmployee', 'suspendEmployee', 'addEmployee', 'getEmployee');
        if (in_array($action, $mutatingActions) && $this->getUserAccessLevel('orgchart.manage') < ACCESS_LEVEL_SA)
        {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Insufficient permissions for this action']);
            die();
        }

        switch ($action)
        {
            case 'updateEmployee':
                $this->updateEmployee();
                break;
            case 'deleteEmployee':
                $this->deleteEmployee();
                break;
            case 'suspendEmployee':
                $this->suspendEmployee();
                break;
            case 'addEmployee':
                $this->addEmployee();
                break;
            case 'getEmployee':
                $this->getEmployee();
                break;
            default:
                $this->show();
                break;
        }
    }

    private function _ensureColumns()
    {
        $db = DatabaseConnection::getInstance();
        // Add reports_to and department columns if they don't exist
        try { $db->query("ALTER TABLE user ADD COLUMN IF NOT EXISTS reports_to INTEGER DEFAULT NULL", true); } catch (Exception $e) {}
        try { $db->query("ALTER TABLE user ADD COLUMN IF NOT EXISTS department VARCHAR(100) DEFAULT NULL", true); } catch (Exception $e) {}
        try { $db->query("ALTER TABLE user ADD COLUMN IF NOT EXISTS employee_id VARCHAR(20) DEFAULT NULL", true); } catch (Exception $e) {}
        try { $db->query("ALTER TABLE user ADD COLUMN IF NOT EXISTS org_status VARCHAR(20) DEFAULT 'active'", true); } catch (Exception $e) {}
    }

    private function show()
    {
        $db     = DatabaseConnection::getInstance();
        $siteID = $_SESSION['CATS']->getSiteID();

        $this->_ensureColumns();

        $sql = sprintf(
            "SELECT
                u.user_id            AS userID,
                u.first_name         AS firstName,
                u.last_name          AS lastName,
                u.email              AS email,
                u.title              AS title,
                u.phone_work         AS phone,
                u.reports_to         AS reportsTo,
                u.department         AS department,
                u.employee_id        AS employeeID,
                u.org_status         AS orgStatus,
                al.short_description AS accessLevel,
                al.access_level_id   AS accessLevelID
             FROM user u
             LEFT JOIN access_level al ON u.access_level = al.access_level_id
             WHERE u.site_id = %s
               AND u.access_level > 0
             ORDER BY al.access_level_id DESC, u.last_name ASC",
            $db->makeQueryInteger($siteID)
        );

        $users = $db->getAllAssoc($sql);

        // Build managers list (for reports_to dropdown)
        $managers = array();
        foreach ($users as $u) {
            $managers[$u['userID']] = trim($u['firstName'] . ' ' . $u['lastName']);
        }

        $this->_template->assign('users',    $users);
        $this->_template->assign('managers', $managers);
        $this->_template->assign('active',   $this);
        $this->_template->display('./modules/orgchart/OrgChart.tpl');
    }

    private function addEmployee()
    {
        $db     = DatabaseConnection::getInstance();
        $siteID = $_SESSION['CATS']->getSiteID();

        $this->_ensureColumns();

        $firstName  = trim($_POST['firstName'] ?? '');
        $lastName   = trim($_POST['lastName'] ?? '');
        $title      = trim($_POST['title'] ?? '');
        $department = trim($_POST['department'] ?? '');
        $phone      = trim($_POST['phone'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $employeeID = trim($_POST['employeeID'] ?? '');
        $reportsTo  = intval($_POST['reportsTo'] ?? 0);

        if (empty($firstName) && empty($lastName))
        {
            echo json_encode(['success' => false, 'error' => 'First or last name is required']);
            die();
        }

        /* Derive a unique username the same way SSO auto-provisioning does
         * (oauth_process.php) -- from the email if given, otherwise from the
         * name -- since this form has no username/password fields of its own. */
        if (!empty($email))
        {
            $usernameBase = preg_replace('/[^a-z0-9_]/', '', strtolower(str_replace('@', '_', $email)));
        }
        else
        {
            $usernameBase = preg_replace('/[^a-z0-9_]/', '', strtolower($firstName . '_' . $lastName));
        }
        if ($usernameBase === '') { $usernameBase = 'employee'; }

        $username = $usernameBase;
        $checkRs = $db->getAllAssoc(sprintf(
            "SELECT user_id FROM `user` WHERE user_name = %s",
            $db->makeQueryString($username)
        ));
        if (!empty($checkRs)) { $username = $usernameBase . '_' . time(); }

        /* No password field exists in this form for an admin to set deliberately,
         * so mirror the SSO auto-provisioning precedent: an unusable random
         * password (Forgot Password is how this account would ever get one),
         * and the safe default access level (Read Only), not Site Admin. */
        $password = md5(uniqid(rand(), true));

        $insertSql = sprintf(
            "INSERT INTO `user` (site_id, user_name, email, password, first_name, last_name,
                title, department, phone_work, employee_id, reports_to, org_status,
                access_level, can_change_password, is_test_user)
             VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 'active', 100, 1, 0)",
            $siteID,
            $db->makeQueryString($username),
            $db->makeQueryString($email),
            $db->makeQueryString($password),
            $db->makeQueryString($firstName),
            $db->makeQueryString($lastName),
            $db->makeQueryString($title),
            $db->makeQueryString($department),
            $db->makeQueryString($phone),
            $db->makeQueryString($employeeID),
            $reportsTo > 0 ? $reportsTo : 'NULL'
        );

        $db->query($insertSql);
        $newUserID = $db->getLastInsertID();

        if (!$newUserID)
        {
            echo json_encode(['success' => false, 'error' => 'Failed to create employee']);
            die();
        }

        echo json_encode(['success' => true, 'userID' => $newUserID]);
        die();
    }

    private function updateEmployee()
    {
        $db     = DatabaseConnection::getInstance();
        $siteID = $_SESSION['CATS']->getSiteID();

        $userID     = intval($_POST['userID'] ?? 0);
        $firstName  = trim($_POST['firstName'] ?? '');
        $lastName   = trim($_POST['lastName'] ?? '');
        $title      = trim($_POST['title'] ?? '');
        $department = trim($_POST['department'] ?? '');
        $phone      = trim($_POST['phone'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $employeeID = trim($_POST['employeeID'] ?? '');
        $reportsTo  = intval($_POST['reportsTo'] ?? 0);

        if (!$userID) { echo json_encode(['success' => false, 'error' => 'Invalid user']); die(); }

        /* A user reporting to themselves is the simplest possible cycle and
         * the tree-rendering code has no cycle detection -- block it here at
         * the one point a client actually controls the value. */
        if ($reportsTo === $userID)
        {
            echo json_encode(['success' => false, 'error' => 'An employee cannot report to themselves']);
            die();
        }

        $sql = sprintf(
            "UPDATE user SET
                first_name  = %s,
                last_name   = %s,
                title       = %s,
                department  = %s,
                phone_work  = %s,
                email       = %s,
                employee_id = %s,
                reports_to  = %s
             WHERE user_id = %s AND site_id = %s",
            $db->makeQueryString($firstName),
            $db->makeQueryString($lastName),
            $db->makeQueryString($title),
            $db->makeQueryString($department),
            $db->makeQueryString($phone),
            $db->makeQueryString($email),
            $db->makeQueryString($employeeID),
            $reportsTo > 0 ? $reportsTo : 'NULL',
            $userID,
            $db->makeQueryInteger($siteID)
        );

        $db->query($sql);
        echo json_encode(['success' => true]);
        die();
    }

    private function deleteEmployee()
    {
        $db     = DatabaseConnection::getInstance();
        $siteID = $_SESSION['CATS']->getSiteID();
        $userID = intval($_POST['userID'] ?? 0);

        if (!$userID) { echo json_encode(['success' => false]); die(); }

        if ($userID === intval($_SESSION['CATS']->getUserID()))
        {
            echo json_encode(['success' => false, 'error' => 'You cannot remove your own account']);
            die();
        }

        /* Don't let the site end up with zero Site-Admin-or-higher accounts --
         * this is otherwise a one-click, no-recovery lockout since nothing in
         * this module (or generally) lets a Read/Edit-level user re-enable
         * anyone. */
        $targetLevel = $db->getAllAssoc(sprintf(
            "SELECT access_level FROM `user` WHERE user_id = %s AND site_id = %s",
            $userID, $db->makeQueryInteger($siteID)
        ));
        $targetLevel = !empty($targetLevel) ? intval($targetLevel[0]['access_level']) : 0;

        if ($targetLevel >= ACCESS_LEVEL_SA)
        {
            $remainingAdmins = $db->getAllAssoc(sprintf(
                "SELECT COUNT(*) AS cnt FROM `user`
                 WHERE site_id = %s AND access_level >= %d AND user_id != %s",
                $db->makeQueryInteger($siteID), ACCESS_LEVEL_SA, $userID
            ));
            $remainingCount = !empty($remainingAdmins) ? intval($remainingAdmins[0]['cnt']) : 0;
            if ($remainingCount < 1)
            {
                echo json_encode(['success' => false, 'error' => 'Cannot remove the only remaining Site Administrator']);
                die();
            }
        }

        // Reassign their direct reports to their manager
        $db->query(sprintf(
            "UPDATE user SET reports_to = (SELECT reports_to FROM user WHERE user_id = %s) WHERE reports_to = %s AND site_id = %s",
            $userID, $userID, $db->makeQueryInteger($siteID)
        ));

        $db->query(sprintf(
            "UPDATE user SET access_level = 0 WHERE user_id = %s AND site_id = %s",
            $userID, $db->makeQueryInteger($siteID)
        ));

        echo json_encode(['success' => true]);
        die();
    }

    private function suspendEmployee()
    {
        $db     = DatabaseConnection::getInstance();
        $siteID = $_SESSION['CATS']->getSiteID();
        $userID = intval($_POST['userID'] ?? 0);
        $status = ($_POST['status'] ?? 'suspended') === 'active' ? 'active' : 'suspended';

        if (!$userID) { echo json_encode(['success' => false]); die(); }

        $db->query(sprintf(
            "UPDATE user SET org_status = %s WHERE user_id = %s AND site_id = %s",
            $db->makeQueryString($status),
            $userID,
            $db->makeQueryInteger($siteID)
        ));

        echo json_encode(['success' => true, 'status' => $status]);
        die();
    }

    private function getEmployee()
    {
        $db     = DatabaseConnection::getInstance();
        $siteID = $_SESSION['CATS']->getSiteID();
        $userID = intval($_GET['userID'] ?? 0);

        /* Explicit column list -- `SELECT u.*` was returning the password hash
         * and session_cookie columns verbatim in the JSON response. */
        $sql = sprintf(
            "SELECT
                u.user_id AS userID, u.first_name AS firstName, u.last_name AS lastName,
                u.email AS email, u.title AS title, u.phone_work AS phone,
                u.reports_to AS reportsTo, u.department AS department,
                u.employee_id AS employeeID, u.org_status AS orgStatus,
                al.short_description AS accessLevel
             FROM user u
             LEFT JOIN access_level al ON u.access_level = al.access_level_id
             WHERE u.user_id = %s AND u.site_id = %s",
            $userID, $db->makeQueryInteger($siteID)
        );

        $rs = $db->getAssoc($sql);
        echo json_encode($rs ?: []);
        die();
    }
}
?>
