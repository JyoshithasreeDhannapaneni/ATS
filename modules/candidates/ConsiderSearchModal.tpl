<?php /* $Id: ConsiderSearchModal.tpl 3093 2007-09-24 21:09:45Z brian $ */ ?>
<?php TemplateUtility::printModalHeader('Candidates', array(), 'Add Candidates to Job Order'); ?>

<div class="form-container">
    <?php if (!$this->isFinishedMode): ?>
        <div class="form-note">Search for a job order below, and then click on the job title to add
        the candidate to the selected job order.</div>

        <div class="form-section">
            <form id="searchByJobTitleForm" name="searchByJobTitleForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=considerForJobSearch" method="post">
                <input type="hidden" name="postback" id="postback" value="postback" />
                <input type="hidden" id="mode_jobtitle" name="mode" value="searchByJobTitle" />
                <input type="hidden" id="candidateID_jobtitle" name="candidateIDArrayStored" value="<?php echo($this->candidateIDArrayStored); ?>" />

                <div class="form-row">
                    <div class="form-label">Job Title</div>
                    <div class="form-input">
                        <input type="text" class="inputbox" id="wildCardString_jobTitle" name="wildCardString" style="max-width: 220px;" />
                        <button type="submit" class="form-btn form-btn-primary" id="searchByJobTitle" name="searchByJobTitle">Search by Job Title</button>
                    </div>
                </div>
            </form>

            <form id="searchByCompanyNameForm" name="searchByCompanyNameForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=considerForJobSearch" method="post">
                <input type="hidden" name="postback" id="postback" value="postback" />
                <input type="hidden" id="mode_companyname" name="mode" value="searchByCompanyName" />
                <input type="hidden" id="candidateID_companyname" name="candidateIDArrayStored" value="<?php echo($this->candidateIDArrayStored); ?>" />

                <div class="form-row">
                    <div class="form-label">Company Name</div>
                    <div class="form-input">
                        <input type="text" class="inputbox" id="wildCardString_companyname" name="wildCardString" style="max-width: 220px;" />
                        <button type="submit" class="form-btn form-btn-primary" id="searchByCompanyName" name="searchByCompanyName">Search by Company Name</button>
                    </div>
                </div>
            </form>
        </div>

        <?php if (empty($_POST['mode']) || $_POST['mode'] == 'searchByJobTitle'): ?>
            <script type="text/javascript">
                document.searchByJobTitleForm.wildCardString.focus();
            </script>
        <?php else: ?>
            <script type="text/javascript">
                document.searchByCompanyNameForm.wildCardString.focus();
            </script>
        <?php endif; ?>

        <?php if ($this->isResultsMode): ?>
            <div class="form-section">
                <div class="form-section-title">Search Results</div>

                <?php if (!empty($this->rs)): ?>
                    <table class="sortable" width="100%">
                        <tr>
                            <th align="left">Ref. #</th>
                            <th align="left">Title</th>
                            <th align="left">Company</th>
                            <th align="left">Type</th>
                            <th align="left">Status</th>
                            <th align="left">Created</th>
                            <th align="left">Start</th>
                            <th align="left">Recruiter</th>
                            <th align="left">Owner</th>
                            <th align="center">Action</th>
                        </tr>

                        <?php foreach ($this->rs as $rowNumber => $data): ?>
                            <tr class="<?php TemplateUtility::printAlternatingRowClass($rowNumber); ?>">
                                <td align="left" valign="top"><?php $this->_($data['jobID']); ?></td>
                                <td align="left" valign="top">
                                    <?php if (!$data['inPipeline']): ?>
                                        <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addToPipeline&amp;getback=getback&amp;candidateIDArrayStored=<?php echo($this->candidateIDArrayStored); ?>&amp;jobOrderID=<?php $this->_($data['jobOrderID']); ?>" class="<?php $this->_($data['linkClass']); ?>">
                                            <?php $this->_($data['title']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="<?php $this->_($data['linkClass']); ?>"><?php $this->_($data['title']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td align="left" valign="top"><?php $this->_($data['companyName']); ?></td>
                                <td align="left" valign="top"><?php $this->_($data['type']); ?></td>
                                <td align="left" valign="top"><?php $this->_($data['status']); ?></td>
                                <td align="left" valign="top" nowrap="nowrap"><?php $this->_($data['dateCreated']); ?></td>
                                <td align="left" valign="top" nowrap="nowrap"><?php $this->_($data['startDate']); ?></td>
                                <td align="left" valign="top" nowrap="nowrap"><?php $this->_($data['recruiterAbbrName']); ?></td>
                                <td align="left" valign="top" nowrap="nowrap"><?php $this->_($data['ownerAbbrName']); ?></td>
                                <td align="center" nowrap="nowrap">
                                    <a href="#" title="Show Job Order" onclick="javascript:openCenteredPopup('<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=show&amp;display=popup&amp;jobOrderID=<?php $this->_($data['jobOrderID']); ?>', 'viewJobOrderDetails', 1000, 675, true); return false;">
                                        <img src="images/new_browser_inline.gif" alt="consider" width="16" height="16" border="0" class="absmiddle" />
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No matching entries found.</p>
                <?php endif; ?>
                <div class="form-actions" style="padding-left: 0; padding-right: 0; background: none; border-top: none;">
                    <button type="button" class="form-btn form-btn-secondary" id="showRecentJobOrders" name="showRecentJobOrders" onclick="document.location.href='<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=considerForJobSearch&amp;candidateIDArrayStored=<?php echo($this->candidateIDArrayStored); ?>';">Show Recently Modified Job Orders</button>
                </div>
            </div>
        <?php else: ?>
            <div class="form-section">
                <div class="form-section-title">Recently Modified Job Orders</div>

                <?php if (!empty($this->rs)): ?>
                    <table class="sortable" width="100%">
                        <tr>
                            <th align="left">Ref. #</th>
                            <th align="left">Title</th>
                            <th align="left">Company</th>
                            <th align="left">Type</th>
                            <th align="left">Status</th>
                            <th align="left">Modified</th>
                            <th align="left">Start</th>
                            <th align="left">Recruiter</th>
                            <th align="left">Owner</th>
                            <th align="center">Action</th>
                        </tr>

                        <?php foreach ($this->rs as $rowNumber => $data): ?>
                            <tr class="<?php TemplateUtility::printAlternatingRowClass($rowNumber); ?>">
                                <td align="left" valign="top"><?php $this->_($data['jobID']); ?></td>
                                <td align="left" valign="top">
                                    <?php if (!$data['inPipeline']): ?>
                                        <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addToPipeline&amp;getback=getback&amp;candidateIDArrayStored=<?php echo($this->candidateIDArrayStored); ?>&amp;jobOrderID=<?php $this->_($data['jobOrderID']); ?>" class="<?php $this->_($data['linkClass']); ?>">
                                            <?php $this->_($data['title']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="<?php $this->_($data['linkClass']); ?>"><?php $this->_($data['title']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td align="left" valign="top"><?php $this->_($data['companyName']); ?></td>
                                <td align="left" valign="top"><?php $this->_($data['type']); ?></td>
                                <td align="left" valign="top"><?php $this->_($data['status']); ?></td>
                                <td align="left" valign="top" nowrap="nowrap"><?php $this->_($data['dateModified']); ?></td>
                                <td align="left" valign="top" nowrap="nowrap"><?php $this->_($data['startDate']); ?></td>
                                <td align="left" valign="top" nowrap="nowrap"><?php $this->_($data['recruiterAbbrName']); ?></td>
                                <td align="left" valign="top" nowrap="nowrap"><?php $this->_($data['ownerAbbrName']); ?></td>
                                <td align="center" nowrap="nowrap">
                                    <a href="#" title="Show Job Order" onclick="javascript:openCenteredPopup('<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=show&amp;display=popup&amp;jobOrderID=<?php $this->_($data['jobOrderID']); ?>', 'viewJobOrderDetails', 1000, 675, true); return false;">
                                        <img src="images/new_browser_inline.gif" alt="consider" width="16" height="16" border="0" class="absmiddle" />
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No recent job orders found.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="form-section">
            <p>The <?php if(count($this->candidateIDArray)>1): ?> <?php echo(count($this->candidateIDArray)); ?> candidates have<?php else: ?>candidate has<?php endif; ?> been successfully added to the pipeline for the selected job order.</p>
        </div>
        <div class="form-actions">
            <button type="button" class="form-btn form-btn-primary" onclick="parentHidePopWinRefresh();">Close</button>
        </div>
    <?php endif; ?>
</div>

    </body>
</html>
