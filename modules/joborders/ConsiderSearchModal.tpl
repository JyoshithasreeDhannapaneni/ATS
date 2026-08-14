<?php /* $Id: ConsiderSearchModal.tpl 3093 2007-09-24 21:09:45Z brian $ */ ?>
<?php TemplateUtility::printModalHeader('Job Orders', 'js/sorttable.js', 'Add Candidate to This Job Order'); ?>

<div class="form-container">
    <?php if (!$this->isFinishedMode): ?>
        <div class="form-note">Search for a candidate below, and then click on the candidate's
        first or last name to add the selected candidate to the job order
        pipeline.</div>

        <div class="form-section">
            <form id="searchByFullNameForm" name="searchByFullNameForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=considerCandidateSearch" method="post">
                <input type="hidden" name="postback" id="postback" value="postback" />
                <input type="hidden" id="mode_fullname" name="mode" value="searchByFullName" />
                <input type="hidden" id="jobOrderID_fullName" name="jobOrderID" value="<?php echo($this->jobOrderID); ?>" />

                <div class="form-row">
                    <div class="form-label">Full Name</div>
                    <div class="form-input">
                        <input type="text" class="inputbox" id="wildCardString_fullname" name="wildCardString" style="max-width: 260px;" />
                        <button type="submit" class="form-btn form-btn-primary" id="searchByFullName" name="searchByFullName">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="form-section">
            <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=addCandidateModal&amp;jobOrderID=<?php echo($this->jobOrderID); ?>">
                <img src="images/candidate_inline.gif" width="16" height="16" class="absmiddle" alt="add" border="0" />&nbsp;Add Candidate
            </a>
        </div>

        <?php if (empty($_POST['mode']) || $_POST['mode'] == 'searchByFullName'): ?>
            <script type="text/javascript">
                document.searchByFullNameForm.wildCardString.focus();
            </script>
        <?php else: ?>
            <script type="text/javascript">
                document.searchByKeySkillsForm.wildCardString.focus();
            </script>
        <?php endif; ?>

        <?php if ($this->isResultsMode): ?>
            <div class="form-section">
                <div class="form-section-title">Search Results</div>

                <?php if (!empty($this->rs)): ?>
                    <table class="sortable" width="100%">
                        <tr>
                            <th align="left" nowrap="nowrap"></th>
                            <th align="left" nowrap="nowrap">First Name</th>
                            <th align="left" nowrap="nowrap">Last Name</th>
                            <th align="left" nowrap="nowrap">Key Skills</th>
                            <th align="left">Created</th>
                            <th align="left">Owner</th>
                            <th align="center">Action</th>
                        </tr>

                        <?php foreach ($this->rs as $rowNumber => $data): ?>
                            <tr class="<?php TemplateUtility::printAlternatingRowClass($rowNumber); ?>">
                                <?php if($data['isDuplicateCandidate'] == 1): ?>
                                    <td valign="top" align="left">
                                        <img src="images/wf_error.gif" alt="" width="16" height="16" title="Duplicate Candidate"/>
                                    </td>
                                <?php else: ?>
                                    <td valign="top" align="left">
                                        <img src="images/mru/blank.gif" alt="" width="16" height="16" />
                                    </td>
                                <?php endif; ?>
                                <?php if (!$data['inPipeline']): ?>
                                    <td valign="top" align="left">
                                        <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=addToPipeline&amp;getback=getback&amp;jobOrderID=<?php echo($this->jobOrderID); ?>&amp;candidateID=<?php $this->_($data['candidateID']); ?>">
                                            <?php $this->_($data['firstName']); ?>
                                        </a>
                                        &nbsp;
                                    </td>
                                    <td valign="top" align="left">
                                        <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=addToPipeline&amp;getback=getback&amp;jobOrderID=<?php echo($this->jobOrderID); ?>&amp;candidateID=<?php $this->_($data['candidateID']); ?>">
                                            <?php $this->_($data['lastName']); ?>
                                        </a>
                                        &nbsp;
                                    </td>
                                <?php else: ?>
                                    <td valign="top" align="left"><?php $this->_($data['firstName']); ?>&nbsp;</td>
                                    <td valign="top" align="left"><?php $this->_($data['lastName']); ?>&nbsp;</td>
                                <?php endif; ?>
                                <td valign="top" align="left"><?php $this->_($data['keySkills']); ?>&nbsp;</td>
                                <td valign="top" align="left" nowrap="nowrap"><?php $this->_($data['dateCreated']); ?>&nbsp;</td>
                                <td valign="top" align="left" nowrap="nowrap"><?php $this->_($data['ownerAbbrName']); ?>&nbsp;</td>
                                <td align="center" nowrap="nowrap">
                                    <a href="#" title="Show Candidate" onclick="javascript:openCenteredPopup('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;display=popup&amp;candidateID=<?php $this->_($data['candidateID']); ?>', 'viewCandidateDetails', 1000, 675, true); return false;">
                                        <img src="images/new_browser_inline.gif" alt="consider" width="16" height="16" border="0" class="absmiddle" />
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No matching entries found.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="form-section">
            <p>The selected candidate has been successfully added to the pipeline for this job order.</p>
        </div>
        <div class="form-actions">
            <button type="button" class="form-btn form-btn-primary" onclick="parentGoToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=show&amp;jobOrderID=<?php echo($this->jobOrderID); ?>');">Close</button>
        </div>
    <?php endif; ?>
</div>
    </body>
</html>
