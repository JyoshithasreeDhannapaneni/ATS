<?php /* $Id: AddModalPopup.tpl 3321 2007-10-25 22:03:10Z brian $ */ ?>
<?php TemplateUtility::printModalHeader('Job Order', array('modules/joborders/validator.js')); ?>

<script type="text/javascript">
    var typeOfAdd = "new";
</script>

<div class="form-container">
    <div class="form-header">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
        <h2>Job Orders: Add Job Order</h2>
    </div>

    <div class="form-section">
        <div class="option-card-group">
            <label class="option-card">
                <input type="radio" name="typeOfAddElement" checked="checked"
                    onclick="document.getElementById('copyFrom').disabled=true; typeOfAdd='new';" />
                <div class="option-card-body">
                    <span class="option-card-title">Empty Job Order</span>
                    <span class="option-card-description">Start from a blank job order.</span>
                </div>
            </label>
            <label class="option-card">
                <input type="radio" name="typeOfAddElement"
                    onclick="document.getElementById('copyFrom').disabled=false; typeOfAdd='existing';" />
                <div class="option-card-body">
                    <span class="option-card-title">Copy Existing Job Order</span>
                    <span class="option-card-description">Duplicate an existing job order's fields as a starting point.</span>
                    <div class="option-card-extra">
                        <select name="copyFrom" id="copyFrom" style="width: 100%; max-width: 350px;" disabled="disabled">
                            <?php foreach($this->rs as $index => $data): ?>
                                <option value="<?php echo($data['jobOrderID']); ?>"><?php $this->_($data['title'].' ('.$data['companyName'].')'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </label>
        </div>
    </div>

    <div class="form-actions">
        <button type="button" class="form-btn form-btn-primary" onclick="parentGoToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=add&amp;jobOrderID='+document.getElementById('copyFrom').value+'&amp;typeOfAdd='+typeOfAdd);">Create Job Order</button>
        <button type="button" class="form-btn form-btn-secondary" onclick="parentHidePopWin();">Close</button>
    </div>
</div>
    </body>
</html>
