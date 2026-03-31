<?php /* Meeting Integration Settings */ ?>
<?php TemplateUtility::printHeader('Settings', array('modules/settings/validator.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<style>
.meeting-platform-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.meeting-platform-card.active {
    border-color: #4CAF50;
    background: #f8fff8;
}
.meeting-platform-card h3 {
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
}
.platform-status {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
    margin-left: 10px;
}
.platform-status.configured {
    background: #e8f5e9;
    color: #2e7d32;
}
.platform-status.not-configured {
    background: #fff3e0;
    color: #e65100;
}
.platform-status.authorized {
    background: #e3f2fd;
    color: #1565c0;
}
.credential-form {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 6px;
    margin-top: 15px;
}
.credential-form label {
    display: block;
    font-weight: 500;
    margin-bottom: 4px;
    font-size: 13px;
    color: #333;
}
.credential-form input[type="text"],
.credential-form input[type="password"] {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 13px;
    margin-bottom: 12px;
    box-sizing: border-box;
}
.credential-form input:focus {
    border-color: #2196F3;
    outline: none;
}
.credential-form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}
.credential-form .form-group {
    margin-bottom: 12px;
}
.btn-save {
    background: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
}
.btn-save:hover {
    background: #388E3C;
}
.btn-test {
    background: #2196F3;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
    margin-right: 10px;
}
.btn-test:hover {
    background: #1976D2;
}
.btn-authorize {
    background: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    font-size: 13px;
}
.btn-authorize:hover {
    background: #388E3C;
    color: white;
}
.btn-revoke {
    background: #f44336;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
}
.btn-revoke:hover {
    background: #d32f2f;
}
.btn-clear {
    background: #ff9800;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
    margin-left: 10px;
}
.btn-clear:hover {
    background: #f57c00;
}
.test-result {
    margin-top: 10px;
    padding: 10px;
    border-radius: 4px;
    display: none;
    font-size: 13px;
}
.test-result.success {
    background: #e8f5e9;
    color: #2e7d32;
    display: block;
}
.test-result.error {
    background: #ffebee;
    color: #c62828;
    display: block;
}
.default-platform-selector {
    background: #e3f2fd;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}
.default-platform-selector select {
    padding: 10px 15px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    min-width: 250px;
}
.credential-hint {
    font-size: 11px;
    color: #666;
    margin-top: -8px;
    margin-bottom: 12px;
}
.toggle-credentials {
    background: none;
    border: 1px solid #2196F3;
    color: #2196F3;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    margin-top: 10px;
}
.toggle-credentials:hover {
    background: #e3f2fd;
}
.masked-value {
    font-family: monospace;
    color: #666;
    font-size: 12px;
}
</style>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <table>
                <tr>
                    <td width="3%">
                        <img src="images/settings.gif" width="24" height="24" border="0" alt="Settings" style="margin-top: 3px;" />&nbsp;
                    </td>
                    <td><h2>Settings: Video Meeting Integration</h2></td>
                </tr>
            </table>

            <p>Configure video meeting platforms for automatic meeting creation when scheduling interviews, calls, and meetings. Enter your API credentials below - they will be securely encrypted and stored in the database.</p>

            <?php if (isset($this->message) && !empty($this->message)): ?>
                <div class="test-result <?php echo $this->messageType; ?>" style="display: block; margin-bottom: 20px;">
                    <?php $this->_($this->message); ?>
                </div>
            <?php endif; ?>

            <!-- Default Platform Selection -->
            <div class="default-platform-selector">
                <form method="post" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=onMeetingSettings">
                    <input type="hidden" name="action" value="setDefaultPlatform" />
                    <label for="defaultPlatform"><strong>Default Meeting Platform:</strong></label>
                    <select name="defaultPlatform" id="defaultPlatform" onchange="this.form.submit()">
                        <option value="none" <?php if ($this->defaultPlatform == 'none') echo 'selected'; ?>>No Video Meeting</option>
                        <option value="teams" <?php if ($this->defaultPlatform == 'teams') echo 'selected'; ?> <?php if (!$this->teamsConfigured) echo 'disabled'; ?>>
                            Microsoft Teams <?php if (!$this->teamsConfigured) echo '(Not Configured)'; ?>
                        </option>
                        <option value="zoom" <?php if ($this->defaultPlatform == 'zoom') echo 'selected'; ?> <?php if (!$this->zoomConfigured) echo 'disabled'; ?>>
                            Zoom <?php if (!$this->zoomConfigured) echo '(Not Configured)'; ?>
                        </option>
                        <option value="google_meet" <?php if ($this->defaultPlatform == 'google_meet') echo 'selected'; ?> <?php if (!$this->googleMeetAuthorized) echo 'disabled'; ?>>
                            Google Meet <?php if (!$this->googleMeetAuthorized) echo '(Not Authorized)'; ?>
                        </option>
                    </select>
                    <p style="margin-top: 10px; color: #666; font-size: 13px;">
                        When scheduling an interview or meeting, this platform will be used automatically to create a video meeting link.
                    </p>
                </form>
            </div>

            <!-- Microsoft Teams -->
            <div class="meeting-platform-card <?php if ($this->defaultPlatform == 'teams') echo 'active'; ?>">
                <h3>
                    Microsoft Teams
                    <?php if ($this->teamsConfigured): ?>
                        <span class="platform-status configured">Configured</span>
                    <?php else: ?>
                        <span class="platform-status not-configured">Not Configured</span>
                    <?php endif; ?>
                </h3>
                
                <p>Automatically create Microsoft Teams meetings for scheduled interviews and calls.</p>
                
                <form method="post" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=onMeetingSettings" class="credential-form">
                    <input type="hidden" name="action" value="saveTeamsCredentials" />
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="teams_client_id">Client ID *</label>
                            <input type="text" name="teams_client_id" id="teams_client_id" 
                                   value="<?php echo htmlspecialchars($this->teamsClientId); ?>" 
                                   placeholder="Application (client) ID from Azure" />
                            <div class="credential-hint">From Azure AD App Registration</div>
                        </div>
                        <div class="form-group">
                            <label for="teams_tenant_id">Tenant ID *</label>
                            <input type="text" name="teams_tenant_id" id="teams_tenant_id" 
                                   value="<?php echo htmlspecialchars($this->teamsTenantId); ?>" 
                                   placeholder="Directory (tenant) ID" />
                            <div class="credential-hint">From Azure AD App Registration</div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="teams_client_secret">Client Secret *</label>
                            <input type="password" name="teams_client_secret" id="teams_client_secret" 
                                   placeholder="<?php echo $this->teamsConfigured ? '••••••••••••' : 'Enter client secret'; ?>" />
                            <div class="credential-hint">Leave blank to keep existing secret</div>
                        </div>
                        <div class="form-group">
                            <label for="teams_user_id">User Email (Organizer)</label>
                            <input type="text" name="teams_user_id" id="teams_user_id" 
                                   value="<?php echo htmlspecialchars($this->teamsUserId); ?>" 
                                   placeholder="meetings@yourdomain.com" />
                            <div class="credential-hint">Email of meeting organizer</div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-save">Save Teams Credentials</button>
                    <?php if ($this->teamsConfigured): ?>
                        <button type="button" class="btn-test" onclick="testConnection('teams')">Test Connection</button>
                        <button type="submit" name="action" value="clearTeamsCredentials" class="btn-clear" onclick="return confirm('Are you sure you want to remove Teams credentials?')">Clear Credentials</button>
                    <?php endif; ?>
                </form>
                <div id="teams-test-result" class="test-result"></div>
            </div>

            <!-- Zoom -->
            <div class="meeting-platform-card <?php if ($this->defaultPlatform == 'zoom') echo 'active'; ?>">
                <h3>
                    Zoom
                    <?php if ($this->zoomConfigured): ?>
                        <span class="platform-status configured">Configured</span>
                    <?php else: ?>
                        <span class="platform-status not-configured">Not Configured</span>
                    <?php endif; ?>
                </h3>
                
                <p>Automatically create Zoom meetings for scheduled interviews and calls.</p>
                
                <form method="post" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=onMeetingSettings" class="credential-form">
                    <input type="hidden" name="action" value="saveZoomCredentials" />
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="zoom_account_id">Account ID *</label>
                            <input type="text" name="zoom_account_id" id="zoom_account_id" 
                                   value="<?php echo htmlspecialchars($this->zoomAccountId); ?>" 
                                   placeholder="Zoom Account ID" />
                            <div class="credential-hint">From Zoom Server-to-Server OAuth App</div>
                        </div>
                        <div class="form-group">
                            <label for="zoom_client_id">Client ID *</label>
                            <input type="text" name="zoom_client_id" id="zoom_client_id" 
                                   value="<?php echo htmlspecialchars($this->zoomClientId); ?>" 
                                   placeholder="Zoom Client ID" />
                            <div class="credential-hint">From Zoom Server-to-Server OAuth App</div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="zoom_client_secret">Client Secret *</label>
                            <input type="password" name="zoom_client_secret" id="zoom_client_secret" 
                                   placeholder="<?php echo $this->zoomConfigured ? '••••••••••••' : 'Enter client secret'; ?>" />
                            <div class="credential-hint">Leave blank to keep existing secret</div>
                        </div>
                        <div class="form-group">
                            <label for="zoom_user_id">User Email (Optional)</label>
                            <input type="text" name="zoom_user_id" id="zoom_user_id" 
                                   value="<?php echo htmlspecialchars($this->zoomUserId); ?>" 
                                   placeholder="meetings@yourdomain.com or 'me'" />
                            <div class="credential-hint">Leave blank to use 'me'</div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-save">Save Zoom Credentials</button>
                    <?php if ($this->zoomConfigured): ?>
                        <button type="button" class="btn-test" onclick="testConnection('zoom')">Test Connection</button>
                        <button type="submit" name="action" value="clearZoomCredentials" class="btn-clear" onclick="return confirm('Are you sure you want to remove Zoom credentials?')">Clear Credentials</button>
                    <?php endif; ?>
                </form>
                <div id="zoom-test-result" class="test-result"></div>
            </div>

            <!-- Google Meet -->
            <div class="meeting-platform-card <?php if ($this->defaultPlatform == 'google_meet') echo 'active'; ?>">
                <h3>
                    Google Meet
                    <?php if ($this->googleMeetAuthorized): ?>
                        <span class="platform-status authorized">Authorized</span>
                    <?php elseif ($this->googleMeetConfigured): ?>
                        <span class="platform-status not-configured">Needs Authorization</span>
                    <?php else: ?>
                        <span class="platform-status not-configured">Not Configured</span>
                    <?php endif; ?>
                </h3>
                
                <p>Automatically create Google Meet meetings via Google Calendar for scheduled interviews and calls.</p>
                
                <form method="post" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=onMeetingSettings" class="credential-form">
                    <input type="hidden" name="action" value="saveGoogleCredentials" />
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="google_client_id">Client ID *</label>
                            <input type="text" name="google_client_id" id="google_client_id" 
                                   value="<?php echo htmlspecialchars($this->googleClientId); ?>" 
                                   placeholder="xxxxx.apps.googleusercontent.com" />
                            <div class="credential-hint">From Google Cloud Console OAuth Credentials</div>
                        </div>
                        <div class="form-group">
                            <label for="google_client_secret">Client Secret *</label>
                            <input type="password" name="google_client_secret" id="google_client_secret" 
                                   placeholder="<?php echo $this->googleMeetConfigured ? '••••••••••••' : 'Enter client secret'; ?>" />
                            <div class="credential-hint">Leave blank to keep existing secret</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Redirect URI (Add this to Google Cloud Console)</label>
                        <input type="text" readonly value="<?php echo htmlspecialchars($this->googleRedirectUri); ?>" 
                               style="background: #f5f5f5; cursor: text;" onclick="this.select();" />
                        <div class="credential-hint">Copy this URL and add it as an Authorized Redirect URI in Google Cloud Console</div>
                    </div>
                    
                    <button type="submit" class="btn-save">Save Google Credentials</button>
                    <?php if ($this->googleMeetConfigured): ?>
                        <button type="submit" name="action" value="clearGoogleCredentials" class="btn-clear" onclick="return confirm('Are you sure you want to remove Google credentials?')">Clear Credentials</button>
                    <?php endif; ?>
                </form>
                
                <?php if ($this->googleMeetConfigured && !$this->googleMeetAuthorized): ?>
                    <div style="margin-top: 15px; padding: 15px; background: #fff3e0; border-radius: 4px;">
                        <strong>Step 2: Authorize Access</strong>
                        <p style="margin: 10px 0; font-size: 13px;">Credentials saved. Now authorize the application to access your Google Calendar:</p>
                        <a href="<?php echo htmlspecialchars($this->googleAuthUrl); ?>" class="btn-authorize">
                            Authorize Google Meet
                        </a>
                    </div>
                <?php elseif ($this->googleMeetAuthorized): ?>
                    <div style="margin-top: 15px;">
                        <button type="button" class="btn-test" onclick="testConnection('google_meet')">Test Connection</button>
                        <form method="post" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=onMeetingSettings" style="display: inline;">
                            <input type="hidden" name="action" value="revokeGoogle" />
                            <button type="submit" class="btn-revoke" onclick="return confirm('Are you sure you want to revoke Google Meet authorization?')">Revoke Authorization</button>
                        </form>
                    </div>
                <?php endif; ?>
                <div id="google_meet-test-result" class="test-result"></div>
            </div>

            <p style="margin-top: 30px;">
                <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=administration">
                    &laquo; Back to Administration
                </a>
            </p>
        </div>
    </div>

<script type="text/javascript">
function testConnection(platform) {
    var resultDiv = document.getElementById(platform + '-test-result');
    resultDiv.className = 'test-result';
    resultDiv.innerHTML = 'Testing connection...';
    resultDiv.style.display = 'block';
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo(CATSUtility::getIndexName()); ?>?m=settings&a=testMeetingConnection', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    resultDiv.className = 'test-result success';
                    resultDiv.innerHTML = '&#10004; ' + response.message;
                } else {
                    resultDiv.className = 'test-result error';
                    resultDiv.innerHTML = '&#10008; ' + response.message;
                }
            } catch (e) {
                resultDiv.className = 'test-result error';
                resultDiv.innerHTML = '&#10008; Error testing connection';
            }
        }
    };
    xhr.send('platform=' + encodeURIComponent(platform));
}
</script>

<?php TemplateUtility::printFooter(); ?>
