<?php /* $Id: Companies.tpl 3460 2007-11-07 03:50:34Z brian $ */ ?>
<?php TemplateUtility::printHeader('Companies', array('js/highlightrows.js', 'js/export.js', 'js/dataGrid.js', 'js/dataGridFilters.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
    <style type="text/css">
    div.addCompaniesButton { background: #4172E3 url(images/nodata/companiesButton.jpg); cursor: pointer; width: 337px; height: 67px; }
    div.addCompaniesButton:hover { background: #4172E3 url(images/nodata/companiesButton-o.jpg); cursor: pointer; width: 337px; height: 67px; }
    </style>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 16px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <img src="images/companies.gif" width="24" height="24" border="0" alt="Companies" />
                    <h2 style="margin: 0;">Companies: Home</h2>
                </div>
                <form name="companiesViewSelectorForm" id="companiesViewSelectorForm" action="<?php echo(CATSUtility::getIndexName()); ?>" method="get" style="margin: 0;">
                    <input type="hidden" name="m" value="companies" />
                    <input type="hidden" name="a" value="listByView" />
                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 16px;">
                        <div>
                            <?php $this->dataGrid->printNavigation(false); ?>
                        </div>
                        <div style="display: flex; align-items: center; gap: 4px;">
                            <input type="checkbox" name="onlyMyCompanies" id="onlyMyCompanies" <?php if ($this->dataGrid->getFilterValue('OwnerID') ==  $this->userID): ?>checked<?php endif; ?> onclick="<?php echo $this->dataGrid->getJSAddRemoveFilterFromCheckbox('OwnerID', '==',  $this->userID); ?>" />
                            <label for="onlyMyCompanies">Only My Companies</label>
                        </div>
                        <div style="display: flex; align-items: center; gap: 4px;">
                            <input type="checkbox" name="onlyHotCompanies" id="onlyHotCompanies" <?php if ($this->dataGrid->getFilterValue('IsHot') == '1'): ?>checked<?php endif; ?> onclick="<?php echo $this->dataGrid->getJSAddRemoveFilterFromCheckbox('IsHot', '==', '\'1\''); ?>" />
                            <label for="onlyHotCompanies">Only Hot Companies</label>
                        </div>
                    </div>
                </form>
            </div>

            <?php if ($this->errMessage != ''): ?>
            <div id="errorMessage" style="padding: 25px 0px 25px 0px; border-top: 1px solid #800000; border-bottom: 1px solid #800000; background-color: #f7f7f7;margin-bottom: 15px;">
            <table>
                <tr>
                    <td align="left" valign="center" style="padding-right: 5px;">
                        <img src="images/large_error.gif" align="left">
                    </td>
                    <td align="left" valign="center">
                        <span style="font-size: 12pt; font-weight: bold; color: #800000; line-height: 12pt;">There was a problem with your request:</span>
                        <div style="font-size: 10pt; font-weight: bold; padding: 3px 0px 0px 0px;"><?php echo $this->errMessage; ?></div>
                    </td>
                </tr>
            </table>
            </div>
            <?php endif; ?>

            <p class="note" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px;">
                <span>Companies  -
                    Page <?php echo($this->dataGrid->getCurrentPageHTML()); ?>
                    (<?php echo($this->dataGrid->getNumberOfRows()); ?> Items)
                    <?php if ($this->dataGrid->getFilterValue('OwnerID') ==  $this->userID): ?>(Only My Companies)<?php endif; ?>
                    <?php if ($this->dataGrid->getFilterValue('IsHot') == '1'): ?>(Only Hot Companies)<?php endif; ?>
                </span>
                <span style="display: flex; align-items: center; gap: 12px;">
                    <?php $this->dataGrid->drawRowsPerPageSelector(); ?>
                    <?php $this->dataGrid->drawShowFilterControl(); ?>
                </span>
            </p>

            <?php $this->dataGrid->drawFilterArea(); ?>
            <div style="width: 100%; overflow-x: auto;">
                <?php $this->dataGrid->draw();  ?>
            </div>

            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px; margin-top: 12px;">
                <div>
                    <?php $this->dataGrid->printActionArea(); ?>
                </div>
                <div>
                    <?php $this->dataGrid->printNavigation(true); ?>
                </div>
            </div>
        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
