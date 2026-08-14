<?php
/**
* Candidate merge duplicates template
* @package OpenCATS
* @subpackage modules/candidates
* @copyright (C) OpenCats
* @license GNU/GPL, see license.txt
* OpenCATS is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License 2
* as published by the Free Software Foundation.
*/
 ?>
<?php TemplateUtility::printModalHeader('Candidates', array(), 'Select information to keep in merge duplicates'); ?>

<style>
    .merge-pair { display: flex; gap: var(--space-3); }
    .merge-pair .option-card { flex: 1; }
</style>

<div class="form-container">
    <?php if (!$this->isFinishedMode): ?>

        <div class="form-note">For each field below, choose whether to keep the value from the original candidate or the duplicate candidate.</div>

        <form id="chooseMergeInformation" name="chooseMergeInformationForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=mergeInfo" method="post">
            <input type="hidden" id="oldCandidateID" name="oldCandidateID" value="<?php echo($this->oldCandidateID); ?>" />
            <input type="hidden" id="newCandidateID" name="newCandidateID" value="<?php echo($this->newCandidateID); ?>" />

            <div class="form-section">
                <div class="form-section-title">First Name</div>
                <div class="merge-pair">
                    <label class="option-card">
                        <input type="radio" name="firstName" value="0" />
                        <div class="option-card-body">
                            <span class="option-card-description">Original</span>
                            <span class="option-card-title"><?php echo($this->rsOld['firstName']); ?></span>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="firstName" value="1" checked="checked" />
                        <div class="option-card-body">
                            <span class="option-card-description">Duplicate</span>
                            <span class="option-card-title"><?php echo($this->rsNew['firstName']); ?></span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Middle Name</div>
                <div class="merge-pair">
                    <label class="option-card">
                        <input type="radio" name="middleName" value="0" />
                        <div class="option-card-body">
                            <span class="option-card-description">Original</span>
                            <span class="option-card-title"><?php echo($this->rsOld['middleName']); ?></span>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="middleName" value="1" checked="checked" />
                        <div class="option-card-body">
                            <span class="option-card-description">Duplicate</span>
                            <span class="option-card-title"><?php echo($this->rsNew['middleName']); ?></span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Last Name</div>
                <div class="merge-pair">
                    <label class="option-card">
                        <input type="radio" name="lastName" value="0" />
                        <div class="option-card-body">
                            <span class="option-card-description">Original</span>
                            <span class="option-card-title"><?php echo($this->rsOld['lastName']); ?></span>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="lastName" value="1" checked="checked" />
                        <div class="option-card-body">
                            <span class="option-card-description">Duplicate</span>
                            <span class="option-card-title"><?php echo($this->rsNew['lastName']); ?></span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">E-mails (max. 2)</div>
                <div class="merge-pair">
                    <label class="option-card">
                        <input type="checkbox" name="email[]" value="<?php echo ($this->rsOld['email1'] == '') ?  '' : ($this->rsOld['email1']); ?>" onclick="return keepCount('email')" />
                        <div class="option-card-body">
                            <span class="option-card-description">Original</span>
                            <span class="option-card-title"><?php echo($this->rsOld['email1'] == '') ?  '(none)' : ($this->rsOld['email1']);  ?></span>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="checkbox" name="email[]" value="<?php echo ($this->rsNew['email1'] == '') ?  '' : ($this->rsNew['email1']); ?>" onclick="return keepCount('email')" checked="checked" />
                        <div class="option-card-body">
                            <span class="option-card-description">Duplicate</span>
                            <span class="option-card-title"><?php echo($this->rsNew['email1'] == '') ?  '(none)' : ($this->rsNew['email1']);  ?></span>
                        </div>
                    </label>
                </div>
                <div class="merge-pair" style="margin-top: var(--space-2);">
                    <label class="option-card">
                        <input type="checkbox" name="email[]" value="<?php echo ($this->rsOld['email2'] == '') ?  '' : ($this->rsOld['email2']); ?>" onclick="return keepCount('email')" />
                        <div class="option-card-body">
                            <span class="option-card-description">Original</span>
                            <span class="option-card-title"><?php echo($this->rsOld['email2'] == '') ?  '(none)' : ($this->rsOld['email2']); ?></span>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="checkbox" name="email[]" value="<?php echo ($this->rsNew['email2'] == '') ?  '' : ($this->rsNew['email2']); ?>" onclick="return keepCount('email')" checked="checked" />
                        <div class="option-card-body">
                            <span class="option-card-description">Duplicate</span>
                            <span class="option-card-title"><?php echo($this->rsNew['email2'] == '') ?  '(none)' : ($this->rsNew['email2']); ?></span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Cell Phone</div>
                <div class="merge-pair">
                    <label class="option-card">
                        <input type="radio" name="phoneCell" value="0" />
                        <div class="option-card-body">
                            <span class="option-card-description">Original</span>
                            <span class="option-card-title"><?php echo($this->rsOld['phoneCell'] == '') ? '(none)' : ($this->rsOld['phoneCell']); ?></span>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="phoneCell" value="1" checked="checked" />
                        <div class="option-card-body">
                            <span class="option-card-description">Duplicate</span>
                            <span class="option-card-title"><?php echo($this->rsNew['phoneCell'] == '') ? '(none)' : ($this->rsNew['phoneCell']); ?></span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Home Phone</div>
                <div class="merge-pair">
                    <label class="option-card">
                        <input type="radio" name="phoneHome" value="0" />
                        <div class="option-card-body">
                            <span class="option-card-description">Original</span>
                            <span class="option-card-title"><?php echo($this->rsOld['phoneHome'] == '') ? '(none)' : ($this->rsOld['phoneHome']); ?></span>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="phoneHome" value="1" checked="checked" />
                        <div class="option-card-body">
                            <span class="option-card-description">Duplicate</span>
                            <span class="option-card-title"><?php echo($this->rsNew['phoneHome'] == '') ? '(none)' : ($this->rsNew['phoneHome']); ?></span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Work Phone</div>
                <div class="merge-pair">
                    <label class="option-card">
                        <input type="radio" name="phoneWork" value="0" />
                        <div class="option-card-body">
                            <span class="option-card-description">Original</span>
                            <span class="option-card-title"><?php echo($this->rsOld['phoneWork'] == '') ? '(none)' : ($this->rsOld['phoneWork']); ?></span>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="phoneWork" value="1" checked="checked" />
                        <div class="option-card-body">
                            <span class="option-card-description">Duplicate</span>
                            <span class="option-card-title"><?php echo($this->rsNew['phoneWork'] == '') ? '(none)' : ($this->rsNew['phoneWork']); ?></span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Website</div>
                <div class="merge-pair">
                    <label class="option-card">
                        <input type="radio" name="website" value="0" />
                        <div class="option-card-body">
                            <span class="option-card-description">Original</span>
                            <span class="option-card-title"><?php echo($this->rsOld['webSite'] == '') ? '(none)' : ($this->rsOld['webSite']); ?></span>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="website" value="1" checked="checked" />
                        <div class="option-card-body">
                            <span class="option-card-description">Duplicate</span>
                            <span class="option-card-title"><?php echo($this->rsNew['webSite'] == '') ? '(none)' : ($this->rsNew['webSite']); ?></span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Address</div>
                <div class="merge-pair">
                    <label class="option-card">
                        <input type="radio" name="address" value="0" />
                        <div class="option-card-body">
                            <span class="option-card-description">Original</span>
                            <?php if($this->rsOld['address'] == "" && $this->rsOld['city'] == "" && $this->rsOld['state'] == "" && $this->rsOld['zip'] == ""): ?>
                                <span class="option-card-title">(none)</span>
                            <?php else: ?>
                                <span class="option-card-title"><?php echo($this->rsOld['address'].'<br/>'.$this->rsOld['city']." ".$this->rsOld['zip'].'<br/>'.$this->rsOld['state']); ?></span>
                            <?php endif; ?>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="address" value="1" checked="checked" />
                        <div class="option-card-body">
                            <span class="option-card-description">Duplicate</span>
                            <?php if($this->rsNew['address'] == "" && $this->rsNew['city'] == "" && $this->rsNew['state'] == "" && $this->rsNew['zip'] == ""): ?>
                                <span class="option-card-title">(none)</span>
                            <?php else: ?>
                                <span class="option-card-title"><?php echo($this->rsNew['address'].'<br/>'.$this->rsNew['city']." ".$this->rsNew['zip'].'<br/>'.$this->rsNew['state']); ?></span>
                            <?php endif; ?>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="form-btn form-btn-primary" id="mergeInfo" name="mergeInfo">Merge</button>
            </div>
        </form>
    <?php else: ?>
        <div class="form-section">
            <p>These candidates have been successfully merged.</p>
        </div>
        <div class="form-actions">
            <button type="button" class="form-btn form-btn-primary" onclick="parentHidePopWinRefresh();">Close</button>
        </div>
    <?php endif; ?>
</div>

    </body>
</html>
<script>
    function keepCount()
    {
        var checkboxes = document.getElementsByName('email[]');
        var count = 0;
        for(var i = 0; i < checkboxes.length; ++i)
        {
            if(checkboxes[i].checked)
                {
                    count++;
                }
        }
        if(count > 2){
            return false;
        }
        else{
            return true;
        }
    }
</script>
