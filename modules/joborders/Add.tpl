<?php /* $Id: Add.tpl 3810 2007-12-05 19:13:25Z brian $ */ ?>
<?php TemplateUtility::printHeader('Job Orders', array('modules/joborders/validator.js',  'js/company.js', 'js/sweetTitles.js', 'js/suggest.js', 'js/joborder.js', 'js/lib.js', 'js/listEditor.js', 'vendor/ckeditor/ckeditor/ckeditor.js', 'js/ckeditor-manager.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>
<style>
.jo-form-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    margin: 20px 0;
    overflow: hidden;
}
.jo-form-header {
    background: #f8fafc;
    padding: 16px 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 12px;
}
.jo-form-header svg {
    color: #2563eb;
}
.jo-form-header h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
}
.jo-form-note {
    color: #2563eb;
    font-size: 13px;
    padding: 12px 24px;
    background: #eff6ff;
    border-bottom: 1px solid #dbeafe;
}
.jo-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
}
.jo-form-column {
    padding: 20px 24px;
}
.jo-form-column:first-child {
    border-right: 1px solid #e5e7eb;
}
.jo-form-row {
    display: flex;
    align-items: center;
    margin-bottom: 16px;
}
.jo-form-row:last-child {
    margin-bottom: 0;
}
.jo-form-label {
    width: 120px;
    flex-shrink: 0;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
.jo-form-input {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 6px;
}
.jo-form-input input[type="text"],
.jo-form-input select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
    color: #1f2937;
    background: #fff;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.jo-form-input input[type="text"]:focus,
.jo-form-input select:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}
.jo-form-input .required {
    color: #dc2626;
    font-weight: 600;
}
.jo-form-input input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #2563eb;
}
.jo-form-input .info-icon {
    width: 16px;
    height: 16px;
    color: #2563eb;
    cursor: help;
}
.jo-form-section {
    border-top: 1px solid #e5e7eb;
    padding: 20px 24px;
}
.jo-form-section-title {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    margin-bottom: 12px;
}
.jo-form-section .ckEditor,
.jo-form-section textarea {
    width: 100%;
    min-height: 150px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 12px;
    font-size: 13px;
    line-height: 1.6;
    resize: vertical;
}
.jo-form-section textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}
.jo-form-actions {
    padding: 16px 24px;
    background: #f8fafc;
    border-top: 1px solid #e5e7eb;
    display: flex;
    gap: 10px;
}
.jo-btn {
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}
.jo-btn-primary {
    background: #2563eb;
    color: #fff;
}
.jo-btn-primary:hover {
    background: #1d4ed8;
}
.jo-btn-secondary {
    background: #fff;
    color: #374151;
    border: 1px solid #d1d5db;
}
.jo-btn-secondary:hover {
    background: #f3f4f6;
}
.jo-btn-dark {
    background: #1f2937;
    color: #fff;
}
.jo-btn-dark:hover {
    background: #111827;
}
.ajaxSearchResults {
    position: absolute;
    z-index: 100;
    background: #fff;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    max-height: 200px;
    overflow-y: auto;
}
</style>
<script type="text/javascript">
    window.CATSUserDateFormat = '<?php echo($_SESSION['CATS']->isDateDMY() ? 'DD-MM-YY' : 'MM-DD-YY'); ?>';

    function generateCompanyJobID() {
        var companyID = document.getElementById('companyID').value;
        if (!companyID || companyID == '0') {
            document.getElementById('companyJobID').value = '';
            return;
        }
        var jobIDField = document.getElementById('companyJobID');
        jobIDField.style.opacity = '0.5';
        fetch('<?php echo(CATSUtility::getIndexName()); ?>?f=generateCompanyJobID', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'companyID=' + encodeURIComponent(companyID) + '&siteID=<?php echo($this->_siteID); ?>'
        })
        .then(response => response.json())
        .then(data => {
            jobIDField.style.opacity = '1';
            if (data.success && data.companyJobID) {
                document.getElementById('companyJobID').value = data.companyJobID;
            }
        })
        .catch(error => { jobIDField.style.opacity = '1'; });
    }

    function selectQuestionnaireByType() {
        var jobType = document.getElementById('type').value;
        if (!jobType || jobType === 'N/A') return;
        var questionnaireField = document.getElementById('questionnaire');
        if (!questionnaireField) return;
        questionnaireField.style.opacity = '0.5';
        fetch('<?php echo(CATSUtility::getIndexName()); ?>?f=getQuestionnaireByJobType', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'jobOrderType=' + encodeURIComponent(jobType) + '&siteID=<?php echo($this->_siteID); ?>'
        })
        .then(response => response.json())
        .then(data => {
            questionnaireField.style.opacity = '1';
            if (data.success && data.questionnaireID) {
                questionnaireField.value = data.questionnaireID;
            } else {
                questionnaireField.value = 'none';
            }
        })
        .catch(error => { questionnaireField.style.opacity = '1'; });
    }
