<?php /* $Id: CreateImageAttachmentModal.tpl 2026 2007-02-27 22:34:05Z brian $ */ ?>
<?php TemplateUtility::printModalHeader('Candidates', array('modules/candidates/validator.js')); ?>

<div class="form-container">
    <div class="form-header">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
        <h2>Edit Profile Image</h2>
    </div>

    <?php if (!$this->isFinishedMode): ?>
        <form name="createAttachmentForm" id="createAttachmentForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addEditImage" enctype="multipart/form-data" method="post" onsubmit="">
            <input type="hidden" name="postback" id="postback" value="postback" />
            <input type="hidden" id="candidateID" name="candidateID" value="<?php echo($this->candidateID); ?>" />
            <?php foreach ($this->attachmentsRS as $rowNumber => $attachmentsData): ?>
                 <?php if ($attachmentsData['isProfileImage'] == '1'): ?>
                    <div class="form-section" style="text-align:center;">
                        <a href="<?php echo htmlspecialchars($attachmentsData['retrievalURL'], ENT_QUOTES, HTML_ENCODING, false); ?>">
                            <img src="<?php echo htmlspecialchars($attachmentsData['retrievalURL'], ENT_QUOTES, HTML_ENCODING, false); ?>" border="0" width="165">
                        </a>
                    </div>
                 <?php endif; ?>
            <?php endforeach; ?>
            <div class="form-section">
                <div class="form-row">
                    <div class="form-label">New Profile Picture</div>
                    <div class="form-input"><input type="file" id="file" name="file" /></div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="form-btn form-btn-primary" name="submit" id="submit">Set Image</button>
                <button type="button" class="form-btn form-btn-secondary" name="close" onclick="parentHidePopWin();">Close</button>
            </div>
        </form>
    <?php else: ?>
        <div class="form-section">
            <p>The picture has been saved.</p>
        </div>
        <script type="text/javascript">
            parentHidePopWin();
        </script>
    <?php endif; ?>
</div>
    </body>
</html>
