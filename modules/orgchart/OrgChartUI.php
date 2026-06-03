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
        try { $db->query("ALTER TABLE \"user\" ADD COLUMN IF NOT EXISTS reports_to INTEGER DEFAULT NULL", true); } catch (Exception $e) {}
        try { $db->query("ALTER TABLE \"user\" ADD COLUMN IF NOT EXISTS department VARCHAR(100) DEFAULT NULL", true); } catch (Exception $e) {}
        try { $db->query("ALTER TABLE \"user\" ADD COLUMN IF NOT EXISTS employee_id VARCHAR(20) DEFAULT NULL", true); } catch (Exception $e) {}
        try { $db->query("ALTER TABLE \"user\" ADD COLUMN IF NOT EXISTS org_status VARCHAR(20) DEFAULT 'active'", true); } catch (Exception $e) {}
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
             FROM \"user\" u
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

        $sql = sprintf(
            "UPDATE \"user\" SET
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

        // Reassign their direct reports to their manager
        $db->query(sprintf(
            "UPDATE \"user\" SET reports_to = (SELECT reports_to FROM \"user\" WHERE user_id = %s) WHERE reports_to = %s AND site_id = %s",
            $userID, $userID, $db->makeQueryInteger($siteID)
        ));

        $db->query(sprintf(
            "UPDATE \"user\" SET access_level = 0 WHERE user_id = %s AND site_id = %s",
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
            "UPDATE \"user\" SET org_status = %s WHERE user_id = %s AND site_id = %s",
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

        $sql = sprintf(
            "SELECT u.*, al.short_description AS accessLevel
             FROM \"user\" u
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
