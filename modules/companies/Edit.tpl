<?php /* $Id: Edit.tpl 3093 2007-09-24 21:09:45Z brian $ */ ?>
<?php TemplateUtility::printHeader('Companies', array('modules/companies/validator.js', 'js/sweetTitles.js', 'js/listEditor.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <?php TemplateUtility::printBreadcrumb(
                CATSUtility::getIndexName().'?m=companies&amp;a=show&amp;companyID='.$this->companyID,
                $this->data['name'],
                'Edit',
                'images/companies.gif'
            ); ?>

            <form name="editCompanyForm" id="editCompanyForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=companies&amp;a=edit" method="post" onsubmit="return checkEditForm(document.editCompanyForm);" autocomplete="off">
                <input type="hidden" name="postback" id="postback" value="postback" />
                <input type="hidden" id="companyID" name="companyID" value="<?php echo($this->companyID); ?>" />

                <div class="form-container">
                    <div class="form-header">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/></svg>
                        <h2>Edit Company</h2>
                    </div>

                    <div class="form-grid">
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Company Name:</label>
                                <div class="form-input">
                                    <input type="text" name="name" id="name" value="<?php $this->_($this->data['name']); ?>" />
                                    <span class="required">*</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Primary Phone:</label>
                                <div class="form-input">
                                    <input type="text" name="phone1" id="phone1" value="<?php $this->_($this->data['phone1']); ?>" onkeydown="document.getElementById('changeAddress').style.display='';" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Address:</label>
                                <div class="form-input">
                                    <textarea name="address" id="address" onkeydown="document.getElementById('changeAddress').style.display='';"><?php $this->_($this->data['address']); ?></textarea>
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
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">City:</label>
                                <div class="form-input">
                                    <input type="text" name="city" id="city" value="<?php $this->_($this->data['city']); ?>" onkeydown="document.getElementById('changeAddress').style.display='';" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">State:</label>
                                <div class="form-input">
                                    <input type="text" name="state" id="state" value="<?php $this->_($this->data['state']); ?>" onkeydown="document.getElementById('changeAddress').style.display='';" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Postal Code:</label>
                                <div class="form-input">
                                    <input type="text" name="zip" id="zip" value="<?php $this->_($this->data['zip']); ?>" style="max-width: 110px;" onkeydown="document.getElementById('changeAddress').style.display='';" />
                                    <button type="button" class="form-btn form-btn-secondary" onclick="CityState_populate('zip', 'ajaxIndicator');">Lookup</button>
                                    <img src="images/indicator2.gif" alt="AJAX" id="ajaxIndicator" style="vertical-align: middle; visibility: hidden;" />
                                </div>
                            </div>
                            <div class="form-row" id="changeAddress" style="display:none;">
                                <label class="form-label"></label>
                                <div class="form-input" style="flex-direction: column; align-items: flex-start; gap: 6px;">
                                    <span style="font-size: 12px; color: var(--gray-600);">Update all contacts' addresses to match this company address?</span>
                                    <select id="updateContacts" name="updateContacts">
                                        <option value="yes">Yes, synchronize addresses.</option>
                                        <option value="no" selected="selected">No, leave addresses unmodified.</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-more-toggle">
                        <button type="button" id="companyMoreFieldsBtn" onclick="toggleFormFields('companyMoreFields', 'companyMoreFieldsBtn');">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            Show More Fields
                        </button>
                    </div>

                    <div id="companyMoreFields" class="form-grid form-additional-fields">
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Billing Contact:</label>
                                <div class="form-input">
                                    <select tabindex="3" id="billingContact" name="billingContact">
                                        <option value="-1">None</option>
                                        <?php foreach ($this->contactsRS as $rowNumber => $contactsData): ?>
                                            <option <?php if ($this->data['billingContact'] == $contactsData['contactID']): ?>selected<?php endif; ?> value="<?php $this->_($contactsData['contactID']) ?>"><?php $this->_($contactsData['firstName']) ?> <?php $this->_($contactsData['lastName']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Departments:</label>
                                <div class="form-input">
                                    <select tabindex="3" id="departmentsSelect" name="departmentsSelect" onchange="if (this.value == 'edit') { listEditor('Departments', 'departmentsSelect', 'departmentsCSV'); } this.value = 'num';">
                                        <option value="edit">(Edit Departments)</option>
                                        <option value="num" selected="selected"><?php echo(count($this->departmentsRS)); ?> Departments</option>
                                        <option value="nullline">-------------------------------</option>
                                        <?php foreach ($this->departmentsRS AS $index => $department): ?>
                                            <option value="<?php $this->_($department['name']); ?>"><?php $this->_($department['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" id="departmentsCSV" name="departmentsCSV" value="<?php $this->_($this->departmentsString); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Web Site:</label>
                                <div class="form-input">
                                    <input type="text" name="url" id="url" value="<?php $this->_($this->data['url']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Hot Company:</label>
                                <div class="form-input">
                                    <input type="checkbox" id="isHot" name="isHot"<?php if ($this->data['isHot'] == 1): ?> checked<?php endif; ?> />
                                </div>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Secondary Phone:</label>
                                <div class="form-input">
                                    <input type="text" name="phone2" id="phone2" value="<?php $this->_($this->data['phone2']); ?>" onkeydown="document.getElementById('changeAddress').style.display='';" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Fax Number:</label>
                                <div class="form-input">
                                    <input type="text" name="faxNumber" id="faxNumber" value="<?php $this->_($this->data['faxNumber']); ?>" onkeydown="document.getElementById('changeAddress').style.display='';" />
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
                        <div class="form-section-title">Key Technologies:</div>
                        <input type="text" id="keyTechnologies" name="keyTechnologies" value="<?php $this->_($this->data['keyTechnologies']); ?>" style="width: 100%;" />
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">Misc. Notes:</div>
                        <textarea name="notes" id="notes" rows="5"><?php $this->_($this->data['notes']); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="form-btn form-btn-primary" name="submit" id="submit">Save</button>
                        <button type="reset" class="form-btn form-btn-secondary" name="reset" id="reset">Reset</button>
                        <button type="button" class="form-btn form-btn-dark" name="back" id="back" onclick="javascript:goToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=companies&amp;a=show&amp;companyID=<?php echo($this->companyID); ?>');">Back to Details</button>
                    </div>
                </div>
            </form>

            <script type="text/javascript">
                document.editCompanyForm.name.focus();
            </script>
        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
