<?php /* $Id: Show.tpl 3814 2007-12-06 17:54:28Z brian $ */
include_once('./vendor/autoload.php');
use OpenCATS\UI\CandidateQuickActionMenu;
use OpenCATS\UI\CandidateDuplicateQuickActionMenu;
?>
<?php if ($this->isPopup): ?>
    <?php TemplateUtility::printHeader('Candidate - '.$this->data['firstName'].' '.$this->data['lastName'], array( 'js/activity.js', 'js/sorttable.js', 'js/match.js', 'js/lib.js', 'js/pipeline.js', 'modules/candidates/quickAction-candidates.js')); ?>
<?php else: ?>
    <?php TemplateUtility::printHeader('Candidate - '.$this->data['firstName'].' '.$this->data['lastName'], array( 'js/activity.js', 'js/sorttable.js', 'js/match.js', 'js/lib.js', 'js/pipeline.js', 'modules/candidates/quickAction-candidates.js', 'modules/candidates/quickAction-duplicates.js')); ?>
    
    <?php TemplateUtility::printHeaderBlock(); ?>
    <?php TemplateUtility::printTabs($this->active); ?>
        <div id="main">
            <?php TemplateUtility::printQuickSearch(); ?>
<?php endif; ?>

        <script type="text/javascript">
            window.CATSUserDateFormat = '<?php echo($_SESSION['CATS']->isDateDMY() ? 'DD-MM-YY' : 'MM-DD-YY'); ?>';
            
            function showTab(tabName) {
                // Hide all tab contents
                document.getElementById('resumeTabContent').style.display = 'none';
                document.getElementById('feedbackTabContent').style.display = 'none';
                document.getElementById('emailTabContent').style.display = 'none';
                
                // Remove active class from all tabs
                document.getElementById('resumeTab').classList.remove('active');
                document.getElementById('feedbackTab').classList.remove('active');
                document.getElementById('emailTab').classList.remove('active');
                
                // Show selected tab content
                document.getElementById(tabName + 'TabContent').style.display = 'block';
                document.getElementById(tabName + 'Tab').classList.add('active');
            }
            
            function saveFeedback() {
                // TODO: Implement feedback save functionality
                alert('Feedback save functionality will be implemented.');
            }
            
            function showEmailDetail(index) {
                // Remove active class from all email items
                var emailItems = document.querySelectorAll('.email-list-item');
                for (var i = 0; i < emailItems.length; i++) {
                    emailItems[i].classList.remove('active');
                }
                // Add active class to selected item
                emailItems[index].classList.add('active');
                // TODO: Update email detail column with selected email
            }
        </script>

        <style type="text/css">
            * {
                box-sizing: border-box;
            }
            /* Override main.css styles that cause spacing issues */
            body {
                padding: 8px 0 8px 0 !important;
            }
            #main {
                padding: 0 !important;
                margin: 0 !important;
            }
            #contents {
                position: relative;
                background: #f5f5f5 !important;
                min-height: 100vh;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }
            .candidate-page-wrapper {
                background: #f5f5f5;
                min-height: 100vh;
                padding: 0;
                position: relative;
                width: 100%;
                margin: 0;
            }
            .status-indicator-top-right {
                position: absolute;
                top: 20px;
                right: 20px;
                font-weight: bold;
                font-size: 14px;
                z-index: 1000;
                background: #fff;
                padding: 10px 15px;
                border-radius: 4px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .candidate-main-panel {
                background: #fff;
                border-radius: 0;
                box-shadow: none;
                margin: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
                display: flex;
                flex-direction: column;
                padding: 0 !important;
            }
            .candidate-header-section {
                padding: 25px 30px 20px 30px;
                border-bottom: 1px solid #e0e0e0;
                position: relative;
                background: #fff;
            }
            .candidate-header-section h1 {
                font-size: 32px;
                font-weight: bold;
                color: #333;
                margin: 0 0 20px 0;
                padding: 0;
                line-height: 1.2;
            }
            .status-badge {
                padding: 6px 12px;
                border-radius: 4px;
                font-size: 13px;
                display: inline-block;
            }
            .status-selected {
                background: #28a745;
                color: #fff;
            }
            .status-rejected {
                background: #dc3545;
                color: #fff;
            }
            .status-none {
                background: #6c757d;
                color: #fff;
            }
            .candidate-tabs {
                display: flex;
                list-style: none;
                margin: 0;
                padding: 0;
                border-bottom: 2px solid #e0e0e0;
            }
            .candidate-tabs li {
                margin: 0;
                padding: 0;
            }
            .candidate-tabs li a {
                display: block;
                padding: 12px 25px;
                text-decoration: none;
                color: #666;
                font-weight: 500;
                border-bottom: 3px solid transparent;
                cursor: pointer;
                transition: all 0.3s;
            }
            .candidate-tabs li.active a {
                color: #28a745;
                border-bottom: 3px solid #28a745;
            }
            .candidate-tabs li a:hover {
                color: #28a745;
                background: #f8f9fa;
            }
            .tab-content-container {
                display: flex !important;
                flex-direction: row !important;
                min-height: 600px;
                align-items: flex-start;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                gap: 0 !important;
                border: none !important;
            }
            .tab-left-panel {
                flex: 0 0 60% !important;
                width: 60% !important;
                max-width: 60% !important;
                min-width: 60% !important;
                padding: 25px 30px;
                border-right: 1px solid #e0e0e0;
                background: #fafafa;
                overflow-y: auto;
                max-height: calc(100vh - 250px);
                min-height: 600px;
                margin: 0 !important;
                float: none !important;
            }
            .tab-right-panel {
                flex: 0 0 40% !important;
                width: 40% !important;
                max-width: 40% !important;
                min-width: 40% !important;
                padding: 25px 30px;
                background: #fff;
                overflow-y: auto;
                max-height: calc(100vh - 250px);
                min-height: 600px;
                position: sticky;
                top: 0;
                align-self: flex-start;
                margin: 0 !important;
                float: none !important;
            }
            .status-badge-panel {
                background: #f0f7ff;
                padding: 12px 15px;
                margin-bottom: 20px;
                border-left: 4px solid #0066cc;
                font-weight: bold;
                border-radius: 4px;
            }
            .status-badge-panel.selected {
                border-left-color: #28a745;
                background: #f0fff4;
                color: #28a745;
            }
            .status-badge-panel.rejected {
                border-left-color: #dc3545;
                background: #fff5f5;
                color: #dc3545;
            }
            .resume-viewer {
                background: #fff;
                padding: 25px;
                border: 1px solid #e0e0e0;
                border-radius: 4px;
                white-space: pre-wrap;
                font-family: 'Arial', sans-serif;
                font-size: 14px;
                line-height: 1.8;
                color: #333;
                min-height: 500px;
            }
            .resume-viewer h3,
            .resume-viewer h4 {
                margin-top: 20px;
                margin-bottom: 10px;
                color: #333;
            }
            .resume-viewer p {
                margin-bottom: 15px;
            }
            .resume-viewer ul {
                margin-left: 20px;
                margin-bottom: 15px;
            }
            .feedback-two-column {
                display: flex;
                gap: 20px;
                align-items: flex-start;
                width: 100%;
            }
            .feedback-input-column {
                flex: 1;
                background: #fff;
                padding: 20px;
                border: 1px solid #e0e0e0;
                border-radius: 4px;
                min-height: 500px;
                box-sizing: border-box;
            }
            .feedback-display-column {
                flex: 1;
                background: #fff;
                padding: 20px;
                border: 1px solid #e0e0e0;
                border-radius: 4px;
                min-height: 500px;
                box-sizing: border-box;
            }
            .form-group {
                margin-bottom: 15px;
            }
            .form-group label {
                display: block;
                font-weight: bold;
                margin-bottom: 5px;
                color: #333;
                font-size: 13px;
            }
            .form-group select,
            .form-group textarea {
                width: 100%;
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 13px;
                font-family: Arial, sans-serif;
            }
            .form-group textarea {
                min-height: 100px;
                resize: vertical;
            }
            .btn-primary {
                background: #28a745;
                color: #fff;
                border: none;
                padding: 10px 20px;
                border-radius: 4px;
                cursor: pointer;
                font-weight: bold;
                font-size: 13px;
            }
            .btn-primary:hover {
                background: #218838;
            }
            .email-two-column {
                display: flex;
                gap: 20px;
                align-items: flex-start;
                width: 100%;
            }
            .email-list-column {
                flex: 1;
                background: #fff;
                padding: 20px;
                border: 1px solid #e0e0e0;
                border-radius: 4px;
                max-height: 600px;
                overflow-y: auto;
                min-height: 500px;
                box-sizing: border-box;
            }
            .email-detail-column {
                flex: 1;
                background: #fff;
                padding: 20px;
                border: 1px solid #e0e0e0;
                border-radius: 4px;
                min-height: 500px;
                box-sizing: border-box;
            }
            .email-list-item {
                padding: 15px;
                margin-bottom: 10px;
                border: 1px solid #e0e0e0;
                border-radius: 4px;
                cursor: pointer;
                transition: all 0.2s;
            }
            .email-list-item:hover {
                background: #f8f9fa;
                border-color: #28a745;
            }
            .email-list-item.active {
                background: #f0fff4;
                border-color: #28a745;
            }
            .email-sender {
                font-weight: bold;
                color: #333;
                margin-bottom: 5px;
            }
            .email-recipient {
                color: #666;
                font-size: 12px;
                margin-bottom: 5px;
            }
            .email-date {
                color: #999;
                font-size: 11px;
                margin-bottom: 5px;
            }
            .email-subject {
                color: #0066cc;
                font-weight: 500;
                margin-bottom: 8px;
            }
            .email-preview {
                color: #666;
                font-size: 12px;
                line-height: 1.5;
            }
            .email-detail-title {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 15px;
                color: #333;
            }
            .email-detail-content {
                color: #333;
                line-height: 1.8;
                margin-bottom: 20px;
            }
            .candidate-details-table {
                width: 100%;
                border-collapse: collapse;
            }
            .candidate-details-table td {
                padding: 12px 0;
                vertical-align: top;
                border-bottom: 1px solid #f0f0f0;
            }
            .candidate-details-table tr:last-child td {
                border-bottom: none;
            }
            .candidate-details-table td.label {
                font-weight: 600;
                color: #666;
                width: 40%;
                font-size: 13px;
                padding-right: 15px;
            }
            .candidate-details-table td.value {
                color: #333;
                font-size: 13px;
                line-height: 1.6;
            }
            .action-buttons {
                margin-top: 20px;
                display: flex;
                gap: 10px;
            }
            .action-buttons .btn {
                flex: 1;
                padding: 10px;
                border: none;
                border-radius: 4px;
                font-weight: bold;
                cursor: pointer;
                font-size: 13px;
            }
            .btn-success {
                background: #28a745;
                color: #fff;
            }
            .btn-success:hover {
                background: #218838;
            }
        </style>

        <div id="contents">
            <!-- Status Indicator Top Right (Outside main panel) -->
            <div class="status-indicator-top-right">
                Status: 
                <?php if (!empty($this->candidateStatus)): ?>
                    <span class="status-badge status-<?php echo strtolower($this->candidateStatus); ?>">
                        <?php $this->_($this->candidateStatus); ?>
                    </span>
                <?php else: ?>
                    <span class="status-badge status-none">Not Set</span>
                <?php endif; ?>
            </div>

            <div class="candidate-page-wrapper">
                <div class="candidate-main-panel">
                    <!-- Candidate Header Section -->
                    <div class="candidate-header-section">
                        <h1><?php $this->_($this->data['firstName']); ?> <?php $this->_($this->data['middleName']); ?> <?php $this->_($this->data['lastName']); ?></h1>

                        <!-- Tabs -->
                        <ul class="candidate-tabs">
                            <li id="resumeTab" class="active">
                                <a href="javascript:void(0);" onclick="showTab('resume');">Resume</a>
                            </li>
                            <li id="feedbackTab">
                                <a href="javascript:void(0);" onclick="showTab('feedback');">Feedback</a>
                            </li>
                            <li id="emailTab">
                                <a href="javascript:void(0);" onclick="showTab('email');">Email</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab Content Container -->
                    <div class="tab-content-container">
                        <!-- Resume Tab Content -->
                        <div id="resumeTabContent" style="display: block;">
                            <div class="tab-left-panel">
                                <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px; color: #333; font-weight: 600;">Resume / CV</h3>
                                <?php if (!empty($this->resumeText)): ?>
                                    <div class="resume-viewer">
                                        <?php echo nl2br(htmlspecialchars($this->resumeText)); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="resume-viewer">
                                        <p style="color: #999; text-align: center; padding: 40px;">No resume available for this candidate.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="tab-right-panel">
                                <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px; color: #333; font-weight: 600;">Candidate Details</h3>
                                <table class="candidate-details-table">
                                    <tr>
                                        <td class="label">Name:</td>
                                        <td class="value">
                                            <span class="<?php echo($this->data['titleClass']); ?>">
                                                <?php $this->_($this->data['firstName']); ?> <?php $this->_($this->data['lastName']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php if (!empty($this->primaryJobOrder)): ?>
                                    <tr>
                                        <td class="label">Job:</td>
                                        <td class="value"><?php $this->_($this->primaryJobOrder['title']); ?> (Job ID: <?php echo($this->primaryJobOrder['clientJobID'] ? $this->primaryJobOrder['clientJobID'] : $this->primaryJobOrder['jobOrderID']); ?>)</td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td class="label">Email:</td>
                                        <td class="value">
                                            <a href="mailto:<?php $this->_($this->data['email1']); ?>">
                                                <?php $this->_($this->data['email1']); ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">Phone:</td>
                                        <td class="value">
                                            <?php 
                                            $phone = !empty($this->data['phoneCell']) ? $this->data['phoneCell'] : 
                                                    (!empty($this->data['phoneHome']) ? $this->data['phoneHome'] : $this->data['phoneWork']);
                                            $this->_($phone);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">Location:</td>
                                        <td class="value"><?php $this->_($this->data['cityAndState']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">Employer:</td>
                                        <td class="value"><?php $this->_($this->data['currentEmployer']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">Notice Period:</td>
                                        <td class="value"><?php echo !empty($this->data['dateAvailable']) ? '30 Days' : 'Not Specified'; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">Expected CTC:</td>
                                        <td class="value"><?php echo !empty($this->data['desiredPay']) ? $this->data['desiredPay'] : (!empty($this->data['currentPay']) ? $this->data['currentPay'] : 'Not Specified'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">Key Skills:</td>
                                        <td class="value"><?php $this->_($this->data['keySkills']); ?></td>
                                    </tr>
                                </table>
                                <?php if ($this->getUserAccessLevel('pipelines.addActivityChangeStatus') >= ACCESS_LEVEL_EDIT): ?>
                                <div class="action-buttons">
                                    <button class="btn btn-success" onclick="showPopWin('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=considerForJobSearch&amp;candidateID=<?php echo($this->candidateID); ?>', 750, 390, null); return false;">Move Candidate</button>
                                    <button class="btn btn-success" onclick="showPopWin('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addActivityChangeStatus&amp;candidateID=<?php echo($this->candidateID); ?>&amp;jobOrderID=-1&amp;onlyScheduleEvent=true', 600, 350, null); return false;">Schedule Interview</button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Feedback Tab Content -->
                        <div id="feedbackTabContent" style="display: none;">
                            <div class="tab-left-panel">
                                <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px; color: #333; font-weight: 600;">Feedback</h3>
                                <div class="feedback-two-column">
                                    <!-- Left Column: Input Form -->
                                    <div class="feedback-input-column">
                                        <h4 style="margin-top: 0; margin-bottom: 15px;">Add Feedback</h4>
                                        <form id="feedbackForm">
                                            <div class="form-group">
                                                <label>Rating (1-5)</label>
                                                <select name="rating">
                                                    <option value="">Select Rating</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Interview Notes</label>
                                                <textarea name="interviewNotes" placeholder="Enter interview notes..."></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Strengths</label>
                                                <textarea name="strengths" placeholder="Enter strengths..."></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Weaknesses</label>
                                                <textarea name="weaknesses" placeholder="Enter weaknesses..."></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" name="recommendHire" value="1" /> Recommend: Hire
                                                </label>
                                            </div>
                                            <button type="button" class="btn-primary" onclick="saveFeedback();">Save Feedback</button>
                                        </form>
                                    </div>
                                    
                                    <!-- Right Column: Displayed Feedback -->
                                    <div class="feedback-display-column">
                                        <h4 style="margin-top: 0; margin-bottom: 15px;">Previous Feedback</h4>
                                        <?php if (!empty($this->feedbackRS)): ?>
                                            <?php $latestFeedback = $this->feedbackRS[0]; ?>
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" checked disabled /> Recommend: Hire
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>Rating (1-5)</label>
                                                <div style="padding: 8px; background: #f8f9fa; border-radius: 4px;">4</div>
                                            </div>
                                            <div class="form-group">
                                                <label>Interview Notes</label>
                                                <div style="padding: 8px; background: #f8f9fa; border-radius: 4px; min-height: 100px;">
                                                    <?php echo htmlspecialchars(substr($latestFeedback['notes'], 0, 200)); ?>...
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Weaknesses</label>
                                                <div style="padding: 8px; background: #f8f9fa; border-radius: 4px; min-height: 60px;">
                                                    Limited experience with backend technologies.
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <p style="color: #999; text-align: center; padding: 40px;">No previous feedback available.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-right-panel">
                                <?php if (!empty($this->candidateStatus)): ?>
                                    <div class="status-badge-panel <?php echo strtolower($this->candidateStatus); ?>" style="margin-bottom: 20px;">
                                        Status: <?php $this->_($this->candidateStatus); ?>
                                    </div>
                                <?php endif; ?>
                                <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px; color: #333; font-weight: 600;">Candidate Details</h3>
                                <table class="candidate-details-table">
                                    <tr>
                                        <td class="label">Name:</td>
                                        <td class="value">
                                            <span class="<?php echo($this->data['titleClass']); ?>">
                                                <?php $this->_($this->data['firstName']); ?> <?php $this->_($this->data['lastName']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php if (!empty($this->primaryJobOrder)): ?>
                                    <tr>
                                        <td class="label">Job:</td>
                                        <td class="value"><?php $this->_($this->primaryJobOrder['title']); ?> (Job ID: <?php echo($this->primaryJobOrder['clientJobID'] ? $this->primaryJobOrder['clientJobID'] : $this->primaryJobOrder['jobOrderID']); ?>)</td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td class="label">Email:</td>
                                        <td class="value">
                                            <a href="mailto:<?php $this->_($this->data['email1']); ?>">
                                                <?php $this->_($this->data['email1']); ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">Phone:</td>
                                        <td class="value">
                                            <?php 
                                            $phone = !empty($this->data['phoneCell']) ? $this->data['phoneCell'] : 
                                                    (!empty($this->data['phoneHome']) ? $this->data['phoneHome'] : $this->data['phoneWork']);
                                            $this->_($phone);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">Location:</td>
                                        <td class="value"><?php $this->_($this->data['cityAndState']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">Employer:</td>
                                        <td class="value"><?php $this->_($this->data['currentEmployer']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">Notice Period:</td>
                                        <td class="value"><?php echo !empty($this->data['dateAvailable']) ? '30 Days' : 'Not Specified'; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">Expected CTC:</td>
                                        <td class="value"><?php echo !empty($this->data['desiredPay']) ? $this->data['desiredPay'] : (!empty($this->data['currentPay']) ? $this->data['currentPay'] : 'Not Specified'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">Key Skills:</td>
                                        <td class="value"><?php $this->_($this->data['keySkills']); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Email Tab Content -->
                        <div id="emailTabContent" style="display: none;">
                            <div class="tab-left-panel">
                        <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px; color: #333; font-weight: 600;">Email</h3>
                        <div class="email-two-column">
                            <!-- Left Column: Email List -->
                            <div class="email-list-column">
                                <h4 style="margin-top: 0; margin-bottom: 15px;">Email List</h4>
                                <?php if (!empty($this->emailRS)): ?>
                                    <?php foreach ($this->emailRS as $index => $email): ?>
                                        <div class="email-list-item <?php echo $index == 0 ? 'active' : ''; ?>" onclick="showEmailDetail(<?php echo $index; ?>);">
                                            <div class="email-sender"><?php echo htmlspecialchars($email['enteredByAbbrName']); ?>@company.com</div>
                                            <div class="email-recipient">To: <?php $this->_($this->data['firstName']); ?> <?php $this->_($this->data['lastName']); ?></div>
                                            <div class="email-date"><?php $this->_($email['dateCreated']); ?></div>
                                            <div class="email-subject"><?php echo !empty($email['regarding']) ? htmlspecialchars($email['regarding']) : 'Job Application Received'; ?></div>
                                            <div class="email-preview">
                                                <?php echo htmlspecialchars(substr($email['notes'], 0, 100)); ?>...
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="email-list-item">
                                        <p style="color: #999; text-align: center; padding: 20px;">No emails available.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Right Column: Email Detail -->
                            <div class="email-detail-column">
                                <h4 style="margin-top: 0; margin-bottom: 15px;">Email Content</h4>
                                <?php if (!empty($this->emailRS)): ?>
                                    <?php $firstEmail = $this->emailRS[0]; ?>
                                    <div class="email-detail-title"><?php echo !empty($firstEmail['regarding']) ? htmlspecialchars($firstEmail['regarding']) : 'Job Application Received'; ?></div>
                                    <div class="email-detail-content">
                                        <?php echo nl2br(htmlspecialchars($firstEmail['notes'])); ?>
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <button class="btn-success" style="width: 100%;">Interview and Offer</button>
                                    </div>
                                    <div style="margin-top: 10px; text-align: center;">
                                        <a href="#" style="color: #0066cc; text-decoration: underline;">Send new email</a>
                                    </div>
                                <?php else: ?>
                                    <p style="color: #999; text-align: center; padding: 40px;">Select an email to view details.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-right-panel">
                        <?php if (!empty($this->candidateStatus)): ?>
                            <div class="status-badge-panel <?php echo strtolower($this->candidateStatus); ?>" style="margin-bottom: 20px;">
                                Status: <?php $this->_($this->candidateStatus); ?>
                            </div>
                        <?php endif; ?>
                                <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px; color: #333; font-weight: 600;">Candidate Details</h3>
                                <table class="candidate-details-table">
                                    <tr>
                                        <td class="label">Name:</td>
                                        <td class="value">
                                            <span class="<?php echo($this->data['titleClass']); ?>">
                                                <?php $this->_($this->data['firstName']); ?> <?php $this->_($this->data['lastName']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php if (!empty($this->primaryJobOrder)): ?>
                                    <tr>
                                        <td class="label">Job:</td>
                                        <td class="value"><?php $this->_($this->primaryJobOrder['title']); ?> (Job ID: <?php echo($this->primaryJobOrder['clientJobID'] ? $this->primaryJobOrder['clientJobID'] : $this->primaryJobOrder['jobOrderID']); ?>)</td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td class="label">Email:</td>
                                        <td class="value">
                                            <a href="mailto:<?php $this->_($this->data['email1']); ?>">
                                                <?php $this->_($this->data['email1']); ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">Phone:</td>
                                        <td class="value">
                                            <?php 
                                            $phone = !empty($this->data['phoneCell']) ? $this->data['phoneCell'] : 
                                                    (!empty($this->data['phoneHome']) ? $this->data['phoneHome'] : $this->data['phoneWork']);
                                            $this->_($phone);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">Location:</td>
                                        <td class="value"><?php $this->_($this->data['cityAndState']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">Employer:</td>
                                        <td class="value"><?php $this->_($this->data['currentEmployer']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">Notice Period:</td>
                                        <td class="value"><?php echo !empty($this->data['dateAvailable']) ? '30 Days' : 'Not Specified'; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">Expected CTC:</td>
                                        <td class="value"><?php echo !empty($this->data['desiredPay']) ? $this->data['desiredPay'] : (!empty($this->data['currentPay']) ? $this->data['currentPay'] : 'Not Specified'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">Key Skills:</td>
                                        <td class="value"><?php $this->_($this->data['keySkills']); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Orders Section (Keep existing functionality) -->
            <br clear="all" />
            <br />
            <p class="note">Job Orders for Candidates</p>
            <table class="sortablepair">
                <tr>
                    <th></th>
                    <th align="left">Match</th>
                    <th align="left">Ref. Number</th>
                    <th align="left">Title</th>
                    <th align="left">Company</th>
                    <th align="left">Owner</th>
                    <th align="left">Added</th>
                    <th align="left">Entered By</th>
                    <th align="left">Status</th>
<?php if (!$this->isPopup): ?>
                    <th align="center">Action</th>
<?php endif; ?>
                </tr>

                <?php foreach ($this->pipelinesRS as $rowNumber => $pipelinesData): ?>
                    <tr class="<?php TemplateUtility::printAlternatingRowClass($rowNumber); ?>" id="pipelineRow<?php echo($rowNumber); ?>">
                        <td valign="top">
                            <span id="pipelineOpen<?php echo($rowNumber); ?>">
                                <a href="javascript:void(0);" onclick="document.getElementById('pipelineDetails<?php echo($rowNumber); ?>').style.display=''; document.getElementById('pipelineClose<?php echo($rowNumber); ?>').style.display = ''; document.getElementById('pipelineOpen<?php echo($rowNumber); ?>').style.display = 'none'; PipelineDetails_populate(<?php echo($pipelinesData['candidateJobOrderID']); ?>, 'pipelineInner<?php echo($rowNumber); ?>', '<?php echo($this->sessionCookie); ?>');">
                                    <img src="images/arrow_next.png" alt="" border="0" title="Show History" />
                                </a>
                            </span>
                            <span id="pipelineClose<?php echo($rowNumber); ?>" style="display: none;">
                                <a href="javascript:void(0);" onclick="document.getElementById('pipelineDetails<?php echo($rowNumber); ?>').style.display = 'none'; document.getElementById('pipelineClose<?php echo($rowNumber); ?>').style.display = 'none'; document.getElementById('pipelineOpen<?php echo($rowNumber); ?>').style.display = '';">
                                    <img src="images/arrow_down.png" alt="" border="0" title="Hide History" />
                                </a>
                            </span>
                        </td>
                        <td valign="top">
                            <?php echo($pipelinesData['ratingLine']); ?>
                        </td>
                        <td valign="top">
                            <?php $this->_($pipelinesData['clientJobID']) ?>
                        </td>
                        <td valign="top">
                            <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=show&amp;jobOrderID=<?php echo($pipelinesData['jobOrderID']); ?>" class="<?php $this->_($pipelinesData['linkClass']) ?>">
                                <?php $this->_($pipelinesData['title']) ?>
                            </a>
                        </td>
                        <td valign="top">
                            <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=companies&amp;companyID=<?php echo($pipelinesData['companyID']); ?>&amp;a=show">
                                <?php $this->_($pipelinesData['companyName']) ?>
                            </a>
                        </td>
                        <td valign="top"><?php $this->_($pipelinesData['ownerAbbrName']) ?></td>
                        <td valign="top"><?php $this->_($pipelinesData['dateCreated']) ?></td>
                        <td valign="top"><?php $this->_($pipelinesData['addedByAbbrName']) ?></td>
                        <td valign="top" nowrap="nowrap">
                            <?php if ($this->getUserAccessLevel('pipelines.addActivityChangeStatus') >= ACCESS_LEVEL_EDIT): ?>
                                <select id="statusSelect<?php echo($pipelinesData['candidateJobOrderID']); ?>" 
                                        onchange="updatePipelineStatus(<?php echo($pipelinesData['candidateJobOrderID']); ?>, <?php echo($this->candidateID); ?>, <?php echo($pipelinesData['jobOrderID']); ?>, this.value, '<?php echo($this->sessionCookie); ?>');"
                                        style="font-size: 11px; padding: 2px;">
                                    <?php foreach ($this->statusesRS as $status): ?>
                                        <option value="<?php echo($status['statusID']); ?>" <?php if ($status['statusID'] == $pipelinesData['statusID']): ?>selected="selected"<?php endif; ?>>
                                            <?php $this->_($status['status']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <?php $this->_($pipelinesData['status']); ?>
                            <?php endif; ?>
                        </td>
<?php if (!$this->isPopup): ?>
                        <td align="center" nowrap="nowrap">
                            <?php eval(Hooks::get('CANDIDATE_TEMPLATE_SHOW_PIPELINE_ACTION')); ?>
                            <?php if ($this->getUserAccessLevel('pipelines.screening') >= ACCESS_LEVEL_EDIT && !$_SESSION['CATS']->hasUserCategory('sourcer')): ?>
                                <?php if ($pipelinesData['ratingValue'] < 0): ?>
                                    <a href="#" id="screenLink<?php echo($pipelinesData['candidateJobOrderID']); ?>" onclick="moImageValue<?php echo($pipelinesData['candidateJobOrderID']); ?> = 0; setRating(<?php echo($pipelinesData['candidateJobOrderID']); ?>, 0, 'moImage<?php echo($pipelinesData['candidateJobOrderID']); ?>', '<?php echo($_SESSION['CATS']->getCookie()); ?> '); return false;">
                                        <img id="screenImage<?php echo($pipelinesData['candidateJobOrderID']); ?>" src="images/actions/screen.gif" width="16" height="16" class="absmiddle" alt="" border="0" title="Mark as Screened" />
                                    </a>
                                <?php else: ?>
                                    <img src="images/actions/blank.gif" width="16" height="16" class="absmiddle" alt="" border="0" />
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($this->getUserAccessLevel('pipelines.addActivityChangeStatus') >= ACCESS_LEVEL_EDIT): ?>
                                <a href="#" onclick="showPopWin('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addActivityChangeStatus&amp;candidateID=<?php echo($this->candidateID); ?>&amp;jobOrderID=<?php echo($pipelinesData['jobOrderID']); ?>', 600, 480, null); return false;" >
                                    <img src="images/actions/edit.gif" width="16" height="16" class="absmiddle" alt="" border="0" title="Log an Activity / Change Status"/>
                                </a>
                            <?php endif; ?>
                            <?php if ($this->getUserAccessLevel('pipelines.removeFromPipeline') >= ACCESS_LEVEL_DELETE): ?>
                                <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=removeFromPipeline&amp;candidateID=<?php echo($this->candidateID); ?>&amp;jobOrderID=<?php echo($pipelinesData['jobOrderID']); ?>"  onclick="javascript:return confirm('Delete from <?php $this->_(str_replace('\'', '\\\'', $pipelinesData['title'])); ?> (<?php $this->_(str_replace('\'', '\\\'', $pipelinesData['companyName'])); ?>) pipeline?')">
                                    <img src="images/actions/delete.gif" width="16" height="16" class="absmiddle" alt="" border="0" title="Remove from Job Order"/>
                                </a>
                            <?php endif; ?>
                        </td>
<?php endif; ?>
                    </tr>
                    <tr class="<?php TemplateUtility::printAlternatingRowClass($rowNumber); ?>" id="pipelineDetails<?php echo($rowNumber); ?>" style="display:none;">
                        <td colspan="11" align="center">
                            <table width="98%" border="1" class="detailsOutside" style="margin: 5px;">
                                <tr>
                                    <td align="left" style="padding: 6px 6px 6px 6px; background-color: white; clear: both;">
                                        <div style="overflow: auto; height: 200px;" id="pipelineInner<?php echo($rowNumber); ?>">
                                            <img src="images/indicator.gif" alt="" />&nbsp;&nbsp;Loading pipeline details...
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </table>

<?php if (!$this->isPopup): ?>
            <?php if ($this->getUserAccessLevel('candidates.considerForJobSearch') >= ACCESS_LEVEL_EDIT): ?>
                <a href="#" onclick="showPopWin('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=considerForJobSearch&amp;candidateID=<?php echo($this->candidateID); ?>', 750, 390, null); return false;">
                    <img src="images/consider.gif" width="16" height="16" class="absmiddle" alt="Add to Job Order" border="0" />&nbsp;Add This Candidate to Job Order
                </a>
            <?php endif; ?>
<?php endif; ?>
            <br clear="all" />
            <br />

            <p class="note">Activity</p>

            <table id="activityTable" class="sortable">
                <tr>
                    <th align="left" width="125">Date</th>
                    <th align="left" width="90">Type</th>
                    <th align="left" width="90">Entered</th>
                    <th align="left" width="250">Regarding</th>
                    <th align="left">Notes</th>
<?php if (!$this->isPopup): ?>
                    <th align="left" width="40">Action</th>
<?php endif; ?>
                </tr>

                <?php foreach ($this->activityRS as $rowNumber => $activityData): ?>
                    <tr class="<?php TemplateUtility::printAlternatingRowClass($rowNumber); ?>">
                        <td align="left" valign="top" id="activityDate<?php echo($activityData['activityID']); ?>"><?php $this->_($activityData['dateCreated']) ?></td>
                        <td align="left" valign="top" id="activityType<?php echo($activityData['activityID']); ?>"><?php $this->_($activityData['typeDescription']) ?></td>
                        <td align="left" valign="top"><?php $this->_($activityData['enteredByAbbrName']) ?></td>
                        <td align="left" valign="top" id="activityRegarding<?php echo($activityData['activityID']); ?>"><?php $this->_($activityData['regarding']) ?></td>
                        <td align="left" valign="top" id="activityNotes<?php echo($activityData['activityID']); ?>"><?php echo($activityData['notes']); ?></td>
<?php if (!$this->isPopup): ?>
                        <td align="center" >
                            <?php if ($this->getUserAccessLevel('candidates.edit') >= ACCESS_LEVEL_EDIT): ?>
                                <a href="#" id="editActivity<?php echo($activityData['activityID']); ?>" onclick="Activity_editEntry(<?php echo($activityData['activityID']); ?>, <?php echo($this->candidateID); ?>, <?php echo(DATA_ITEM_CANDIDATE); ?>, '<?php echo($this->sessionCookie); ?>'); return false;">
                                    <img src="images/actions/edit.gif" width="16" height="16" class="absmiddle" alt="" border="0" title="Edit" />
                                </a>
                            <?php endif; ?>
                            <?php if ($this->getUserAccessLevel('candidates.delete') >= ACCESS_LEVEL_DELETE): ?>
                                <a href="#" id="deleteActivity<?php echo($activityData['activityID']); ?>" onclick="Activity_deleteEntry(<?php echo($activityData['activityID']); ?>, '<?php echo($this->sessionCookie); ?>'); return false;">
                                    <img src="images/actions/delete.gif" width="16" height="16" class="absmiddle" alt="" border="0" title="Delete" />
                                </a>
                            <?php endif; ?>
                        </td>
<?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
<?php if (!$this->isPopup): ?>
            <div id="addActivityDiv">
                <?php if ($this->getUserAccessLevel('pipelines.addActivityChangeStatus') >= ACCESS_LEVEL_EDIT): ?>
                    <a href="#" id="addActivityLink" onclick="showPopWin('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addActivityChangeStatus&amp;candidateID=<?php echo($this->candidateID); ?>&amp;jobOrderID=-1', 600, 480, null); return false;">
                        <img src="images/new_activity_inline.gif" width="16" height="16" class="absmiddle" title="Log an Activity / Change Status" alt="Log an Activity / Change Status" border="0" />&nbsp;Log an Activity
                    </a>
                <?php endif; ?>
                <img src="images/indicator2.gif" id="addActivityIndicator" alt="" style="visibility: hidden; margin-left: 5px;" height="16" width="16" />
            </div>
        </div>
    </div>

<?php endif; ?>
	
<?php TemplateUtility::printFooter(); ?>
