<?php /* $Id: Home.tpl - Modern Dashboard UI */ ?>
<?php TemplateUtility::printHeader('Home', array('js/sweetTitles.js', 'js/dataGrid.js', 'js/dataGridFilters.js', 'js/home.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>

<div id="main" class="home">
    <?php TemplateUtility::printQuickSearch(); ?>
    <div id="contents">

<style>
/* ── Reset & Base ────────────────────────────────── */
.db-wrap { font-family: 'Inter', system-ui, sans-serif; color: #111827; }

/* ── Page Header ─────────────────────────────────── */
.db-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}
.db-page-title {
    display: flex;
    align-items: center;
    gap: 10px;
}
.db-page-title h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    letter-spacing: -0.01em;
}
.db-page-title .page-icon {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.db-page-subtitle {
    font-size: 13px;
    color: #6b7280;
    margin-top: 2px;
}
.db-role-badge {
    padding: 5px 14px;
    background: #eff6ff;
    color: #2563eb;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid #dbeafe;
    white-space: nowrap;
}

/* ── Stat Cards ──────────────────────────────────── */
.db-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.db-stat {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e9eaee;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: box-shadow 0.15s, border-color 0.15s, transform 0.15s;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    position: relative;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
}
.db-stat:hover {
    border-color: #d8dae0;
    box-shadow: 0 4px 14px rgba(16, 24, 40, 0.07);
    transform: translateY(-1px);
}
.db-stat-icon {
    width: 44px; height: 44px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.db-stat.blue  .db-stat-icon { background: #eff6ff; }
.db-stat.green .db-stat-icon { background: #ecfdf5; }
.db-stat.purple .db-stat-icon { background: #f5f3ff; }
.db-stat.red   .db-stat-icon { background: #fef2f2; }
.db-stat-num {
    font-size: 28px; font-weight: 800; line-height: 1; color: #111827;
    letter-spacing: -0.03em; margin-bottom: 4px;
}
.db-stat-label {
    font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase;
    letter-spacing: 0.06em; line-height: 1.2;
}
.db-stat-sub { font-size: 12px; color: #9ca3af; font-weight: 400; margin-top: 1px; }

/* ── Dashboard Grid ──────────────────────────────── */
.db-grid-2 {
    display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;
}

/* ── Cards ───────────────────────────────────────── */
.db-card {
    background: #fff; border-radius: 14px; border: 1px solid #e5e7eb;
    overflow: hidden; transition: box-shadow 0.2s;
}
.db-card:hover { box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
.db-card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 24px 18px; border-bottom: 1px solid #f3f4f6;
}
.db-card-title {
    display: flex; align-items: center; gap: 9px;
    font-size: 15px; font-weight: 600; color: #111827;
}
.db-card-title svg { flex-shrink: 0; }
.db-view-all {
    font-size: 12px; font-weight: 500; color: #2563eb;
    text-decoration: none; white-space: nowrap; transition: color 0.15s;
}
.db-view-all:hover, .db-view-all:link, .db-view-all:visited { color: #2563eb; text-decoration: none; }
.db-view-all:hover { color: #1d4ed8; }
.db-card-body { padding: 0; min-height: 320px; display: flex; flex-direction: column; }
/* Thin, flat scrollbar for scrollable card bodies (e.g. My Recent Calls) —
 * avoids the browser's default scrollbar with bulky up/down arrow buttons. */
.db-card-body::-webkit-scrollbar { width: 6px; }
.db-card-body::-webkit-scrollbar-track { background: transparent; }
.db-card-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 3px; }
.db-card-body::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
.db-card-body { scrollbar-width: thin; scrollbar-color: #e2e8f0 transparent; }

/* ── List rows (Upcoming Interviews / Recent Activity) ── */
.db-list-item {
    display: flex; align-items: center; justify-content: space-between; gap: 14px;
    padding: 16px 24px; border-bottom: 1px solid #f3f4f6; font-size: 14px;
    text-decoration: none; color: inherit; transition: background 0.15s;
}
.db-list-item:last-child { border-bottom: none; }
.db-list-item:hover { background: #f9fafb; }
.db-list-item:hover .db-list-title { color: #2563eb; }
.db-list-main { display: flex; flex-direction: column; gap: 3px; min-width: 0; }
.db-list-title { color: #111827; font-weight: 500; }
.db-list-sub { color: #6b7280; font-size: 13px; }
.db-list-meta { color: #9ca3af; font-size: 12px; white-space: nowrap; flex-shrink: 0; text-align: right; line-height: 1.5; }

/* ── Empty states ────────────────────────────────── */
.db-empty {
    flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 40px 24px; text-align: center;
}
.db-empty-icon {
    width: 64px; height: 64px; border-radius: 50%; background: #f3f4f6;
    display: flex; align-items: center; justify-content: center; margin-bottom: 16px;
}
.db-empty p { margin: 0 0 6px; font-size: 15px; font-weight: 500; color: #374151; }
.db-empty small { font-size: 13px; color: #9ca3af; display: block; margin-bottom: 16px; }

/* ── Recent Hires period dropdown ─────────────────── */
.db-period-select {
    padding: 5px 28px 5px 10px; border: 1px solid #e5e7eb; border-radius: 8px;
    font-size: 12px; color: #374151; background: #fff;
    appearance: none; -webkit-appearance: none; cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%236b7280' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 8px center;
}

/* ── Responsive ──────────────────────────────────── */
@media (max-width: 1024px) {
    .db-stats { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .db-grid-2 { grid-template-columns: 1fr; }
    .db-stats  { grid-template-columns: repeat(2, 1fr); }
}
</style>

<?php
$role = isset($this->userRole) ? $this->userRole : 'admin';
$roleLabels = [
    'admin'       => ['label' => 'Admin',      'desc' => 'Full system overview and management controls'],
    'recruiter'   => ['label' => 'Recruiter',  'desc' => 'Manage your candidates, jobs, and hiring pipelines'],
    'interviewer' => ['label' => 'Interviewer','desc' => 'View your interview schedule and provide feedback'],
];
$roleInfo = $roleLabels[$role] ?? $roleLabels['admin'];
?>

<div class="db-wrap">

    <!-- ── Page Header ─────────────────────────────── -->
    <div class="db-page-header">
        <div class="db-page-title">
            <div class="page-icon">
                <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
            </div>
            <div>
                <h2>Welcome, <?php echo htmlspecialchars($this->userFullName ?? 'User'); ?></h2>
                <div class="db-page-subtitle"><?php echo $roleInfo['desc']; ?></div>
            </div>
        </div>
        <span class="db-role-badge"><?php echo htmlspecialchars($roleInfo['label']); ?></span>
    </div>

    <?php if ($role === 'admin' || $role === 'recruiter'): ?>

    <!-- ── Stat Cards ──────────────────────────────── -->
    <div class="db-stats">
        <a class="db-stat blue" href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=listByView">
            <div class="db-stat-icon">
                <svg width="22" height="22" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div>
                <div class="db-stat-num"><?php echo $this->candidateCount ?? 0; ?></div>
                <div class="db-stat-label">Total Candidates</div>
                <div class="db-stat-sub">Across all jobs</div>
            </div>
        </a>
        <a class="db-stat green" href="<?php echo htmlspecialchars($this->openJobOrdersURL); ?>">
            <div class="db-stat-icon">
                <svg width="22" height="22" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
            </div>
            <div>
                <div class="db-stat-num"><?php echo $this->jobOrdersOpenCount ?? 0; ?></div>
                <div class="db-stat-label">Jobs Open</div>
                <div class="db-stat-sub">Active, On Hold &amp; Full</div>
            </div>
        </a>
        <a class="db-stat red" href="<?php echo htmlspecialchars($this->closedJobOrdersURL); ?>">
            <div class="db-stat-icon">
                <svg width="22" height="22" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/><line x1="2" y1="14" x2="22" y2="14"/></svg>
            </div>
            <div>
                <div class="db-stat-num"><?php echo $this->jobOrdersClosedCount ?? 0; ?></div>
                <div class="db-stat-label">Jobs Closed</div>
                <div class="db-stat-sub">Closed &amp; Canceled</div>
            </div>
        </a>
        <div class="db-stat purple" style="cursor: default;">
            <div class="db-stat-icon" style="background:#f5f3ff;">
                <svg width="22" height="22" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            </div>
            <div style="flex: 1; min-width: 0;">
                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=listByView" style="text-decoration:none;color:inherit;display:block;">
                    <div class="db-stat-num" id="recentHiresCount"><?php echo $this->recentHiresCount ?? 0; ?></div>
                    <div class="db-stat-label">Recent Hires</div>
                </a>
                <select class="db-period-select" id="recentHiresPeriod" onchange="updateRecentHires(this.value)" style="margin-top:4px;font-size:11px;padding:3px 22px 3px 8px;">
                    <option value="week">Past Week</option>
                    <option value="month" selected>Past Month</option>
                    <option value="year">Past Year</option>
                </select>
            </div>
        </div>
    </div>
    <script>
    function updateRecentHires(period) {
        var countEl = document.getElementById('recentHiresCount');
        fetch('ajax.php?f=getRecentHiresCount&period=' + encodeURIComponent(period))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data && data.error === 0) {
                    countEl.textContent = data.count;
                }
            });
    }
    </script>

    <!-- ── Upcoming Interviews + Recent Activity ────── -->
    <div class="db-grid-2">

        <!-- Upcoming Interviews -->
        <div class="db-card">
            <div class="db-card-head">
                <div class="db-card-title">
                    <svg width="16" height="16" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Upcoming Interviews
                </div>
                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=calendar" class="db-view-all">View all</a>
            </div>
            <div class="db-card-body">
                <?php if (!empty($this->upcomingInterviewsRS)): ?>
                    <?php foreach ($this->upcomingInterviewsRS as $interview): ?>
                        <a class="db-list-item" href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=show&amp;candidateID=<?php echo (int) $interview['candidateID']; ?>">
                            <div class="db-list-main">
                                <span class="db-list-title"><?php echo htmlspecialchars(trim($interview['candidateFirstName'] . ' ' . $interview['candidateLastName']) ?: $interview['title']); ?></span>
                                <span class="db-list-sub">
                                    <?php echo htmlspecialchars($interview['jobTitle'] ?: $interview['title']); ?>
                                    <?php if (!empty($interview['interviewerFirstName'])): ?>
                                        &middot; with <?php echo htmlspecialchars($interview['interviewerFirstName'] . ' ' . $interview['interviewerLastName']); ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="db-list-meta"><?php echo htmlspecialchars($interview['eventDate']); ?><br><?php echo htmlspecialchars($interview['eventTime']); ?></div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="db-empty">
                        <div class="db-empty-icon">
                            <svg width="26" height="26" fill="none" stroke="#9ca3af" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <p>No upcoming interviews</p>
                        <small>Interviews scheduled in the next 7 days will appear here</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="db-card">
            <div class="db-card-head">
                <div class="db-card-title">
                    <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    Recent Activity
                </div>
                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=activity&amp;a=listByView" class="db-view-all">View all</a>
            </div>
            <div class="db-card-body">
                <?php if (!empty($this->recentActivityRS)): ?>
                    <?php foreach ($this->recentActivityRS as $activityRow): ?>
                        <a class="db-list-item" href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=show&amp;candidateID=<?php echo (int) $activityRow['candidateID']; ?>">
                            <div class="db-list-main">
                                <span class="db-list-title"><?php echo htmlspecialchars(trim($activityRow['candidateFirstName'] . ' ' . $activityRow['candidateLastName'])); ?></span>
                                <span class="db-list-sub">
                                    <?php echo htmlspecialchars($activityRow['typeDescription']); ?>
                                    <?php if (!empty($activityRow['enteredByFirstName'])): ?>
                                        by <?php echo htmlspecialchars($activityRow['enteredByFirstName'] . ' ' . $activityRow['enteredByLastName']); ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="db-list-meta"><?php echo htmlspecialchars($activityRow['dateCreated']); ?></div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="db-empty">
                        <div class="db-empty-icon">
                            <svg width="26" height="26" fill="none" stroke="#9ca3af" stroke-width="1.5" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        </div>
                        <p>No recent activity</p>
                        <small>Logged calls, notes, and status changes will appear here</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php endif; /* admin/recruiter */ ?>

    <?php if ($role === 'interviewer'): ?>
    <!-- ── Interviewer View ─────────────────────────── -->
    <div class="db-grid-2">
        <div class="db-card">
            <div class="db-card-head">
                <div class="db-card-title">
                    <svg width="16" height="16" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    My Interview Schedule
                </div>
            </div>
            <div class="db-card-body" style="font-size:13px;padding:8px 0;">
                <?php echo $this->upcomingEventsHTML; ?>
                <?php if (empty(trim($this->upcomingEventsHTML))): ?>
                <div class="db-empty"><div class="db-empty-icon"><svg width="22" height="22" fill="none" stroke="#9ca3af" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div><p>No upcoming interviews</p></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="db-card">
            <div class="db-card-head">
                <div class="db-card-title">
                    <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Pending Feedback
                </div>
            </div>
            <div class="db-card-body">
                <div class="db-empty"><div class="db-empty-icon"><svg width="22" height="22" fill="none" stroke="#9ca3af" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><p>All feedback submitted</p><small>Great work!</small></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div><!-- .db-wrap -->

    </div><!-- #contents -->
</div><!-- #main -->
<?php TemplateUtility::printFooter(); ?>
