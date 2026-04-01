<?php /* $Id: Users.tpl 2452 2007-05-11 17:47:55Z brian $ */ ?>
<?php TemplateUtility::printHeader('Settings', 'js/sorttable.js'); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <table>
                <tr>
                    <td width="3%">
                        <img src="images/settings.gif" width="24" height="24" alt="Settings" style="border: none; margin-top: 3px;" />&nbsp;
                    </td>
                    <td><h2>Settings: User Management</h2></td>
                </tr>
            </table>

            <p class="note">User Management</p>

            <table class="sortable">
                <thead>
                    <tr>
                        <th align="left" nowrap="nowrap">First Name</th>
                        <th align="left" nowrap="nowrap">Last Name</th>
                        <th align="left">Username</th>
                        <th align="left" nowrap="nowrap">Role</th>
                        <th align="left" nowrap="nowrap">Access Level</th>
                        <th align="left" nowrap="nowrap">Last Success</th>
                        <th align="left" nowrap="nowrap">Last Fail</th>
                        <th align="center" nowrap="nowrap">Actions</th>
                    </tr>
                </thead>

                <?php if (!empty($this->rs)): ?>
                    <?php foreach ($this->rs as $rowNumber => $data): ?>
                        <tr class="<?php TemplateUtility::printAlternatingRowClass($rowNumber); ?>">
                            <td valign="top" align="left">
                                <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=showUser&amp;userID=<?php $this->_($data['userID']); ?>">
                                    <?php $this->_($data['firstName']); ?>
                                </a>
                            </td>
                            <td valign="top" align="left">
                                <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=showUser&amp;userID=<?php $this->_($data['userID']); ?>">
                                    <?php $this->_($data['lastName']); ?>
                                </a>
                            </td>
                            <td valign="top" align="left"><?php $this->_($data['username']); ?></td>
                            <td valign="top" align="left">
                                <?php 
                                    $role = isset($data['role']) ? $data['role'] : null;
                                    $interviewerType = isset($data['interviewer_type']) ? $data['interviewer_type'] : '';
                                    
                                    // If role column doesn't exist, infer from access level
                                    if (empty($role)) {
                                        if ($data['accessLevel'] >= 400) {
                                            $role = 'admin';
                                        } else {
                                            $role = 'recruiter';
                                        }
                                    }
                                    
                                    // Display role with styled badge
                                    if ($role == 'admin') {
                                        echo '<span style="background: #dbeafe; color: #1e40af; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">Admin</span>';
                                    } elseif ($role == 'interviewer') {
                                        $typeLabel = $interviewerType ? $interviewerType : '';
                                        $bgColor = '#dcfce7'; $textColor = '#166534';
                                        if ($interviewerType == 'L2') { $bgColor = '#fef3c7'; $textColor = '#92400e'; }
                                        elseif ($interviewerType == 'L3') { $bgColor = '#fee2e2'; $textColor = '#991b1b'; }
                                        elseif ($interviewerType == 'HR') { $bgColor = '#f3e8ff'; $textColor = '#7c3aed'; }
                                        echo '<span style="background: ' . $bgColor . '; color: ' . $textColor . '; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">' . ($typeLabel ? $typeLabel . ' ' : '') . 'Interviewer</span>';
                                    } else {
                                        echo '<span style="background: #f3f4f6; color: #374151; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">Recruiter</span>';
                                    }
                                ?>
                            </td>
                            <td valign="top" align="left"><?php $this->_($data['accessLevelDescription']); ?></td>
                            <td valign="top" align="left"><?php $this->_($data['successfulDate']); ?></td>
                            <td valign="top" align="left"><?php $this->_($data['unsuccessfulDate']); ?></td>
                            <td valign="top" align="center">
                                <?php 
                                    $canEdit = true;
                                    $canDelete = true;
                                    $currentUserAccessLevel = $_SESSION['CATS']->getAccessLevel(ACL::SECOBJ_ROOT);
                                    
                                    // Cannot delete yourself
                                    if ($data['userID'] == $_SESSION['CATS']->getUserID()) {
                                        $canDelete = false;
                                    }
                                    
                                    // Cannot delete/edit users with same or higher access level (unless you're root)
                                    if ($data['accessLevel'] >= $currentUserAccessLevel && $currentUserAccessLevel < ACCESS_LEVEL_ROOT) {
                                        $canDelete = false;
                                        if ($data['userID'] != $_SESSION['CATS']->getUserID()) {
                                            $canEdit = false;
                                        }
                                    }
                                ?>
                                <?php if ($canEdit): ?>
                                <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=editUser&amp;userID=<?php $this->_($data['userID']); ?>" title="Edit">
                                    <img src="images/actions/edit.gif" width="16" height="16" style="border: none;" alt="Edit" />
                                </a>
                                <?php else: ?>
                                <img src="images/actions/edit.gif" width="16" height="16" style="border: none; opacity: 0.3;" alt="Cannot Edit" title="Cannot edit users with same or higher access level" />
                                <?php endif; ?>
                                &nbsp;
                                <?php if ($canDelete): ?>
                                <a href="javascript:void(0);" onclick="if(confirm('Are you sure you want to delete user <?php echo addslashes($data['firstName'] . ' ' . $data['lastName']); ?>? This cannot be undone.')) { window.location='<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=deleteUser&amp;userID=<?php $this->_($data['userID']); ?>'; }" title="Delete">
                                    <img src="images/actions/delete.gif" width="16" height="16" style="border: none;" alt="Delete" />
                                </a>
                                <?php else: ?>
                                <img src="images/actions/delete.gif" width="16" height="16" style="border: none; opacity: 0.3;" alt="Cannot Delete" title="<?php echo ($data['userID'] == $_SESSION['CATS']->getUserID()) ? 'Cannot delete yourself' : 'Cannot delete users with same or higher access level'; ?>" />
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
            <?php if (AUTH_MODE != "ldap"): ?>
                <a id="add_link" href="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=addUser" title="You have <?php $this->_($this->license['diff']); ?> user accounts remaining.">
                    <img src="images/candidate_inline.gif" width="16" height="16" class="absmiddle" alt="add" style="border: none;" />&nbsp;Add User
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
