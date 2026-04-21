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
                var allTabs = ['resumeTabContent', 'feedbackTabContent', 'emailTabContent', 'documentsTabContent'];
                allTabs.forEach(function(id) {
                    var el = document.getElementById(id);
                    if (el) el.style.setProperty('display', 'none', 'important');
                });

                var resumeTab = document.getElementById('resumeTabContent');
                resumeTab.style.setProperty('display', 'flex', 'important');
                resumeTab.style.setProperty('flex-direction', 'row', 'important');
                resumeTab.style.setProperty('width', '100%', 'important');
            });

            function showTab(tabName) {
                var allTabs = ['resume', 'feedback', 'email', 'documents'];
                allTabs.forEach(function(name) {
                    var content = document.getElementById(name + 'TabContent');
                    var tab = document.getElementById(name + 'Tab');
                    if (content) content.style.setProperty('display', 'none', 'important');
                    if (tab) tab.classList.remove('active');
                });

                var tabContent = document.getElementById(tabName + 'TabContent');
                tabContent.style.setProperty('display', 'flex', 'important');
                tabContent.style.setProperty('flex-direction', 'row', 'important');
                tabContent.style.setProperty('width', '100%', 'important');
                document.getElementById(tabName + 'Tab').classList.add('active');

                if (tabName === 'documents') { loadCandidateDocuments(); }

                // Animate tab indicator
                var activeTabEl = document.getElementById(tabName + 'Tab');
                var indicator = document.getElementById('tabIndicator');
                if (activeTabEl && indicator) {
                    indicator.style.left = activeTabEl.offsetLeft + 'px';
                    indicator.style.width = activeTabEl.offsetWidth + 'px';
                }

                // Re-trigger fade-in animation on tab content
                tabContent.classList.remove('tab-fade-in');
                void tabContent.offsetWidth;
                tabContent.classList.add('tab-fade-in');
            }

            function saveFeedback() {
                // TODO: Implement feedback save functionality
                alert('Feedback save functionality will be implemented.');
            }

            function showEmailDetail(index) {
                var emailItems = document.querySelectorAll('.email-list-item');
                for (var i = 0; i < emailItems.length; i++) {
                    emailItems[i].classList.remove('active');
                }
                emailItems[index].classList.add('active');
            }

            function updateCandidateStatus(newStatusID) {
                var candidateJobOrderID = <?php echo isset($this->candidateJobOrderID) ? (int)$this->candidateJobOrderID : 0; ?>;
                var candidateID = <?php echo (int)$this->candidateID; ?>;
                var jobOrderID = <?php echo isset($this->candidateJobOrderJobID) ? (int)$this->candidateJobOrderJobID : 0; ?>;

                if (!candidateJobOrderID || !jobOrderID) {
                    alert('No pipeline found for this candidate.');
                    return;
                }

                var url = 'ajax.php?f=updatePipelineStatus'
                    + '&candidateJobOrderID=' + candidateJobOrderID
                    + '&candidateID=' + candidateID
                    + '&jobOrderID=' + jobOrderID
                    + '&statusID=' + newStatusID;

                var xhr = new XMLHttpRequest();
                xhr.open('GET', url, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200 && xhr.responseText.indexOf('<errorcode>0</errorcode>') !== -1) {
                            var dropdown = document.getElementById('profileStatusDropdown');
                            var selectedText = dropdown.options[dropdown.selectedIndex].text.trim();
                            dropdown.title = 'Current: ' + selectedText;
                        } else {
                            alert('Failed to update status. Please try again.');
                        }
                    }
                };
                xhr.send();
            }

            var candidateEmailTemplates = {
                <?php if (!empty($this->emailTemplatesRS)): ?>
                    <?php foreach ($this->emailTemplatesRS as $tpl): ?>
                        <?php echo json_encode($tpl['emailTemplateID']); ?>: <?php echo json_encode($tpl['text'] ?? ''); ?>,
                    <?php endforeach; ?>
                <?php endif; ?>
            };

            function loadCandidateTemplate(templateId) {
                if (templateId == '-1' || templateId === '') {
                    document.getElementById('candidateEmailBody').value = '';
                    return;
                }

                var text = candidateEmailTemplates[templateId] || '';
                var plainText = text.replace(/<br\s*\/?>/gi, '\n').replace(/<[^>]+>/g, '');
                document.getElementById('candidateEmailBody').value = plainText;
            }

            function toggleResumeView() {
                var textView = document.getElementById('resumeTextView');
                var fileView = document.getElementById('resumeFileView');
                var toggleBtn = document.getElementById('resumeViewToggle');
                if (!textView || !fileView || !toggleBtn) return;

                var textHidden = (textView.style.display === 'none');
                if (textHidden) {
                    fileView.style.display = 'none';
                    textView.style.display = '';
                    toggleBtn.textContent = 'Show File';
                } else {
                    textView.style.display = 'none';
                    fileView.style.display = '';
                    toggleBtn.textContent = 'Show Text';
                }
            }

            function validateCandidateEmail() {
                var subject = document.getElementById('candidateEmailSubject').value.trim();
                var body = document.getElementById('candidateEmailBody').value.trim();
                if (subject === '' || body === '') {
                    alert('Please fill in both the subject and body before sending.');
                    return false;
                }
                return true;
            }

            // Position tab indicator after DOM loads
            window.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    var activeTab = document.querySelector('.cand-tab.active');
                    var indicator = document.getElementById('tabIndicator');
                    if (activeTab && indicator) {
                        indicator.style.left = activeTab.offsetLeft + 'px';
                        indicator.style.width = activeTab.offsetWidth + 'px';
                    }
                }, 100);
            });

            function generateUploadLink() {
                var candidateID = <?php echo (int)$this->candidateID; ?>;
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'ajax/generateUploadLink.php?candidateID=' + candidateID + '&expiryDays=7', true);
                xhr.onload = function() {
                    try {
                        var resp = JSON.parse(xhr.responseText);
                        if (resp.success) {
                            document.getElementById('uploadLinkURL').value = resp.url;
                            document.getElementById('uploadLinkResult').style.display = 'block';
                        } else {
                            alert('Error: ' + resp.error);
                        }
                    } catch(e) {
                        alert('Failed to generate link. Please try again.');
                    }
                };
                xhr.send();
            }

            function copyUploadLink() {
                var input = document.getElementById('uploadLinkURL');
                input.select();
                document.execCommand('copy');
                var btn = input.nextElementSibling;
                btn.textContent = 'Copied!';
                setTimeout(function() { btn.textContent = 'Copy'; }, 2000);
            }

            // ==================== PIPELINE DROPDOWN FUNCTIONS ====================
            var pipelineJobOrders = [];
            var selectedJobOrderID = null;
            var pipelineDropdownOpen = false;

            function togglePipelineDropdown(event) {
                event.stopPropagation();
                var menu = document.getElementById('pipelineDropdownMenu');
                if (menu.classList.contains('show')) {
                    closePipelineDropdown();
                } else {
                    openPipelineDropdown();
                }
            }

            function openPipelineDropdown() {
                var menu = document.getElementById('pipelineDropdownMenu');
                var badge = document.getElementById('pipelineBadge');
                
                if (!badge || !menu) {
                    console.error('Pipeline badge or menu not found');
                    return;
                }
                
                var rect = badge.getBoundingClientRect();
                var menuWidth = 320;
                var menuHeight = 400;
                
                // Position below the badge
                var top = rect.bottom + 8;
                var left = rect.left;
                
                // Ensure dropdown doesn't go off-screen to the right
                if (left + menuWidth > window.innerWidth) {
                    left = window.innerWidth - menuWidth - 20;
                }
                
                // Ensure dropdown doesn't go off-screen at the bottom
                if (top + menuHeight > window.innerHeight) {
                    top = rect.top - menuHeight - 8; // Show above the badge
                }
                
                // Ensure left is not negative
                if (left < 10) left = 10;
                
                menu.style.top = top + 'px';
                menu.style.left = left + 'px';
                
                menu.classList.add('show');
                pipelineDropdownOpen = true;
                loadJobOrders();
                showJobOrderStep();
            }

            function closePipelineDropdown() {
                var menu = document.getElementById('pipelineDropdownMenu');
                if (menu) {
                    menu.classList.remove('show');
                }
                pipelineDropdownOpen = false;
                selectedJobOrderID = null;
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                var container = document.getElementById('pipelineBadgeContainer');
                var menu = document.getElementById('pipelineDropdownMenu');
                if (container && menu && !container.contains(event.target) && !menu.contains(event.target)) {
                    closePipelineDropdown();
                }
            });

            // Close dropdown on scroll or resize
            window.addEventListener('scroll', function() {
                if (pipelineDropdownOpen) {
                    closePipelineDropdown();
                }
            }, true);
            
            window.addEventListener('resize', function() {
                if (pipelineDropdownOpen) {
                    closePipelineDropdown();
                }
            });

            function loadJobOrders() {
                var container = document.getElementById('jobOrdersList');
                if (!container) {
                    console.error('jobOrdersList container not found');
                    return;
                }
                container.innerHTML = '<div class="pipeline-dropdown-empty"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: spin 1s linear infinite; margin-bottom: 8px;"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg><br>Loading job orders...</div>';

                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'ajax.php?f=getJobOrdersList', true);
                xhr.onload = function() {
                    try {
                        var resp = JSON.parse(xhr.responseText);
                        if (resp.error === 0 && resp.jobOrders && resp.jobOrders.length > 0) {
                            pipelineJobOrders = resp.jobOrders;
                            renderJobOrdersList(pipelineJobOrders);
                        } else if (resp.jobOrders && resp.jobOrders.length === 0) {
                            container.innerHTML = '<div class="pipeline-dropdown-empty"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 8px; opacity: 0.5;"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg><br>No job orders available.<br><small style="opacity: 0.6; font-size: 11px;">Create a job order first.</small></div>';
                        } else {
                            container.innerHTML = '<div class="pipeline-dropdown-empty">Error loading job orders</div>';
                        }
                    } catch(e) {
                        console.error('Parse error:', e, xhr.responseText);
                        container.innerHTML = '<div class="pipeline-dropdown-empty">Failed to parse response</div>';
                    }
                };
                xhr.onerror = function() {
                    container.innerHTML = '<div class="pipeline-dropdown-empty">Network error</div>';
                };
                xhr.send();
            }

            function renderJobOrdersList(jobOrders) {
                var container = document.getElementById('jobOrdersList');
                if (jobOrders.length === 0) {
                    container.innerHTML = '<div class="pipeline-dropdown-empty">No matching job orders found.</div>';
                    return;
                }

                var html = '';
                for (var i = 0; i < jobOrders.length; i++) {
                    var jo = jobOrders[i];
                    html += '<div class="pipeline-dropdown-item" onclick="selectJobOrder(' + jo.jobOrderID + ')">';
                    html += '<div class="pipeline-dropdown-item-icon">';
                    html += '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>';
                    html += '</div>';
                    html += '<div>';
                    html += '<div class="pipeline-dropdown-item-title">' + escapeHtml(jo.title) + '</div>';
                    html += '<div class="pipeline-dropdown-item-subtitle">' + escapeHtml(jo.companyName || 'No company') + '</div>';
                    html += '</div>';
                    html += '</div>';
                }
                container.innerHTML = html;
            }

            function filterJobOrders(query) {
                query = query.toLowerCase().trim();
                if (query === '') {
                    renderJobOrdersList(pipelineJobOrders);
                    return;
                }

                var filtered = pipelineJobOrders.filter(function(jo) {
                    return (jo.title && jo.title.toLowerCase().indexOf(query) !== -1) ||
                           (jo.companyName && jo.companyName.toLowerCase().indexOf(query) !== -1);
                });
                renderJobOrdersList(filtered);
            }

            function selectJobOrder(jobOrderID) {
                selectedJobOrderID = jobOrderID;
                showStatusStep();
            }

            function showJobOrderStep() {
                document.getElementById('pipelineStepJobOrder').style.display = 'block';
                document.getElementById('pipelineStepStatus').style.display = 'none';
                selectedJobOrderID = null;
            }

            function showStatusStep() {
                document.getElementById('pipelineStepJobOrder').style.display = 'none';
                document.getElementById('pipelineStepStatus').style.display = 'block';
            }

            function addToPipelineWithStatus(statusID) {
                if (!selectedJobOrderID) {
                    alert('Please select a job order first.');
                    return;
                }

                var candidateID = <?php echo (int)$this->candidateID; ?>;
                var url = 'ajax.php?f=addToPipeline&candidateID=' + candidateID + 
                          '&jobOrderID=' + selectedJobOrderID + 
                          '&statusID=' + statusID;

                var xhr = new XMLHttpRequest();
                xhr.open('GET', url, true);
                xhr.onload = function() {
                    if (xhr.responseText.indexOf('<errorcode>0</errorcode>') !== -1) {
                        closePipelineDropdown();
                        // Refresh the page to show updated pipeline
                        window.location.reload();
                    } else {
                        var errorMatch = xhr.responseText.match(/<errormessage>(.*?)<\/errormessage>/);
                        var errorMsg = errorMatch ? errorMatch[1] : 'Failed to add to pipeline.';
                        alert(errorMsg);
                    }
                };
                xhr.onerror = function() {
                    alert('Network error. Please try again.');
                };
                xhr.send();
            }

            function escapeHtml(text) {
                if (!text) return '';
                var div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
            // ==================== END PIPELINE DROPDOWN FUNCTIONS ====================

            var documentsLoaded = false;
            function loadCandidateDocuments(forceRefresh) {
                if (documentsLoaded && !forceRefresh) return;
                var candidateID = <?php echo (int)$this->candidateID; ?>;
                var container = document.getElementById('documentsListContainer');

                // Show loading state
                container.innerHTML = '<div style="text-align: center; padding: 40px; color: #9ca3af; font-size: 13px;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: spin 1s linear infinite; margin-bottom: 8px;"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg><br>Loading documents...</div>';

                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'ajax/getCandidateDocuments.php?candidateID=' + candidateID, true);
                xhr.onload = function() {
                    try {
                        var resp = JSON.parse(xhr.responseText);
                        if (resp.success && resp.documents && resp.documents.length > 0) {
                            var html = '<div style="display: flex; justify-content: flex-end; margin-bottom: 12px;"><button onclick="loadCandidateDocuments(true);" style="padding: 6px 12px; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 12px; color: #4b5563; cursor: pointer; display: flex; align-items: center; gap: 6px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg>Refresh</button></div>';
                            html += '<table style="width: 100%; border-collapse: collapse; font-size: 13px;">';
                            html += '<tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">';
                            html += '<th style="padding: 10px 14px; text-align: left; font-weight: 600; color: #374151;">Document</th>';
                            html += '<th style="padding: 10px 14px; text-align: left; font-weight: 600; color: #374151;">Type</th>';
                            html += '<th style="padding: 10px 14px; text-align: left; font-weight: 600; color: #374151;">Size</th>';
                            html += '<th style="padding: 10px 14px; text-align: left; font-weight: 600; color: #374151;">Uploaded</th>';
                            html += '<th style="padding: 10px 14px; text-align: left; font-weight: 600; color: #374151;">Status</th>';
                            html += '<th style="padding: 10px 14px; text-align: center; font-weight: 600; color: #374151;">Action</th>';
                            html += '</tr>';

                            for (var i = 0; i < resp.documents.length; i++) {
                                var d = resp.documents[i];
                                var statusColor = d.status === 'approved' ? '#059669' : (d.status === 'rejected' ? '#dc2626' : '#d97706');
                                var statusBg = d.status === 'approved' ? '#ecfdf5' : (d.status === 'rejected' ? '#fef2f2' : '#fffbeb');
                                html += '<tr style="border-bottom: 1px solid #f3f4f6;">';
                                html += '<td style="padding: 10px 14px;"><div style="font-weight: 600; color: #1f2937;">' + escapeHtml(d.original_filename) + '</div></td>';
                                html += '<td style="padding: 10px 14px; color: #6b7280;">' + escapeHtml(d.typeLabel) + '</td>';
                                html += '<td style="padding: 10px 14px; color: #6b7280;">' + d.file_size_kb + ' KB</td>';
                                html += '<td style="padding: 10px 14px; color: #6b7280;">' + escapeHtml(d.uploadedDateFormatted) + '</td>';
                                html += '<td style="padding: 10px 14px;"><span style="padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; background: ' + statusBg + '; color: ' + statusColor + ';">' + d.status + '</span></td>';
                                html += '<td style="padding: 10px 14px; text-align: center;"><a href="ajax/downloadDocument.php?id=' + d.document_id + '" target="_blank" style="color: #2563eb; font-weight: 600; text-decoration: none;">View</a></td>';
                                html += '</tr>';
                            }
                            html += '</table>';
                            container.innerHTML = html;
                        } else if (resp.success) {
                            container.innerHTML = '<div style="text-align: center; padding: 40px; color: #9ca3af; font-size: 13px;">' +
                                '<svg width="36" height="36" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 10px; display: block;"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>' +
                                'No documents uploaded yet.<br><span style="font-size: 11px; color: #b0b7c3; margin-top: 4px; display: inline-block;">Generate an upload link and share it with the candidate.</span>' +
                                '<br><button onclick="loadCandidateDocuments(true);" style="margin-top: 16px; padding: 8px 16px; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 12px; color: #4b5563; cursor: pointer;">Refresh</button></div>';
                        } else {
                            container.innerHTML = '<div style="text-align: center; padding: 40px; color: #ef4444; font-size: 13px;">Error: ' + (resp.error || 'Unknown error') + '</div>';
                        }
                        documentsLoaded = true;
                    } catch(e) {
                        console.error('Documents parse error:', e, xhr.responseText);
                        container.innerHTML = '<div style="text-align: center; padding: 40px; color: #ef4444; font-size: 13px;">Failed to load documents.<br><button onclick="loadCandidateDocuments(true);" style="margin-top: 16px; padding: 8px 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; font-size: 12px; color: #dc2626; cursor: pointer;">Try Again</button></div>';
                    }
                };
                xhr.onerror = function() {
                    container.innerHTML = '<div style="text-align: center; padding: 40px; color: #ef4444; font-size: 13px;">Network error loading documents.<br><button onclick="loadCandidateDocuments(true);" style="margin-top: 16px; padding: 8px 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; font-size: 12px; color: #dc2626; cursor: pointer;">Try Again</button></div>';
                };
                xhr.send();
            }

            // Delete candidate confirmation
            function confirmDeleteCandidate(candidateID, candidateName) {
                var modal = document.getElementById('deleteConfirmModal');
                var nameSpan = document.getElementById('deleteCandidateName');
                var confirmBtn = document.getElementById('confirmDeleteBtn');
                
                nameSpan.textContent = candidateName;
                confirmBtn.onclick = function() {
                    window.location.href = '<?php echo CATSUtility::getIndexName(); ?>?m=candidates&a=delete&candidateID=' + candidateID;
                };
                
                modal.classList.add('active');
            }

            function closeDeleteModal() {
                document.getElementById('deleteConfirmModal').classList.remove('active');
            }
        </script>

        <style type="text/css">
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

            * { box-sizing: border-box; }

            /* ===================== ANIMATIONS ===================== */
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(24px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to   { opacity: 1; }
            }
            @keyframes slideInRight {
                from { opacity: 0; transform: translateX(20px); }
                to   { opacity: 1; transform: translateX(0); }
            }
            @keyframes scaleIn {
                from { opacity: 0; transform: scale(0.92); }
                to   { opacity: 1; transform: scale(1); }
            }
            @keyframes pulseGlow {
                0%, 100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.25); }
                50% { box-shadow: 0 0 0 8px rgba(37, 99, 235, 0); }
            }

            .anim-fade-up-1 { animation: fadeInUp 0.5s ease-out both; animation-delay: 0.05s; }
            .anim-fade-up-2 { animation: fadeInUp 0.5s ease-out both; animation-delay: 0.15s; }
            .anim-fade-up-3 { animation: fadeInUp 0.5s ease-out both; animation-delay: 0.25s; }
            .anim-fade-up-4 { animation: fadeInUp 0.5s ease-out both; animation-delay: 0.35s; }
            .anim-fade-up-5 { animation: fadeInUp 0.5s ease-out both; animation-delay: 0.45s; }

            .tab-fade-in { animation: fadeIn 0.35s ease-out both; }

            /* ===================== PAGE OVERRIDES ===================== */
            body { padding: 0 !important; font-family: 'Inter', system-ui, -apple-system, sans-serif !important; }
            
            /* Keep sidebar layout intact - don't override margin-left */
            #main { 
                padding-top: var(--topbar-height, 56px) !important;
                /* margin-left is controlled by main.css for sidebar */
            }
            
            #contents {
                position: relative;
                background: var(--gray-50, #f8fafc) !important;
                min-height: calc(100vh - var(--topbar-height, 56px));
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                overflow-x: hidden !important;
            }

            .candidate-page-wrapper {
                background: var(--gray-50, #f8fafc);
                min-height: calc(100vh - var(--topbar-height, 56px));
                padding: 0;
                position: relative;
                width: 100%;
                max-width: 100%;
                overflow-x: hidden;
            }
            
            .candidate-main-panel {
                width: 100%;
                max-width: 100%;
                overflow-x: hidden;
            }

            /* ===================== HERO PROFILE HEADER ===================== */
            .profile-hero {
                background: linear-gradient(135deg, #2563eb 0%, #1e40af 60%, #1e3a8a 100%);
                padding: 0;
                position: relative;
                overflow: visible;
                border-radius: 0;
                width: 100%;
                max-width: 100%;
            }
            .profile-hero::before {
                content: '';
                position: absolute;
                top: -50%;
                right: -10%;
                width: 400px;
                height: 400px;
                background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
                pointer-events: none;
            }
            .profile-hero::after {
                content: '';
                position: absolute;
                bottom: -30%;
                left: 10%;
                width: 300px;
                height: 300px;
                background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
                pointer-events: none;
            }
            .profile-hero-inner {
                display: flex;
                align-items: center;
                gap: 24px;
                padding: 32px 24px 24px 24px;
                position: relative;
                z-index: 1;
                flex-wrap: wrap;
                width: 100%;
                max-width: 100%;
            }
            .profile-avatar {
                width: 88px;
                height: 88px;
                border-radius: 50%;
                background: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 32px;
                font-weight: 700;
                color: var(--primary, #2563eb);
                flex-shrink: 0;
                box-shadow: 0 4px 24px rgba(0,0,0,0.15);
                border: 3px solid rgba(255,255,255,0.3);
                letter-spacing: -0.02em;
            }
            .profile-info { flex: 1; min-width: 0; overflow: visible; }
            .profile-name {
                font-size: 28px;
                font-weight: 800;
                color: #fff;
                margin: 0 0 8px 0;
                line-height: 1.15;
                letter-spacing: -0.02em;
                font-family: 'Inter', system-ui, sans-serif;
            }
            .profile-meta {
                display: flex;
                align-items: center;
                gap: 16px;
                flex-wrap: wrap;
                margin-bottom: 14px;
                overflow: visible;
                position: relative;
            }

            .profile-status-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 5px 14px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: 0.02em;
            }
            .profile-status-badge.selected, .profile-status-badge.placed { background: rgba(22, 163, 74, 0.2); color: #bbf7d0; }
            .profile-status-badge.rejected, .profile-status-badge.client-declined { background: rgba(220, 38, 38, 0.2); color: #fecaca; }
            .profile-status-badge.interviewing, .profile-status-badge.offered { background: rgba(37, 99, 235, 0.2); color: #bfdbfe; }
            .profile-status-badge.qualifying, .profile-status-badge.submitted { background: rgba(124, 58, 237, 0.2); color: #ddd6fe; }
            .profile-status-badge.contacted, .profile-status-badge.candidate-responded { background: rgba(217, 119, 6, 0.2); color: #fde68a; }
            .profile-status-badge.none, .profile-status-badge.no-contact { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.7); }
            /* Custom Status Badge Colors */
            .profile-status-badge.screen-select { background: rgba(34, 197, 94, 0.2); color: #86efac; }
            .profile-status-badge.screen-reject { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }
            .profile-status-badge.l1-select { background: rgba(59, 130, 246, 0.2); color: #93c5fd; }
            .profile-status-badge.l2-select { background: rgba(139, 92, 246, 0.2); color: #c4b5fd; }
            .profile-status-badge.l3-select { background: rgba(236, 72, 153, 0.2); color: #f9a8d4; }
            .profile-status-badge.offer-release { background: rgba(251, 191, 36, 0.2); color: #fde68a; }
            .profile-status-badge.onboarded { background: rgba(16, 185, 129, 0.2); color: #6ee7b7; }
            .profile-status-badge.no-show { background: rgba(107, 114, 128, 0.2); color: #d1d5db; }

            /* Clickable Pipeline Badge Styles */
            .pipeline-badge-container {
                position: relative;
                display: inline-block;
                z-index: 100;
            }
            .profile-status-badge.clickable {
                cursor: pointer;
                transition: all 0.2s ease;
                user-select: none;
            }
            .profile-status-badge.clickable:hover {
                background: rgba(255,255,255,0.25);
                transform: translateY(-1px);
            }
            .profile-status-badge.clickable:active {
                transform: scale(0.98);
            }
            .pipeline-dropdown-menu {
                position: fixed;
                min-width: 320px;
                max-height: 450px;
                overflow-y: auto;
                overflow-x: hidden;
                background: #1e293b;
                border: 1px solid rgba(255,255,255,0.2);
                border-radius: 12px;
                box-shadow: 0 25px 60px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.1);
                z-index: 99999;
                display: none;
                animation: dropdownFadeIn 0.2s ease;
            }
            .pipeline-dropdown-menu.show {
                display: block !important;
            }
            @keyframes dropdownFadeIn {
                from { opacity: 0; transform: translateY(-8px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .pipeline-dropdown-header {
                padding: 14px 16px 10px;
                border-bottom: 1px solid rgba(255,255,255,0.1);
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: rgba(255,255,255,0.6);
                background: rgba(0,0,0,0.2);
            }
            .pipeline-dropdown-search {
                padding: 12px;
                border-bottom: 1px solid rgba(255,255,255,0.1);
                background: rgba(0,0,0,0.1);
            }
            .pipeline-dropdown-search input {
                width: 100%;
                padding: 10px 14px;
                border: 1px solid rgba(255,255,255,0.2);
                border-radius: 8px;
                background: rgba(255,255,255,0.08);
                color: #fff;
                font-size: 13px;
                outline: none;
                transition: all 0.2s ease;
            }
            .pipeline-dropdown-search input:focus {
                border-color: #3b82f6;
                background: rgba(255,255,255,0.12);
                box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
            }
            .pipeline-dropdown-search input::placeholder {
                color: rgba(255,255,255,0.4);
            }
            .pipeline-dropdown-item {
                padding: 12px 16px;
                cursor: pointer;
                transition: all 0.15s ease;
                display: flex;
                align-items: center;
                gap: 12px;
                border-bottom: 1px solid rgba(255,255,255,0.05);
            }
            .pipeline-dropdown-item:last-child {
                border-bottom: none;
            }
            .pipeline-dropdown-item:hover {
                background: rgba(59, 130, 246, 0.15);
            }
            .pipeline-dropdown-item.selected {
                background: rgba(59, 130, 246, 0.3);
            }
            .pipeline-dropdown-item-title {
                font-size: 14px;
                font-weight: 600;
                color: #fff;
                line-height: 1.3;
            }
            .pipeline-dropdown-item-subtitle {
                font-size: 12px;
                color: rgba(255,255,255,0.5);
                margin-top: 2px;
            }
            .pipeline-dropdown-item-icon {
                width: 36px;
                height: 36px;
                border-radius: 8px;
                background: linear-gradient(135deg, rgba(59,130,246,0.3) 0%, rgba(139,92,246,0.3) 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            .pipeline-dropdown-item-icon svg {
                opacity: 0.9;
                color: #fff;
            }
            .pipeline-dropdown-divider {
                height: 1px;
                background: rgba(255,255,255,0.1);
                margin: 4px 0;
            }
            .pipeline-dropdown-section {
                max-height: 250px;
                overflow-y: auto;
                padding: 0;
            }
            .pipeline-dropdown-empty {
                padding: 30px 20px;
                text-align: center;
                color: rgba(255,255,255,0.6);
                font-size: 13px;
                background: rgba(0,0,0,0.1);
            }
            .pipeline-status-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
                padding: 12px;
            }
            .pipeline-status-item {
                padding: 14px 12px;
                border-radius: 10px;
                cursor: pointer;
                transition: all 0.15s ease;
                text-align: center;
                border: 1px solid transparent;
            }
            .pipeline-status-item:hover {
                transform: scale(1.03);
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            }
            .pipeline-status-item.screen-select { background: rgba(34, 197, 94, 0.2); color: #86efac; }
            .pipeline-status-item.screen-reject { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }
            .pipeline-status-item.l1-select { background: rgba(59, 130, 246, 0.2); color: #93c5fd; }
            .pipeline-status-item.l2-select { background: rgba(139, 92, 246, 0.2); color: #c4b5fd; }
            .pipeline-status-item.l3-select { background: rgba(236, 72, 153, 0.2); color: #f9a8d4; }
            .pipeline-status-item.offer-release { background: rgba(251, 191, 36, 0.2); color: #fde68a; }
            .pipeline-status-item.onboarded { background: rgba(16, 185, 129, 0.2); color: #6ee7b7; }
            .pipeline-status-item.no-show { background: rgba(107, 114, 128, 0.2); color: #d1d5db; }
            .pipeline-status-item span {
                font-size: 12px;
                font-weight: 600;
            }
            .pipeline-back-btn {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 12px 16px;
                cursor: pointer;
                transition: all 0.15s ease;
                border-bottom: 1px solid rgba(255,255,255,0.1);
                color: rgba(255,255,255,0.7);
                font-size: 13px;
                font-weight: 500;
                background: rgba(0,0,0,0.2);
            }
            .pipeline-back-btn:hover {
                background: rgba(59, 130, 246, 0.2);
                color: #fff;
            }
            .pipeline-back-btn svg {
                opacity: 0.7;
            }
            .pipeline-back-btn:hover svg {
                opacity: 1;
            }

            .profile-status-dropdown {
                appearance: none;
                -webkit-appearance: none;
                background: rgba(255,255,255,0.12);
                color: #fff;
                border: 1px solid rgba(255,255,255,0.25);
                border-radius: 20px;
                padding: 5px 30px 5px 14px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: 0.02em;
                cursor: pointer;
                outline: none;
                transition: all 0.2s ease;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 10px center;
            }
            .profile-status-dropdown:hover {
                background-color: rgba(255,255,255,0.2);
                border-color: rgba(255,255,255,0.4);
            }
            .profile-status-dropdown:focus {
                border-color: #60a5fa;
                box-shadow: 0 0 0 2px rgba(96, 165, 250, 0.3);
            }
            .profile-status-dropdown option {
                background: #1f2937;
                color: #fff;
                padding: 8px;
            }

            .profile-contact-row {
                display: flex;
                align-items: center;
                gap: 20px;
                flex-wrap: wrap;
            }
            .profile-contact-item {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 13px;
                color: rgba(255,255,255,0.85);
                text-decoration: none;
                transition: color 0.2s;
            }
            .profile-contact-item:hover { color: #fff; }
            .profile-contact-item svg { flex-shrink: 0; opacity: 0.7; }
            .profile-contact-item a { color: inherit; text-decoration: none; }
            .profile-contact-item a:hover { color: #fff; text-decoration: underline; }

            .profile-actions {
                display: flex;
                gap: 10px;
                flex-shrink: 0;
                align-items: flex-start;
                flex-wrap: wrap;
            }
            .profile-action-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 9px 18px;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 600;
                font-family: 'Inter', system-ui, sans-serif;
                cursor: pointer;
                transition: all 0.2s ease;
                border: none;
                text-decoration: none !important;
                white-space: nowrap;
            }
            .profile-action-btn.btn-white {
                background: #fff;
                color: #2563eb;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            .profile-action-btn.btn-white:hover {
                background: #f0f4ff;
                transform: translateY(-1px);
                box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            }
            .profile-action-btn.btn-outline {
                background: rgba(255,255,255,0.12);
                color: #fff;
                border: 1px solid rgba(255,255,255,0.25);
            }
            .profile-action-btn.btn-outline:hover {
                background: rgba(255,255,255,0.22);
                transform: translateY(-1px);
            }
            .profile-action-btn.btn-danger {
                background: rgba(220, 38, 38, 0.15);
                color: #fff;
                border: 1px solid rgba(220, 38, 38, 0.4);
            }
            .profile-action-btn.btn-danger:hover {
                background: #dc2626;
                border-color: #dc2626;
                transform: translateY(-1px);
            }

            /* Delete Confirmation Modal */
            .delete-modal-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 10000;
                align-items: center;
                justify-content: center;
            }
            .delete-modal-overlay.active {
                display: flex;
            }
            .delete-modal {
                background: #fff;
                border-radius: 16px;
                padding: 32px;
                max-width: 420px;
                width: 90%;
                text-align: center;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                animation: modalSlideIn 0.3s ease;
            }
            @keyframes modalSlideIn {
                from { opacity: 0; transform: scale(0.9) translateY(-20px); }
                to { opacity: 1; transform: scale(1) translateY(0); }
            }
            .delete-modal-icon {
                width: 64px;
                height: 64px;
                background: #fee2e2;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
            }
            .delete-modal-icon svg {
                width: 32px;
                height: 32px;
                color: #dc2626;
            }
            .delete-modal h3 {
                font-size: 20px;
                font-weight: 700;
                color: #1f2937;
                margin: 0 0 12px;
            }
            .delete-modal p {
                font-size: 14px;
                color: #6b7280;
                margin: 0 0 24px;
                line-height: 1.5;
            }
            .delete-modal p strong {
                color: #1f2937;
            }
            .delete-modal-actions {
                display: flex;
                gap: 12px;
                justify-content: center;
            }
            .delete-modal-btn {
                padding: 12px 24px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
                border: none;
            }
            .delete-modal-btn.cancel {
                background: #f3f4f6;
                color: #374151;
            }
            .delete-modal-btn.cancel:hover {
                background: #e5e7eb;
            }
            .delete-modal-btn.confirm {
                background: #dc2626;
                color: #fff;
            }
            .delete-modal-btn.confirm:hover {
                background: #b91c1c;
            }

            /* ===================== INFO CARDS ROW ===================== */
            .info-cards-row {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 20px;
                padding: 24px;
                background: var(--gray-50, #f8fafc);
                width: 100%;
                max-width: 100%;
            }
            .info-card {
                background: #fff;
                border-radius: 12px;
                border: 1px solid var(--gray-200, #e5e7eb);
                box-shadow: 0 1px 3px rgba(0,0,0,0.04);
                overflow: hidden;
                transition: all 0.25s ease;
            }
            .info-card:hover {
                box-shadow: 0 8px 24px rgba(0,0,0,0.08);
                transform: translateY(-2px);
                border-color: var(--primary, #2563eb);
            }
            .info-card-header {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 16px 20px 12px;
                border-bottom: 1px solid var(--gray-100, #f3f4f6);
            }
            .info-card-icon {
                width: 36px;
                height: 36px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            .info-card-icon.blue    { background: #eff6ff; color: #2563eb; }
            .info-card-icon.green   { background: #f0fdf4; color: #16a34a; }
            .info-card-icon.purple  { background: #f5f3ff; color: #7c3aed; }
            .info-card-title {
                font-size: 14px;
                font-weight: 700;
                color: var(--gray-800, #1f2937);
                font-family: 'Inter', system-ui, sans-serif;
            }
            .info-card-body { padding: 16px 20px; }
            .info-row {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                padding: 8px 0;
                border-bottom: 1px solid var(--gray-50, #f8fafc);
            }
            .info-row:last-child { border-bottom: none; }
            .info-row-label {
                font-size: 12px;
                font-weight: 500;
                color: var(--gray-500, #6b7280);
                text-transform: uppercase;
                letter-spacing: 0.04em;
                flex-shrink: 0;
                min-width: 90px;
            }
            .info-row-value {
                font-size: 13px;
                color: var(--gray-800, #1f2937);
                font-weight: 500;
                text-align: right;
                word-break: break-word;
            }
            .info-row-value a {
                color: var(--primary, #2563eb);
                text-decoration: none;
            }
            .info-row-value a:hover { text-decoration: underline; }

            /* Skill pills */
            .skill-pills {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                padding-top: 4px;
            }
            .skill-pill {
                display: inline-block;
                padding: 4px 12px;
                background: #eff6ff;
                color: #2563eb;
                border-radius: 20px;
                font-size: 11px;
                font-weight: 600;
                letter-spacing: 0.02em;
                transition: all 0.2s;
                border: 1px solid #dbeafe;
            }
            .skill-pill:hover {
                background: #2563eb;
                color: #fff;
                transform: scale(1.05);
            }

            /* ===================== TABS SECTION ===================== */
            .cand-tabs-bar {
                display: flex;
                align-items: center;
                background: #fff;
                padding: 0 24px;
                border-bottom: 2px solid var(--gray-200, #e5e7eb);
                position: relative;
                width: 100%;
                max-width: 100%;
                overflow-x: auto;
            }
            .cand-tabs-list {
                display: flex;
                list-style: none;
                margin: 0;
                padding: 0;
                gap: 0;
                position: relative;
            }
            .cand-tab {
                margin: 0;
                padding: 0;
            }
            .cand-tab a {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 14px 28px;
                text-decoration: none !important;
                color: var(--gray-500, #6b7280);
                font-weight: 500;
                font-size: 14px;
                cursor: pointer;
                transition: all 0.2s ease;
                font-family: 'Inter', system-ui, sans-serif;
                border-bottom: 3px solid transparent;
                margin-bottom: -2px;
                position: relative;
            }
            .cand-tab a svg { opacity: 0.5; transition: opacity 0.2s; }
            .cand-tab.active a {
                color: var(--primary, #2563eb);
                font-weight: 600;
            }
            .cand-tab.active a svg { opacity: 1; }
            .cand-tab a:hover {
                color: var(--primary, #2563eb);
                background: var(--gray-50, #f8fafc);
                border-radius: 6px 6px 0 0;
            }

            /* Sliding tab indicator */
            .tab-indicator {
                position: absolute;
                bottom: -2px;
                height: 3px;
                background: var(--primary, #2563eb);
                border-radius: 3px 3px 0 0;
                transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* ===================== TAB CONTENT PANELS ===================== */
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

            .tab-left-panel {
                flex: 1 1 auto !important;
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                padding: 24px;
                border-right: 1px solid var(--gray-200, #e5e7eb);
                background: var(--gray-50, #f8fafc);
                overflow-y: auto;
                max-height: calc(100vh - 200px);
                min-height: 450px;
                margin: 0 !important;
                float: none !important;
                box-sizing: border-box !important;
            }

            .tab-right-panel {
                flex: 0 0 320px !important;
                width: 320px !important;
                max-width: 320px !important;
                min-width: 280px !important;
                padding: 20px;
                background: #fff !important;
                overflow-y: auto;
                max-height: calc(100vh - 200px);
                min-height: 450px;
                position: sticky;
                top: 0;
                align-self: flex-start;
                margin: 0 !important;
                float: none !important;
                display: flex !important;
                flex-direction: column !important;
                box-sizing: border-box !important;
            }

            .tab-right-panel h3 {
                font-size: 15px !important;
                margin-top: 0 !important;
                margin-bottom: 16px !important;
                color: #1f2937 !important;
                font-weight: 700 !important;
                font-family: 'Inter', system-ui, sans-serif !important;
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
                border-radius: 0 8px 8px 0;
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

            /* ===================== RESUME VIEWER ===================== */
            .resume-viewer {
                background: #fff;
                border: 1px solid var(--gray-200, #e5e7eb);
                border-radius: 12px;
                font-family: 'Inter', system-ui, sans-serif;
                font-size: 13px;
                line-height: 1.8;
                color: var(--gray-700, #374151);
                min-height: 400px;
                box-shadow: 0 1px 4px rgba(0,0,0,0.04);
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
                overflow: hidden;
                display: flex;
                flex-direction: column;
            }
            .resume-viewer-text {
                padding: 28px 32px;
                white-space: pre-wrap;
                overflow-y: auto;
                max-height: 70vh;
                min-height: 400px;
                font: normal 13px/1.8 'Inter', -apple-system, sans-serif;
                color: #1f2937;
                background: #fff;
            }
            .resume-viewer-text.resume-document {
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.06);
                padding: 32px 36px;
                font: normal 13.5px/1.9 'Inter', 'Georgia', serif;
                color: #111827;
                letter-spacing: 0.01em;
            }
            .resume-viewer iframe,
            .resume-viewer embed {
                width: 100%;
                min-height: 500px;
                height: 70vh;
                border: none;
                border-radius: 0 0 12px 12px;
            }
            .resume-toolbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 6px 12px;
                background: #f8fafc;
                border-bottom: 1px solid #e5e7eb;
                border-radius: 8px 8px 0 0;
                flex-shrink: 0;
            }
            .resume-toolbar .resume-file-name {
                font-size: 12px;
                font-weight: 500;
                color: #374151;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .resume-toolbar .resume-actions {
                display: flex;
                gap: 6px;
                flex-shrink: 0;
            }
            .resume-toolbar .resume-actions a {
                padding: 4px 10px;
                font-size: 11px;
                font-weight: 500;
                color: #2563eb;
                border: 1px solid #2563eb;
                border-radius: 4px;
                text-decoration: none;
                transition: all 0.2s ease;
            }
            .resume-toolbar .resume-actions a:hover {
                background: #2563eb;
                color: #fff;
                box-shadow: 0 2px 6px rgba(37,99,235,0.25);
            }
            .resume-viewer h3, .resume-viewer h4 {
                margin-top: 18px;
                margin-bottom: 8px;
                color: var(--gray-800, #1f2937);
            }
            .resume-viewer p { margin-bottom: 12px; }
            .resume-viewer ul { margin-left: 18px; margin-bottom: 12px; }

            /* ===================== CANDIDATE DETAILS TABLE ===================== */
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
                table-layout: fixed;
            }
            .candidate-details-table td.label {
                font-weight: 600;
                color: var(--gray-500, #6b7280);
                width: 38%;
                font-size: 11px;
                padding-right: 12px;
                padding-bottom: 10px;
                padding-top: 10px;
                text-transform: uppercase;
                letter-spacing: 0.03em;
                display: table-cell;
                vertical-align: top;
                word-wrap: break-word;
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
                width: 62%;
            }
            .candidate-details-table tr { display: table-row; margin-bottom: 0; }
            .candidate-details-table tr:last-child td.value { border-bottom: none; }

            /* ===================== FEEDBACK ===================== */
            .feedback-two-column {
                display: flex;
                gap: 20px;
                align-items: flex-start;
                width: 100%;
            }
            .feedback-input-column,
            .feedback-display-column {
                flex: 1;
                background: #fff;
                padding: 24px;
                border: 1px solid var(--gray-200, #e5e7eb);
                border-radius: 12px;
                min-height: 400px;
                box-shadow: 0 1px 4px rgba(0,0,0,0.04);
                transition: box-shadow 0.25s;
            }
            .feedback-input-column:hover,
            .feedback-display-column:hover {
                box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            }
            .form-group { margin-bottom: 16px; }
            .form-group label {
                display: block;
                font-weight: 600;
                margin-bottom: 6px;
                color: var(--gray-700, #374151);
                font-size: 13px;
                font-family: 'Inter', system-ui, sans-serif;
            }
            .form-group select,
            .form-group textarea {
                width: 100%;
                padding: 10px 12px;
                border: 1px solid var(--gray-300, #d1d5db);
                border-radius: 8px;
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

            /* ===================== BUTTONS ===================== */
            .btn-primary {
                background: var(--primary, #2563eb);
                color: #fff;
                border: none;
                padding: 10px 22px;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 600;
                font-size: 13px;
                font-family: 'Inter', system-ui, sans-serif;
                transition: all 0.2s ease;
            }
            .btn-primary:hover {
                background: var(--primary-dark, #1d4ed8);
                box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);
                transform: translateY(-1px);
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
                border-radius: 8px;
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
                box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);
                transform: translateY(-1px);
            }

            /* ===================== EMAIL ===================== */
            .email-two-column {
                display: flex;
                gap: 20px;
                align-items: flex-start;
                width: 100%;
            }
            .email-list-column,
            .email-detail-column {
                flex: 1;
                background: #fff;
                padding: 24px;
                border: 1px solid var(--gray-200, #e5e7eb);
                border-radius: 12px;
                min-height: 400px;
                box-shadow: 0 1px 4px rgba(0,0,0,0.04);
                transition: box-shadow 0.25s;
            }
            .email-list-column:hover,
            .email-detail-column:hover {
                box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            }
            .email-list-column {
                max-height: 550px;
                overflow-y: auto;
            }
            .email-list-item {
                padding: 14px;
                margin-bottom: 8px;
                border: 1px solid var(--gray-200, #e5e7eb);
                border-radius: 10px;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            .email-list-item:hover {
                background: var(--gray-50, #f8fafc);
                border-color: var(--primary, #2563eb);
                box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                transform: translateX(2px);
            }
            .email-list-item.active {
                background: #eff6ff;
                border-color: var(--primary, #2563eb);
            }

            /* ===================== JOB ORDERS SECTION ===================== */
            .section-card {
                background: #fff;
                border-radius: 12px;
                border: 1px solid var(--gray-200, #e5e7eb);
                box-shadow: 0 1px 4px rgba(0,0,0,0.04);
                margin: 0 20px 20px 20px;
                overflow: hidden;
                transition: box-shadow 0.25s;
                max-width: calc(100% - 40px);
            }
            .section-card:hover {
                box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            }
            .section-card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 18px 24px;
                border-bottom: 1px solid var(--gray-100, #f3f4f6);
                background: #fff;
            }
            .section-card-title {
                font-size: 16px;
                font-weight: 700;
                color: var(--gray-800, #1f2937);
                font-family: 'Inter', system-ui, sans-serif;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .section-card-title svg { color: var(--primary, #2563eb); }

            .section-card table.sortablepair,
            .section-card table.sortable {
                width: 100%;
                border-collapse: collapse;
                margin: 0;
            }
            .section-card table th {
                background: #f8fafc;
                padding: 12px 16px;
                font-size: 11px;
                font-weight: 600;
                color: var(--gray-500, #6b7280);
                text-transform: uppercase;
                letter-spacing: 0.05em;
                text-align: left;
                font-family: 'Inter', system-ui, sans-serif;
                border-bottom: 1px solid var(--gray-200, #e5e7eb);
            }
            .section-card table td {
                padding: 12px 16px;
                font-size: 13px;
                color: var(--gray-700, #374151);
                border-bottom: 1px solid var(--gray-50, #f8fafc);
                font-family: 'Inter', system-ui, sans-serif;
            }
            .section-card table tr:hover td {
                background: #f8fafc;
            }
            .section-card table tr:last-child td { border-bottom: none; }
            .section-card table a {
                color: var(--primary, #2563eb);
                text-decoration: none;
                font-weight: 500;
            }
            .section-card table a:hover { text-decoration: underline; }

            .section-card-footer {
                padding: 14px 24px;
                border-top: 1px solid var(--gray-100, #f3f4f6);
                background: #fafbfc;
            }
            .section-card-footer a {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                color: var(--primary, #2563eb);
                font-size: 13px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.2s;
            }
            .section-card-footer a:hover { text-decoration: underline; }

            /* ===================== ACTIVITY TIMELINE ===================== */
            .activity-timeline {
                padding: 0 24px 16px;
            }
            .activity-timeline-item {
                display: flex;
                gap: 16px;
                padding: 16px 0;
                border-bottom: 1px solid var(--gray-50, #f8fafc);
                transition: background 0.2s;
                border-radius: 8px;
                margin: 0 -8px;
                padding-left: 8px;
                padding-right: 8px;
            }
            .activity-timeline-item:hover { background: #f8fafc; }
            .activity-timeline-item:last-child { border-bottom: none; }
            .activity-dot-col {
                display: flex;
                flex-direction: column;
                align-items: center;
                padding-top: 4px;
            }
            .activity-dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background: var(--primary, #2563eb);
                flex-shrink: 0;
                box-shadow: 0 0 0 3px #eff6ff;
            }
            .activity-line {
                width: 2px;
                flex: 1;
                background: var(--gray-200, #e5e7eb);
                margin-top: 4px;
            }
            .activity-content { flex: 1; min-width: 0; }
            .activity-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 4px;
            }
            .activity-type {
                font-size: 13px;
                font-weight: 600;
                color: var(--gray-800, #1f2937);
            }
            .activity-date {
                font-size: 11px;
                color: var(--gray-400, #9ca3af);
            }
            .activity-regarding {
                font-size: 12px;
                color: var(--gray-600, #4b5563);
                margin-bottom: 2px;
            }
            .activity-notes {
                font-size: 12px;
                color: var(--gray-500, #6b7280);
                line-height: 1.5;
            }
            .activity-entered {
                font-size: 11px;
                color: var(--gray-400, #9ca3af);
                margin-top: 4px;
            }
            .activity-actions {
                display: flex;
                gap: 4px;
                margin-left: 8px;
                flex-shrink: 0;
                align-self: flex-start;
            }
            .activity-actions a {
                opacity: 0;
                transition: opacity 0.2s;
            }
            .activity-timeline-item:hover .activity-actions a { opacity: 1; }

            /* Override old table-based activity display */
            #activityTable { display: none; }

            /* ===================== RESPONSIVE ===================== */
            
            /* Ensure content works with sidebar */
            @media (min-width: 1025px) {
                .candidate-page-wrapper {
                    width: 100%;
                    max-width: 100%;
                    overflow-x: hidden;
                }
                .profile-hero {
                    width: 100%;
                    max-width: 100%;
                }
                .info-cards-row {
                    max-width: 100%;
                }
            }
            
            @media (max-width: 1200px) {
                .tab-right-panel {
                    flex: 0 0 280px !important;
                    width: 280px !important;
                    max-width: 280px !important;
                    min-width: 250px !important;
                }
            }
            
            @media (max-width: 1024px) {
                .info-cards-row {
                    grid-template-columns: 1fr;
                    padding: 16px 20px;
                }
                .profile-hero-inner {
                    padding: 20px 16px;
                    flex-wrap: wrap;
                }
                .profile-actions {
                    width: 100%;
                    flex-wrap: wrap;
                }
                .section-card { margin: 0 12px 16px; }
                .tab-left-panel {
                    flex: 0 0 100% !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    min-width: 100% !important;
                }
                .tab-right-panel { display: none !important; }
                .cand-tabs-bar { padding: 0 16px; }
            }

            @media (max-width: 768px) {
                .profile-hero-inner {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 16px;
                }
                .profile-avatar { width: 64px; height: 64px; font-size: 24px; }
                .profile-name { font-size: 22px; }
                .info-cards-row { padding: 12px 16px; }
                .feedback-two-column,
                .email-two-column {
                    flex-direction: column;
                }
                .section-card { margin: 0 8px 12px; }
            }

            /* ===================== TAB SECTION HEADERS ===================== */
            .tab-section-title {
                margin-top: 0;
                margin-bottom: 20px;
                font-size: 17px;
                color: #1f2937;
                font-weight: 700;
                font-family: 'Inter', system-ui, sans-serif;
                letter-spacing: -0.01em;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .tab-section-title svg { color: var(--primary, #2563eb); }
        </style>

        <div id="contents">
            <div class="candidate-page-wrapper">
                <div class="candidate-main-panel">

                    <!-- ==================== HERO PROFILE HEADER ==================== -->
                    <div class="profile-hero anim-fade-up-1">
                        <div class="profile-hero-inner">
                            <div class="profile-avatar">
                                <?php echo strtoupper(substr($this->data['firstName'], 0, 1) . substr($this->data['lastName'], 0, 1)); ?>
                            </div>
                            <div class="profile-info">
                                <h1 class="profile-name"><?php $this->_($this->data['firstName']); ?> <?php $this->_($this->data['middleName']); ?> <?php $this->_($this->data['lastName']); ?></h1>
                                <div class="profile-meta">
                                    <?php if (!empty($this->pipelinesRS)): ?>
                                        <select class="profile-status-dropdown" id="profileStatusDropdown" onchange="updateCandidateStatus(this.value)">
                                            <?php
                                                $statusOptions = array(
                                                    100 => 'No Contact',
                                                    200 => 'Contacted',
                                                    250 => 'Candidate Responded',
                                                    300 => 'Qualifying',
                                                    400 => 'Submitted',
                                                    500 => 'Interviewing',
                                                    600 => 'Offered',
                                                    700 => 'Client Declined',
                                                    800 => 'Placed',
                                                    900 => 'Selected',
                                                    950 => 'Rejected',
                                                    1000 => 'Screen Select',
                                                    1010 => 'Screen Reject',
                                                    1020 => 'L1 Select',
                                                    1030 => 'L2 Select',
                                                    1040 => 'L3 Select',
                                                    1050 => 'Offer Release',
                                                    1060 => 'Onboarded',
                                                    1070 => 'No Show'
                                                );
                                                foreach ($statusOptions as $statusVal => $statusLabel):
                                            ?>
                                                <option value="<?php echo $statusVal; ?>" <?php echo ($this->candidateStatusID == $statusVal) ? 'selected' : ''; ?>>
                                                    <?php echo $statusLabel; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else: ?>
                                        <div class="pipeline-badge-container" id="pipelineBadgeContainer">
                                            <span class="profile-status-badge none clickable" id="pipelineBadge" onclick="togglePipelineDropdown(event)">
                                                <svg width="8" height="8" viewBox="0 0 8 8" fill="currentColor"><circle cx="4" cy="4" r="4"/></svg>
                                                No Pipeline
                                                <svg width="10" height="10" viewBox="0 0 16 16" fill="currentColor" style="margin-left: 4px;"><path d="M8 11L3 6h10z"/></svg>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($this->data['currentEmployer'])): ?>
                                        <span style="color: rgba(255,255,255,0.7); font-size: 13px;">at <?php $this->_($this->data['currentEmployer']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="profile-contact-row">
                                    <?php if (!empty($this->data['email1'])): ?>
                                    <span class="profile-contact-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                        <a href="mailto:<?php $this->_($this->data['email1']); ?>"><?php $this->_($this->data['email1']); ?></a>
                                    </span>
                                    <?php endif; ?>
                                    <?php
                                        $heroPhone = !empty($this->data['phoneCell']) ? $this->data['phoneCell'] :
                                            (!empty($this->data['phoneHome']) ? $this->data['phoneHome'] : $this->data['phoneWork']);
                                    ?>
                                    <?php if (!empty($heroPhone)): ?>
                                    <span class="profile-contact-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        <?php echo htmlspecialchars($heroPhone); ?>
                                    </span>
                                    <?php endif; ?>
                                    <?php if (!empty($this->data['cityAndState'])): ?>
                                    <span class="profile-contact-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                        <?php $this->_($this->data['cityAndState']); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="profile-actions">
                                <?php if ($this->getUserAccessLevel('candidates.edit') >= ACCESS_LEVEL_EDIT): ?>
                                <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=edit&amp;candidateID=<?php echo($this->candidateID); ?>" class="profile-action-btn btn-white">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                                    Edit
                                </a>
                                <?php endif; ?>
                                <?php if ($this->getUserAccessLevel('pipelines.addActivityChangeStatus') >= ACCESS_LEVEL_EDIT): ?>
                                <button class="profile-action-btn btn-outline" onclick="showPopWin('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=considerForJobSearch&amp;candidateID=<?php echo($this->candidateID); ?>', 750, 390, null); return false;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 3h5v5"/><path d="m21 3-9 9"/><path d="M21 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5"/></svg>
                                    Move Candidate
                                </button>
                                <button class="profile-action-btn btn-outline" onclick="showPopWin('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addActivityChangeStatus&amp;candidateID=<?php echo($this->candidateID); ?>&amp;jobOrderID=-1&amp;onlyScheduleEvent=true', 600, 350, null); return false;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                    Schedule Interview
                                </button>
                                <?php endif; ?>
                                <?php if ($this->getUserAccessLevel('candidates.delete') >= ACCESS_LEVEL_DELETE): ?>
                                <button class="profile-action-btn btn-danger" onclick="confirmDeleteCandidate(<?php echo($this->candidateID); ?>, '<?php echo addslashes($this->data['firstName'] . ' ' . $this->data['lastName']); ?>');">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    Delete
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== TABS BAR ==================== -->
                    <div class="cand-tabs-bar anim-fade-up-3">
                        <ul class="cand-tabs-list">
                            <li id="resumeTab" class="cand-tab active">
                                <a href="javascript:void(0);" onclick="showTab('resume');">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    Resume
                                </a>
                            </li>
                            <li id="feedbackTab" class="cand-tab">
                                <a href="javascript:void(0);" onclick="showTab('feedback');">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                    Feedback
                                </a>
                            </li>
                            <li id="emailTab" class="cand-tab">
                                <a href="javascript:void(0);" onclick="showTab('email');">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                    Email
                                </a>
                            </li>
                            <li id="documentsTab" class="cand-tab">
                                <a href="javascript:void(0);" onclick="showTab('documents');">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                    Documents
                                </a>
                            </li>
                            <div id="tabIndicator" class="tab-indicator"></div>
                        </ul>
                    </div>

                    <!-- ==================== TAB CONTENT ==================== -->
                    <div class="tab-content-container">

                        <!-- ===== RESUME TAB ===== -->
                        <div id="resumeTabContent" class="tab-fade-in" style="display: flex; flex-direction: row; width: 100%;">
                            <div class="tab-left-panel" style="flex: 0 0 55% !important; width: 55% !important; max-width: 55% !important; min-width: 55% !important;">
                                <h3 class="tab-section-title">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    Resume / CV
                                </h3>
                                <?php
                                    $hasResumeFile = !empty($this->resumeFileURL);
                                    $hasResumeText = !empty($this->resumeText);
                                    $resumeExt = $hasResumeFile ? strtolower(pathinfo($this->resumeFileName, PATHINFO_EXTENSION)) : '';
                                    $isPDF = ($resumeExt === 'pdf');
                                    $resumeDownloadURL = $hasResumeFile ? str_replace('&amp;', '&', $this->resumeFileURL) : '';
                                ?>
                                <?php if ($hasResumeFile || $hasResumeText): ?>
                                    <div class="resume-viewer">
                                        <?php if ($hasResumeFile): ?>
                                            <div class="resume-toolbar">
                                                <span class="resume-file-name"><?php echo htmlspecialchars($this->resumeFileName); ?></span>
                                                <div class="resume-actions">
                                                    <a href="<?php echo $resumeDownloadURL; ?>" target="_blank">Download</a>
                                                    <?php if ($hasResumeText): ?>
                                                        <a href="#" onclick="toggleResumeView(); return false;" id="resumeViewToggle"><?php echo $isPDF ? 'Show Text' : 'Show File'; ?></a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($isPDF): ?>
                                            <!-- PDF: embed with object tag + fallback -->
                                            <div id="resumeFileView">
                                                <object data="<?php echo $resumeDownloadURL; ?>" type="application/pdf" width="100%" style="min-height:550px; border-radius:0 0 8px 8px;">
                                                    <div style="text-align:center; padding:40px 20px;">
                                                        <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.5" style="display:block; margin:0 auto 16px;"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                                                        <p style="font-weight:600; color:#1f2937; margin:0 0 6px;">PDF Resume</p>
                                                        <p style="color:#6b7280; font-size:13px; margin:0 0 16px;"><?php echo htmlspecialchars($this->resumeFileName); ?></p>
                                                        <a href="<?php echo $resumeDownloadURL; ?>" target="_blank" style="display:inline-block; padding:10px 28px; background:#2563eb; color:#fff; border-radius:8px; text-decoration:none; font-weight:600; font-size:14px;">Open PDF</a>
                                                    </div>
                                                </object>
                                            </div>
                                            <?php if ($hasResumeText): ?>
                                                <div class="resume-viewer-text resume-document" id="resumeTextView" style="display:none;"><?php echo nl2br(htmlspecialchars($this->resumeText)); ?></div>
                                            <?php endif; ?>
                                        <?php elseif ($hasResumeText): ?>
                                            <!-- Non-PDF: show extracted text as formatted resume document -->
                                            <div class="resume-viewer-text resume-document" id="resumeTextView"><?php echo nl2br(htmlspecialchars($this->resumeText)); ?></div>
                                        <?php elseif ($hasResumeFile): ?>
                                            <!-- File exists but no text extracted -->
                                            <div style="text-align:center; padding:50px 20px;">
                                                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" style="display:block; margin:0 auto 16px;"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                                <p style="font-weight:600; color:#374151; margin:0 0 6px;"><?php echo htmlspecialchars($this->resumeFileName); ?></p>
                                                <p style="color:#6b7280; font-size:13px; margin:0 0 16px;">Preview not available for this file type</p>
                                                <a href="<?php echo $resumeDownloadURL; ?>" target="_blank" style="display:inline-block; padding:10px 28px; background:#2563eb; color:#fff; border-radius:8px; text-decoration:none; font-weight:600;">Download File</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="resume-viewer">
                                        <p style="color: #9ca3af; text-align: center; padding: 60px 20px; font-size: 14px;">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" style="display: block; margin: 0 auto 12px;"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                            No resume available for this candidate.
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- Right side: Info Cards -->
                            <div class="resume-info-cards-panel" style="flex: 0 0 45%; width: 45%; padding: 16px; background: #fff; display: flex; flex-direction: column; gap: 12px; overflow-y: auto; max-height: calc(100vh - 200px);">
                                <!-- Card 1: Personal Info -->
                                <div class="info-card" style="margin: 0;">
                                    <div class="info-card-header" style="padding: 10px 14px 8px;">
                                        <div class="info-card-icon blue" style="width: 28px; height: 28px; border-radius: 6px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        </div>
                                        <span class="info-card-title" style="font-size: 12px;">Personal Info</span>
                                    </div>
                                    <div class="info-card-body" style="padding: 10px 14px;">
                                        <div class="info-row" style="padding: 4px 0;">
                                            <span class="info-row-label" style="font-size: 10px; min-width: 70px;">Employer</span>
                                            <span class="info-row-value" style="font-size: 11px;"><?php $this->_($this->data['currentEmployer']); ?></span>
                                        </div>
                                        <div class="info-row" style="padding: 4px 0;">
                                            <span class="info-row-label" style="font-size: 10px; min-width: 70px;">Expected CTC</span>
                                            <span class="info-row-value" style="font-size: 11px;"><?php echo !empty($this->data['desiredPay']) ? $this->data['desiredPay'] : (!empty($this->data['currentPay']) ? $this->data['currentPay'] : 'Not Specified'); ?></span>
                                        </div>
                                        <div class="info-row" style="padding: 4px 0;">
                                            <span class="info-row-label" style="font-size: 10px; min-width: 70px;">Notice</span>
                                            <span class="info-row-value" style="font-size: 11px;"><?php echo !empty($this->data['dateAvailable']) ? '30 Days' : 'Not Specified'; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card 2: Contact Details -->
                                <div class="info-card" style="margin: 0;">
                                    <div class="info-card-header" style="padding: 10px 14px 8px;">
                                        <div class="info-card-icon green" style="width: 28px; height: 28px; border-radius: 6px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                        </div>
                                        <span class="info-card-title" style="font-size: 12px;">Contact Details</span>
                                    </div>
                                    <div class="info-card-body" style="padding: 10px 14px;">
                                        <div class="info-row" style="padding: 4px 0;">
                                            <span class="info-row-label" style="font-size: 10px; min-width: 70px;">Email</span>
                                            <span class="info-row-value" style="font-size: 11px;"><a href="mailto:<?php $this->_($this->data['email1']); ?>"><?php $this->_($this->data['email1']); ?></a></span>
                                        </div>
                                        <?php if (!empty($this->data['phoneCell'])): ?>
                                        <div class="info-row" style="padding: 4px 0;">
                                            <span class="info-row-label" style="font-size: 10px; min-width: 70px;">Phone</span>
                                            <span class="info-row-value" style="font-size: 11px;"><?php $this->_($this->data['phoneCell']); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <div class="info-row" style="padding: 4px 0;">
                                            <span class="info-row-label" style="font-size: 10px; min-width: 70px;">Location</span>
                                            <span class="info-row-value" style="font-size: 11px;"><?php $this->_($this->data['cityAndState']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card 3: Skills & Notes -->
                                <div class="info-card" style="margin: 0;">
                                    <div class="info-card-header" style="padding: 10px 14px 8px;">
                                        <div class="info-card-icon purple" style="width: 28px; height: 28px; border-radius: 6px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        </div>
                                        <span class="info-card-title" style="font-size: 12px;">Skills & Notes</span>
                                    </div>
                                    <div class="info-card-body" style="padding: 10px 14px;">
                                        <?php if (!empty($this->data['keySkills'])): ?>
                                        <div style="margin-bottom: 8px;">
                                            <span class="info-row-label" style="display: block; margin-bottom: 6px; font-size: 10px;">Key Skills</span>
                                            <div class="skill-pills" style="gap: 4px;">
                                                <?php
                                                    $skills = preg_split('/[,;]+/', $this->data['keySkills']);
                                                    $skillCount = 0;
                                                    foreach ($skills as $skill):
                                                        $skill = trim($skill);
                                                        if (!empty($skill) && $skillCount < 5):
                                                            $skillCount++;
                                                ?>
                                                    <span class="skill-pill" style="font-size: 10px; padding: 2px 8px;"><?php echo htmlspecialchars($skill); ?></span>
                                                <?php
                                                        endif;
                                                    endforeach;
                                                    if (count($skills) > 5): ?>
                                                        <span style="font-size: 10px; color: #6b7280;">+<?php echo count($skills) - 5; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <?php if (!empty($this->data['notes'])): ?>
                                        <div>
                                            <span class="info-row-label" style="display: block; margin-bottom: 4px; font-size: 10px;">Notes</span>
                                            <p style="font-size: 11px; color: #4b5563; line-height: 1.5; margin: 0;"><?php echo nl2br(htmlspecialchars(substr($this->data['notes'], 0, 100))); ?><?php echo strlen($this->data['notes']) > 100 ? '...' : ''; ?></p>
                                        </div>
                                        <?php endif; ?>
                                        <?php if (empty($this->data['keySkills']) && empty($this->data['notes'])): ?>
                                            <p style="color: #9ca3af; text-align: center; padding: 10px 0; font-size: 11px;">No skills or notes recorded.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== FEEDBACK TAB ===== -->
                        <div id="feedbackTabContent" style="display: none; flex-direction: row; width: 100%;">
                            <div class="tab-left-panel">
                                <h3 class="tab-section-title">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                    Feedback
                                </h3>
                                <div class="feedback-two-column">
                                    <!-- Left Column: Input Form -->
                                    <div class="feedback-input-column">
                                        <h4 style="margin-top: 0; margin-bottom: 15px; font-weight: 700; font-family: 'Inter', system-ui, sans-serif;">Add Feedback</h4>
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
                                                <label>
                                                    <input type="checkbox" name="recommendHire" value="1" /> Recommend: Hire
                                                </label>
                                            </div>
                                            <button type="button" class="btn-primary" onclick="saveFeedback();">Save Feedback</button>
                                        </form>
                                    </div>

                                    <!-- Right Column: Displayed Feedback -->
                                    <div class="feedback-display-column">
                                        <h4 style="margin-top: 0; margin-bottom: 15px; font-weight: 700; font-family: 'Inter', system-ui, sans-serif;">Previous Feedback</h4>
                                        <?php if (!empty($this->feedbackRS)): ?>
                                            <?php $latestFeedback = $this->feedbackRS[0]; ?>
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" checked disabled /> Recommend: Hire
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>Rating (1-5)</label>
                                                <div style="padding: 10px; background: #f8fafc; border-radius: 8px; font-weight: 600; color: #2563eb;">4</div>
                                            </div>
                                            <div class="form-group">
                                                <label>Interview Notes</label>
                                                <div style="padding: 10px; background: #f8fafc; border-radius: 8px; min-height: 100px; line-height: 1.6; font-size: 13px;">
                                                    <?php echo htmlspecialchars(substr($latestFeedback['notes'], 0, 200)); ?>...
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <p style="color: #9ca3af; text-align: center; padding: 40px; font-size: 13px;">No previous feedback available.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== EMAIL TAB ===== -->
                        <div id="emailTabContent" style="display: none; flex-direction: row; width: 100%;">
                            <div class="tab-left-panel">
                                <h3 class="tab-section-title">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                    Email
                                </h3>

                        <?php if (isset($_GET['emailSent'])): ?>
                            <?php if ($_GET['emailSent'] === '1'): ?>
                                <div id="emailSuccessMsg" style="background: #ecfdf5; border: 1px solid #6ee7b7; color: #065f46; padding: 12px 18px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    Email sent successfully to <?php $this->_($this->data['firstName']); ?> <?php $this->_($this->data['lastName']); ?>.
                                </div>
                            <?php else: ?>
                                <div id="emailErrorMsg" style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 18px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
                                    Failed to send email. Please check your SMTP settings.
                                </div>
                            <?php endif; ?>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    showTab('email');
                                });
                            </script>
                        <?php endif; ?>

                        <div class="email-two-column">
                            <!-- Left Column: Compose Email -->
                            <div class="email-list-column">
                                <h4 style="margin-top: 0; margin-bottom: 15px; font-weight: 700; font-family: 'Inter', system-ui, sans-serif;">Compose Email</h4>
                                <form id="candidateEmailForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=sendCandidateEmail" method="post">
                                    <input type="hidden" name="candidateID" value="<?php $this->_($this->candidateID); ?>" />

                                    <div style="margin-bottom: 14px;">
                                        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 5px;">To</label>
                                        <input type="text" readonly value="<?php echo htmlspecialchars($this->data['email1']); ?>" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; background: #f8fafc; color: #6b7280; box-sizing: border-box; font-family: 'Inter', system-ui, sans-serif;" />
                                    </div>

                                    <div style="margin-bottom: 14px;">
                                        <label for="candidateEmailTemplate" style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 5px;">Template</label>
                                        <select id="candidateEmailTemplate" name="emailTemplate" onchange="loadCandidateTemplate(this.value);" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; background: #fff; box-sizing: border-box; cursor: pointer; font-family: 'Inter', system-ui, sans-serif;">
                                            <option value="-1">-- Select a template --</option>
                                            <?php if (!empty($this->emailTemplatesRS)): ?>
                                                <?php foreach ($this->emailTemplatesRS as $tpl): ?>
                                                    <option value="<?php echo $tpl['emailTemplateID']; ?>"><?php echo htmlspecialchars($tpl['emailTemplateTitle']); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div style="margin-bottom: 14px;">
                                        <label for="candidateEmailSubject" style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 5px;">Subject</label>
                                        <input type="text" id="candidateEmailSubject" name="emailSubject" placeholder="Enter email subject" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; box-sizing: border-box; font-family: 'Inter', system-ui, sans-serif;" required />
                                    </div>

                                    <div style="margin-bottom: 14px;">
                                        <label for="candidateEmailBody" style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 5px;">Body</label>
                                        <textarea id="candidateEmailBody" name="emailBody" rows="8" placeholder="Compose your email or select a template above..." style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; resize: vertical; box-sizing: border-box; font-family: 'Inter', system-ui, sans-serif;" required></textarea>
                                    </div>

                                    <button type="submit" onclick="return validateCandidateEmail();" style="width: 100%; padding: 12px 16px; background: #2563eb; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; font-family: 'Inter', system-ui, sans-serif;">
                                        <span style="display: inline-flex; align-items: center; gap: 8px; justify-content: center;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                            Send Email
                                        </span>
                                    </button>
                                </form>
                            </div>

                            <!-- Right Column: Email History -->
                            <div class="email-detail-column">
                                <h4 style="margin-top: 0; margin-bottom: 15px; font-weight: 700; font-family: 'Inter', system-ui, sans-serif;">Email History</h4>
                                <?php if (!empty($this->emailRS)): ?>
                                    <?php foreach ($this->emailRS as $index => $email): ?>
                                        <div class="email-list-item" style="margin-bottom: 10px; padding: 14px; border: 1px solid #e5e7eb; border-radius: 10px; background: #fff;">
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                                <span style="font-weight: 600; font-size: 12px; color: #1f2937;"><?php echo htmlspecialchars($email['enteredByAbbrName']); ?></span>
                                                <span style="font-size: 11px; color: #9ca3af;"><?php $this->_($email['dateCreated']); ?></span>
                                            </div>
                                            <div style="font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 2px;"><?php echo !empty($email['regarding']) ? htmlspecialchars($email['regarding']) : 'Email'; ?></div>
                                            <div style="font-size: 12px; color: #6b7280; line-height: 1.5;"><?php echo htmlspecialchars(substr($email['notes'], 0, 120)); ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p style="color: #9ca3af; text-align: center; padding: 40px 20px; font-size: 13px;">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" style="display: block; margin: 0 auto 10px;"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                        No emails sent yet.
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                        </div>

                        <!-- ===== DOCUMENTS TAB ===== -->
                        <div id="documentsTabContent" style="display: none; flex-direction: row; width: 100%;">
                            <div class="tab-left-panel">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                                    <h3 class="tab-section-title" style="margin-bottom: 0;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        Candidate Documents
                                    </h3>
                                    <button type="button" onclick="generateUploadLink();" style="padding: 8px 16px; background: #2563eb; color: #fff; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; font-family: 'Inter', system-ui, sans-serif; display: flex; align-items: center; gap: 6px;">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                                        Generate Upload Link
                                    </button>
                                </div>

                                <div id="uploadLinkResult" style="display: none; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 14px 18px; margin-bottom: 16px;">
                                    <div style="font-size: 12px; font-weight: 600; color: #1e40af; margin-bottom: 6px;">Upload Link (share with candidate):</div>
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <input type="text" id="uploadLinkURL" readonly style="flex: 1; padding: 8px 12px; border: 1px solid #93c5fd; border-radius: 6px; font-size: 12px; font-family: monospace; background: #fff; color: #1e3a8a;" />
                                        <button type="button" onclick="copyUploadLink();" style="padding: 8px 14px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; white-space: nowrap;">Copy</button>
                                    </div>
                                    <div style="font-size: 11px; color: #6b7280; margin-top: 6px;">Link expires in 7 days. Candidate can upload up to 20 documents.</div>
                                </div>

                                <div id="documentsListContainer">
                                    <div id="documentsLoading" style="text-align: center; padding: 40px; color: #9ca3af; font-size: 13px;">
                                        Loading documents...
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!-- ==================== JOB ORDERS SECTION ==================== -->
            <div class="section-card anim-fade-up-4">
                <div class="section-card-header">
                    <span class="section-card-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        Job Orders for Candidate
                    </span>
                </div>
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
                <div class="section-card-footer">
                    <?php if ($this->getUserAccessLevel('candidates.considerForJobSearch') >= ACCESS_LEVEL_EDIT): ?>
                    <a href="#" onclick="showPopWin('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=considerForJobSearch&amp;candidateID=<?php echo($this->candidateID); ?>', 750, 390, null); return false;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                        Add This Candidate to Job Order
                    </a>
                    <?php endif; ?>
                </div>
<?php endif; ?>
            </div>

            <!-- ==================== ACTIVITY SECTION ==================== -->
            <div class="section-card anim-fade-up-5">
                <div class="section-card-header">
                    <span class="section-card-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        Activity
                    </span>
                </div>

                <!-- Hidden original table for sortable functionality -->
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

                <!-- Modern Timeline View -->
                <div class="activity-timeline">
                    <?php if (!empty($this->activityRS)): ?>
                        <?php foreach ($this->activityRS as $rowNumber => $activityData): ?>
                            <div class="activity-timeline-item">
                                <div class="activity-dot-col">
                                    <div class="activity-dot"></div>
                                    <?php if ($rowNumber < count($this->activityRS) - 1): ?>
                                        <div class="activity-line"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-header">
                                        <span class="activity-type"><?php $this->_($activityData['typeDescription']) ?></span>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <span class="activity-date"><?php $this->_($activityData['dateCreated']) ?></span>
<?php if (!$this->isPopup): ?>
                                            <span class="activity-actions">
                                                <?php if ($this->getUserAccessLevel('candidates.edit') >= ACCESS_LEVEL_EDIT): ?>
                                                    <a href="#" onclick="Activity_editEntry(<?php echo($activityData['activityID']); ?>, <?php echo($this->candidateID); ?>, <?php echo(DATA_ITEM_CANDIDATE); ?>, '<?php echo($this->sessionCookie); ?>'); return false;">
                                                        <img src="images/actions/edit.gif" width="16" height="16" class="absmiddle" alt="" border="0" title="Edit" />
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($this->getUserAccessLevel('candidates.delete') >= ACCESS_LEVEL_DELETE): ?>
                                                    <a href="#" onclick="Activity_deleteEntry(<?php echo($activityData['activityID']); ?>, '<?php echo($this->sessionCookie); ?>'); return false;">
                                                        <img src="images/actions/delete.gif" width="16" height="16" class="absmiddle" alt="" border="0" title="Delete" />
                                                    </a>
                                                <?php endif; ?>
                                            </span>
<?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if (!empty($activityData['regarding'])): ?>
                                        <div class="activity-regarding"><?php $this->_($activityData['regarding']) ?></div>
                                    <?php endif; ?>
                                    <div class="activity-notes"><?php echo($activityData['notes']); ?></div>
                                    <div class="activity-entered">by <?php $this->_($activityData['enteredByAbbrName']) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: #9ca3af; text-align: center; padding: 40px 20px; font-size: 13px;">No activity recorded yet.</p>
                    <?php endif; ?>
                </div>

<?php if (!$this->isPopup): ?>
                <div class="section-card-footer">
                    <div id="addActivityDiv">
                        <?php if ($this->getUserAccessLevel('pipelines.addActivityChangeStatus') >= ACCESS_LEVEL_EDIT): ?>
                            <a href="#" id="addActivityLink" onclick="showPopWin('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addActivityChangeStatus&amp;candidateID=<?php echo($this->candidateID); ?>&amp;jobOrderID=-1', 600, 480, null); return false;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                                Log an Activity
                            </a>
                        <?php endif; ?>
                        <img src="images/indicator2.gif" id="addActivityIndicator" alt="" style="visibility: hidden; margin-left: 5px;" height="16" width="16" />
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="delete-modal-overlay" onclick="if(event.target === this) closeDeleteModal();">
    <div class="delete-modal">
        <div class="delete-modal-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 6h18"/>
                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                <line x1="10" x2="10" y1="11" y2="17"/>
                <line x1="14" x2="14" y1="11" y2="17"/>
            </svg>
        </div>
        <h3>Delete Candidate?</h3>
        <p>Are you sure you want to delete <strong id="deleteCandidateName"></strong>? This action cannot be undone and will remove all associated data including resumes, activities, and pipeline entries.</p>
        <div class="delete-modal-actions">
            <button class="delete-modal-btn cancel" onclick="closeDeleteModal();">Cancel</button>
            <button class="delete-modal-btn confirm" id="confirmDeleteBtn">Delete Candidate</button>
        </div>
    </div>
</div>

<!-- Pipeline Dropdown (positioned fixed, outside all containers) -->
<?php if (empty($this->pipelinesRS)): ?>
<div class="pipeline-dropdown-menu" id="pipelineDropdownMenu">
    <!-- Step 1: Select Job Order -->
    <div id="pipelineStepJobOrder">
        <div class="pipeline-dropdown-header">Select Job Order</div>
        <div class="pipeline-dropdown-search">
            <input type="text" id="jobOrderSearchInput" placeholder="Search job orders..." oninput="filterJobOrders(this.value)">
        </div>
        <div class="pipeline-dropdown-section" id="jobOrdersList">
            <div class="pipeline-dropdown-empty">Loading job orders...</div>
        </div>
    </div>
    <!-- Step 2: Select Status -->
    <div id="pipelineStepStatus" style="display: none;">
        <div class="pipeline-back-btn" onclick="showJobOrderStep()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            Back to Job Orders
        </div>
        <div class="pipeline-dropdown-header">Select Status</div>
        <div class="pipeline-status-grid">
            <div class="pipeline-status-item screen-select" onclick="addToPipelineWithStatus(1000)">
                <span>Screen Select</span>
            </div>
            <div class="pipeline-status-item screen-reject" onclick="addToPipelineWithStatus(1010)">
                <span>Screen Reject</span>
            </div>
            <div class="pipeline-status-item l1-select" onclick="addToPipelineWithStatus(1020)">
                <span>L1 Select</span>
            </div>
            <div class="pipeline-status-item l2-select" onclick="addToPipelineWithStatus(1030)">
                <span>L2 Select</span>
            </div>
            <div class="pipeline-status-item l3-select" onclick="addToPipelineWithStatus(1040)">
                <span>L3 Select</span>
            </div>
            <div class="pipeline-status-item offer-release" onclick="addToPipelineWithStatus(1050)">
                <span>Offer Release</span>
            </div>
            <div class="pipeline-status-item onboarded" onclick="addToPipelineWithStatus(1060)">
                <span>Onboarded</span>
            </div>
            <div class="pipeline-status-item no-show" onclick="addToPipelineWithStatus(1070)">
                <span>No Show</span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php TemplateUtility::printFooter(); ?>
