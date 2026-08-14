<?php /* $Id: CreateAttachmentModal.tpl 3093 2007-09-24 21:09:45Z brian $ */ ?>
<?php TemplateUtility::printModalHeader('Candidates', array('modules/candidates/validator.js'), 'Create Candidate Attachment'); ?>

    <?php if (!$this->isFinishedMode){ ?>
        <form name="createAttachmentForm" id="createAttachmentForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=createAttachment" enctype="multipart/form-data" method="post" onsubmit="return checkCreateAttachmentForm(document.createAttachmentForm);">
            <input type="hidden" name="postback" id="postback" value="postback" />
            <input type="hidden" id="candidateID" name="candidateID" value="<?php echo($this->candidateID); ?>" />

            <div class="form-container">
                <div class="form-section">
                    <div class="form-row">
                        <div class="form-label">Attachment</div>
                        <div class="form-input"><input type="file" id="file" name="file" /></div>
                    </div>
                </div>
                <div class="form-section">
                    <div class="form-section-title">Is this a resume?</div>
                    <div class="option-card-group">
                        <label class="option-card">
                            <input type="radio" id="resumeYes" name="resume" value="1" checked="checked" />
                            <div class="option-card-body"><span class="option-card-title">Yes</span></div>
                        </label>
                        <label class="option-card">
                            <input type="radio" id="resumeNo" name="resume" value="0" />
                            <div class="option-card-body"><span class="option-card-title">No</span></div>
                        </label>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="form-btn form-btn-primary" name="submit" id="submit">Create Attachment</button>
                    <button type="button" class="form-btn form-btn-secondary" name="cancel" onclick="parentHidePopWin();">Cancel</button>
                </div>
            </div>
        </form>
    <?php } else { ?>
        <div class="form-container">
            <div class="form-section">
                <?php if(isset($this->resumeText) && $this->resumeText == ''): ?>
                    <p>The file has been successfully attached, but OpenCATS was unable to index the resume keywords to make the document searchable.  The file format may be unsupported by OpenCATS.</p>
                <?php else: ?>
                    <p>The file has been successfully attached.</p>
                <?php endif; ?>
            </div>
            <div class="form-actions">
                <button type="button" class="form-btn form-btn-primary" name="close" onclick="parentHidePopWinRefresh();">Close</button>
            </div>
        </div>
    <?php } ?>
    </body>
</html>
