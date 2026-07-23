<?php /* Manage configurable interview/pipeline round templates. */ ?>
<?php TemplateUtility::printHeader('Settings'); ?>
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
                    <td><h2>Settings: Administration</h2></td>
                </tr>
            </table>

            <p class="note">Interview Process Templates — define a custom, ordered list of interview rounds that can be assigned to a job order (optionally defaulted to a department) instead of the default fixed pipeline.</p>

            <table class="searchTable" width="100%">
                <tr>
                    <td>
                        <h3>Existing Templates</h3>
                        <?php if (empty($this->pipelineTemplates)): ?>
                            <p><em>No templates created yet.</em></p>
                        <?php else: ?>
                            <table border="1" cellpadding="6" cellspacing="0" width="100%">
                                <tr>
                                    <th align="left">Name</th>
                                    <th align="left">Default For Department</th>
                                    <th align="left">Stages (in order)</th>
                                    <th align="left">&nbsp;</th>
                                </tr>
                                <?php foreach ($this->pipelineTemplates as $template): ?>
                                    <tr>
                                        <td><?php $this->_($template['name']); ?></td>
                                        <td><?php $this->_($template['departmentName'] !== null ? $template['departmentName'] : '—'); ?></td>
                                        <td><?php $this->_(implode(' → ', $template['stageNames'])); ?></td>
                                        <td>
                                            <form action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=administration" method="post" style="display:inline;" onsubmit="return confirm('Delete this template? Job orders using it will fall back to the default pipeline.');">
                                                <input type="hidden" name="postback" value="postback" />
                                                <input type="hidden" name="administrationMode" value="deletePipelineTemplate" />
                                                <input type="hidden" name="templateID" value="<?php $this->_($template['templateID']); ?>" />
                                                <input type="submit" class="button" value="Delete" />
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php endif; ?>

                        <br />
                        <h3>Create New Template</h3>
                        <form action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=administration" method="post" autocomplete="off">
                            <input type="hidden" name="postback" value="postback" />
                            <input type="hidden" name="administrationMode" value="createPipelineTemplate" />

                            <label for="templateName">Template Name:</label><br />
                            <input type="text" name="templateName" id="templateName" style="width:300px;" placeholder="e.g. Senior Engineering Hiring" /><br /><br />

                            <label for="departmentID">Default for Department (optional):</label><br />
                            <select name="departmentID" id="departmentID" style="width:300px;">
                                <option value="">None</option>
                                <?php foreach ($this->departmentOptions as $dept): ?>
                                    <option value="<?php $this->_($dept['departmentID']); ?>"><?php $this->_($dept['companyName']); ?> - <?php $this->_($dept['departmentName']); ?></option>
                                <?php endforeach; ?>
                            </select><br /><br />

                            <label for="stagesText">Interview Rounds (one per line, in order):</label><br />
                            <textarea name="stagesText" id="stagesText" rows="6" style="width:300px;" placeholder="Screening&#10;Technical Round&#10;Panel Interview&#10;Offer"></textarea><br /><br />

                            <input type="submit" name="save" class="button" value="Create Template" />
                            <input type="button" name="back" class="button" value="Back" onclick="document.location.href='<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=administration';" />
                        </form>
                    </td>
                </tr>
            </table>

        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