</script>
<div id="main">
    <?php TemplateUtility::printQuickSearch(); ?>

    <div id="contents">
        <?php if ($this->noCompanies): ?>
            <div class="jo-form-container">
                <div class="jo-form-header">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    <h2>Job Orders: Add Job Order</h2>
                </div>
                <div style="padding: 40px; text-align: center; color: #6b7280;">
                    <p><strong>You have not added any companies yet.</strong></p>
                    <p>You can't add a job order until you add at least one company.</p>
                    <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=companies" class="jo-btn jo-btn-primary" style="display: inline-block; margin-top: 16px; text-decoration: none;">Go to Companies</a>
                </div>
            </div>
        <?php else: ?>
            <form name="addJobOrderForm" id="addJobOrderForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=add" method="post" onsubmit="return checkAddForm(document.addJobOrderForm);" autocomplete="off">
                <input type="hidden" name="postback" id="postback" value="postback" />
                <input type="hidden" name="companyID" id="companyID" value="<?php if ($this->selectedCompanyID === false) { if (isset($this->jobOrderSourceRS['companyID'])) { echo ($this->jobOrderSourceRS['companyID']); } else { echo(0); } } else { echo($this->selectedCompanyID); } ?>" />

                <div class="jo-form-container">
                    <div class="jo-form-header">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        <h2>Job Orders: Add Job Order</h2>
                    </div>
                    <div class="jo-form-note">Add a new job order to the system.</div>

                    <div class="jo-form-grid">
                        <!-- Left Column -->
                        <div class="jo-form-column">
                            <div class="jo-form-row">
                                <label class="jo-form-label">Title:</label>
                                <div class="jo-form-input">
                                    <input type="text" id="title" name="title" tabindex="1" <?php if(isset($this->jobOrderSourceRS['title'])): ?>value="<?php $this->_($this->jobOrderSourceRS['title']); ?>"<?php endif; ?> />
                                    <span class="required">*</span>
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Company:</label>
                                <div class="jo-form-input" style="position: relative;">
                                    <input type="text" name="companyName" id="companyName" tabindex="2" value="<?php if ($this->selectedCompanyID !== false) { $this->_($this->companyRS['name']); } ?><?php if(isset($this->jobOrderSourceRS['companyName']) && $this->selectedCompanyID == false ): ?><?php $this->_($this->jobOrderSourceRS['companyName']); ?><?php endif; ?>" onFocus="suggestListActivate('getCompanyNames', 'companyName', 'CompanyResults', 'companyID', 'ajaxTextEntryHover', 0, '<?php echo($this->sessionCookie); ?>', 'helpShim');" onchange="generateCompanyJobID();" <?php if ($this->selectedCompanyID !== false) { echo('disabled'); } ?> />
                                    <span class="required">*</span>
                                    <iframe id="helpShim" src="javascript:void(0);" scrolling="no" frameborder="0" style="position:absolute; display:none;"></iframe>
                                    <div id="CompanyResults" class="ajaxSearchResults"></div>
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Department:</label>
                                <div class="jo-form-input">
                                    <select id="departmentSelect" name="department" onchange="if (this.value == 'edit') { listEditor('Departments', 'departmentSelect', 'departmentsCSV', false); this.value = '(none)'; } if (this.value == 'nullline') { this.value = '(none)'; }">
                                        <option value="(none)" selected>None</option>
                                    </select>
                                    <input type="hidden" id="departmentsCSV" name="departmentsCSV" value="<?php if ($this->selectedCompanyID !== false): $this->_($this->selectedDepartmentsString); endif; ?>" />
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Contact:</label>
                                <div class="jo-form-input">
                                    <select id="contactID" name="contactID" tabindex="3">
                                        <option value="-1">None</option>
                                        <?php if ($this->selectedCompanyID !== false): ?>
                                            <?php foreach ($this->selectedCompanyContacts as $rowNumber => $contactsData): ?>
                                                <option value="<?php $this->_($contactsData['contactID']) ?>"><?php $this->_($contactsData['lastName']) ?>, <?php $this->_($contactsData['firstName']) ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <img src="images/indicator2.gif" id="contactsIndicator" alt="" style="visibility: hidden;" height="16" width="16" />
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">City:</label>
                                <div class="jo-form-input">
                                    <input type="text" id="city" name="city" tabindex="4" <?php if ($this->selectedCompanyID !== false): ?>value="<?php $this->_($this->selectedCompanyLocation['city']); ?>"<?php endif; ?> />
                                    <span class="required">*</span>
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">State:</label>
                                <div class="jo-form-input">
                                    <input type="text" id="state" name="state" tabindex="5" <?php if ($this->selectedCompanyID !== false): ?>value="<?php $this->_($this->selectedCompanyLocation['state']); ?>"<?php endif; ?> />
                                    <span class="required">*</span>
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Shift Timing:</label>
                                <div class="jo-form-input" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                                    <select id="shiftTimingSelect" onchange="if(this.value) document.getElementById('shiftTiming').value = this.value;">
                                        <option value="">Select Shift</option>
                                        <option value="1:00 PM to 10:00 PM (IST)">1:00 PM to 10:00 PM (IST)</option>
                                        <option value="4:00 PM to 1:00 AM (IST)">4:00 PM to 1:00 AM (IST)</option>
                                        <option value="9:00 PM to 6:00 AM (IST)">9:00 PM to 6:00 AM (IST)</option>
                                        <option value="6:00 AM to 2:00 PM (IST)">6:00 AM to 2:00 PM (IST)</option>
                                        <option value="6:00 PM to 3:00 AM (IST)">6:00 PM to 3:00 AM (IST)</option>
                                    </select>
                                    <input type="text" id="shiftTiming" name="shiftTiming" placeholder="Or enter custom shift timing" style="width: 100%;" />
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Recruiter:</label>
                                <div class="jo-form-input">
                                    <select id="recruiter" name="recruiter" tabindex="6">
                                        <option value="">(Select a User)</option>
                                        <?php foreach ($this->usersRS as $rowNumber => $usersData): ?>
                                            <option <?php if ($usersData['userID'] == $this->userID): ?>selected<?php endif; ?> value="<?php $this->_($usersData['userID']) ?>"><?php $this->_($usersData['lastName']) ?>, <?php $this->_($usersData['firstName']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="required">*</span>
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Owner:</label>
                                <div class="jo-form-input">
                                    <select id="owner" name="owner" tabindex="7">
                                        <option value="">(Select a User)</option>
                                        <?php foreach ($this->usersRS as $rowNumber => $usersData): ?>
                                            <option <?php if ($usersData['userID'] == $this->userID): ?>selected<?php endif; ?> value="<?php $this->_($usersData['userID']) ?>"><?php $this->_($usersData['lastName']) ?>, <?php $this->_($usersData['firstName']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="required">*</span>
                                </div>
                            </div>

                            <?php for ($i = 0; $i < count($this->extraFieldRS); $i++): ?>
                            <div class="jo-form-row">
                                <label class="jo-form-label"><?php $this->_($this->extraFieldRS[$i]['fieldName']); ?>:</label>
                                <div class="jo-form-input">
                                    <?php echo($this->extraFieldRS[$i]['addHTML']); ?>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>

                        <!-- Right Column -->
                        <div class="jo-form-column">
                            <div class="jo-form-row">
                                <label class="jo-form-label">Start Date:</label>
                                <div class="jo-form-input">
                                    <script type="text/javascript">DateInput('startDate', false, (typeof window.CATSUserDateFormat !== 'undefined' ? window.CATSUserDateFormat : 'MM-DD-YY'), '', 8);</script>
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Duration:</label>
                                <div class="jo-form-input">
                                    <input type="text" id="duration" name="duration" tabindex="11" <?php if(isset($this->jobOrderSourceRS['duration'])): ?>value="<?php $this->_($this->jobOrderSourceRS['duration']); ?>"<?php endif; ?> />
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Maximum Rate:</label>
                                <div class="jo-form-input">
                                    <input type="text" id="maxRate" name="maxRate" tabindex="12" <?php if(isset($this->jobOrderSourceRS['maxRate'])): ?>value="<?php $this->_($this->jobOrderSourceRS['maxRate']); ?>"<?php endif; ?> />
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Type:</label>
                                <div class="jo-form-input">
                                    <select id="type" name="type" tabindex="13" onchange="selectQuestionnaireByType();">
                                        <?php foreach($this->jobTypes as $jobTypeShort => $jobTypeLong): ?>
                                            <option value="<?php echo $jobTypeShort ?>" <?php if(isset($this->jobOrderSourceRS['type']) && $this->jobOrderSourceRS['type'] == $jobTypeShort) echo('selected'); ?>><?php echo $jobTypeShort." (".$jobTypeLong.")";?></option>
                                        <?php endforeach; ?>
                                        <?php if(count($this->jobTypes) < 1): ?>
                                            <option value="N/A" selected>N/A (Not Applicable)</option>
                                        <?php endif; ?>
                                    </select>
                                    <span class="required">*</span>
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Salary:</label>
                                <div class="jo-form-input">
                                    <input type="text" id="salary" name="salary" tabindex="14" <?php if(isset($this->jobOrderSourceRS['salary'])): ?>value="<?php $this->_($this->jobOrderSourceRS['salary']); ?>"<?php endif; ?> />
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Market:</label>
                                <div class="jo-form-input">
                                    <select id="market" name="market">
                                        <option value="">Select Market</option>
                                        <option value="United States">United States</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="Canada">Canada</option>
                                        <option value="Australia">Australia</option>
                                        <option value="India">India</option>
                                        <option value="Europe">Europe</option>
                                        <option value="Asia Pacific">Asia Pacific</option>
                                        <option value="Middle East">Middle East</option>
                                        <option value="Latin America">Latin America</option>
                                        <option value="Global">Global</option>
                                    </select>
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Openings:</label>
                                <div class="jo-form-input">
                                    <input type="text" id="openings" name="openings" tabindex="15" value="<?php echo isset($this->jobOrderSourceRS['openings']) ? $this->jobOrderSourceRS['openings'] : '1'; ?>" />
                                    <span class="required">*</span>
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Company Job ID:</label>
                                <div class="jo-form-input">
                                    <input type="text" id="companyJobID" name="companyJobID" tabindex="16" />
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Hot:</label>
                                <div class="jo-form-input">
                                    <input type="checkbox" id="isHot" name="isHot" tabindex="17" />
                                    <img title="Checking this box indicates that the job order is 'hot', and shows up highlighted throughout the system." src="images/information.gif" alt="" width="16" height="16" class="info-icon" />
                                </div>
                            </div>

                            <div class="jo-form-row">
                                <label class="jo-form-label">Public:</label>
                                <div class="jo-form-input">
                                    <input type="checkbox" id="public" name="public" tabindex="18" onchange="checkPublic(this);" onclick="checkPublic(this);" onkeydown="checkPublic(this);" />
                                    <img title="Checking this box indicates that the job order is public. Job orders flagged as public will be able to be viewed by anonymous users." src="images/information.gif" alt="" width="16" height="16" class="info-icon" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="jo-form-section">
                        <div class="jo-form-section-title">Description:</div>
                        <textarea name="description" id="description" class="ckEditor" rows="10"><?php if(isset($this->jobOrderSourceRS['description'])): ?><?php $this->_($this->jobOrderSourceRS['description']); ?><?php endif; ?></textarea>
                    </div>

                    <!-- Internal Notes Section -->
                    <div class="jo-form-section">
                        <div class="jo-form-section-title">Internal Notes:</div>
                        <textarea name="notes" id="notes" rows="4"><?php if(isset($this->jobOrderSourceRS['notes'])): ?><?php $this->_($this->jobOrderSourceRS['notes']); ?><?php endif; ?></textarea>
                    </div>

                    <?php if ($this->careerPortalEnabled): ?>
                    <div class="jo-form-section" id="displayQuestionnaires" style="display: none;">
                        <div class="jo-form-section-title">Questionnaire:</div>
                        <select id="questionnaire" name="questionnaire" style="width: 100%; max-width: 400px; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;">
                            <option value="none" selected>None</option>
                            <?php foreach ($this->questionnaires as $questionnaire): ?>
                                <option value="<?php echo $questionnaire['questionnaireID']; ?>"><?php echo $questionnaire['title']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($this->getUserAccessLevel('settings.careerPortalSettings') >= ACCESS_LEVEL_SA): ?>
                        <br /><a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&a=careerPortalSettings" target="_blank" style="font-size: 12px; color: #2563eb;">Add / Edit / Delete Questionnaires</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Form Actions -->
                    <div class="jo-form-actions">
                        <button type="submit" class="jo-btn jo-btn-primary" tabindex="20">Add Job Order</button>
                        <button type="reset" class="jo-btn jo-btn-secondary" tabindex="21">Reset</button>
                        <button type="button" class="jo-btn jo-btn-dark" tabindex="22" onclick="javascript:goToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=listByView');">Back to Job Orders</button>
                    </div>
                </div>
            </form>

            <script type="text/javascript">
                placeCkEditorIn('description');
                document.addJobOrderForm.title.focus();
                <?php if ($this->selectedCompanyID !== false): ?>
                    listEditorUpdateSelectFromCSV('departmentSelect', 'departmentsCSV', true, false);
                <?php endif; ?>
                <?php if (isset($this->jobOrderSourceRS['companyID'])): ?>updateCompanyData('<?php echo($this->sessionCookie); ?>');<?php endif; ?>
                oldCompanyID = -1;
                watchCompanyIDChangeJO('<?php echo($this->sessionCookie); ?>');
            </script>
        <?php endif; ?>
    </div>
</div>
<?php TemplateUtility::printFooter(); ?>
