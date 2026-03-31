<?php /* $Id: Show.tpl 3814 2007-12-06 17:54:28Z brian $ */
include_once('./vendor/autoload.php');
use OpenCATS\UI\QuickActionMenu;

function jo_display($value, $fallback = '&mdash;') {
    $v = is_string($value) ? trim($value) : $value;
    if ($v === '' || $v === null || $v === '(Unknown)' || $v === 'Not Available') return $fallback;
    return $v;
}
?>
<?php if ($this->isPopup): ?>
    <?php TemplateUtility::printHeader('Job Order - '.$this->data['title'], array('js/sorttable.js', 'js/match.js', 'js/pipeline.js')); ?>
<?php else: ?>
    <?php TemplateUtility::printHeader('Job Order - '.$this->data['title'], array( 'js/sorttable.js', 'js/match.js', 'js/pipeline.js')); ?>
    <?php TemplateUtility::printHeaderBlock(); ?>
    <?php TemplateUtility::printTabs($this->active); ?>
        <div id="main">
            <?php TemplateUtility::printQuickSearch(); ?>
<?php endif; ?>

        <div id="contents">
            <!-- BREADCRUMB + TITLE -->
            <div class="page-header-row" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                <div class="page-header-left" style="display: flex; align-items: center; gap: 8px;">
                    <img src="images/job_orders.gif" width="24" height="24" border="0" alt="Job Orders" style="border: none;" />
                    <h2 style="margin: 0;">
                        <a href="<?php echo CATSUtility::getIndexName(); ?>?m=joborders&amp;a=listByView" style="color: #6b7280; text-decoration: none; font-weight: 500;">Job Orders</a>
                        <span style="color: #9ca3af; margin: 0 6px;">&rsaquo;</span>
                        <span style="color: #111827;"><?php $this->_($this->data['title']); ?></span>
                    </h2>
                </div>
            </div>

<?php if (!$this->isPopup): ?>
            <!-- ACTION BAR — moved to top -->
            <div id="actionbar" style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px 14px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                <span style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <?php if ($this->getUserAccessLevel('joborders.edit') >= ACCESS_LEVEL_EDIT): ?>
                        <a id="edit_link" href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=edit&amp;jobOrderID=<?php echo($this->jobOrderID); ?>" style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; background: #2563eb; color: #fff; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none;">
                            <img src="images/actions/edit.gif" width="14" height="14" class="absmiddle" alt="edit" border="0" style="filter: brightness(0) invert(1);" /> Edit
                        </a>
                    <?php endif; ?>
                    <?php if ($this->getUserAccessLevel('joborders.delete') >= ACCESS_LEVEL_DELETE): ?>
                        <a id="delete_link" href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=delete&amp;jobOrderID=<?php echo($this->jobOrderID); ?>" onclick="javascript:return confirm('Delete this job order?');" style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; background: #fff; color: #dc2626; border: 1px solid #fca5a5; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none;">
                            <img src="images/actions/delete.gif" width="14" height="14" class="absmiddle" alt="delete" border="0" /> Delete
                        </a>
                    <?php endif; ?>
                    <?php if ($this->getUserAccessLevel('joborders.hidden') >= ACCESS_LEVEL_MULTI_SA): ?>
                        <?php if ($this->data['isAdminHidden'] == 1): ?>
                            <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=administrativeHideShow&amp;jobOrderID=<?php echo($this->jobOrderID); ?>&amp;state=0" style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; background: #fff; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none;">
                                <img src="images/resume_preview_inline.gif" width="14" height="14" class="absmiddle" alt="" border="0" /> Show
                            </a>
                        <?php else: ?>
                            <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=administrativeHideShow&amp;jobOrderID=<?php echo($this->jobOrderID); ?>&amp;state=1" style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; background: #fff; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none;">
                                <img src="images/resume_preview_inline.gif" width="14" height="14" class="absmiddle" alt="" border="0" /> Hide
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </span>
                <span style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <?php if (!empty($this->data['public']) && $this->careerPortalEnabled): ?>
                        <a id="public_link" href="<?php echo(CATSUtility::getAbsoluteURI()); ?>careers/<?php echo(CATSUtility::getIndexName()); ?>?p=showJob&amp;ID=<?php echo($this->jobOrderID); ?>" style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; background: #fff; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none;">
                            <img src="images/public.gif" width="14" height="14" class="absmiddle" alt="" border="0" /> Online Application
                        </a>
                    <?php endif; ?>
                    <a id="report_link" href="<?php echo(CATSUtility::getIndexName()); ?>?m=reports&amp;a=customizeJobOrderReport&amp;jobOrderID=<?php echo($this->jobOrderID); ?>" style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; background: #fff; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none;">
                        <img src="images/reportsSmall.gif" width="14" height="14" class="absmiddle" alt="" border="0" /> Report
                    </a>
                    <?php if ($this->privledgedUser): ?>
                        <a id="history_link" href="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=viewItemHistory&amp;dataItemType=400&amp;dataItemID=<?php echo($this->jobOrderID); ?>" style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; background: #fff; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none;">
                        <img src="images/icon_clock.gif" width="14" height="14" class="absmiddle" alt="" border="0" /> History
                        </a>
                    <?php endif; ?>
                </span>
            </div>
