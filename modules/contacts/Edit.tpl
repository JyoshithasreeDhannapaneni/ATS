<?php /* $Id: Edit.tpl 3093 2007-09-24 21:09:45Z brian $ */ ?>
<?php TemplateUtility::printHeader('Contacts', array('modules/contacts/validator.js', 'js/sweetTitles.js', 'js/suggest.js', 'js/listEditor.js',  'js/contact.js', 'js/company.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <?php TemplateUtility::printBreadcrumb(
                CATSUtility::getIndexName().'?m=contacts&amp;a=show&amp;contactID='.$this->contactID,
                $this->data['firstName'].' '.$this->data['lastName'],
                'Edit',
                'images/contact.gif'
            ); ?>

            <form name="editContactForm" id="editContactForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=contacts&amp;a=edit" method="post" onsubmit="return checkEditForm(document.editContactForm);" autocomplete="off">
                <input type="hidden" name="postback" id="postback" value="postback" />
                <input type="hidden" name="contactID" id="contactID" value="<?php echo($this->contactID); ?>" />

                <div class="form-container">
                    <div class="form-header">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="M15 8h4"/><path d="M15 12h4"/><path d="M5 16c1-2 3-3 4-3s3 1 4 3"/></svg>
                        <h2>Edit Contact</h2>
                    </div>

                    <div class="form-grid">
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">First Name:</label>
                                <div class="form-input">
                                    <input type="text" name="firstName" id="firstName" value="<?php $this->_($this->data['firstName']); ?>" />
                                    <span class="required">*</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Last Name:</label>
                                <div class="form-input">
                                    <input type="text" name="lastName" id="lastName" value="<?php $this->_($this->data['lastName']); ?>" />
                                    <span class="required">*</span>
                                </div>
                            </div>
                            <div class="form-row" style="align-items: flex-start;">
                                <label class="form-label" style="margin-top: 8px;"><span id="companyAssociatedLabel" <?php if ($this->data['leftCompany'] != 1): ?>style="display:none;"<?php endif; ?>>Previous </span>Company:</label>
                                <div class="form-input" style="position: relative; flex-wrap: wrap;">
                                    <input type="hidden" name="companyID" id="companyID" value="<?php $this->_($this->data['companyID']); ?>" />
                                    <input type="text" name="companyName" id="companyName" value="<?php $this->_($this->data['companyName']); ?>" onFocus="suggestListActivate('getCompanyNames', 'companyName', 'CompanyResults', 'companyID', 'ajaxTextEntryHover', 0, '<?php echo($this->sessionCookie); ?>', 'helpShim');" <?php if ($this->defaultCompanyID == $this->data['companyID']) echo('disabled'); ?> />
                                    <span class="required">*</span>
                                    <?php if ($this->defaultCompanyID !== false): ?>
                                        <label style="font-size: 12px; font-weight: 500; color: var(--gray-600); display: flex; align-items: center; gap: 4px; width: 100%; margin-top: 6px;">
                                            <input type="checkbox" id="defaultCompany" onchange="if (this.checked) { document.getElementById('companyName').disabled = true; document.getElementById('companyID').value = '<?php echo($this->defaultCompanyID); ?>'; document.getElementById('companyName').value = &quot;<?php $this->_($this->defaultCompanyRS['name']); ?>&quot;; } else { document.getElementById('companyName').disabled = false; }"<?php if ($this->defaultCompanyID == $this->data['companyID']) echo(' checked'); ?> />
                                            Internal Contact
                                        </label>
                                    <?php endif; ?>
                                    <script type="text/javascript">watchCompanyIDChange('<?php echo($this->sessionCookie); ?>');</script>
                                    <iframe id="helpShim" src="javascript:void(0);" scrolling="no" frameborder="0" style="position:absolute; display:none;"></iframe>
                                    <div id="CompanyResults" class="ajaxSearchResults"></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Title:</label>
                                <div class="form-input">
                                    <input type="text" name="title" id="title" value="<?php $this->_($this->data['title']); ?>" />
                                    <span class="required">*</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">E-Mail:</label>
                                <div class="form-input">
                                    <input type="text" name="email1" id="email1" value="<?php $this->_($this->data['email1']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Cell Phone:</label>
                                <div class="form-input">
                                    <input type="text" name="phoneCell" id="phoneCell" value="<?php $this->_($this->data['phoneCell']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Owner:</label>
                                <div class="form-input">
                                    <select id="owner" name="owner" <?php if (!$this->emailTemplateDisabled): ?>onchange="document.getElementById('divOwnershipChange').style.display=''; <?php if ($this->canEmail): ?>document.getElementById('checkboxOwnershipChange').checked=true;<?php endif; ?>"<?php endif; ?>>
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
                        </div>
                    </div>

                    <div class="form-more-toggle">
                        <button type="button" id="contactMoreFieldsBtn" onclick="toggleFormFields('contactMoreFields', 'contactMoreFieldsBtn');">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            Show More Fields
                        </button>
                    </div>

                    <div id="contactMoreFields" class="form-grid form-additional-fields">
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Department:</label>
                                <div class="form-input">
                                    <select id="departmentSelect" name="department" onchange="if (this.value == 'edit') { listEditor('Departments', 'departmentSelect', 'departmentsCSV', false); this.value = '(none)'; } if (this.value == 'nullline') { this.value = '(none)'; }">
                                        <option value="edit">(Edit Departments)</option>
                                        <option value="nullline">-------------------------------</option>
                                        <?php if ($this->data['departmentID'] == 0): ?>
                                            <option value="(none)" selected="selected">(None)</option>
                                        <?php else: ?>
                                            <option value="(none)">(None)</option>
                                        <?php endif; ?>
                                        <?php foreach ($this->departmentsRS as $index => $department): ?>
                                            <option value="<?php $this->_($department['name']); ?>" <?php if ($department['name'] == $this->data['department']): ?>selected<?php endif; ?>><?php $this->_($department['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" id="departmentsCSV" name="departmentsCSV" value="<?php $this->_($this->departmentsString); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Reports To:</label>
                                <div class="form-input">
                                    <select id="reportsTo" name="reportsTo">
                                        <?php if ($this->data['reportsTo'] == -1): ?>
                                            <option value="(none)" selected="selected">(None)</option>
                                        <?php else: ?>
                                            <option value="(none)">(None)</option>
                                        <?php endif; ?>
                                        <?php foreach ($this->reportsToRS as $index => $contact): ?>
                                            <?php if ($contact['contactID'] != $this->contactID): ?>
                                                <option value="<?php $this->_($contact['contactID']); ?>" <?php if ($contact['contactID'] == $this->data['reportsTo']): ?>selected<?php endif; ?>><?php $this->_($contact['firstName'] . ' ' . $contact['lastName']); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <img src="images/indicator2.gif" alt="AJAX" id="ajaxIndicatorReportsTo" style="vertical-align: middle; visibility: hidden;" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Hot Contact:</label>
                                <div class="form-input">
                                    <input type="checkbox" id="isHot" name="isHot"<?php if ($this->data['isHotContact'] == 1): ?> checked<?php endif; ?> />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Left Company:</label>
                                <div class="form-input">
                                    <input type="checkbox" id="leftCompany" name="leftCompany"<?php if ($this->data['leftCompany'] == 1): ?> checked<?php endif; ?> onclick="if (document.getElementById('leftCompany').checked) document.getElementById('companyAssociatedLabel').style.display=''; else document.getElementById('companyAssociatedLabel').style.display='none';" />
                                </div>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">2nd E-Mail:</label>
                                <div class="form-input">
                                    <input type="text" name="email2" id="email2" value="<?php $this->_($this->data['email2']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Work Phone:</label>
                                <div class="form-input">
                                    <input type="text" name="phoneWork" id="phoneWork" value="<?php $this->_($this->data['phoneWork']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Other Phone:</label>
                                <div class="form-input">
                                    <input type="text" name="phoneOther" id="phoneOther" value="<?php $this->_($this->data['phoneOther']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Address:</label>
                                <div class="form-input">
                                    <textarea name="address" id="address"><?php $this->_($this->data['address']); ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">City:</label>
                                <div class="form-input">
                                    <input type="text" name="city" id="city" value="<?php $this->_($this->data['city']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">State:</label>
                                <div class="form-input">
                                    <input type="text" name="state" id="state" value="<?php $this->_($this->data['state']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Postal Code:</label>
                                <div class="form-input">
                                    <input type="text" name="zip" id="zip" value="<?php $this->_($this->data['zip']); ?>" style="max-width: 110px;" />
                                    <button type="button" class="form-btn form-btn-secondary" onclick="CityState_populate('zip', 'ajaxIndicator');">Lookup</button>
                                    <img src="images/indicator2.gif" alt="AJAX" id="ajaxIndicator" style="vertical-align: middle; visibility: hidden;" />
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

                    <div class="form-section">
                        <div class="form-section-title">Misc. Notes:</div>
                        <textarea name="notes" id="notes" rows="5"><?php $this->_($this->data['notes']); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="form-btn form-btn-primary" name="submit" id="submit">Save</button>
                        <button type="reset" class="form-btn form-btn-secondary" name="reset" id="reset">Reset</button>
                        <button type="button" class="form-btn form-btn-dark" name="back" id="back" onclick="javascript:goToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=contacts&amp;a=show&amp;contactID=<?php echo($this->contactID); ?>');">Back to Details</button>
                    </div>
                </div>
            </form>

            <script type="text/javascript">
                document.editContactForm.firstName.focus();
            </script>
        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
