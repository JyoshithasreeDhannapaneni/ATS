<?php /* $Id: CreateAttachmentModal.tpl 3093 2007-09-24 21:09:45Z brian $ */ ?>
<?php TemplateUtility::printModalHeader('Companies', array('modules/companies/validator.js'), 'Create Company Attachment'); ?>

    <?php if (!$this->isFinishedMode): ?>
        <form name="createAttachmentForm" id="createAttachmentForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=companies&amp;a=createAttachment" enctype="multipart/form-data" method="post" onsubmit="return checkAttachmentForm(document.createAttachmentForm);">
            <input type="hidden" name="postback" id="postback" value="postback" />
            <input type="hidden" id="companyID" name="companyID" value="<?php echo($this->companyID); ?>" />

            <div class="form-container">
                <div class="form-section">
                    <div class="form-row">
                        <div class="form-label">Attachment</div>
                        <div class="form-input"><input type="file" id="file" name="file" /></div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="form-btn form-btn-primary" name="submit" id="submit">Create Attachment</button>
                    <button type="button" class="form-btn form-btn-secondary" name="cancel" onclick="parentHidePopWin();">Cancel</button>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div class="form-container">
            <div class="form-section">
                <p>The file has been successfully attached.</p>
            </div>
            <div class="form-actions">
                <button type="button" class="form-btn form-btn-primary" name="close" onclick="parentHidePopWinRefresh();">Close</button>
            </div>
        </div>
    <?php endif; ?>
    </body>
</html>
