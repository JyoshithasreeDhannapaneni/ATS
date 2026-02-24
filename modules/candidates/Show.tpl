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
            
            // Initialize tabs on page load
            window.addEventListener('DOMContentLoaded', function() {
                // Hide all tabs first
                document.getElementById('resumeTabContent').style.setProperty('display', 'none', 'important');
                document.getElementById('feedbackTabContent').style.setProperty('display', 'none', 'important');
                document.getElementById('emailTabContent').style.setProperty('display', 'none', 'important');
                
                // Show only Resume tab by default
                var resumeTab = document.getElementById('resumeTabContent');
                resumeTab.style.setProperty('display', 'flex', 'important');
                resumeTab.style.setProperty('flex-direction', 'row', 'important');
                resumeTab.style.setProperty('width', '100%', 'important');
            });
            
            function showTab(tabName) {
                // Hide all tab contents with !important override
                var resumeTab = document.getElementById('resumeTabContent');
                var feedbackTab = document.getElementById('feedbackTabContent');
                var emailTab = document.getElementById('emailTabContent');
                
                resumeTab.style.setProperty('display', 'none', 'important');
                feedbackTab.style.setProperty('display', 'none', 'important');
                emailTab.style.setProperty('display', 'none', 'important');
                
                // Remove active class from all tabs
                document.getElementById('resumeTab').classList.remove('active');
                document.getElementById('feedbackTab').classList.remove('active');
                document.getElementById('emailTab').classList.remove('active');
                
                // Show selected tab content with !important
                var tabContent = document.getElementById(tabName + 'TabContent');
                if (tabName === 'resume' || tabName === 'feedback' || tabName === 'email') {
                    tabContent.style.setProperty('display', 'flex', 'important');
                    tabContent.style.setProperty('flex-direction', 'row', 'important');
                    tabContent.style.setProperty('width', '100%', 'important');
                } else {
                    tabContent.style.setProperty('display', 'block', 'important');
                }
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
            * { box-sizing: border-box; }

            /* Page-level overrides for candidate detail */
            body { padding: 0 !important; }
            #main { margin: 0 16px !important; padding-top: 1.8em !important; }
            #contents {
                position: relative;
                background: var(--gray-50, #f9fafb) !important;
                min-height: 100vh;
                width: 100% !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 0 8px 8px !important;
            }

            .candidate-page-wrapper {
                background: var(--gray-50, #f9fafb);
                min-height: 100vh;
                padding: 0;
                position: relative;
                width: 100%;
            }

            .status-indicator-top-right {
                position: absolute;
                top: 16px;
                right: 20px;
                font-weight: 600;
                font-size: 13px;
                z-index: 100;
                background: #fff;
                padding: 8px 16px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                border: 1px solid var(--gray-200, #e5e7eb);
            }

            .candidate-main-panel {
                background: #fff;
                border-radius: 0;
                box-shadow: none;
                margin: 0 !important;
                width: 100% !important;
                display: flex;
                flex-direction: column;
                padding: 0 !important;
            }

            .candidate-header-section {
                padding: 24px 28px 0 28px;
                border-bottom: none;
                position: relative;
                background: #fff;
            }

            .candidate-header-section h1 {
                font-size: 36px;
                font-weight: 700;
                color: var(--gray-900, #111827);
                margin: 0 0 20px 0;
                padding: 0;
                line-height: 1.2;
                letter-spacing: -0.02em;
                font-family: 'Inter', system-ui, sans-serif;
            }

            .status-badge {
                padding: 5px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                display: inline-block;
                letter-spacing: 0.02em;
            }

            .status-selected { background: #dcfce7; color: #16a34a; }
            .status-rejected { background: #fee2e2; color: #dc2626; }
            .status-none     { background: #f3f4f6; color: #6b7280; }

            .candidate-tabs {
                display: flex;
                list-style: none;
                margin: 0;
                padding: 0 28px;
                border-bottom: 2px solid var(--gray-200, #e5e7eb);
                background: #fff;
                gap: 0;
                align-items: center;
                justify-content: space-between;
            }
            
            .candidate-tabs-wrapper {
                display: flex;
                align-items: center;
                gap: 0;
            }
            
            .candidate-status-inline {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 0 20px;
                font-size: 14px;
                font-weight: 500;
                color: var(--gray-600, #4b5563);
            }

            .candidate-tabs li { margin: 0; padding: 0; }
            
            .candidate-tabs ul {
                display: flex;
                list-style: none;
                margin: 0;
                padding: 0;
                gap: 0;
            }

            .candidate-tabs li a {
                display: block;
                padding: 12px 24px;
                text-decoration: none !important;
                color: var(--gray-500, #6b7280);
                font-weight: 500;
                font-size: 14px;
                border-bottom: 3px solid transparent;
                cursor: pointer;
                transition: all 0.2s ease;
                font-family: 'Inter', system-ui, sans-serif;
                margin-bottom: -2px;
            }

            .candidate-tabs li.active a {
                color: var(--primary, #2563eb);
                border-bottom-color: var(--primary, #2563eb);
                font-weight: 600;
            }

            .candidate-tabs li a:hover {
                color: var(--primary, #2563eb);
                background: var(--gray-50, #f9fafb);
                border-radius: 6px 6px 0 0;
            }

            .tab-content-container {
                display: flex !important;
                flex-direction: row !important;
                min-height: 550px;
                align-items: stretch;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                gap: 0 !important;
                border: none !important;
                box-sizing: border-box !important;
            }
            
            /* Make resumeTabContent a flex container for side-by-side layout */
            #resumeTabContent {
                display: flex;
                flex-direction: row;
                width: 100%;
                min-height: 550px;
                align-items: stretch;
                margin: 0;
                padding: 0;
                gap: 0;
                box-sizing: border-box;
            }
            
            /* Make feedbackTabContent a flex container for side-by-side layout */
            #feedbackTabContent {
                display: none;
                flex-direction: row;
                width: 100%;
                min-height: 550px;
                align-items: stretch;
                margin: 0;
                padding: 0;
                gap: 0;
                box-sizing: border-box;
            }
            
            #feedbackTabContent .tab-left-panel {
                flex: 0 0 65% !important;
                width: 65% !important;
                max-width: 65% !important;
                min-width: 65% !important;
                border-right: 1px solid var(--gray-200, #e5e7eb) !important;
            }
            
            /* Make emailTabContent a flex container for side-by-side layout */
            #emailTabContent {
                display: none;
                flex-direction: row;
                width: 100%;
                min-height: 550px;
                align-items: stretch;
                margin: 0;
                padding: 0;
                gap: 0;
                box-sizing: border-box;
            }
            
            #emailTabContent .tab-left-panel {
                flex: 0 0 65% !important;
                width: 65% !important;
                max-width: 65% !important;
                min-width: 65% !important;
                border-right: 1px solid var(--gray-200, #e5e7eb) !important;
            }
            
            .tab-left-panel {
                flex: 0 0 70% !important;
                width: 70% !important;
                max-width: 70% !important;
                min-width: 70% !important;
                padding: 24px 28px;
                border-right: 1px solid var(--gray-200, #e5e7eb);
                background: var(--gray-50, #f9fafb);
                overflow-y: auto;
                max-height: calc(100vh - 200px);
                min-height: 550px;
                margin: 0 !important;
                float: none !important;
                box-sizing: border-box !important;
            }
            
            #resumeTabContent .tab-left-panel {
                flex: 0 0 65% !important;
                width: 65% !important;
                max-width: 65% !important;
                min-width: 65% !important;
                border-right: 1px solid var(--gray-200, #e5e7eb) !important;
            }
            
            .tab-right-panel {
                flex: 0 0 30% !important;
                width: 30% !important;
                max-width: 30% !important;
                min-width: 30% !important;
                padding: 20px 24px;
                background: #fff !important;
                overflow-y: auto;
                max-height: calc(100vh - 200px);
                min-height: 550px;
                position: sticky;
                top: 0;
                align-self: flex-start;
                margin: 0 !important;
                float: none !important;
                display: flex !important;
                flex-direction: column !important;
                box-sizing: border-box !important;
            }
            
            #resumeTabContent .tab-right-panel {
                flex: 0 0 35% !important;
                width: 35% !important;
                min-width: 35% !important;
                max-width: 35% !important;
                display: flex !important;
                flex-direction: column !important;
                visibility: visible !important;
                opacity: 1 !important;
                height: auto !important;
                overflow-y: auto !important;
                background: #fff !important;
                padding: 20px 24px !important;
            }
            
            .tab-right-panel h3 {
                font-size: 15px !important;
                margin-top: 0 !important;
                margin-bottom: 16px !important;
                color: #1f2937 !important;
                font-weight: 700 !important;
            }
            
            .tab-right-panel .action-buttons {
                flex-direction: column;
                gap: 8px;
                margin-top: 16px;
                width: 100%;
            }
            
            .tab-right-panel .action-buttons .btn {
                font-size: 12px;
                padding: 10px 12px;
                width: 100%;
                white-space: normal;
                word-wrap: break-word;
            }

            .status-badge-panel {
                background: #eff6ff;
                padding: 12px 16px;
                margin-bottom: 20px;
                margin-top: 0;
                border-left: 4px solid var(--primary, #2563eb);
                font-weight: 600;
                border-radius: 0 6px 6px 0;
                font-size: 13px;
                width: 100%;
                box-sizing: border-box;
            }

            .status-badge-panel.selected {
                border-left-color: #16a34a;
                background: #f0fdf4;
                color: #16a34a;
            }

            .status-badge-panel.rejected {
                border-left-color: #dc2626;
                background: #fef2f2;
                color: #dc2626;
            }
            
            .tab-right-panel .status-badge-panel {
                margin-top: 0;
                margin-bottom: 20px;
            }

            .resume-viewer {
                background: #fff;
                padding: 24px;
                border: 1px solid var(--gray-200, #e5e7eb);
                border-radius: 8px;
                white-space: pre-wrap;
                font-family: 'Inter', system-ui, sans-serif;
                font-size: 13px;
                line-height: 1.8;
                color: var(--gray-700, #374151);
                min-height: 400px;
                box-shadow: 0 1px 2px rgba(0,0,0,0.04);
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
            }

            .resume-viewer h3,
            .resume-viewer h4 {
                margin-top: 18px;
                margin-bottom: 8px;
                color: var(--gray-800, #1f2937);
            }

            .resume-viewer p { margin-bottom: 12px; }
            .resume-viewer ul { margin-left: 18px; margin-bottom: 12px; }

            .feedback-two-column {
                display: flex;
                gap: 16px;
                align-items: flex-start;
                width: 100%;
            }

            .feedback-input-column,
            .feedback-display-column {
                flex: 1;
                background: #fff;
                padding: 20px;
                border: 1px solid var(--gray-200, #e5e7eb);
                border-radius: 8px;
                min-height: 400px;
                box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            }

            .form-group { margin-bottom: 14px; }

            .form-group label {
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
                color: var(--gray-700, #374151);
                font-size: 13px;
                font-family: 'Inter', system-ui, sans-serif;
            }

            .form-group select,
            .form-group textarea {
                width: 100%;
                padding: 8px 10px;
                border: 1px solid var(--gray-300, #d1d5db);
                border-radius: 6px;
                font-size: 13px;
                font-family: 'Inter', system-ui, sans-serif;
                transition: all 0.2s ease;
                outline: none;
            }

            .form-group select:focus,
            .form-group textarea:focus {
                border-color: var(--primary, #2563eb);
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
            }

            .form-group textarea {
                min-height: 90px;
                resize: vertical;
            }

            .btn-primary {
                background: var(--primary, #2563eb);
                color: #fff;
                border: none;
                padding: 10px 22px;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 600;
                font-size: 13px;
                font-family: 'Inter', system-ui, sans-serif;
                transition: all 0.2s ease;
            }

            .btn-primary:hover {
                background: var(--primary-dark, #1d4ed8);
                box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            }

            .email-two-column {
                display: flex;
                gap: 16px;
                align-items: flex-start;
                width: 100%;
            }

            .email-list-column,
            .email-detail-column {
                flex: 1;
                background: #fff;
                padding: 20px;
                border: 1px solid var(--gray-200, #e5e7eb);
                border-radius: 8px;
                min-height: 400px;
                box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            }

            .email-list-column {
                max-height: 550px;
                overflow-y: auto;
            }

            .email-list-item {
                padding: 14px;
                margin-bottom: 8px;
                border: 1px solid var(--gray-200, #e5e7eb);
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .email-list-item:hover {
                background: var(--gray-50, #f9fafb);
                border-color: var(--primary, #2563eb);
                box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            }

            .email-list-item.active {
                background: #eff6ff;
                border-color: var(--primary, #2563eb);
            }

            .email-sender {
                font-weight: 600;
                color: var(--gray-800, #1f2937);
                margin-bottom: 4px;
                font-size: 13px;
            }

            .email-recipient {
                color: var(--gray-500, #6b7280);
                font-size: 12px;
                margin-bottom: 4px;
            }

            .email-date {
                color: var(--gray-400, #9ca3af);
                font-size: 11px;
                margin-bottom: 4px;
            }

            .email-subject {
                color: var(--primary, #2563eb);
                font-weight: 500;
                margin-bottom: 6px;
                font-size: 13px;
            }

            .email-preview {
                color: var(--gray-500, #6b7280);
                font-size: 12px;
                line-height: 1.5;
            }

            .email-detail-title {
                font-size: 17px;
                font-weight: 700;
                margin-bottom: 12px;
                color: var(--gray-800, #1f2937);
            }

            .email-detail-content {
                color: var(--gray-700, #374151);
                line-height: 1.7;
                margin-bottom: 16px;
                font-size: 13px;
            }

            .candidate-details-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 0;
            }

            .candidate-details-table td {
                padding: 0;
                vertical-align: top;
                border-bottom: none;
                font-size: 13px;
            }

            .candidate-details-table tr:last-child td { border-bottom: none; }
            
            .tab-right-panel .candidate-details-table {
                width: 100%;
                display: table;
            }

            .candidate-details-table td.label {
                font-weight: 600;
                color: var(--gray-500, #6b7280);
                width: 40%;
                font-size: 11px;
                padding-right: 12px;
                padding-bottom: 10px;
                padding-top: 10px;
                text-transform: uppercase;
                letter-spacing: 0.02em;
                display: table-cell;
                vertical-align: top;
            }
            
            .candidate-details-table td.value {
                display: table-cell;
                padding-bottom: 10px;
                padding-top: 10px;
                border-bottom: 1px solid var(--gray-100, #f3f4f6);
                color: var(--gray-800, #1f2937);
                font-size: 12px;
                line-height: 1.5;
                word-wrap: break-word;
                vertical-align: top;
            }
            
            .candidate-details-table tr {
                display: table-row;
                margin-bottom: 0;
            }
            
            .candidate-details-table tr:last-child td.value {
                border-bottom: none;
                padding-bottom: 10px;
            }
            
            .tab-right-panel .candidate-details-table {
                width: 100%;
                table-layout: fixed;
            }
            
            .tab-right-panel .candidate-details-table td.label {
                width: 38%;
                font-size: 10px;
                padding-right: 8px;
                word-wrap: break-word;
            }
            
            .tab-right-panel .candidate-details-table td.value {
                width: 62%;
                font-size: 11px;
                padding-left: 0;
                word-wrap: break-word;
            }

            .action-buttons {
                margin-top: 20px;
                display: flex;
                gap: 8px;
            }

            .action-buttons .btn {
                flex: 1;
                padding: 10px;
                border: none;
                border-radius: 6px;
                font-weight: 600;
                cursor: pointer;
                font-size: 13px;
                font-family: 'Inter', system-ui, sans-serif;
                transition: all 0.2s ease;
            }

            .btn-success {
                background: var(--primary, #2563eb);
                color: #fff;
            }

            .btn-success:hover {
                background: var(--primary-dark, #1d4ed8);
                box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            }
        </style>

        <div id="contents">
            <div class="candidate-page-wrapper">
                <div class="candidate-main-panel">
                    <!-- Candidate Header Section -->
                    <div class="candidate-header-section">
                        <h1><?php $this->_($this->data['firstName']); ?> <?php $this->_($this->data['middleName']); ?> <?php $this->_($this->data['lastName']); ?></h1>

                        <!-- Tabs with Status -->
                        <div class="candidate-tabs">
                            <div class="candidate-tabs-wrapper">
                                <ul style="display: flex; list-style: none; margin: 0; padding: 0; gap: 0;">
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
                            <div class="candidate-status-inline">
                                Status: 
                                <?php if (!empty($this->candidateStatus)): ?>
                                    <span class="status-badge status-<?php echo strtolower($this->candidateStatus); ?>">
                                        <?php $this->_($this->candidateStatus); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge status-none">Not Set</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content Container -->
                    <div class="tab-content-container">
                        <!-- Resume Tab Content -->
                        <div id="resumeTabContent" style="display: flex; flex-direction: row; width: 100%;">
                            <div class="tab-left-panel">
                                <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 16px; color: #1f2937; font-weight: 700; font-family: 'Inter', system-ui, sans-serif; letter-spacing: -0.01em;">Resume / CV</h3>
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
                                <?php if (!empty($this->candidateStatus)): ?>
                                    <div class="status-badge-panel <?php echo strtolower($this->candidateStatus); ?>" style="margin-bottom: 20px; margin-top: 0;">
                                        Status: <?php $this->_($this->candidateStatus); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="status-badge-panel status-none" style="margin-bottom: 20px; margin-top: 0;">
                                        Status: Not Set
                                    </div>
                                <?php endif; ?>
                                <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 16px; color: #1f2937; font-weight: 700; font-family: 'Inter', system-ui, sans-serif; letter-spacing: -0.01em;">Candidate Details</h3>
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
                        <div id="feedbackTabContent" style="display: none; flex-direction: row; width: 100%;">
                            <div class="tab-left-panel">
                                <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 16px; color: #1f2937; font-weight: 700; font-family: 'Inter', system-ui, sans-serif; letter-spacing: -0.01em;">Feedback</h3>
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
                                <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 16px; color: #1f2937; font-weight: 700; font-family: 'Inter', system-ui, sans-serif; letter-spacing: -0.01em;">Candidate Details</h3>
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
                        <div id="emailTabContent" style="display: none; flex-direction: row; width: 100%;">
                            <div class="tab-left-panel">
                        <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 16px; color: #1f2937; font-weight: 700; font-family: 'Inter', system-ui, sans-serif; letter-spacing: -0.01em;">Email</h3>
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
                                <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 16px; color: #1f2937; font-weight: 700; font-family: 'Inter', system-ui, sans-serif; letter-spacing: -0.01em;">Candidate Details</h3>
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
