<?php /* $Id: Edit.tpl 3810 2007-12-05 19:13:25Z brian $ */ ?>
<?php TemplateUtility::printHeader('Job Orders', array('modules/joborders/validator.js', 'js/company.js', 'js/sweetTitles.js',  'js/suggest.js', 'js/joborder.js', 'js/listEditor.js', 'vendor/ckeditor/ckeditor/ckeditor.js', 'js/ckeditor-manager.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
    <script type="text/javascript">
        window.CATSUserDateFormat = '<?php echo($_SESSION['CATS']->isDateDMY() ? 'DD-MM-YY' : 'MM-DD-YY'); ?>';

        // Auto-generate Company Job ID based on company selection (only if empty)
        function generateCompanyJobID() {
            var companyID = document.getElementById('companyID').value;
            var jobIDField = document.getElementById('companyJobID');

            // Only auto-generate if the field is empty
            if (!jobIDField || jobIDField.value.trim() !== '') {
                return;
            }

            if (!companyID || companyID == '0') {
                jobIDField.value = '';
                return;
            }

            // Show loading indicator
            jobIDField.style.opacity = '0.5';

            // Capture the field value at call time to guard against race conditions
            var valueAtCallTime = jobIDField.value.trim();

            // Call AJAX endpoint to generate Company Job ID
            fetch('ajax/generateCompanyJobID.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'companyID=' + encodeURIComponent(companyID) + '&siteID=<?php echo($_SESSION['CATS']->getSiteID()); ?>'
            })
            .then(response => response.json())
            .then(data => {
                jobIDField.style.opacity = '1';
                if (data.success && data.companyJobID) {
                    // Only update if the field has not been changed since the request was made
                    if (jobIDField.value.trim() === valueAtCallTime) {
                        jobIDField.value = data.companyJobID;
                    }
                } else if (data.error) {
                    console.error('Error generating Company Job ID:', data.error);
                }
            })
            .catch(error => {
                jobIDField.style.opacity = '1';
                console.error('Error:', error);
            });
        }

        // Auto-select questionnaire based on job order type
        function selectQuestionnaireByType() {
            var jobType = document.getElementById('type').value;
            if (!jobType || jobType === 'N/A') {
                return;
            }

            // Show loading indicator
            var questionnaireField = document.getElementById('questionnaire');
            if (!questionnaireField) {
                return;
            }
            questionnaireField.style.opacity = '0.5';

            // Call AJAX endpoint to get questionnaire for this job type
            fetch('<?php echo(CATSUtility::getIndexName()); ?>?f=getQuestionnaireByJobType', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'jobOrderType=' + encodeURIComponent(jobType) + '&siteID=<?php echo($_SESSION['CATS']->getSiteID()); ?>'
            })
            .then(response => response.json())
            .then(data => {
                questionnaireField.style.opacity = '1';
                if (data.success && data.questionnaireID) {
                    // Auto-select the questionnaire
                    questionnaireField.value = data.questionnaireID;
                    console.log('Questionnaire auto-selected for ' + jobType + ' type');
                } else if (data.success && !data.questionnaireID) {
                    // No questionnaire configured for this type
                    questionnaireField.value = 'none';
                    console.info(data.message);
                } else if (data.error) {
                    console.info('No specific questionnaire for this job type - manual selection available');
                    questionnaireField.value = 'none';
                }
            })
            .catch(error => {
                questionnaireField.style.opacity = '1';
                console.error('Error auto-selecting questionnaire:', error);
            });
        }
    </script>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <?php TemplateUtility::printBreadcrumb(
                CATSUtility::getIndexName().'?m=joborders&amp;a=show&amp;jobOrderID='.$this->jobOrderID,
                $this->data['title'],
                'Edit',
                'images/job_orders.gif'
            ); ?>

            <form name="editJobOrderForm" id="editJobOrderForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=edit" method="post" onsubmit="return checkEditForm(document.editJobOrderForm);" autocomplete="off">
                <input type="hidden" name="postback" id="postback" value="postback" />
                <input type="hidden" id="jobOrderID" name="jobOrderID" value="<?php echo($this->jobOrderID); ?>" />

                <div class="form-container">
                    <div class="form-header">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        <h2>Edit Job Order</h2>
                    </div>

                    <div class="form-grid">
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Title:</label>
                                <div class="form-input">
                                    <input type="text" tabindex="1" id="title" name="title" value="<?php $this->_($this->data['title']); ?>" />
                                    <span class="required">*</span>
                                </div>
                            </div>
                            <div class="form-row" style="align-items: flex-start;">
                                <label class="form-label" style="margin-top: 8px;">Company:</label>
                                <div class="form-input" style="position: relative; flex-wrap: wrap;">
                                    <input type="hidden" name="companyID" id="companyID" value="<?php echo($this->data['companyID']); ?>" />
                                    <?php if ($this->defaultCompanyID !== false): ?>
                                        <label style="display: flex; align-items: center; gap: 6px; width: 100%;">
                                            <input type="radio" name="typeCompany" <?php if ($this->defaultCompanyID != $this->data['companyID']) echo(' checked'); ?> onchange="document.getElementById('companyName').disabled = false; if (oldCompanyID != -1) document.getElementById('companyID').value = oldCompanyID; generateCompanyJobID();" />
                                            <input type="text" name="companyName" id="companyName" tabindex="2" value="<?php $this->_($this->data['companyName']) ?>" onFocus="suggestListActivate('getCompanyNames', 'companyName', 'CompanyResults', 'companyID', 'ajaxTextEntryHover', 0, '<?php echo($this->sessionCookie); ?>', 'helpShim');" onchange="generateCompanyJobID();" <?php if ($this->defaultCompanyID == $this->data['companyID']) echo(' disabled'); ?> style="flex: 1;" />
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 6px; width: 100%; font-size: 13px; color: var(--gray-600);">
                                            <input type="radio" name="typeCompany" id="defaultCompany" <?php if ($this->defaultCompanyID == $this->data['companyID']) echo(' checked'); ?> onchange="if(document.getElementById('companyName').disabled == false && document.getElementById('companyID').value > 0) {oldCompanyID = document.getElementById('companyID').value; } else if(document.getElementById('companyName').disabled == false) { oldCompanyID = 0; } document.getElementById('companyName').disabled = true; document.getElementById('companyID').value = '<?php echo($this->defaultCompanyID); ?>'; generateCompanyJobID();" />
                                            <?php echo($this->defaultCompanyRS['name']); ?>
                                        </label>
                                        <script type="text/javascript">oldCompanyID = -1; watchCompanyIDChangeJO('<?php echo($this->sessionCookie); ?>');</script>
                                    <?php else: ?>
                                        <input type="text" name="companyName" id="companyName" tabindex="2" value="<?php $this->_($this->data['companyName']) ?>" onFocus="suggestListActivate('getCompanyNames', 'companyName', 'CompanyResults', 'companyID', 'ajaxTextEntryHover', 0, '<?php echo($this->sessionCookie); ?>', 'helpShim');" onchange="generateCompanyJobID();" />
                                        <script type="text/javascript">oldCompanyID = -1;</script>
                                    <?php endif; ?>
                                    <span class="required">*</span>
                                    <iframe id="helpShim" src="javascript:void(0);" scrolling="no" frameborder="0" style="position:absolute; display:none;"></iframe>
                                    <div id="CompanyResults" class="ajaxSearchResults"></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">City:</label>
                                <div class="form-input">
                                    <input type="text" tabindex="4" id="city" name="city" value="<?php $this->_($this->data['city']); ?>" />
                                    <span class="required">*</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">State:</label>
                                <div class="form-input">
                                    <input type="text" tabindex="5" id="state" name="state" value="<?php $this->_($this->data['state']); ?>" />
                                    <span class="required">*</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Type:</label>
                                <div class="form-input">
                                    <select tabindex="15" id="type" name="type" onchange="selectQuestionnaireByType();">
                                        <?php foreach($this->jobTypes as $jobTypeShort => $jobTypeLong): ?>
                                            <option value="<?php echo $jobTypeShort;?>" <?php if($this->data['type'] == $jobTypeShort): ?>selected="selected"<?php endif; ?>><?php echo $jobTypeShort." (".$jobTypeLong.")";?></option>
                                        <?php endforeach; ?>
                                        <?php if(count($this->jobTypes) < 1): ?>
                                            <option value="N/A" selected>N/A (Not Applicable)</option>
                                        <?php endif; ?>
                                    </select>
                                    <span class="required">*</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Total Openings:</label>
                                <div class="form-input">
                                    <input type="text" tabindex="16" id="openings" name="openings" value="<?php $this->_($this->data['openings']); ?>" />
                                    <span class="required">*</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Recruiter:</label>
                                <div class="form-input">
                                    <select tabindex="6" id="recruiter" name="recruiter">
                                        <option value="">(Select a User)</option>
                                        <?php foreach ($this->usersRS as $rowNumber => $usersData): ?>
                                            <option <?php if ($this->data['recruiter'] == $usersData['userID']): ?>selected<?php endif; ?> value="<?php $this->_($usersData['userID']) ?>"><?php $this->_($usersData['firstName']) ?> <?php $this->_($usersData['lastName']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="required">*</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Owner:</label>
                                <div class="form-input">
                                    <select tabindex="7" id="owner" name="owner" <?php if (!$this->emailTemplateDisabled): ?>onchange="document.getElementById('divOwnershipChange').style.display=''; <?php if ($this->canEmail): ?>document.getElementById('checkboxOwnershipChange').checked=true;<?php endif; ?>"<?php endif; ?>>
                                        <option value="-1">None</option>
                                        <?php foreach ($this->usersRS as $rowNumber => $usersData): ?>
                                            <option <?php if ($this->data['owner'] == $usersData['userID']): ?>selected<?php endif; ?> value="<?php $this->_($usersData['userID']) ?>"><?php $this->_($usersData['firstName']) ?> <?php $this->_($usersData['lastName']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="required">*</span>
                                </div>
                            </div>
                            <div class="form-row" id="divOwnershipChange" style="display:none;">
                                <label class="form-label"></label>
                                <div class="form-input">
                                    <input type="checkbox" name="ownershipChange" id="checkboxOwnershipChange" <?php if (!$this->canEmail): ?>disabled<?php endif; ?> /> E-Mail new owner of change
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Status:</label>
                                <div class="form-input">
                                    <select tabindex="8" id="status" name="status">
                                        <?php foreach($this->jobOrderStatuses as $statusTypeName => $statusType){
                                            echo("<optgroup label=".$statusTypeName.">");
                                            foreach($statusType as $status){
                                                $selected = "";
                                                if ($this->data['status'] == $status){
                                                    $selected = "selected ";
                                                }
                                                echo('<option '.$selected.'value="'.$status.'">'.$status.'</option>');
                                            }
                                            echo("</optgroup>");
                                        }?>
                                    </select>
                                    <span class="required">*</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-more-toggle">
                        <button type="button" id="jobOrderMoreFieldsBtn" onclick="toggleFormFields('jobOrderMoreFields', 'jobOrderMoreFieldsBtn');">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            Show More Fields
                        </button>
                    </div>

                    <div id="jobOrderMoreFields" class="form-grid form-additional-fields">
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Start Date:</label>
                                <div class="form-input" style="flex-direction: column; align-items: flex-start; gap: 8px;">
<?php if (!empty($this->data['startDate'])): ?>
                                    <script type="text/javascript">DateInput('startDate', false, (typeof window.CATSUserDateFormat !== 'undefined' ? window.CATSUserDateFormat : 'MM-DD-YY'), '<?php echo($this->data['startDateUser']); ?>', 9);</script>
<?php else: ?>
                                    <script type="text/javascript">DateInput('startDate', false, (typeof window.CATSUserDateFormat !== 'undefined' ? window.CATSUserDateFormat : 'MM-DD-YY'), '', 9);</script>
<?php endif; ?>
                                    <input type="time" id="startTime" name="startTime" value="<?php $this->_($this->data['startTimeUser']); ?>" style="width: 100%;" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Duration:</label>
                                <div class="form-input">
                                    <input type="text" tabindex="12" id="duration" name="duration" value="<?php $this->_($this->data['duration']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Department:</label>
                                <div class="form-input">
                                    <select id="departmentSelect" name="department" onchange="if (this.value == 'edit') { listEditor('Departments', 'departmentSelect', 'departmentsCSV', false); this.value = '(none)'; } if (this.value == 'nullline') { this.value = '(none)'; }">
                                        <?php if ($this->data['departmentID'] == 0): ?>
                                            <option value="(none)" selected="selected">None</option>
                                        <?php else: ?>
                                            <option value="(none)">None</option>
                                        <?php endif; ?>
                                        <?php foreach ($this->departmentsRS as $index => $department): ?>
                                            <option value="<?php $this->_($department['name']); ?>" <?php if ($department['name'] == $this->data['department']): ?>selected<?php endif; ?>><?php $this->_($department['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" id="departmentsCSV" name="departmentsCSV" value="<?php $this->_($this->departmentsString); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Interview Process:</label>
                                <div class="form-input">
                                    <select id="pipelineTemplateID" name="pipelineTemplateID">
                                        <option value="">Default</option>
                                        <?php foreach ($this->pipelineTemplatesRS as $templateRow): ?>
                                            <option value="<?php $this->_($templateRow['templateID']); ?>" <?php if (isset($this->data['pipelineTemplateID']) && $this->data['pipelineTemplateID'] == $templateRow['templateID']): ?>selected<?php endif; ?>><?php $this->_($templateRow['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Contact:</label>
                                <div class="form-input">
                                    <select tabindex="3" id="contactID" name="contactID">
                                        <option value="-1">None</option>
                                        <?php foreach ($this->contactsRS as $rowNumber => $contactsData): ?>
                                            <option <?php if ($this->data['contactID'] == $contactsData['contactID']): ?>selected<?php endif; ?> value="<?php $this->_($contactsData['contactID']) ?>"><?php $this->_($contactsData['lastName']) ?>, <?php $this->_($contactsData['firstName']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <img src="images/indicator2.gif" id="contactsIndicator" alt="" style="vertical-align: middle; visibility: hidden;" height="16" width="16" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Contact Mobile Number:</label>
                                <div class="form-input">
                                    <input type="text" tabindex="3" id="contactPhone" name="contactPhone" value="<?php $this->_($this->data['contactPhone']); ?>" placeholder="e.g. 9876543210" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Company Job ID:</label>
                                <div class="form-input">
                                    <input type="text" tabindex="17" id="companyJobID" name="companyJobID" value="<?php $this->_($this->data['companyJobID']); ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Maximum Rate:</label>
                                <div class="form-input">
                                    <input type="text" tabindex="13" id="maxRate" name="maxRate" value="<?php $this->_($this->data['maxRate']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Salary:</label>
                                <div class="form-input">
                                    <input type="text" tabindex="14" id="salary" name="salary" value="<?php $this->_($this->data['salary']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Remaining Openings:</label>
                                <div class="form-input">
                                    <input type="text" tabindex="16" id="openingsAvailable" name="openingsAvailable" value="<?php $this->_($this->data['openingsAvailable']); ?>" />
                                    <span class="required">*</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Hot:</label>
                                <div class="form-input">
                                    <input type="checkbox" tabindex="18" id="isHot" name="isHot"<?php if ($this->data['isHot'] == 1): ?> checked<?php endif; ?> />
                                    <img title="Checking this box indicates that the job order is 'hot', and shows up highlighted throughout the system." src="images/information.gif" alt="" width="16" height="16" class="info-icon" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Public:</label>
                                <div class="form-input">
                                    <input type="checkbox" tabindex="19" id="public" name="public" onchange="checkPublic(this);" onclick="checkPublic(this);" onkeydown="checkPublic(this);"<?php if ($this->data['public'] == 1): ?> checked<?php endif; ?> />
                                    <img title="Checking this box indicates that the job order is public. Job orders flagged as public will be able to be viewed by anonymous users." src="images/information.gif" alt="" width="16" height="16" class="info-icon" />
                                </div>
                            </div>
                        </div>
                        <?php if (count($this->extraFieldRS) > 0): ?>
                        <div class="form-column">
                            <?php for ($i = 0; $i < count($this->extraFieldRS); $i++): ?>
                            <div class="form-row">
                                <label class="form-label" id="extraFieldTd<?php echo($i); ?>"><?php $this->_($this->extraFieldRS[$i]['fieldName']); ?>:</label>
                                <div class="form-input" id="extraFieldData<?php echo($i); ?>">
                                    <?php echo($this->extraFieldRS[$i]['editHTML']); ?>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php eval(Hooks::get('JO_TEMPLATE_BOTTOM_OF_TOP')); ?>

                    <div class="form-section">
                        <div class="form-section-title">Description:</div>
                        <textarea tabindex="20" class="ckEditor" name="description" id="description" rows="15"><?php $this->_($this->data['description']); ?></textarea>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">Internal Notes:</div>
                        <textarea tabindex="21" class="ckEditor" name="notes" id="notes" rows="5"><?php $this->_($this->data['notes']); ?></textarea>
                    </div>

                    <?php if ($this->careerPortalEnabled): ?>
                    <div class="form-section" id="displayQuestionnaires" style="<?php if (!$this->isPublic): ?>display: none;<?php endif; ?>">
                        <div class="form-section-title">Questionnaire:</div>
                        <select id="questionnaire" name="questionnaire" style="width: 100%; max-width: 400px; padding: 8px 12px; border: 1px solid var(--gray-300); border-radius: var(--radius);">
                            <option value="none">None</option>
                            <?php foreach ($this->questionnaires as $questionnaire): ?>
                                <option value="<?php echo $questionnaire['questionnaireID']; ?>"<?php if ($this->questionnaireID == $questionnaire['questionnaireID']) echo ' selected'; ?>><?php echo $questionnaire['title']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($this->getUserAccessLevel('settings.careerPortalSettings') >= ACCESS_LEVEL_SA): ?>
                        <br /><a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&a=careerPortalSettings" target="_blank" style="font-size: 12px;">Add / Edit / Delete Questionnaires</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div class="form-actions">
                        <button type="submit" tabindex="22" class="form-btn form-btn-primary" name="submit" id="submit">Save</button>
                        <button type="reset" tabindex="23" class="form-btn form-btn-secondary" name="reset" id="reset">Reset</button>
                        <button type="button" tabindex="24" class="form-btn form-btn-dark" name="back" id="back" onclick="javascript:goToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=show&amp;jobOrderID=<?php echo($this->jobOrderID); ?>');">Back to Details</button>
                    </div>
                </div>
            </form>

            <script type="text/javascript">
                placeCkEditorIn('description');
            </script>

            <script type="text/javascript">
                document.editJobOrderForm.title.focus();
            </script>
        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
