<?php
/**
* Candidates link duplicity template
* @package OpenCATS
* @subpackage modules/candidates
* @copyright (C) OpenCats
* @license GNU/GPL, see license.txt
* OpenCATS is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License 2
* as published by the Free Software Foundation.
*/
?>
<?php TemplateUtility::printModalHeader('Candidates', array(), 'Select duplicate to this Candidate'); ?>

<div class="form-container">
    <?php if (!$this->isFinishedMode): ?>
        <div class="form-note">Search for a candidate below, and then click on the candidate name to link
        this candidate as a duplicate to them.</div>

        <div class="form-section">
            <form id="searchByCandidateNameForm" name="searchByCandidateNameForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=linkDuplicate" method="post">
                <input type="hidden" name="postback" id="postback" value="postback" />
                <input type="hidden" id="mode_candidateName" name="mode" value="searchByCandidateName" />
                <input type="hidden" id="candidateID" name="candidateID" value="<?php echo($this->duplicateCandidateID)?>" />
                <input type="hidden" id="candidateID_jobtitle" name="candidateIDArrayStored" value="<?php echo($this->candidateIDArrayStored); ?>" />

                <div class="form-row">
                    <div class="form-label">Candidate Name</div>
                    <div class="form-input">
                        <input type="text" class="inputbox" id="wildCardString_candidateName" name="wildCardString" style="max-width: 220px;" />
                        <button type="submit" class="form-btn form-btn-primary" id="searchByCandidateName" name="searchByCandidateName">Search by Candidate Name</button>
                    </div>
                </div>
            </form>
        </div>

        <?php if (empty($_POST['mode']) || $_POST['mode'] == 'searchByCandidateName'): ?>
            <script type="text/javascript">
                document.searchByCandidateNameForm.wildCardString.focus();
            </script>
        <?php endif; ?>

        <?php if ($this->isResultsMode): ?>
            <div class="form-section">
                <div class="form-section-title">Search Results</div>

                <?php if (!empty($this->rs)): ?>
                    <table class="sortable" width="100%">
                        <tr>
                            <th align="left">First Name</th>
                            <th align="left">Last Name</th>
                            <th align="left">E-mail</th>
                            <th align="left">Cell phone</th>
                            <th align="left">City</th>
                            <th align="left">State</th>
                            <th align="left">Owner</th>
                            <th align="center">Action</th>
                        </tr>

                        <?php foreach ($this->rs as $rowNumber => $data): ?>
                            <tr class="<?php TemplateUtility::printAlternatingRowClass($rowNumber); ?>">
                                <td align="left" valign="top">
                                    <?php if (!$data['linked']): ?>
                                        <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addDuplicates&amp;getback=getback&amp;candidateID=<?php $this->_($data['candidateID']); ?>&amp;duplicateCandidateID=<?php $this->_($data['duplicateCandidateID']); ?>" class="<?php $this->_($data['linkClass']); ?>">
                                            <?php $this->_($data['firstName']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="<?php $this->_($data['linkClass']); ?>"><?php $this->_($data['firstName']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td align="left" valign="top">
                                    <?php if (!$data['linked']): ?>
                                        <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addDuplicates&amp;getback=getback&amp;candidateID=<?php $this->_($data['candidateID']); ?>&amp;duplicateCandidateID=<?php $this->_($data['duplicateCandidateID']); ?>" class="<?php $this->_($data['linkClass']); ?>">
                                            <?php $this->_($data['lastName']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="<?php $this->_($data['linkClass']); ?>"><?php $this->_($data['lastName']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td align="left" valign="top"><?php $this->_($data['email1']); ?></td>
                                <td align="left" valign="top"><?php $this->_($data['phoneCell']); ?></td>
                                <td align="left" valign="top"><?php $this->_($data['city']); ?></td>
                                <td align="left" valign="top"><?php $this->_($data['state']); ?></td>
                                <td align="left" valign="top" nowrap="nowrap"><?php $this->_($data['ownerFirstName'])." ".$this->_($data['ownerLastName']); ?></td>
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
        <?php else: ?>
            <div class="form-section">
                <div class="form-section-title">All Candidates</div>

                <?php if (!empty($this->rs)): ?>
                    <table class="sortable" width="100%">
                        <tr>
                            <th align="left">First Name</th>
                            <th align="left">Last Name</th>
                            <th align="left">E-mail</th>
                            <th align="left">Cell phone</th>
                            <th align="left">City</th>
                            <th align="left">State</th>
                            <th align="left">Owner</th>
                            <th align="center">Action</th>
                        </tr>

                        <?php foreach ($this->rs as $rowNumber => $data): ?>
                            <tr class="<?php TemplateUtility::printAlternatingRowClass($rowNumber); ?>">
                                <td align="left" valign="top">
                                    <?php if (!$data['linked']): ?>
                                        <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addDuplicates&amp;getback=getback&amp;candidateID=<?php $this->_($data['candidateID']); ?>&amp;duplicateCandidateID=<?php $this->_($data['duplicateCandidateID']); ?>" class="<?php $this->_($data['linkClass']); ?>">
                                            <?php $this->_($data['firstName']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="<?php $this->_($data['linkClass']); ?>"><?php $this->_($data['firstName']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td align="left" valign="top">
                                    <?php if (!$data['linked']): ?>
                                        <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addDuplicates&amp;getback=getback&amp;candidateID=<?php $this->_($data['candidateID']); ?>&amp;duplicateCandidateID=<?php $this->_($data['duplicateCandidateID']); ?>" class="<?php $this->_($data['linkClass']); ?>">
                                            <?php $this->_($data['lastName']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="<?php $this->_($data['linkClass']); ?>"><?php $this->_($data['lastName']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td align="left" valign="top"><?php $this->_($data['email1']); ?></td>
                                <td align="left" valign="top"><?php $this->_($data['phoneCell']); ?></td>
                                <td align="left" valign="top"><?php $this->_($data['city']); ?></td>
                                <td align="left" valign="top"><?php $this->_($data['state']); ?></td>
                                <td align="left" valign="top" nowrap="nowrap"><?php $this->_($data['ownerFirstName'])." ".$this->_($data['ownerLastName']); ?></td>
                                <td align="center" nowrap="nowrap">
                                    <a href="#" title="Show Candidate" onclick="javascript:openCenteredPopup('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;display=popup&amp;candidateID=<?php $this->_($data['candidateID']); ?>', 'viewCandidateDetails', 1000, 675, true); return false;">
                                        <img src="images/new_browser_inline.gif" alt="consider" width="16" height="16" border="0" class="absmiddle" />
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No candidates found.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="form-section">
            <p>This candidate has been successfully added as a duplicate for the selected candidate.</p>
        </div>
        <div class="form-actions">
            <button type="button" class="form-btn form-btn-primary" onclick="parentHidePopWinRefresh();">Close</button>
        </div>
    <?php endif; ?>
</div>

    </body>
</html>
