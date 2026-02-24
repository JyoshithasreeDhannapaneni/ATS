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
                <div>
                    <?php $this->dataGrid->printActionArea(); ?>
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
                                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=import&amp;a=massImport">
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
<?php TemplateUtility::printFooter(); ?>
