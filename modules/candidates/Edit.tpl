<?php /* $Id: Edit.tpl 3695 2007-11-26 22:01:04Z brian $ */ ?>
<?php TemplateUtility::printHeader('Candidates', array('modules/candidates/validator.js', 'js/sweetTitles.js', 'js/listEditor.js', 'js/doubleListEditor.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
    <script type="text/javascript">
        window.CATSUserDateFormat = '<?php echo($_SESSION['CATS']->isDateDMY() ? 'DD-MM-YY' : 'MM-DD-YY'); ?>';
    </script>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <?php TemplateUtility::printBreadcrumb(
                CATSUtility::getIndexName().'?m=candidates&amp;a=show&amp;candidateID='.$this->candidateID,
                $this->data['firstName'].' '.$this->data['lastName'],
                'Edit',
                'images/candidate.gif'
            ); ?>

            <form name="editCandidateForm" id="editCandidateForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=edit" method="post" onsubmit="return checkEditForm(document.editCandidateForm);" autocomplete="off">
                <input type="hidden" name="postback" id="postback" value="postback" />
                <input type="hidden" id="candidateID" name="candidateID" value="<?php $this->_($this->data['candidateID']); ?>" />

                <div class="form-container">
                    <div class="form-header">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <h2>Edit Candidate</h2>
                    </div>

                    <div class="form-grid">
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Active:</label>
                                <div class="form-input">
                                    <input type="checkbox" id="isActive" name="isActive"<?php if ($this->data['isActive'] == 1): ?> checked<?php endif; ?> />
                                    <img class="info-icon" title="Unchecking this box indicates the candidate is inactive, and will no longer display on the resume search results." src="images/information.gif" alt="" width="16" height="16" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">First Name:</label>
                                <div class="form-input">
                                    <input type="text" id="firstName" name="firstName" value="<?php $this->_($this->data['firstName']); ?>" />
                                    <span class="required">*</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Last Name:</label>
                                <div class="form-input">
                                    <input type="text" id="lastName" name="lastName" value="<?php $this->_($this->data['lastName']); ?>" />
                                    <span class="required">*</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">E-Mail:</label>
                                <div class="form-input">
                                    <input type="text" id="email1" name="email1" value="<?php $this->_($this->data['email1']); ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Cell Phone:</label>
                                <div class="form-input">
                                    <input type="text" id="phoneCell" name="phoneCell" value="<?php $this->_($this->data['phoneCell']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">City:</label>
                                <div class="form-input">
                                    <input type="text" id="city" name="city" value="<?php $this->_($this->data['city']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">State:</label>
                                <div class="form-input">
                                    <input type="text" id="state" name="state" value="<?php $this->_($this->data['state']); ?>" />
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
                        <button type="button" id="candidateMoreFieldsBtn" onclick="toggleFormFields('candidateMoreFields', 'candidateMoreFieldsBtn');">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            Show More Fields
                        </button>
                    </div>

                    <div id="candidateMoreFields" class="form-grid form-additional-fields">
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Middle Name:</label>
                                <div class="form-input">
                                    <input type="text" id="middleName" name="middleName" value="<?php $this->_($this->data['middleName']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">2nd E-Mail:</label>
                                <div class="form-input">
                                    <input type="text" id="email2" name="email2" value="<?php $this->_($this->data['email2']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Home Phone:</label>
                                <div class="form-input">
                                    <input type="text" id="phoneHome" name="phoneHome" value="<?php $this->_($this->data['phoneHome']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Work Phone:</label>
                                <div class="form-input">
                                    <input type="text" id="phoneWork" name="phoneWork" value="<?php $this->_($this->data['phoneWork']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Web Site:</label>
                                <div class="form-input">
                                    <input type="text" id="webSite" name="webSite" value="<?php $this->_($this->data['webSite']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Address:</label>
                                <div class="form-input">
                                    <textarea id="address" name="address"><?php $this->_($this->data['address']); ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Postal Code:</label>
                                <div class="form-input">
                                    <input type="text" id="zip" name="zip" value="<?php $this->_($this->data['zip']); ?>" style="max-width:110px;" />
                                    <button type="button" class="form-btn form-btn-secondary" onclick="CityState_populate('zip', 'ajaxIndicator');">Lookup</button>
                                    <img src="images/indicator2.gif" alt="AJAX" id="ajaxIndicator" style="vertical-align: middle; visibility: hidden;" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Best Time To Call:</label>
                                <div class="form-input">
                                    <input type="text" id="bestTimeToCall" name="bestTimeToCall" value="<?php $this->_($this->data['bestTimeToCall']); ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Hot Candidate:</label>
                                <div class="form-input">
                                    <input type="checkbox" id="isHot" name="isHot"<?php if ($this->data['isHot'] == 1): ?> checked<?php endif; ?> />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Source:</label>
                                <div class="form-input">
                                    <select id="sourceSelect" name="source" onchange="if (this.value == 'edit') { listEditor('Sources', 'sourceSelect', 'sourceCSV', false, ''); this.value = '(none)'; } if (this.value == 'nullline') { this.value = '(none)'; }">
                                        <option value="edit">(Edit Sources)</option>
                                        <option value="nullline">-------------------------------</option>
                                        <?php if ($this->sourceInRS == false): ?>
                                            <?php if ($this->data['source'] != '(none)'): ?>
                                                <option value="(none)">(None)</option>
                                            <?php endif; ?>
                                            <option value="<?php $this->_($this->data['source']); ?>" selected="selected"><?php $this->_($this->data['source']); ?></option>
                                        <?php else: ?>
                                            <option value="(none)">(None)</option>
                                        <?php endif; ?>
                                        <?php foreach ($this->sourcesRS AS $index => $source): ?>
                                            <option value="<?php $this->_($source['name']); ?>" <?php if ($source['name'] == $this->data['source']): ?>selected<?php endif; ?>><?php $this->_($source['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" id="sourceCSV" name="sourceCSV" value="<?php $this->_($this->sourcesString); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Can Relocate:</label>
                                <div class="form-input">
                                    <input type="checkbox" id="canRelocate" name="canRelocate"<?php if ($this->data['canRelocate'] == 1): ?> checked<?php endif; ?> />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Date Available:</label>
                                <div class="form-input">
<?php if (!empty($this->data['dateAvailable'])): ?>
                                    <script type="text/javascript">DateInput('dateAvailable', false, (typeof window.CATSUserDateFormat !== 'undefined' ? window.CATSUserDateFormat : 'MM-DD-YY'), '<?php echo($this->data['dateAvailableUser']); ?>', -1);</script>
<?php else: ?>
                                    <script type="text/javascript">DateInput('dateAvailable', false, (typeof window.CATSUserDateFormat !== 'undefined' ? window.CATSUserDateFormat : 'MM-DD-YY'), '', -1);</script>
<?php endif; ?>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Current Employer:</label>
                                <div class="form-input">
                                    <input type="text" id="currentEmployer" name="currentEmployer" value="<?php $this->_($this->data['currentEmployer']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Current Pay:</label>
                                <div class="form-input">
                                    <input type="text" name="currentPay" id="currentPay" value="<?php $this->_($this->data['currentPay']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Desired Pay:</label>
                                <div class="form-input">
                                    <input type="text" name="desiredPay" id="desiredPay" value="<?php $this->_($this->data['desiredPay']); ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Picture:</label>
                                <div class="form-input">
                                    <button type="button" class="form-btn form-btn-secondary" onclick="showPopWin('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addEditImage&amp;candidateID=<?php echo($this->candidateID); ?>', 400, 370, null); return false;">Edit Profile Picture</button>
                                </div>
                            </div>
                        </div>

                        <?php if ($this->EEOSettingsRS['enabled'] == 1 && $this->EEOSettingsRS['canSeeEEOInfo']): ?>
                        <div class="form-column">
                            <?php if ($this->EEOSettingsRS['genderTracking'] == 1): ?>
                            <div class="form-row">
                                <label class="form-label">Gender:</label>
                                <div class="form-input">
                                    <select id="gender" name="gender">
                                        <option value="">----</option>
                                        <option value="m" <?php if (strtolower($this->data['eeoGender']) == 'm') echo('selected'); ?>>Male</option>
                                        <option value="f" <?php if (strtolower($this->data['eeoGender']) == 'f') echo('selected'); ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($this->EEOSettingsRS['ethnicTracking'] == 1): ?>
                            <div class="form-row">
                                <label class="form-label">Ethnic Background:</label>
                                <div class="form-input">
                                    <select id="race" name="race">
                                        <option value="">----</option>
                                        <option value="1" <?php if ($this->data['eeoEthnicTypeID'] == 1) echo('selected'); ?>>American Indian</option>
                                        <option value="2" <?php if ($this->data['eeoEthnicTypeID'] == 2) echo('selected'); ?>>Asian or Pacific Islander</option>
                                        <option value="3" <?php if ($this->data['eeoEthnicTypeID'] == 3) echo('selected'); ?>>Hispanic or Latino</option>
                                        <option value="4" <?php if ($this->data['eeoEthnicTypeID'] == 4) echo('selected'); ?>>Non-Hispanic Black</option>
                                        <option value="5" <?php if ($this->data['eeoEthnicTypeID'] == 5) echo('selected'); ?>>Non-Hispanic White</option>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($this->EEOSettingsRS['veteranTracking'] == 1): ?>
                            <div class="form-row">
                                <label class="form-label">Veteran Status:</label>
                                <div class="form-input">
                                    <select id="veteran" name="veteran">
                                        <option value="">----</option>
                                        <option value="1" <?php if ($this->data['eeoVeteranTypeID'] == 1) echo('selected'); ?>>No</option>
                                        <option value="2" <?php if ($this->data['eeoVeteranTypeID'] == 2) echo('selected'); ?>>Eligible Veteran</option>
                                        <option value="3" <?php if ($this->data['eeoVeteranTypeID'] == 3) echo('selected'); ?>>Disabled Veteran</option>
                                        <option value="4" <?php if ($this->data['eeoVeteranTypeID'] == 4) echo('selected'); ?>>Eligible and Disabled</option>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($this->EEOSettingsRS['disabilityTracking'] == 1): ?>
                            <div class="form-row">
                                <label class="form-label">Disability Status:</label>
                                <div class="form-input">
                                    <select id="disability" name="disability">
                                        <option value="">----</option>
                                        <option value="No" <?php if ($this->data['eeoDisabilityStatus'] == 'No') echo('selected'); ?>>No</option>
                                        <option value="Yes" <?php if ($this->data['eeoDisabilityStatus'] == 'Yes') echo('selected'); ?>>Yes</option>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php elseif ($this->EEOSettingsRS['enabled'] == 1 && !$this->EEOSettingsRS['canSeeEEOInfo']): ?>
                        <div class="form-column">
                            <p style="color: var(--gray-500); font-size: 13px; margin: 0;">Editing EEO data is disabled.</p>
                        </div>
                        <?php endif; ?>

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
                        <div class="form-section-title">Key Skills:</div>
                        <input type="text" id="keySkills" name="keySkills" value="<?php $this->_($this->data['keySkills']); ?>" style="width: 100%;" />
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">Misc. Notes:</div>
                        <textarea id="notes" name="notes" rows="5"><?php $this->_($this->data['notes']); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="form-btn form-btn-primary" name="submit" id="submit">Save</button>
                        <button type="reset" class="form-btn form-btn-secondary" name="reset" id="reset" onclick="resetFormForeign();">Reset</button>
                        <button type="button" class="form-btn form-btn-dark" name="back" id="back" onclick="javascript:goToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;candidateID=<?php echo($this->candidateID); ?>');">Back to Details</button>
                    </div>
                </div>
            </form>

            <script type="text/javascript">
                document.editCandidateForm.firstName.focus();
            </script>
        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