<?php endif; ?>

            <?php if ($this->data['isAdminHidden'] == 1): ?>
                <p class="warning">This Job Order is hidden.  Only CATS Administrators can view it or search for it.  To make it visible by the site users, click <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=administrativeHideShow&amp;jobOrderID=<?php echo($this->jobOrderID); ?>&amp;state=0" style="font-weight:bold;">Here.</a></p>
            <?php endif; ?>

            <?php if (isset($this->frozen)): ?>
                <table style="font-weight:bold; border: 1px solid #000; background-color: #ffed1a; padding:5px; margin-bottom:7px;" width="100%" id="candidateAlreadyInSystemTable">
                    <tr>
                        <td class="tdVertical">
                            This Job Order is <?php $this->_($this->data['status']); ?> and can not be modified.
                           <?php if ($this->getUserAccessLevel('joborders.edit') >= ACCESS_LEVEL_EDIT): ?>
                               <a id="edit_link" href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=edit&amp;jobOrderID=<?php echo($this->jobOrderID); ?>">
                                   <img src="images/actions/edit.gif" width="16" height="16" class="absmiddle" alt="edit" border="0" />&nbsp;Edit
                               </a>
                               the Job Order to make it Active.&nbsp;&nbsp;
                           <?php endif; ?>
                        </td>
                    </tr>
                </table>
            <?php endif; ?>

            <!-- PUBLIC NOTICE — split into separate banners -->
            <?php if ($this->isPublic): ?>
                <div style="background: #eff6ff; padding: 10px 14px; margin: 0 0 8px 0; border: 1px solid #bfdbfe; border-radius: 6px; font-size: 13px; color: #1e40af; display: flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    <span>
                        <b>This job order is public</b><?php if ($this->careerPortalURL !== false): ?>
                            &mdash; shown on your
                            <?php if ($this->getUserAccessLevel('joborders.careerPortalUrl') >= ACCESS_LEVEL_SA): ?>
                                <a style="font-weight: 600; color: #1e40af;" href="<?php $this->_($this->careerPortalURL); ?>">Careers Website</a>.
                            <?php else: ?>
                                Careers Website.
                            <?php endif; ?>
                        <?php else: ?>.<?php endif; ?>
                    </span>
                </div>

                <?php if ($this->questionnaireID !== false): ?>
                    <div style="background: #eff6ff; padding: 10px 14px; margin: 0 0 12px 0; border: 1px solid #bfdbfe; border-radius: 6px; font-size: 13px; color: #1e40af;">
                        Applicants must complete the "<i><?php echo $this->questionnaireData['title']; ?></i>" (<a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&a=careerPortalQuestionnaire&questionnaireID=<?php echo $this->questionnaireID; ?>" style="color: #1e40af; font-weight: 600;">edit</a>) questionnaire when applying.
                    </div>
                <?php else: ?>
                    <div style="background: #fffbeb; padding: 10px 14px; margin: 0 0 12px 0; border: 1px solid #fde68a; border-radius: 6px; font-size: 13px; color: #92400e; display: flex; align-items: center; gap: 8px;">
                        <svg width="16" height="16" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        No questionnaire attached.
                        <?php if ($this->getUserAccessLevel('setting.carrerPortalSettings') >= ACCESS_LEVEL_SA): ?>
                            <a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&a=careerPortalSettings" style="color: #92400e; font-weight: 600;">Assign one</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (!empty($this->publicApplyUrl)): ?>
            <div id="jo-share-apply-link" style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 8px; padding: 14px 16px; margin: 0 0 14px 0; font-size: 13px; color: #14532d;">
                <?php if (!empty($this->jobCreatedHighlight)): ?>
                    <div style="font-weight: 600; margin-bottom: 10px; color: #166534;">Job order saved. Share the application link below on LinkedIn, your careers page, or anywhere you post jobs.</div>
                <?php endif; ?>
                <div style="font-weight: 600; margin-bottom: 6px;">Public application link</div>
                <p style="margin: 0 0 10px 0; color: #15803d; line-height: 1.45;">Anyone with this link can open the job and apply (same as &ldquo;Online Application&rdquo;). Only works while this job is <strong>public</strong> and the career portal is enabled.</p>
                <div style="display: flex; flex-wrap: wrap; align-items: stretch; gap: 8px;">
                    <input type="text" readonly="readonly" id="joPublicApplyUrlInput" value="<?php echo htmlspecialchars($this->publicApplyUrl, ENT_QUOTES, 'UTF-8'); ?>" onclick="this.select();" style="flex: 1; min-width: 240px; padding: 10px 12px; border: 1px solid #bbf7d0; border-radius: 6px; font-size: 12px; background: #fff; color: #166534;" />
                    <button type="button" id="joPublicApplyUrlCopyBtn" style="padding: 10px 16px; background: #16a34a; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">Copy link</button>
                </div>
                <span id="joPublicApplyUrlCopied" style="display: none; margin-top: 8px; font-weight: 600; color: #166534;">Copied to clipboard.</span>
            </div>
            <script type="text/javascript">
            (function () {
                var btn = document.getElementById('joPublicApplyUrlCopyBtn');
                var input = document.getElementById('joPublicApplyUrlInput');
                var msg = document.getElementById('joPublicApplyUrlCopied');
                if (!btn || !input) return;
                function showCopied() {
                    if (msg) {
                        msg.style.display = 'block';
                        setTimeout(function () { msg.style.display = 'none'; }, 2500);
                    }
                }
                btn.onclick = function () {
                    input.focus();
                    input.select();
                    input.setSelectionRange(0, 99999);
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(input.value).then(showCopied).catch(function () {
                            try { document.execCommand('copy'); } catch (e) {}
                            showCopied();
                        });
                    } else {
                        try { document.execCommand('copy'); } catch (e) {}
                        showCopied();
                    }
                };
            })();
            </script>
            <?php endif; ?>

            <!-- JOB DETAILS — two-column table with empty-field fallbacks -->
            <table class="detailsOutside" width="100%" height="<?php echo((count($this->extraFieldRS)/2 + 12) * 22); ?>">
                <tr style="vertical-align:top;">
                    <td width="50%" height="100%">
                        <table class="detailsInside" height="100%">
                            <tr>
                                <td class="vertical">Title:</td>
                                <td class="data" width="300">
                                    <span class="<?php echo($this->data['titleClass']); ?>"><?php $this->_($this->data['title']); ?></span>
                                    <?php echo($this->data['public']) ?>
                                    <?php TemplateUtility::printSingleQuickActionMenu(new QuickActionMenu(DATA_ITEM_JOBORDER, $this->data['jobOrderID'], $_SESSION['CATS']->getAccessLevel('joborders.edit'))); ?>
                                </td>
                            </tr>

                            <tr>
                                <td class="vertical">Company:</td>
                                <td class="data">
                                    <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=companies&amp;a=show&amp;companyID=<?php echo($this->data['companyID']); ?>">
                                        <?php echo jo_display($this->data['companyName']); ?>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td class="vertical">Department:</td>
                                <td class="data"><?php echo jo_display($this->data['department']); ?></td>
                            </tr>

                            <!-- CONTACT INFO -->
                            <?php if (!empty(trim($this->data['contactFullName'] ?? ''))): ?>
                            <tr>
                                <td class="vertical">Contact:</td>
                                <td class="data">
                                    <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=contacts&amp;a=show&amp;contactID=<?php echo($this->data['contactID']); ?>">
                                        <?php echo($this->data['contactFullName']); ?>
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>

                            <?php if (!empty(trim($this->data['contactWorkPhone'] ?? ''))): ?>
                            <tr>
                                <td class="vertical">Contact Phone:</td>
                                <td class="data"><?php echo($this->data['contactWorkPhone']); ?></td>
                            </tr>
                            <?php endif; ?>

                            <?php if (!empty(trim($this->data['contactEmail'] ?? ''))): ?>
                            <tr>
                                <td class="vertical">Contact Email:</td>
                                <td class="data">
                                    <a href="mailto:<?php $this->_($this->data['contactEmail']); ?>"><?php $this->_($this->data['contactEmail']); ?></a>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <!-- /CONTACT INFO -->

                            <tr>
                                <td class="vertical">Location:</td>
                                <td class="data"><?php echo jo_display($this->data['cityAndState']); ?></td>
                            </tr>

                            <?php if (!empty(trim($this->data['maxRate'] ?? ''))): ?>
                            <tr>
                                <td class="vertical">Max Rate:</td>
                                <td class="data"><?php $this->_($this->data['maxRate']); ?></td>
                            </tr>
                            <?php endif; ?>

                            <tr>
                                <td class="vertical">Salary:</td>
                                <td class="data"><?php echo jo_display($this->data['salary']); ?></td>
                            </tr>

                            <tr>
                                <td class="vertical">Start Date:</td>
                                <td class="data"><?php echo jo_display($this->data['startDate']); ?></td>
                            </tr>

                            <?php for ($i = 0; $i < intval(count($this->extraFieldRS)/2); $i++): ?>
                               <?php if(($this->extraFieldRS[$i]['extraFieldType']) != EXTRA_FIELD_TEXTAREA): ?>
                                   <tr>
                                        <td class="vertical"><?php $this->_($this->extraFieldRS[$i]['fieldName']); ?>:</td>
                                        <td class="data"><?php echo jo_display($this->extraFieldRS[$i]['display']); ?></td>
                                   </tr>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php eval(Hooks::get('JO_TEMPLATE_SHOW_BOTTOM_OF_LEFT')); ?>

                        </table>
                    </td>

                    <td width="50%" height="100%" style="vertical-align:top;" >
                        <table class="detailsInside" height="100%">
                            <tr>
                                <td class="vertical">Duration:</td>
                                <td class="data"><?php echo jo_display($this->data['duration']); ?></td>
                            </tr>

                            <tr>
                                <td class="vertical">Openings:</td>
                                <td class="data"><?php $this->_($this->data['openings']); if ($this->data['openingsAvailable'] != $this->data['openings']): ?> (<?php $this->_($this->data['openingsAvailable']); ?> Available)<?php endif; ?></td>
                            </tr>

                            <tr>
                                <td class="vertical">Type:</td>
                                <td class="data">
                                    <?php
                                        $typeVal = jo_display($this->data['typeDescription'], '');
                                        if ($typeVal === '' || $typeVal === '(Unknown)'):
                                    ?>
                                        <span style="color: #9ca3af; font-style: italic;">Not set</span>
                                        <?php if ($this->getUserAccessLevel('joborders.edit') >= ACCESS_LEVEL_EDIT): ?>
                                            &nbsp;<a href="<?php echo CATSUtility::getIndexName(); ?>?m=joborders&amp;a=edit&amp;jobOrderID=<?php echo $this->jobOrderID; ?>" style="font-size: 11px; color: #2563eb;">Set type</a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php echo $typeVal; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <tr>
                                <td class="vertical">Questionnaire:</td>
                                <td class="data">
                                    <?php if (isset($this->questionnaireID) && $this->questionnaireID): ?>
                                        <strong><?php echo htmlspecialchars($this->questionnaireData['title']); ?></strong>
                                        <br />
                                        <small style="color: #666;">
                                            <?php if ($this->getUserAccessLevel('joborders.edit') >= ACCESS_LEVEL_EDIT): ?>
                                                <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=edit&amp;jobOrderID=<?php echo($this->jobOrderID); ?>#questionnaire">Edit Questionnaire</a>
                                            <?php else: ?>
                                                Assigned: <?php echo htmlspecialchars($this->questionnaireData['description']); ?>
                                            <?php endif; ?>
                                        </small>
                                    <?php else: ?>
                                        <em style="color: #999;">No questionnaire assigned</em>
                                        <br />
                                        <small style="color: #666;">
                                            <?php if ($this->getUserAccessLevel('joborders.edit') >= ACCESS_LEVEL_EDIT): ?>
                                                <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=edit&amp;jobOrderID=<?php echo($this->jobOrderID); ?>#questionnaire">Assign Questionnaire</a>
                                            <?php endif; ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <tr>
                                <td class="vertical">Status:</td>
                                <td class="data">
                                    <?php
                                        $statusVal = trim($this->data['status']);
                                        $statusColors = array(
                                            'Active' => 'background:#ecfdf5;color:#065f46;',
                                            'Closed' => 'background:#fef2f2;color:#991b1b;',
                                            'Full' => 'background:#fef2f2;color:#991b1b;',
                                            'Upcoming' => 'background:#eff6ff;color:#1e40af;',
                                            'Lead' => 'background:#f5f3ff;color:#5b21b6;',
                                            'On Hold' => 'background:#fffbeb;color:#92400e;',
                                        );
                                        $sStyle = isset($statusColors[$statusVal]) ? $statusColors[$statusVal] : 'background:#f3f4f6;color:#374151;';
                                    ?>
                                    <span style="<?php echo $sStyle; ?> padding: 2px 10px; border-radius: 10px; font-size: 12px; font-weight: 600;"><?php echo htmlspecialchars($statusVal); ?></span>
                                </td>
                            </tr>

                            <tr>
                                <td class="vertical">Candidates:</td>
                                <td class="data"><?php $this->_($this->data['pipeline']) ?></td>
                            </tr>

                            <tr>
                                <td class="vertical">Submitted:</td>
                                <td class="data"><?php $this->_($this->data['submitted']) ?></td>
                            </tr>

                            <tr>
                                <td class="vertical">Posted:</td>
                                <td class="data">
                                    <?php
                                        $daysOld = intval($this->data['daysOld']);
                                        if ($daysOld == 0) echo 'Today';
                                        elseif ($daysOld == 1) echo '1 day ago';
                                        else echo $daysOld . ' days ago';
                                    ?>
                                    <span style="color: #9ca3af; font-size: 11px; margin-left: 4px;">(<?php $this->_($this->data['dateCreated']); ?>)</span>
                                </td>
                            </tr>

                            <tr>
                                <td class="vertical">Created By:</td>
                                <td class="data"><?php $this->_($this->data['enteredByFullName']); ?></td>
                            </tr>

                            <tr>
                                <td class="vertical">Recruiter:</td>
                                <td class="data"><?php echo jo_display($this->data['recruiterFullName']); ?></td>
                            </tr>

                            <tr>
                                <td class="vertical">Owner:</td>
                                <td class="data"><?php echo jo_display($this->data['ownerFullName']); ?></td>
                            </tr>

                            <?php
                                $hasCompanyJobID = !empty(trim($this->data['companyJobID'] ?? ''));
                            ?>
                            <?php if ($hasCompanyJobID): ?>
                            <tr>
                                <td class="vertical">Company Job ID:</td>
                                <td class="data"><?php echo($this->data['companyJobID']); ?></td>
                            </tr>
                            <?php endif; ?>

                            <?php for ($i = (intval(count($this->extraFieldRS))/2); $i < (count($this->extraFieldRS)); $i++): ?>
                                <?php if(($this->extraFieldRS[$i]['extraFieldType']) != EXTRA_FIELD_TEXTAREA): ?>
                                    <tr>
                                        <td class="vertical"><?php $this->_($this->extraFieldRS[$i]['fieldName']); ?>:</td>
                                        <td class="data"><?php echo jo_display($this->extraFieldRS[$i]['display']); ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php eval(Hooks::get('JO_TEMPLATE_SHOW_BOTTOM_OF_RIGHT')); ?>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- DESCRIPTION + NOTES + ATTACHMENTS -->
            <table class="detailsOutside">
                <tr>
                    <td>
                        <table class="detailsInside">
                            <tr>
                                <td valign="top" class="vertical">Attachments:</td>
                                <td valign="top" class="data">
                                    <table class="attachmentsTable">
                                        <?php foreach ($this->attachmentsRS as $rowNumber => $attachmentsData): ?>
                                            <tr>
                                                <td>
                                                    <?php echo $attachmentsData['retrievalLink']; ?>
                                                        <img src="<?php $this->_($attachmentsData['attachmentIcon']) ?>" alt="" width="16" height="16" border="0" />
                                                        &nbsp;
                                                        <?php $this->_($attachmentsData['originalFilename']) ?>
                                                    </a>
                                                </td>
                                                <td><?php $this->_($attachmentsData['dateCreated']) ?></td>
                                                <td>
                                                    <?php if (!$this->isPopup): ?>
                                                        <?php if ($this->getUserAccessLevel('joborders.deleteAttachment') >= ACCESS_LEVEL_DELETE): ?>
                                                            <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=deleteAttachment&amp;jobOrderID=<?php echo($this->jobOrderID); ?>&amp;attachmentID=<?php $this->_($attachmentsData['attachmentID']) ?>"  title="Delete" onclick="javascript:return confirm('Delete this attachment?');">
                                                                <img src="images/actions/delete.gif" alt="" width="16" height="16" border="0" />
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                    <?php if (!$this->isPopup): ?>
                                        <?php if ($this->getUserAccessLevel('joborders.createAttachment') >= ACCESS_LEVEL_EDIT): ?>
                                            <?php if (isset($this->attachmentLinkHTML)): ?>
                                                <?php echo($this->attachmentLinkHTML); ?>
                                            <?php else: ?>
                                                <a href="#" onclick="showPopWin('<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=createAttachment&amp;jobOrderID=<?php echo($this->jobOrderID); ?>', 400, 125, null); return false;">
                                            <?php endif; ?>
                                                <img src="images/paperclip_add.gif" width="16" height="16" border="0" alt="add attachment" class="absmiddle" />&nbsp;Add Attachment
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <tr>
                                <td valign="top" class="vertical">Description:</td>
                                <td class="data" colspan="2">
                                    <?php if($this->data['description'] != ''): ?>
                                    <div style="border: #ddd 1px solid; padding: 10px; border-radius: 6px; background: #fafbfc; line-height: 1.6; font-size: 13px;">
                                        <?php echo($this->data['description']); ?>
                                    </div>
                                    <?php else: ?>
                                    <span style="color: #9ca3af;">&mdash;</span>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <?php for ($i = (intval(count($this->extraFieldRS))/2); $i < (count($this->extraFieldRS)); $i++): ?>
                                <?php if(($this->extraFieldRS[$i]['extraFieldType']) == EXTRA_FIELD_TEXTAREA): ?>
                                    <tr>
                                        <td class="vertical"><?php $this->_($this->extraFieldRS[$i]['fieldName']); ?>:</td>
                                        <td class="data"><?php echo jo_display($this->extraFieldRS[$i]['display']); ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <tr>
                                <td valign="top" class="vertical">Internal Notes:</td>
                                <td class="data">
                                    <?php if($this->data['notes'] != ''): ?>
                                        <div style="border: #ddd 1px solid; padding: 10px; border-radius: 6px; background: #fafbfc; line-height: 1.6; font-size: 13px;">
                                            <?php echo($this->data['notes']); ?>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #9ca3af;">&mdash;</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <br />

            <p class="note">Candidates in Job Order</p>

            <p id="ajaxPipelineControl">
                Number of visible entries:&nbsp;&nbsp;
                <select id="numberOfEntriesSelect" onchange="PipelineJobOrder_changeLimit(<?php $this->_($this->data['jobOrderID']); ?>, this.value, <?php if ($this->isPopup) echo(1); else echo(0); ?>, 'ajaxPipelineTable', '<?php echo($this->sessionCookie); ?>', 'ajaxPipelineTableIndicator', '<?php echo(CATSUtility::getIndexName()); ?>');" class="selectBox">
                    <option value="15" <?php if ($this->pipelineEntriesPerPage == 15): ?>selected<?php endif; ?>>15 entries</option>
                    <option value="30" <?php if ($this->pipelineEntriesPerPage == 30): ?>selected<?php endif; ?>>30 entries</option>
                    <option value="50" <?php if ($this->pipelineEntriesPerPage == 50): ?>selected<?php endif; ?>>50 entries</option>
                    <option value="99999" <?php if ($this->pipelineEntriesPerPage == 99999): ?>selected<?php endif; ?>>All entries</option>
                </select>&nbsp;
                <span id="ajaxPipelineNavigation">
                </span>&nbsp;
                <img src="images/indicator.gif" alt="" id="ajaxPipelineTableIndicator" />
            </p>

            <div id="ajaxPipelineTable"></div>
            <input type="checkbox" name="select_all" onclick="selectAll_candidates(this)" title="Select all candidates" /> <a href="javascript:void(0);" onclick="exportFromPipeline()" title="Export selected candidates">Export</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <script type="text/javascript">
            	function exportFromPipeline(){
<?php
	$params = array(
			'sortBy' => 'dateModifiedSort',
			'sortDirection' => 'DESC',
	        'filterVisible' => false,
	        'rangeStart' => 0,
	        'maxResults' => 100000000,
	        'exportIDs' => '<dynamic>',
	        'noSaveParameters' => true);

	$instance_name = 'candidates:candidatesListByViewDataGrid';
	$instance_md5 = md5($instance_name);
?>
					var exportArray<?= $instance_md5 ?> = getSelected_candidates();
            		if (exportArray<?= $instance_md5 ?>.length>0) {
                		window.location.href='<?= CATSUtility::getIndexName()?>?m=export&a=exportByDataGrid&i=<?= urlencode($instance_name); ?>&p=<?= urlencode(serialize($params)) ?>&dynamicArgument<?= $instance_md5 ?>=' + urlEncode(serializeArray(exportArray<?= $instance_md5 ?>));
            		} else {
                		alert('No data selected');
            		}
            	}


            </script>
            <script type="text/javascript">
                PipelineJobOrder_populate(<?php $this->_($this->data['jobOrderID']); ?>, 0, <?php $this->_($this->pipelineEntriesPerPage); ?>, 'dateCreatedInt', 'desc', <?php if ($this->isPopup) echo(1); else echo(0); ?>, 'ajaxPipelineTable', '<?php echo($this->sessionCookie); ?>', 'ajaxPipelineTableIndicator', '<?php echo(CATSUtility::getIndexName()); ?>');
            </script>

            <?php if (!$this->isPopup): ?>
            <?php if ($this->getUserAccessLevel('joborders.considerCandidateSearch') >= ACCESS_LEVEL_EDIT && !isset($this->frozen)): ?>
                <a href="#" onclick="showPopWin('<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=considerCandidateSearch&amp;jobOrderID=<?php echo($this->jobOrderID); ?>', 820, 550, null); return false;">
                    <img src="images/consider.gif" width="16" height="16" class="absmiddle" alt="add candidate" border="0" />&nbsp;Add Candidate to This Job Order
                </a>
            <?php endif; ?>
        </div>
    </div>

<?php endif; ?>
<?php TemplateUtility::printFooter(); ?>
