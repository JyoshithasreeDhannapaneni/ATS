<?php /* $Id: Search.tpl 3813 2007-12-05 23:16:22Z brian $ */ ?>
<?php TemplateUtility::printHeader('Candidates', array('modules/candidates/validator.js', 'js/searchSaved.js', 'js/sweetTitles.js', 'js/searchAdvanced.js', 'js/highlightrows.js', 'js/export.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <table>
                <tr>
                    <td width="3%">
                        <img src="images/candidate.gif" width="24" height="24" border="0" alt="Candidates" style="margin-top: 3px;" />&nbsp;
                    </td>
                    <td><h2>Candidates: Search Candidates</h2></td>
                </tr>
            </table>

            <p class="note">Search Candidates</p>

            <table class="searchTable" id="searchTable ">
                <tr>
                    <td>
                        <form name="searchForm" id="searchForm" action="<?php echo(CATSUtility::getIndexName()); ?>" method="get" autocomplete="off">
                            <input type="hidden" name="m" id="moduleName" value="candidates" />
                            <input type="hidden" name="a" id="moduleAction" value="search" />
                            <input type="hidden" name="getback" id="getback" value="getback" />

                            <?php TemplateUtility::printSavedSearch($this->savedSearchRS); ?>

                            <label id="searchModeLabel" for="searchMode">Search By:</label>&nbsp;
                            <select id="searchMode" name="mode" onclick="advancedSearchConsider();" class="selectBox">
                                <option value="searchByFullName"<?php if ($this->mode == "searchByFullName"): ?> selected<?php endif; ?>>Candidate Name</option>
                                <option value="searchByResume"<?php if ($this->mode == "searchByResume" || empty($this->mode)): ?> selected<?php endif; ?>>Resume Keywords</option>
                                <option value="searchByKeySkills"<?php if ($this->mode == "searchByKeySkills"): ?> selected<?php endif; ?>>Key Skills</option>
                                <option value="searchByCity"<?php if ($this->mode == "searchByCity"): ?> selected<?php endif; ?>>City</option>
                                <option value="phoneNumber"<?php if ($this->mode == "phoneNumber"): ?> selected<?php endif; ?>>Phone Number</option>
                            </select>&nbsp;
                            <input type="text" class="inputbox" id="searchText" name="wildCardString" value="<?php if (!empty($this->wildCardString)) $this->_($this->wildCardString); ?>" style="width:250px" />&nbsp;*&nbsp;
                            <input type="submit" class="button" id="searchCandidates" name="searchCandidates" value="Search" />
                            <?php TemplateUtility::printAdvancedSearch('searchByKeySkills,searchByResume'); ?>
                        </form>
                    </td>
                </tr>
            </table>

            <script type="text/javascript">
                document.searchForm.wildCardString.focus();
            </script>

            <?php if ($this->isResumeMode && $this->isResultsMode): ?>
                <br />
                <?php if (!empty($this->rs)): ?>
                    <p class="note">Search Results &nbsp;<?php $this->_($this->pageStart); ?> to <?php $this->_($this->pageEnd); ?> of <?php $this->_($this->totalResults); ?></p>
                    <?php echo($this->exportForm['header']); ?>
                <?php else: ?>
                    <p class="note">Search Results</p>
                <?php endif; ?>

                <table class="sortable">
                    <thead>
                        <tr>
                            <th nowrap>&nbsp;</th>
                            <th align="left" nowrap="nowrap">
                                <?php $this->pager->printSortLink('firstName', 'First Name'); ?>
                            </th>
                            <th align="left" nowrap="nowrap">
                                <?php $this->pager->printSortLink('lastName', 'Last Name'); ?>
                            </th>
                            <th align="left" nowrap="nowrap">Resume</th>
                            <th align="left" nowrap="nowrap">
                                <?php $this->pager->printSortLink('city', 'City'); ?>
                            </th>
                            <th align="left" nowrap="nowrap">
                                <?php $this->pager->printSortLink('state', 'State'); ?>
                            </th>
                            <th align="left" nowrap="nowrap">
                                <?php $this->pager->printSortLink('dateCreatedSort', 'Created'); ?>
                            </th>
                            <th align="left" nowrap="nowrap">
                                <?php $this->pager->printSortLink('dateModifiedSort', 'Modified'); ?>
                            </th>
                            <th align="left" nowrap="nowrap">
                                <?php $this->pager->printSortLink('ownerSort', 'Owner'); ?>
                            </th>
                        </tr>
                    </thead>

                    <?php if (!empty($this->rs)): ?>
                        <?php foreach ($this->rs as $rowNumber => $data): ?>
                            <tr class="<?php TemplateUtility::printAlternatingRowClass($rowNumber); ?>">
                                <?php if ($data['candidateID'] > 0): ?>
                                    <td valign="top" nowrap>
                                        <input type="checkbox" id="checked_<?php echo($data['candidateID']); echo($data['attachmentID']); ?>" name="checked_<?php echo($data['candidateID']); ?>" />
                                        <a href="javascript:void(0);" onClick="window.open('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;candidateID=<?php $this->_($data['candidateID']); ?>')" title="View in New Window">
                                            <img src="images/new_window.gif" class="abstop" alt="(Preview)" border="0" width="15" height="15" />
                                        </a>
                                    </td>
                                    <td valign="top">
                                        <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;candidateID=<?php $this->_($data['candidateID']); ?>">
                                            <?php $this->_($data['firstName']); ?>
                                        </a>
                                    </td>
                                    <td valign="top">
                                        <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;candidateID=<?php $this->_($data['candidateID']); ?>">
                                            <?php $this->_($data['lastName']); ?>
                                        </a>
                                    </td>
                                <?php else: ?>
                                    <td>&nbsp;</td>
                                    <td valign="top" nowrap="nowrap">
                                    </td>
                                    <td valign="top" colspan="2">
                                        <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=add&amp;attachmentID=<?php $this->_($data['attachmentID']); ?>">
                                            <img src="images/candidate_tiny.gif" width="16" height="16" border="0" class="absmiddle" alt="" title="Create Candidate Profile" />
                                        </a>
                                        &nbsp;Bulk Resume
                                    </td>
                                <?php endif; ?>
                                <td valign="top">
                                    <a href="#" onclick="window.open('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=viewResume&amp;wildCardString=<?php $this->_(urlencode($this->wildCardString)); ?>&amp;attachmentID=<?php $this->_($data['attachmentID']); ?>', 'viewResume', 'scrollbars=1,width=700,height=600')">
                                        <img src="images/resume_preview_inline.gif" class="abstop" alt="(Preview)" border="0" width="15" height="15" />
                                    </a>&nbsp;
                                    <?php echo($data['excerpt']); ?>
                                </td>
                                <td valign="top"><?php $this->_($data['city']); ?></td>
                                <td valign="top"><?php $this->_($data['state']); ?></td>
                                <td valign="top"><?php $this->_($data['dateCreated']); ?></td>
                                <td valign="top"><?php $this->_($data['dateModified']); ?></td>
                                <td valign="top" nowrap="nowrap"><?php $this->_($data['ownerAbbrName']); ?>&nbsp;</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No matching entries found.</td>
                        </tr>
                    <?php endif; ?>
                </table>
                <?php echo($this->exportForm['footer']); ?>
                <?php echo($this->exportForm['menu']); ?>
                <?php if (!empty($this->rs)): ?>
                    <div style="float: right"><?php $this->pager->printNavigation(); ?></div>
                    <br />
                <?php endif; ?>
            <?php elseif ($this->isResultsMode): ?>
                <br />
                <p class="note">Search Results (<?php echo(count($this->rs)); ?>)</p>

                <?php if (!empty($this->rs)): ?>
                    <?php echo($this->exportForm['header']); ?>
                    <table class="sortable" width="100%" onmouseover="javascript:trackTableHighlight(event)">
                        <tr>
                            <th nowrap>&nbsp;</th>
                            <th align="left" nowrap="nowrap">
                                <?php $this->pager->printSortLink('firstName', 'First Name'); ?>
                            </th>
                            <th align="left" nowrap="nowrap">
                                <?php $this->pager->printSortLink('lastName', 'Last Name'); ?>
                            </th>
                            <th align="left" nowrap="nowrap">Key Skills</th>
                            <th align="left" nowrap="nowrap">
                                <?php $this->pager->printSortLink('city', 'City'); ?>
                            </th>
                            <th align="left" nowrap="nowrap">
                                <?php $this->pager->printSortLink('state', 'State'); ?>
                            </th>
                            <th align="left" nowrap="nowrap">
                                <?php $this->pager->printSortLink('dateCreated', 'Created'); ?>
                            </th>
                            <th align="left" nowrap="nowrap">
                                <?php $this->pager->printSortLink('dateModified', 'Modified'); ?>
                            </th>
                            <th align="left" nowrap="nowrap">
                                <?php $this->pager->printSortLink('owner_user.last_name', 'Owner'); ?>
                            </th>
                        </tr>

                        <?php foreach ($this->rs as $rowNumber => $data): ?>
                            <tr class="<?php TemplateUtility::printAlternatingRowClass($rowNumber); ?>">
                                <td nowrap>
                                    <input type="checkbox" id="checked_<?php echo($data['candidateID']); ?>" name="checked_<?php echo($data['candidateID']); ?>" />
                                    <a href="javascript:void(0);" onClick="window.open('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;candidateID=<?php $this->_($data['candidateID']); ?>')" title="View in New Window">
                                        <img src="images/new_window.gif" class="abstop" alt="(Preview)" border="0" width="15" height="15" />
                                    </a>&nbsp;
                                </td>
                                <td>
                                    <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;candidateID=<?php $this->_($data['candidateID']); ?>">
                                        <?php $this->_($data['firstName']); ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;candidateID=<?php $this->_($data['candidateID']); ?>">
                                        <?php $this->_($data['lastName']); ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if (isset($data['resumeID'])): ?>
                                        <a href="javascript:void(0);" onclick="window.open('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=viewResume&amp;wildCardString=<?php $this->_(urlencode($this->wildCardString)); ?>&amp;attachmentID=<?php $this->_($data['resumeID']); ?>', 'viewResume', 'scrollbars=1,width=700,height=600')" Title="View resume">
                                            <img src="images/resume_preview_inline.gif" class="abstop" alt="(Preview)" border="0" width="15" height="15" />
                                        </a>
                                    <?php endif; ?>
                                    <?php $this->_($data['keySkills']); ?>&nbsp;
                                </td>
                                <td><?php $this->_($data['city']); ?>&nbsp;</td>
                                <td><?php $this->_($data['state']); ?>&nbsp;</td>
                                <td><?php $this->_($data['dateCreated']); ?>&nbsp;</td>
                                <td><?php $this->_($data['dateModified']); ?>&nbsp;</td>
                                <td nowrap="nowrap"><?php $this->_($data['ownerAbbrName']); ?>&nbsp;</td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php echo($this->exportForm['footer']); ?>
                    <?php echo($this->exportForm['menu']); ?>
                    
                    <?php if ($this->getUserAccessLevel('candidates.delete') >= ACCESS_LEVEL_DELETE): ?>
                    <div style="margin-top: 16px; padding: 12px 16px; background: #f8fafc; border-radius: 8px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                        <input type="checkbox" id="selectAllSearch" onclick="toggleAllSearchCheckboxes(this.checked);" />
                        <label for="selectAllSearch" style="margin: 0; font-size: 13px; color: #374151;">Select All</label>
                        <button type="button" onclick="confirmBulkDeleteSearch();" style="padding: 8px 16px; background: #dc2626; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18"/>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                            </svg>
                            Delete Selected
                        </button>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p>No matching entries found.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

<!-- Bulk Delete Confirmation Modal for Search Results -->
<div id="bulkDeleteModalSearch" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; max-width: 420px; width: 90%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
        <div style="text-align: center; margin-bottom: 16px;">
            <div style="width: 56px; height: 56px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
                    <path d="M3 6h18"/>
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                    <line x1="10" x2="10" y1="11" y2="17"/>
                    <line x1="14" x2="14" y1="11" y2="17"/>
                </svg>
            </div>
            <h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 600; color: #111827;">Delete Selected Candidates?</h3>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">
                You are about to delete <strong id="bulkDeleteCountSearch" style="color: #dc2626;">0</strong> candidate(s). 
                This action cannot be undone and will remove all associated data including resumes, activities, and pipeline entries.
            </p>
        </div>
        <div style="display: flex; gap: 12px; justify-content: center;">
            <button onclick="closeBulkDeleteModalSearch();" style="padding: 10px 20px; border: 1px solid #d1d5db; background: white; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; color: #374151;">
                Cancel
            </button>
            <button id="confirmBulkDeleteBtnSearch" style="padding: 10px 20px; border: none; background: #dc2626; color: white; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer;">
                Delete Candidates
            </button>
        </div>
    </div>
</div>

<!-- Hidden form for bulk delete submission -->
<form id="bulkDeleteFormSearch" method="post" action="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&a=bulkDelete" style="display: none;">
    <input type="hidden" name="candidateIDs" id="bulkDeleteCandidateIDsSearch" value="" />
</form>

<script type="text/javascript">
function toggleAllSearchCheckboxes(checked) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"][name^="checked_"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = checked;
    });
}

function confirmBulkDeleteSearch() {
    var checkboxes = document.querySelectorAll('input[type="checkbox"][name^="checked_"]:checked');
    var candidateIDs = [];
    
    checkboxes.forEach(function(checkbox) {
        var name = checkbox.name;
        var id = name.replace('checked_', '');
        if (id && !isNaN(id)) {
            candidateIDs.push(id);
        }
    });
    
    if (candidateIDs.length === 0) {
        alert('Please select at least one candidate to delete.');
        return;
    }
    
    document.getElementById('bulkDeleteCountSearch').textContent = candidateIDs.length;
    document.getElementById('bulkDeleteCandidateIDsSearch').value = candidateIDs.join(',');
    
    var modal = document.getElementById('bulkDeleteModalSearch');
    modal.style.display = 'flex';
    
    document.getElementById('confirmBulkDeleteBtnSearch').onclick = function() {
        document.getElementById('bulkDeleteFormSearch').submit();
    };
}

function closeBulkDeleteModalSearch() {
    document.getElementById('bulkDeleteModalSearch').style.display = 'none';
}

document.getElementById('bulkDeleteModalSearch').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBulkDeleteModalSearch();
    }
});
</script>

<?php TemplateUtility::printFooter(); ?>
