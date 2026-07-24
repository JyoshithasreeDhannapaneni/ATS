<?php /* $Id: Add.tpl 3810 2007-12-05 19:13:25Z brian $ */ ?>
<?php TemplateUtility::printHeader('Job Orders', array('modules/joborders/validator.js',  'js/company.js', 'js/sweetTitles.js', 'js/suggest.js', 'js/joborder.js', 'js/listEditor.js', 'vendor/ckeditor/ckeditor/ckeditor.js', 'js/ckeditor-manager.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>
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
        fetch('ajax/generateCompanyJobID.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'companyID=' + encodeURIComponent(companyID) + '&siteID=<?php echo($_SESSION['CATS']->getSiteID()); ?>'
        })
        .then(response => response.json())
        .then(data => {
            jobIDField.style.opacity = '1';
            if (data.success && data.companyJobID) {
                document.getElementById('companyJobID').value = data.companyJobID;
            }
        })
        .catch(error => { jobIDField.style.opacity = '1'; jobIDField.placeholder = 'Auto-generate failed — enter manually'; });
    }

    function generateJobDescriptionWithAI() {
        var title = document.getElementById('title').value;
        var keywords = document.getElementById('aiKeywords').value;
        var companyName = document.getElementById('companyName') ? document.getElementById('companyName').value : '';

        if (!title && !keywords) {
            alert('Enter a job title or a few keywords first.');
            return;
        }

        var btn = document.getElementById('aiGenerateBtn');
        var originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Generating…';

        fetch('ajax/generateJobDescription.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'title=' + encodeURIComponent(title)
                + '&keywords=' + encodeURIComponent(keywords)
                + '&companyName=' + encodeURIComponent(companyName)
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.textContent = originalText;
            if (data.success && data.description) {
                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['description']) {
                    CKEDITOR.instances['description'].setData(data.description);
                } else {
                    document.getElementById('description').value = data.description;
                }
            } else {
                alert(data.error || 'Could not generate a description.');
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.textContent = originalText;
            alert('Could not reach the AI service. Please try again.');
        });
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
            body: 'jobOrderType=' + encodeURIComponent(jobType) + '&siteID=<?php echo($_SESSION['CATS']->getSiteID()); ?>'
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
            <div class="form-container">
                <div class="form-header">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    <h2>Job Orders: Add Job Order</h2>
                </div>
                <div style="padding: 40px; text-align: center; color: #6b7280;">
                    <p><strong>You have not added any companies yet.</strong></p>
                    <p>You can't add a job order until you add at least one company.</p>
                    <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=companies" class="form-btn form-btn-primary" style="display: inline-block; margin-top: 16px; text-decoration: none;">Go to Companies</a>
                </div>
            </div>
        <?php else: ?>
            <form name="addJobOrderForm" id="addJobOrderForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=add" method="post" onsubmit="return checkAddForm(document.addJobOrderForm);" autocomplete="off">
                <input type="hidden" name="postback" id="postback" value="postback" />
                <input type="hidden" name="companyID" id="companyID" value="<?php if ($this->selectedCompanyID === false) { if (isset($this->jobOrderSourceRS['companyID'])) { echo ($this->jobOrderSourceRS['companyID']); } else { echo(0); } } else { echo($this->selectedCompanyID); } ?>" />

                <div class="form-container">
                    <div class="form-header">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        <h2>Job Orders: Add Job Order</h2>
                    </div>
                    <div class="form-note">Add a new job order to the system.</div>

                    <div class="form-grid">
                        <!-- Left Column: essentials -->
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Title:</label>
                                <div class="form-input">
                                    <input type="text" id="title" name="title" tabindex="1" <?php if(isset($this->jobOrderSourceRS['title'])): ?>value="<?php $this->_($this->jobOrderSourceRS['title']); ?>"<?php endif; ?> />
                                    <span class="required">*</span>
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-label">Company:</label>
                                <div class="form-input" style="position: relative;">
                                    <input type="text" name="companyName" id="companyName" tabindex="2" value="<?php if ($this->selectedCompanyID !== false) { $this->_($this->companyRS['name']); } ?><?php if(isset($this->jobOrderSourceRS['companyName']) && $this->selectedCompanyID == false ): ?><?php $this->_($this->jobOrderSourceRS['companyName']); ?><?php endif; ?>" onFocus="suggestListActivate('getCompanyNames', 'companyName', 'CompanyResults', 'companyID', 'ajaxTextEntryHover', 0, '<?php echo($this->sessionCookie); ?>', 'helpShim');" onchange="generateCompanyJobID();" <?php if ($this->selectedCompanyID !== false) { echo('disabled'); } ?> />
                                    <span class="required">*</span>
                                    <iframe id="helpShim" src="javascript:void(0);" scrolling="no" frameborder="0" style="position:absolute; display:none;"></iframe>
                                    <div id="CompanyResults" class="ajaxSearchResults"></div>
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-label">City:</label>
                                <div class="form-input">
                                    <input type="text" id="city" name="city" tabindex="4" <?php if ($this->selectedCompanyID !== false): ?>value="<?php $this->_($this->selectedCompanyLocation['city']); ?>"<?php endif; ?> />
                                    <span class="required">*</span>
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-label">State:</label>
                                <div class="form-input">
                                    <input type="text" id="state" name="state" tabindex="5" <?php if ($this->selectedCompanyID !== false): ?>value="<?php $this->_($this->selectedCompanyLocation['state']); ?>"<?php endif; ?> />
                                    <span class="required">*</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: essentials -->
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Type:</label>
                                <div class="form-input">
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

                            <div class="form-row">
                                <label class="form-label">Openings:</label>
                                <div class="form-input">
                                    <input type="text" id="openings" name="openings" tabindex="15" value="<?php echo isset($this->jobOrderSourceRS['openings']) ? $this->jobOrderSourceRS['openings'] : '1'; ?>" />
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-label">Recruiter:</label>
                                <div class="form-input">
                                    <select id="recruiter" name="recruiter" tabindex="6">
                                        <option value="">(Select a User)</option>
                                        <?php foreach ($this->usersRS as $rowNumber => $usersData): ?>
                                            <option <?php if ($usersData['userID'] == $this->userID): ?>selected<?php endif; ?> value="<?php $this->_($usersData['userID']) ?>"><?php $this->_($usersData['firstName']) ?> <?php $this->_($usersData['lastName']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="required">*</span>
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-label">Owner:</label>
                                <div class="form-input">
                                    <select id="owner" name="owner" tabindex="7">
                                        <option value="">(Select a User)</option>
                                        <?php foreach ($this->usersRS as $rowNumber => $usersData): ?>
                                            <option <?php if ($usersData['userID'] == $this->userID): ?>selected<?php endif; ?> value="<?php $this->_($usersData['userID']) ?>"><?php $this->_($usersData['firstName']) ?> <?php $this->_($usersData['lastName']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="required">*</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Show More Fields Toggle -->
                    <div class="form-more-toggle">
                        <button type="button" id="jobOrderMoreFieldsBtn" onclick="toggleFormFields('jobOrderMoreFields', 'jobOrderMoreFieldsBtn');">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            Show More Fields
                        </button>
                    </div>

                    <!-- Additional Fields (hidden by default) -->
                    <div id="jobOrderMoreFields" class="form-grid form-additional-fields">
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Department:</label>
                                <div class="form-input">
                                    <select id="departmentSelect" name="department" onchange="if (this.value == 'edit') { listEditor('Departments', 'departmentSelect', 'departmentsCSV', false); this.value = '(none)'; } if (this.value == 'nullline') { this.value = '(none)'; }">
                                        <option value="(none)" selected>None</option>
                                    </select>
                                    <input type="hidden" id="departmentsCSV" name="departmentsCSV" value="<?php if ($this->selectedCompanyID !== false): $this->_($this->selectedDepartmentsString); endif; ?>" />
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-label">Interview Process:</label>
                                <div class="form-input">
                                    <select id="pipelineTemplateID" name="pipelineTemplateID">
                                        <option value="">Default</option>
                                        <?php foreach ($this->pipelineTemplatesRS as $templateRow): ?>
                                            <option value="<?php $this->_($templateRow['templateID']); ?>"><?php $this->_($templateRow['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=administration&amp;s=pipelineTemplates" target="_blank" style="font-size:11px;">Manage</a>
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-label">Contact:</label>
                                <div class="form-input">
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

                            <div class="form-row">
                                <label class="form-label">Contact Mobile Number:</label>
                                <div class="form-input">
                                    <input type="text" id="contactPhone" name="contactPhone" placeholder="e.g. 9876543210" />
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-label">Shift Timing:</label>
                                <div class="form-input">
                                    <select id="shiftTiming" name="shiftTiming">
                                        <option value="">Select Shift</option>
                                        <option value="Fixed">Fixed</option>
                                        <option value="Rotational">Rotational</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-label">Start Date:</label>
                                <div class="form-input" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                                    <script type="text/javascript">DateInput('startDate', false, (typeof window.CATSUserDateFormat !== 'undefined' ? window.CATSUserDateFormat : 'MM-DD-YY'), '', 8);</script>
                                    <input type="time" id="startTime" name="startTime" style="width: 100%;" />
                                </div>
                            </div>

                            <?php for ($i = 0; $i < count($this->extraFieldRS); $i++): ?>
                            <div class="form-row">
                                <label class="form-label"><?php $this->_($this->extraFieldRS[$i]['fieldName']); ?>:</label>
                                <div class="form-input">
                                    <?php echo($this->extraFieldRS[$i]['addHTML']); ?>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>

                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Duration:</label>
                                <div class="form-input">
                                    <input type="text" id="duration" name="duration" tabindex="11" <?php if(isset($this->jobOrderSourceRS['duration'])): ?>value="<?php $this->_($this->jobOrderSourceRS['duration']); ?>"<?php endif; ?> />
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-label">Maximum Rate:</label>
                                <div class="form-input">
                                    <input type="text" id="maxRate" name="maxRate" tabindex="12" <?php if(isset($this->jobOrderSourceRS['maxRate'])): ?>value="<?php $this->_($this->jobOrderSourceRS['maxRate']); ?>"<?php endif; ?> />
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-label">Salary:</label>
                                <div class="form-input">
                                    <input type="text" id="salary" name="salary" tabindex="14" <?php if(isset($this->jobOrderSourceRS['salary'])): ?>value="<?php $this->_($this->jobOrderSourceRS['salary']); ?>"<?php endif; ?> />
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-label">Market:</label>
                                <div class="form-input">
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

                            <div class="form-row">
                                <label class="form-label">Company Job ID:</label>
                                <div class="form-input">
                                    <input type="text" id="companyJobID" name="companyJobID" tabindex="16" />
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-label">Hot:</label>
                                <div class="form-input">
                                    <input type="checkbox" id="isHot" name="isHot" tabindex="17" />
                                    <img title="Checking this box indicates that the job order is 'hot', and shows up highlighted throughout the system." src="images/information.gif" alt="" width="16" height="16" class="info-icon" />
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-label">Public:</label>
                                <div class="form-input">
                                    <input type="checkbox" id="public" name="public" tabindex="18" onchange="checkPublic(this);" onclick="checkPublic(this);" onkeydown="checkPublic(this);" />
                                    <img title="Checking this box indicates that the job order is public. Job orders flagged as public will be able to be viewed by anonymous users." src="images/information.gif" alt="" width="16" height="16" class="info-icon" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="form-section">
                        <div class="form-section-title">
                            Description:
                            <span style="float:right; font-weight:normal;">
                                <input type="text" id="aiKeywords" placeholder="e.g. React, 3+ years, remote" style="width:220px; font-size:12px; padding:4px;" />
                                <button type="button" id="aiGenerateBtn" class="form-btn form-btn-dark" style="font-size:12px; padding:4px 10px;" onclick="generateJobDescriptionWithAI();">Generate with AI</button>
                            </span>
                        </div>
                        <textarea name="description" id="description" class="ckEditor" rows="10"><?php if(isset($this->jobOrderSourceRS['description'])): ?><?php $this->_($this->jobOrderSourceRS['description']); ?><?php endif; ?></textarea>
                    </div>

                    <!-- Internal Notes Section -->
                    <div class="form-section">
                        <div class="form-section-title">Internal Notes:</div>
                        <textarea name="notes" id="notes" rows="4"><?php if(isset($this->jobOrderSourceRS['notes'])): ?><?php $this->_($this->jobOrderSourceRS['notes']); ?><?php endif; ?></textarea>
                    </div>

                    <?php if ($this->careerPortalEnabled): ?>
                    <div class="form-section" id="displayQuestionnaires" style="display: none;">
                        <div class="form-section-title">Questionnaire:</div>
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
                    <div class="form-actions">
                        <button type="submit" class="form-btn form-btn-primary" tabindex="20">Add Job Order</button>
                        <button type="reset" class="form-btn form-btn-secondary" tabindex="21">Reset</button>
                        <button type="button" class="form-btn form-btn-dark" tabindex="22" onclick="javascript:goToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=listByView');">Back to Job Orders</button>
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
