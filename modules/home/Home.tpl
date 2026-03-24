<?php /* $Id: Home.tpl 3563 2007-11-12 07:41:54Z will $ */ ?>
<?php TemplateUtility::printHeader('Home', array('js/sweetTitles.js', 'js/dataGrid.js', 'js/dataGridFilters.js', 'js/home.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
    <div id="main" class="home">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">

            <style>
                @keyframes fadeInUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
                @keyframes countUp { from { opacity: 0; transform: scale(0.8); } to { opacity: 1; transform: scale(1); } }

                .role-welcome-banner {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 14px 20px;
                    border-radius: 12px;
                    margin-bottom: 18px;
                    animation: fadeInUp 0.5s ease;
                    font-family: 'Inter', system-ui, sans-serif;
                    position: relative;
                    overflow: hidden;
                }

                .role-welcome-banner::before {
                    content: '';
                    position: absolute;
                    top: 0; left: 0; right: 0; bottom: 0;
                    opacity: 0.06;
                    background: repeating-linear-gradient(45deg, transparent, transparent 10px, currentColor 10px, currentColor 11px);
                    pointer-events: none;
                }

                .role-banner-admin { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #1e40af; }
                .role-banner-recruiter { background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #065f46; }
                .role-banner-interviewer { background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #92400e; }

                .role-welcome-text h2 {
                    font-size: 17px;
                    font-weight: 700;
                    margin: 0 0 2px;
                    letter-spacing: -0.02em;
                }

                .role-welcome-text p {
                    font-size: 12px;
                    margin: 0;
                    opacity: 0.7;
                    font-weight: 400;
                }

                .role-badge-lg {
                    padding: 4px 12px;
                    border-radius: 16px;
                    font-size: 11px;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                }

                .role-badge-admin { background: #2563eb; color: #fff; }
                .role-badge-recruiter { background: #059669; color: #fff; }
                .role-badge-interviewer { background: #d97706; color: #fff; }

                /* Quick Stats Row */
                .quick-stats {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 14px;
                    margin-bottom: 20px;
                }

                .stat-card {
                    background: #fff;
                    border: 1px solid #e5e7eb;
                    border-radius: 12px;
                    padding: 18px 20px;
                    display: flex;
                    align-items: center;
                    gap: 14px;
                    transition: all 0.25s ease;
                    animation: fadeInUp 0.5s ease;
                    font-family: 'Inter', system-ui, sans-serif;
                    cursor: pointer;
                    text-decoration: none;
                    color: inherit;
                }

                .stat-card:nth-child(1) { animation-delay: 0.05s; }
                .stat-card:nth-child(2) { animation-delay: 0.1s; }
                .stat-card:nth-child(3) { animation-delay: 0.15s; }
                .stat-card:nth-child(4) { animation-delay: 0.2s; }

                .stat-card:hover {
                    box-shadow: 0 4px 16px rgba(37, 99, 235, 0.1);
                    transform: translateY(-2px);
                }

                .quick-stats .stat-card:nth-child(1) { border-left: 3px solid #2563eb; }
                .quick-stats .stat-card:nth-child(2) { border-left: 3px solid #059669; }
                .quick-stats .stat-card:nth-child(3) { border-left: 3px solid #7c3aed; }
                .quick-stats .stat-card:nth-child(4) { border-left: 3px solid #d97706; }

                .stat-icon {
                    width: 44px;
                    height: 44px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                }

                .stat-icon.blue { background: #eff6ff; }
                .stat-icon.green { background: #ecfdf5; }
                .stat-icon.purple { background: #f5f3ff; }
                .stat-icon.amber { background: #fffbeb; }

                .stat-number {
                    font-size: 24px;
                    font-weight: 800;
                    color: #111827;
                    line-height: 1;
                    animation: countUp 0.6s ease;
                }

                .stat-label {
                    font-size: 12px;
                    color: #6b7280;
                    font-weight: 500;
                    margin-top: 2px;
                }

                /* Interviewer-specific styles */
                .interviewer-section {
                    animation: fadeInUp 0.6s ease;
                }

                .interview-schedule-card {
                    background: #fff;
                    border: 1px solid #e5e7eb;
                    border-radius: 12px;
                    padding: 16px 20px;
                    margin-bottom: 12px;
                    display: flex;
                    align-items: center;
                    gap: 16px;
                    transition: all 0.2s ease;
                    font-family: 'Inter', system-ui, sans-serif;
                }

                .interview-schedule-card:hover {
                    border-color: #d97706;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
                }

                .interview-time {
                    text-align: center;
                    min-width: 60px;
                    padding: 8px;
                    background: #fffbeb;
                    border-radius: 10px;
                }

                .interview-time .time {
                    font-size: 14px;
                    font-weight: 700;
                    color: #92400e;
                }

                .interview-time .date {
                    font-size: 10px;
                    color: #d97706;
                    font-weight: 500;
                }

                .interview-info h4 {
                    font-size: 14px;
                    font-weight: 600;
                    color: #1f2937;
                    margin: 0 0 4px;
                }

                .interview-info p {
                    font-size: 12px;
                    color: #6b7280;
                    margin: 0;
                }

                @media (max-width: 900px) {
                    .quick-stats { grid-template-columns: repeat(2, 1fr); }
                }
            </style>

            <!-- Role-Based Welcome Banner -->
            <?php
                $role = isset($this->userRole) ? $this->userRole : 'admin';
                $roleName = $role === 'admin' ? 'Administrator' : ($role === 'recruiter' ? 'Recruiter' : 'Interviewer');
                $roleDesc = $role === 'admin'
                    ? 'Full system overview and management controls'
                    : ($role === 'recruiter'
                        ? 'Manage your candidates, jobs, and hiring pipelines'
                        : 'View your interview schedule and provide feedback');
            ?>
            <div class="role-welcome-banner role-banner-<?php echo $role; ?>">
                <div class="role-welcome-text">
                    <h2>Welcome, <?php echo htmlspecialchars($this->userFullName ?? 'User'); ?></h2>
                    <p><?php echo $roleDesc; ?></p>
                </div>
                <span class="role-badge-lg role-badge-<?php echo $role; ?>"><?php echo $roleName; ?></span>
            </div>

            <!-- Quick Stats (Admin & Recruiter) -->
            <?php if ($role === 'admin' || $role === 'recruiter'): ?>
            <div class="quick-stats">
                <a class="stat-card" href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=listByView">
                    <div class="stat-icon blue">
                        <svg width="22" height="22" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    </div>
                    <div>
                        <div class="stat-number"><?php echo $this->candidateCount ?? 0; ?></div>
                        <div class="stat-label">Total Candidates</div>
                    </div>
                </a>
                <a class="stat-card" href="<?php echo CATSUtility::getIndexName(); ?>?m=joborders&amp;a=listByView">
                    <div class="stat-icon green">
                        <svg width="22" height="22" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                    </div>
                    <div>
                        <div class="stat-number"><?php echo $this->jobOrderCount ?? 0; ?></div>
                        <div class="stat-label">Job Orders</div>
                    </div>
                </a>
                <a class="stat-card" href="<?php echo CATSUtility::getIndexName(); ?>?m=joborders&amp;a=pipelineBoard">
                    <div class="stat-icon purple">
                        <svg width="22" height="22" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                    </div>
                    <div>
                        <div class="stat-number"><?php echo count($this->placedRS); ?></div>
                        <div class="stat-label">Recent Hires</div>
                    </div>
                </a>
                <a class="stat-card" href="<?php echo CATSUtility::getIndexName(); ?>?m=calendar">
                    <div class="stat-icon amber">
                        <svg width="22" height="22" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="stat-number">&mdash;</div>
                        <div class="stat-label">Upcoming Events</div>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            <!-- Interviewer Quick Stats -->
            <?php if ($role === 'interviewer'): ?>
            <div class="quick-stats" style="grid-template-columns: repeat(3, 1fr);">
                <div class="stat-card">
                    <div class="stat-icon amber">
                        <svg width="22" height="22" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="stat-number">&mdash;</div>
                        <div class="stat-label">Upcoming Interviews</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">
                        <svg width="22" height="22" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="stat-number">&mdash;</div>
                        <div class="stat-label">Feedback Submitted</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <svg width="22" height="22" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <div class="stat-number">&mdash;</div>
                        <div class="stat-label">Candidates Reviewed</div>
                    </div>
                </div>
            </div>

            <!-- Interviewer Schedule & Feedback Section -->
            <div class="interviewer-section">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="dashboard-card">
                        <div class="dashboard-card-header">
                            <svg width="15" height="15" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            My Interview Schedule
                        </div>
                        <div class="dashboard-card-body" style="font-size: 12px;">
                            <?php echo($this->upcomingEventsHTML); ?>
                            <?php if (empty(trim($this->upcomingEventsHTML))): ?>
                                <div style="padding: 40px 20px; text-align: center; color: #9ca3af; font-size: 13px;">
                                    <svg width="36" height="36" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 8px; display: block;"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    No upcoming interviews scheduled
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="dashboard-card">
                        <div class="dashboard-card-header">
                            <svg width="15" height="15" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Pending Feedback
                        </div>
                        <div class="dashboard-card-body">
                            <div style="padding: 40px 20px; text-align: center; color: #9ca3af; font-size: 13px;">
                                <svg width="36" height="36" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 8px; display: block;"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                All feedback submitted. Great work!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Admin/Recruiter Dashboard Content -->
            <?php if ($role === 'admin' || $role === 'recruiter'): ?>

            <!-- Row 1: Two equal cards -->
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <svg width="15" height="15" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        My Recent Calls
                    </div>
                    <div class="dashboard-card-body" style="padding: 0; overflow: auto;">
                        <?php $this->dataGrid2->drawHTML(); ?>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <svg width="15" height="15" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Upcoming Follow-Ups
                    </div>
                    <div class="dashboard-card-body" style="font-size: 12px;">
                        <?php echo($this->upcomingEventsFupHTML); ?>
                    </div>
                </div>
            </div>

            <!-- Row 2: Two equal cards -->
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <svg width="15" height="15" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Recent Hires
                    </div>
                    <div class="dashboard-card-body" style="padding: 0;">
                        <table class="sortable" style="margin: 0; border: none !important; border-radius: 0; box-shadow: none;">
                            <tr>
                                <th align="left">Name</th>
                                <th align="left">Company</th>
                                <th align="left">Recruiter</th>
                                <th align="left">Date</th>
                            </tr>
                            <?php foreach($this->placedRS as $index => $data): ?>
                            <tr class="<?php TemplateUtility::printAlternatingRowClass($index); ?>">
                                <td><a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;candidateID=<?php echo($data['candidateID']); ?>" class="<?php echo($data['candidateClassName']); ?>"><?php $this->_($data['firstName']); ?> <?php $this->_($data['lastName']); ?></a></td>
                                <td><a href="<?php echo(CATSUtility::getIndexName()); ?>?m=companies&amp;a=show&amp;companyID=<?php echo($data['companyID']); ?>" class="<?php echo($data['companyClassName']); ?>"><?php $this->_($data['companyName']); ?></a></td>
                                <td><?php $this->_(StringUtility::makeInitialName($data['userFirstName'], $data['userLastName'], false, LAST_NAME_MAXLEN)); ?></td>
                                <td><?php $this->_($data['date']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>

                        <?php if (!count($this->placedRS)): ?>
                            <div style="padding: 40px 20px; text-align: center; color: #9ca3af; font-size: 13px; background: #f9fafb;">
                                <svg width="36" height="36" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 8px; display: block;"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75"/></svg>
                                No recent hires yet
                                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=add" style="display: inline-block; margin-top: 10px; padding: 6px 16px; background: #2563eb; color: #fff; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none;">+ Add Candidate</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <svg width="15" height="15" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Hiring Overview
                    </div>
                    <div class="dashboard-card-body" style="padding: 0;">
                        <map name="dashboardmap" id="dashboardmap">
                           <area href="#" alt="Weekly" title="Weekly" shape="rect" coords="398,0,461,24" onclick="swapHomeGraph(<?php echo(DASHBOARD_GRAPH_WEEKLY); ?>);" />
                           <area href="#" alt="Monthly" title="Monthly" shape="rect" coords="398,25,461,48" onclick="swapHomeGraph(<?php echo(DASHBOARD_GRAPH_MONTHLY); ?>);" />
                           <area href="#" alt="Yearly" title="Yearly" shape="rect" coords="398,49,461,74" onclick="swapHomeGraph(<?php echo(DASHBOARD_GRAPH_YEARLY); ?>);" />
                        </map>
                        <div id="hiringOverviewContainer" style="width: 100%; height: 220px; background: #f9fafb; position: relative; overflow: hidden;">
                            <img src="<?php echo(CATSUtility::getIndexName()); ?>?m=graphs&amp;a=miniPlacementStatistics&amp;width=495&amp;height=230&amp;view=<?php echo(DASHBOARD_GRAPH_WEEKLY); ?>&amp;t=<?php echo(time()); ?>" id="homeGraph" alt="Hiring Overview" usemap="#dashboardmap" border="0" style="display: block; width: 100%; height: 100%; object-fit: contain;" onerror="handleGraphError(this);" onload="handleGraphLoad(this);" />
                            <div id="hiringOverviewError" style="display: none; text-align: center; padding-top: 60px; color: #9ca3af; font-size: 13px; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #f9fafb;">
                                <svg width="36" height="36" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 8px; display: block;"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                No hiring data available yet
                                <div style="margin-top: 6px; font-size: 11px; color: #b0b7c3;">Start placing candidates to see trends here.</div>
                                <a href="javascript:void(0);" onclick="retryGraph(); return false;" style="color: #2563eb; margin-top: 8px; display: inline-block; font-size: 12px;">Retry loading</a>
                            </div>
                            <div id="hiringOverviewLoading" style="display: none; text-align: center; padding-top: 80px; color: #9ca3af; font-size: 13px; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #f9fafb;">
                                Loading graph...
                            </div>
                        </div>
                        <script type="text/javascript">
                        function handleGraphError(img) {
                            img.style.display = 'none';
                            document.getElementById('hiringOverviewError').style.display = 'block';
                            document.getElementById('hiringOverviewLoading').style.display = 'none';
                        }
                        function handleGraphLoad(img) {
                            img.style.display = 'block';
                            document.getElementById('hiringOverviewError').style.display = 'none';
                            document.getElementById('hiringOverviewLoading').style.display = 'none';
                        }
                        function retryGraph() {
                            var img = document.getElementById('homeGraph');
                            document.getElementById('hiringOverviewError').style.display = 'none';
                            document.getElementById('hiringOverviewLoading').style.display = 'block';
                            img.style.display = 'none';
                            var src = img.src.split('&t=')[0];
                            img.src = src + '&t=' + new Date().getTime();
                            img.style.display = 'block';
                        }
                        </script>
                    </div>
                </div>
            </div>

            <!-- Row 3: Full-width candidates card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header" style="justify-content: space-between;">
                    <span style="display: flex; align-items: center; gap: 8px;">
                        <svg width="15" height="15" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        Important Candidates
                    </span>
                    <span style="font-weight: 400; font-size: 12px; color: #9ca3af;">Page <?php echo($this->dataGrid->getCurrentPageHTML()); ?> &middot; <?php echo($this->dataGrid->getNumberOfRows()); ?> Items</span>
                </div>
                <div class="dashboard-card-body" style="padding: 0; overflow-x: auto;">
                    <?php $this->dataGrid->draw(); ?>
                    <?php if ($this->dataGrid->getNumberOfRows()): ?>
                    <div style="padding: 8px 16px; text-align: right; border-top: 1px solid #f3f4f6; font-size: 12px;">
                        <?php $this->dataGrid->printNavigation(false); ?>&nbsp;&nbsp;&nbsp;<?php $this->dataGrid->printShowAll(); ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!$this->dataGrid->getNumberOfRows()): ?>
                    <div style="padding: 40px 20px; text-align: center; color: #9ca3af; font-size: 13px; background: #f9fafb;">
                        <svg width="36" height="36" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 8px; display: block;"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        No important candidates at the moment
                        <div style="margin-top: 6px; font-size: 11px; color: #b0b7c3;">Mark candidates as "Hot" from their profile to see them here.</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php endif; ?>

            <!-- Admin-Only: System Management Section -->
            <?php if ($role === 'admin'): ?>
            <div style="margin-top: 16px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px;">
                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&amp;a=administration" class="stat-card" style="animation-delay: 0.3s;">
                    <div class="stat-icon blue">
                        <svg width="22" height="22" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: 600; color: #111827;">System Settings</div>
                        <div class="stat-label">Configure system preferences</div>
                    </div>
                </a>
                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&amp;a=manageUsers" class="stat-card" style="animation-delay: 0.35s;">
                    <div class="stat-icon green">
                        <svg width="22" height="22" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: 600; color: #111827;">Manage Users</div>
                        <div class="stat-label">Add, edit, or remove users</div>
                    </div>
                </a>
                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&amp;a=emailTemplates" class="stat-card" style="animation-delay: 0.4s;">
                    <div class="stat-icon purple">
                        <svg width="22" height="22" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: 600; color: #111827;">Email Templates</div>
                        <div class="stat-label">Manage email templates</div>
                    </div>
                </a>
            </div>
            <?php endif; ?>

        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
