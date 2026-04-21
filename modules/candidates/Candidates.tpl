<?php /* $Id: Candidates.tpl 3445 2007-11-06 23:17:04Z will $ */ ?>
<?php TemplateUtility::printHeader('Candidates', array( 'js/highlightrows.js', 'js/export.js', 'js/dataGrid.js', 'js/dataGridFilters.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
<?php $md5InstanceName = md5($this->dataGrid->getInstanceName());?>
    <style type="text/css">
    div.addCandidateButton { background: #4172E3 url(images/nodata/candidatesButton.jpg); cursor: pointer; width: 337px; height: 67px; }
    div.addCandidateButton:hover { background: #4172E3 url(images/nodata/candidateButton-o.jpg); cursor: pointer; width: 337px; height: 67px; }
    div.addMassImportButton { background: #4172E3 url(images/nodata/addMassImport.jpg); cursor: pointer; width: 337px; height: 67px; }
    div.addMassImportButton:hover { background: #4172E3 url(images/nodata/addMassImport-o.jpg); cursor: pointer; width: 337px; height: 67px; }
    </style>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents"<?php echo !$this->totalCandidates ? ' style="background-color: #E6EEFF; padding: 0px;"' : ''; ?>>
            <?php if ($this->totalCandidates): ?>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <img src="images/candidate.gif" width="24" height="24" alt="Candidates" style="border: none; flex-shrink: 0;" />
                    <h2 style="margin: 0;">Candidates: Home</h2>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                        <form name="candidatesViewSelectorForm" id="candidatesViewSelectorForm" action="<?php echo(CATSUtility::getIndexName()); ?>" method="get">
                            <input type="hidden" name="m" value="candidates" />
                            <input type="hidden" name="a" value="listByView" />

                            <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                                    <div style="display: flex; align-items: center;">
                                        <?php $this->dataGrid->printNavigation(false); ?>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <input type="checkbox" name="onlyMyCandidates" id="onlyMyCandidates" <?php if ($this->dataGrid->getFilterValue('OwnerID') ==  $this->userID): ?>checked<?php endif; ?> onclick="<?php echo $this->dataGrid->getJSAddRemoveFilterFromCheckbox('OwnerID', '==',  $this->userID); ?>" />
                                        <label for="onlyMyCandidates" style="margin: 0; font-size: 13px;">Only My Candidates</label>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <input type="checkbox" name="onlyHotCandidates" id="onlyHotCandidates" <?php if ($this->dataGrid->getFilterValue('IsHot') == '1'): ?>checked<?php endif; ?> onclick="<?php echo $this->dataGrid->getJSAddRemoveFilterFromCheckbox('IsHot', '==', '\'1\''); ?>" />
                                        <label for="onlyHotCandidates" style="margin: 0; font-size: 13px;">Only Hot Candidates</label>
                                    </div>
                                    <div style="display: flex; align-items: center;">
	                					<a href="javascript:void(0);" id="exportBoxLink<?= $md5InstanceName ?>" onclick="toggleHideShowControls('<?= $md5InstanceName ?>-tags'); return false;">Filter by tag</a>
	                					<div id="tagsContainer" style="position:relative">
	                					<div class="ajaxSearchResults" id="ColumnBox<?= $md5InstanceName ?>-tags" align="left"  style="position:absolute;width:200px;right:0<?= isset($this->globalStyle)?$this->globalStyle:"" ?>">
	                						<table width="100%"><tr><td style="font-weight:bold; color:#000000;">Tag list</td>
	                						<td align="right">
	                							<input type="button" onclick="applyTagFilter()" value="Save&amp;Close" />
	                							<input type="button" onclick="document.getElementById('ColumnBox<?= $md5InstanceName?>').style.display='none';" value="Close" />
	                						</td>
	                						</tr></table>


	                                        <ul>
	                                        <script type="text/javascript">
	                                        function applyTagFilter(){
	                                        	var arrValues=[];
	                                        	var tags=document.getElementsByName('candidate_tags[]');
	                                        	for(var el in tags){
	                                        		if (tags[el].checked) arrValues.push(tags[el].value);
	                                        	};

	                                        	<?php echo $this->dataGrid->getJSAddFilter('Tags', '=#',  "arrValues.join('/')")?>;
	                                        }
	                                        </script>
											<?php $i=1;

											function drw($data, $id){
												global $i;
												foreach($data as $k => $v){
													if ($v['tag_parent_id'] == $id){
														?><li><input type="checkbox" name="candidate_tags[]" id="checkbox<?= $i ?>" value="<?= $v['tag_id'] ?>"><label for="checkbox<?= $i++ ?>"><?= $v['tag_title'] ?></label></li><?php
														echo "\n<ul>";
														drw($data, $v['tag_id']);
														echo "\n</ul>";
													}
												}
											}
											drw($this->tagsRS, '');
											?></ul>
	                					</div>
	                					</div>
										<span style="display:none;" id="ajaxTableIndicator<?= $md5InstanceName ?>"><img src="images/indicator_small.gif" alt="" /></span>
                                    </div>
                            </div>
                        </form>
                </div>
            </div>

            <?php if ($this->topLog != ''): ?>
            <div style="margin: 20px 0px 20px 0px;">
                <?php echo $this->topLog; ?>
            </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['bulkDeleteMessage']) && !empty($_SESSION['bulkDeleteMessage'])): ?>
            <div id="successMessage" style="padding: 16px 20px; border-left: 4px solid #16a34a; background-color: #f0fdf4; margin-bottom: 16px; border-radius: 0 6px 6px 0; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" style="flex-shrink: 0;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <div style="font-size: 14px; font-weight: 500; color: #166534;"><?php echo htmlspecialchars($_SESSION['bulkDeleteMessage']); ?></div>
                </div>
                <button onclick="this.parentElement.style.display='none';" style="background: none; border: none; cursor: pointer; padding: 4px; color: #16a34a;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <?php unset($_SESSION['bulkDeleteMessage']); ?>
            <?php endif; ?>

            <?php if ($this->errMessage != ''): ?>
            <div id="errorMessage" style="padding: 16px 20px; border-left: 4px solid #dc2626; background-color: #fef2f2; margin-bottom: 16px; border-radius: 0 6px 6px 0; display: flex; align-items: flex-start; gap: 12px;">
                <img src="images/large_error.gif" style="flex-shrink: 0; margin-top: 2px;" alt="Error">
                <div>
                    <div style="font-size: 14px; font-weight: 600; color: #dc2626; margin-bottom: 4px;">There was a problem with your request:</div>
                    <div style="font-size: 13px; color: #991b1b;"><?php echo $this->errMessage; ?></div>
                </div>
            </div>
            <?php endif; ?>

            <div class="note" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <span>Candidates - Page <?php echo($this->dataGrid->getCurrentPageHTML()); ?> (<?php echo($this->dataGrid->getNumberOfRows()); ?> Items)</span>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <?php $this->dataGrid->drawRowsPerPageSelector(); ?>
                    <?php $this->dataGrid->drawShowFilterControl(); ?>
                </div>
            </div>

            <?php $this->dataGrid->drawFilterArea(); ?>
            <?php $this->dataGrid->draw();  ?>

            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-top: 16px;">
                <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <?php $this->dataGrid->printActionArea(); ?>
                    
                    <?php if ($this->getUserAccessLevel('candidates.delete') >= ACCESS_LEVEL_DELETE): ?>
                    <button type="button" id="bulkDeleteBtn" onclick="confirmBulkDelete<?php echo $md5InstanceName; ?>();" style="padding: 8px 16px; background: #dc2626; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                        </svg>
                        Delete Selected
                    </button>
                    <?php endif; ?>
                </div>
                <div>
                    <?php $this->dataGrid->printNavigation(true); ?>
                </div>
            </div>

            <?php else: ?>

            <br /><br /><br /><br />
            <div style="height: 95px; background: #E6EEFF url(images/nodata/candidatesTop.jpg);">
                &nbsp;
            </div>
            <br /><br />
                <?php if ($this->getUserAccessLevel('candidates.add') >= ACCESS_LEVEL_EDIT): ?>
            <table cellpadding="0" cellspacing="0" border="0" width="956">
                <tr>
                <td style="padding-left: 62px;" align="center" valign="center">

                    <div style="text-align: center; width: 600px; line-height: 22px; font-size: 18px; font-weight: bold; color: #666666; padding-bottom: 20px;">
                    Add candidates to keep track of possible applicants you can consider for your job orders.
                    </div>

                    <table cellpadding="10" cellspacing="0" border="0">
                        <tr>
                            <td style="padding-right: 20px;">
                                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=add">
                                <div class="addCandidateButton">&nbsp;</div>
                                </a>
                            </td>
                            <td>
                                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=import&amp;a=bulkImport">
                                <div class="addMassImportButton">&nbsp;</div>
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>

                </tr>
            </table>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>

<!-- Bulk Delete Confirmation Modal -->
<div id="bulkDeleteModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
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
                You are about to delete <strong id="bulkDeleteCount" style="color: #dc2626;">0</strong> candidate(s). 
                This action cannot be undone and will remove all associated data including resumes, activities, and pipeline entries.
            </p>
        </div>
        <div style="display: flex; gap: 12px; justify-content: center;">
            <button onclick="closeBulkDeleteModal();" style="padding: 10px 20px; border: 1px solid #d1d5db; background: white; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; color: #374151;">
                Cancel
            </button>
            <button id="confirmBulkDeleteBtn" style="padding: 10px 20px; border: none; background: #dc2626; color: white; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer;">
                Delete Candidates
            </button>
        </div>
    </div>
</div>

<!-- Hidden form for bulk delete submission -->
<form id="bulkDeleteForm" method="post" action="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&a=bulkDelete" style="display: none;">
    <input type="hidden" name="candidateIDs" id="bulkDeleteCandidateIDs" value="" />
</form>

<script type="text/javascript">
function confirmBulkDelete<?php echo $md5InstanceName; ?>() {
    // Get all checked checkboxes
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
    
    // Update modal with count
    document.getElementById('bulkDeleteCount').textContent = candidateIDs.length;
    document.getElementById('bulkDeleteCandidateIDs').value = candidateIDs.join(',');
    
    // Show modal
    var modal = document.getElementById('bulkDeleteModal');
    modal.style.display = 'flex';
    
    // Set up confirm button
    document.getElementById('confirmBulkDeleteBtn').onclick = function() {
        document.getElementById('bulkDeleteForm').submit();
    };
}

function closeBulkDeleteModal() {
    document.getElementById('bulkDeleteModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('bulkDeleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBulkDeleteModal();
    }
});
</script>

<?php TemplateUtility::printFooter(); ?>
