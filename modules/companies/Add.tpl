<?php /* $Id: Add.tpl 3093 2007-09-24 21:09:45Z brian $ */ ?>
<?php TemplateUtility::printHeader('Companies', array('modules/companies/validator.js', 'js/sweetTitles.js', 'js/listEditor.js',  'js/addressParser.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <?php TemplateUtility::printBreadcrumb(
                CATSUtility::getIndexName().'?m=companies&amp;a=show',
                'Companies',
                'Add Company',
                'images/companies.gif'
            ); ?>

            <form name="addCompanyForm" id="addCompanyForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=companies&amp;a=add" method="post" onsubmit="return checkAddForm(document.addCompanyForm);" autocomplete="off">
                <input type="hidden" name="postback" id="postback" value="postback" />

                <div class="form-container">
                    <div class="form-header">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/></svg>
                        <h2>Add Company</h2>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">Quick-Fill From Pasted Address (optional)</div>
                        <?php $freeformTop = '<p class="freeformtop" style="margin: 0 0 8px 0;">Paste a full address below, then click Parse to auto-fill the fields.</p>'; ?>
                        <?php eval(Hooks::get('CANDIDATE_TEMPLATE_ABOVE_FREEFORM')); ?>
                        <?php echo($freeformTop); ?>
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <textarea tabindex="90" name="addressBlock" id="addressBlock" rows="3" style="flex: 1;"></textarea>
                            <button id="arrowButton" tabindex="91" type="button" class="form-btn form-btn-secondary" onclick="AddressParser_parse('addressBlock', 'company', 'addressParserIndicator', 'arrowButton'); document.addCompanyForm.name.focus();">Parse</button>
                            <img src="images/indicator2.gif" id="addressParserIndicator" alt="" style="visibility: hidden; margin-top: 8px;" height="16" width="16" />
                        </div>
                        <?php $freeformBottom = ''; ?>
                        <?php eval(Hooks::get('CANDIDATE_TEMPLATE_BELOW_FREEFORM')); ?>
                        <?php echo($freeformBottom); ?>
                    </div>

                    <div class="form-grid">
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Company Name:</label>
                                <div class="form-input">
                                    <input type="text" name="name" id="name" />
                                    <span class="required">*</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Primary Phone:</label>
                                <div class="form-input">
                                    <input type="text" name="phone1" id="phone1" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Address:</label>
                                <div class="form-input">
                                    <textarea name="address" id="address" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">City:</label>
                                <div class="form-input">
                                    <input type="text" name="city" id="city" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">State:</label>
                                <div class="form-input">
                                    <input type="text" name="state" id="state" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Postal Code:</label>
                                <div class="form-input">
                                    <input type="text" name="zip" id="zip" style="max-width: 110px;" />
                                    <button type="button" class="form-btn form-btn-secondary" onclick="CityState_populate('zip', 'ajaxIndicator');">Lookup</button>
                                    <img src="images/indicator2.gif" alt="AJAX" id="ajaxIndicator" style="vertical-align: middle; visibility: hidden;" />
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
                                <label class="form-label">Secondary Phone:</label>
                                <div class="form-input">
                                    <input type="text" name="phone2" id="phone2" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Fax Number:</label>
                                <div class="form-input">
                                    <input type="text" name="faxNumber" id="faxNumber" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Web Site:</label>
                                <div class="form-input">
                                    <input type="text" name="url" id="url" />
                                </div>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-row">
                                <label class="form-label">Departments:</label>
                                <div class="form-input">
                                    <select tabindex="3" id="departmentsSelect" name="departmentsSelect" onchange="if (this.value == 'edit') { listEditor('Departments', 'departmentsSelect', 'departmentsCSV'); } this.value = 'num';">
                                        <option value="edit">(Edit Departments)</option>
                                        <option value="num" selected="selected">No Departments</option>
                                        <option value="nullline">-------------------------------</option>
                                    </select>
                                    <input type="hidden" id="departmentsCSV" name="departmentsCSV" value="" />
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-label">Hot Company:</label>
                                <div class="form-input">
                                    <input type="checkbox" id="isHot" name="isHot" />
                                </div>
                            </div>
                        </div>
                        <?php if (count($this->extraFieldRS) > 0): ?>
                        <div class="form-column">
                            <?php for ($i = 0; $i < count($this->extraFieldRS); $i++): ?>
                            <div class="form-row">
                                <label class="form-label" id="extraFieldTd<?php echo($i); ?>"><?php $this->_($this->extraFieldRS[$i]['fieldName']); ?>:</label>
                                <div class="form-input" id="extraFieldData<?php echo($i); ?>">
                                    <?php echo($this->extraFieldRS[$i]['addHTML']); ?>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">Key Technologies:</div>
                        <input type="text" name="keyTechnologies" id="keyTechnologies" style="width: 100%;" />
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">Misc. Notes:</div>
                        <textarea name="notes" id="notes" rows="5"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="form-btn form-btn-primary">Add Company</button>
                        <button type="reset" class="form-btn form-btn-secondary">Reset</button>
                        <button type="button" class="form-btn form-btn-dark" onclick="javascript:goToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=companies&amp;a=show');">Back to Companies</button>
                    </div>
                </div>
            </form>

            <script type="text/javascript">
                document.addCompanyForm.name.focus();
            </script>
        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
