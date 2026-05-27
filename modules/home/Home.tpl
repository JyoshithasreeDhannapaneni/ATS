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

/* ── Welcome Banner ──────────────────────────────── */
.db-banner {
    position: relative;
    background: linear-gradient(135deg, #1e40af 0%, #2563eb 60%, #3b82f6 100%);
    border-radius: 16px;
    padding: 28px 32px;
    margin-bottom: 24px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 100px;
}
.db-banner::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 60% 80% at 90% 50%, rgba(255,255,255,0.08) 0%, transparent 60%),
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 60'%3E%3Cpath d='M0 50 Q30 20 60 40 Q90 55 120 25 Q150 5 180 30 L200 20 L200 60 L0 60Z' fill='rgba(255,255,255,0.06)'/%3E%3C/svg%3E") right bottom / 55% auto no-repeat;
    pointer-events: none;
}
.db-banner-text h2 {
    font-size: 22px; font-weight: 700; color: #fff; margin: 0 0 4px; letter-spacing: -0.02em;
}
.db-banner-text p { font-size: 13px; color: rgba(255,255,255,0.75); margin: 0; }
.db-banner-badge {
    padding: 5px 14px; background: rgba(255,255,255,0.18); color: #fff;
    border-radius: 20px; font-size: 12px; font-weight: 600;
    border: 1px solid rgba(255,255,255,0.25); white-space: nowrap;
    backdrop-filter: blur(4px);
}
.db-banner-graphic {
    position: absolute; right: 120px; bottom: 0; top: 0;
    display: flex; align-items: flex-end; opacity: 0.18; pointer-events: none;
}
.db-banner-graphic svg { height: 80px; width: auto; }

/* ── Stat Cards ──────────────────────────────────── */
.db-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.db-stat {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
    padding: 20px 22px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: box-shadow 0.2s, transform 0.2s;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    position: relative;
    overflow: hidden;
}
.db-stat::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0;
    width: 4px; border-radius: 14px 0 0 14px;
}
.db-stat.blue::before  { background: #2563eb; }
.db-stat.green::before { background: #059669; }
.db-stat.purple::before{ background: #7c3aed; }
.db-stat.amber::before { background: #d97706; }
.db-stat:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); transform: translateY(-2px); }
.db-stat-icon {
    width: 48px; height: 48px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.db-stat.blue  .db-stat-icon { background: #eff6ff; }
.db-stat.green .db-stat-icon { background: #ecfdf5; }
.db-stat.purple.db-stat .db-stat-icon { background: #f5f3ff; }
.db-stat.amber .db-stat-icon { background: #fffbeb; }
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
.db-grid-3 {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px;
}

/* ── Cards ───────────────────────────────────────── */
.db-card {
    background: #fff; border-radius: 14px; border: 1px solid #e5e7eb;
    overflow: hidden; transition: box-shadow 0.2s;
}
.db-card:hover { box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
.db-card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px 14px; border-bottom: 1px solid #f3f4f6;
}
.db-card-title {
    display: flex; align-items: center; gap: 8px;
    font-size: 14px; font-weight: 600; color: #111827;
}
.db-card-title svg { flex-shrink: 0; }
.db-view-all {
    font-size: 12px; font-weight: 500; color: #2563eb;
    text-decoration: none; white-space: nowrap; transition: color 0.15s;
}
.db-view-all:hover, .db-view-all:link, .db-view-all:visited { color: #2563eb; text-decoration: none; }
.db-view-all:hover { color: #1d4ed8; }
.db-card-body { padding: 0; }

/* ── Call List ───────────────────────────────────── */
.db-call-row {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 20px; border-bottom: 1px solid #f9fafb; font-size: 13px;
}
.db-call-row:last-child { border-bottom: none; }
.db-call-icon {
    width: 28px; height: 28px; border-radius: 50%; background: #ecfdf5;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.db-call-time { color: #6b7280; font-size: 12px; min-width: 130px; }
.db-call-name { color: #2563eb; font-weight: 500; text-decoration: none; }
.db-call-name:link, .db-call-name:visited { color: #2563eb; }

/* ── Follow-up list item ─────────────────────────── */
.db-fup-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 20px; border-bottom: 1px solid #f3f4f6;
    font-size: 13px; font-weight: 500; color: #374151;
    cursor: pointer; transition: background 0.15s;
}
.db-fup-item:hover { background: #f9fafb; }
.db-fup-item:last-child { border-bottom: none; }
.db-fup-arrow { color: #9ca3af; }

/* ── Table inside cards ──────────────────────────── */
.db-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.db-table th {
    padding: 10px 20px; text-align: left;
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.07em; color: #9ca3af;
    border-bottom: 1px solid #f3f4f6; background: #fafafa;
}
.db-table td {
    padding: 11px 20px; border-bottom: 1px solid #f3f4f6;
    color: #374151; font-weight: 400;
}
.db-table tbody tr:last-child td { border-bottom: none; }
.db-table tbody tr:hover td { background: #f9fafb; }
.db-table a { color: #2563eb; text-decoration: none; }
.db-table a:link, .db-table a:visited { color: #2563eb; }

/* ── Empty states ────────────────────────────────── */
.db-empty {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 40px 20px; text-align: center;
}
.db-empty-icon {
    width: 52px; height: 52px; border-radius: 50%; background: #f3f4f6;
    display: flex; align-items: center; justify-content: center; margin-bottom: 12px;
}
.db-empty p { margin: 0 0 4px; font-size: 14px; font-weight: 500; color: #374151; }
.db-empty small { font-size: 12px; color: #9ca3af; display: block; margin-bottom: 16px; }
.db-btn-primary {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 8px 18px; background: #2563eb; color: #fff !important;
    border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none !important;
    border: none; cursor: pointer; transition: background 0.15s;
}
.db-btn-primary:hover { background: #1d4ed8; }
.db-btn-outline {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 16px; background: #fff; color: #374151 !important;
    border: 1px solid #d1d5db; border-radius: 8px;
    font-size: 13px; font-weight: 500; text-decoration: none !important;
    cursor: pointer; transition: all 0.15s;
}
.db-btn-outline:hover { background: #f9fafb; border-color: #9ca3af; }

/* ── Hiring Overview dropdown ────────────────────── */
.db-period-select {
    padding: 5px 28px 5px 10px; border: 1px solid #e5e7eb; border-radius: 8px;
    font-size: 12px; color: #374151; background: #fff;
    appearance: none; -webkit-appearance: none; cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%236b7280' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 8px center;
}

/* ── Admin shortcut cards ────────────────────────── */
.db-admin-card {
    background: #fff; border: 1px solid #e5e7eb; border-radius: 14px;
    padding: 18px 20px; display: flex; align-items: center; gap: 14px;
    text-decoration: none; color: inherit; transition: all 0.2s;
    cursor: pointer;
}
.db-admin-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.07); transform: translateY(-1px); }
.db-admin-card:link, .db-admin-card:visited { color: inherit; text-decoration: none; }
.db-admin-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.db-admin-icon.blue   { background: #eff6ff; }
.db-admin-icon.green  { background: #ecfdf5; }
.db-admin-icon.purple { background: #f5f3ff; }
.db-admin-text { flex: 1; min-width: 0; }
.db-admin-text strong { display: block; font-size: 14px; font-weight: 600; color: #111827; margin-bottom: 2px; }
.db-admin-text span   { font-size: 12px; color: #9ca3af; }
.db-admin-arrow { color: #d1d5db; flex-shrink: 0; }

/* ── Responsive ──────────────────────────────────── */
@media (max-width: 1024px) {
    .db-stats { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .db-grid-2 { grid-template-columns: 1fr; }
    .db-grid-3 { grid-template-columns: 1fr; }
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

    <!-- ── Welcome Banner ──────────────────────────── -->
    <div class="db-banner">
        <div class="db-banner-text">
            <h2>Welcome, <?php echo htmlspecialchars($this->userFullName ?? 'User'); ?></h2>
            <p><?php echo $roleInfo['desc']; ?></p>
        </div>
        <!-- Decorative chart bars -->
        <div class="db-banner-graphic">
            <svg viewBox="0 0 120 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="0"  y="50" width="14" height="30" rx="3" fill="white"/>
                <rect x="18" y="35" width="14" height="45" rx="3" fill="white"/>
                <rect x="36" y="20" width="14" height="60" rx="3" fill="white"/>
                <rect x="54" y="40" width="14" height="40" rx="3" fill="white"/>
                <rect x="72" y="10" width="14" height="70" rx="3" fill="white"/>
                <rect x="90" y="28" width="14" height="52" rx="3" fill="white"/>
                <rect x="108" y="45" width="12" height="35" rx="3" fill="white"/>
            </svg>
        </div>
        <span class="db-banner-badge"><?php echo htmlspecialchars($roleInfo['label']); ?></span>
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
        <a class="db-stat green" href="<?php echo CATSUtility::getIndexName(); ?>?m=joborders&amp;a=listByView">
            <div class="db-stat-icon">
                <svg width="22" height="22" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
            </div>
            <div>
                <div class="db-stat-num"><?php echo $this->jobOrderCount ?? 0; ?></div>
                <div class="db-stat-label">Job Orders</div>
                <div class="db-stat-sub">Active positions</div>
            </div>
        </a>
        <a class="db-stat" style="border-left:none;" href="<?php echo CATSUtility::getIndexName(); ?>?m=joborders&amp;a=pipelineBoard">
            <style>.db-stat.purple-card::before { background: #7c3aed; }</style>
            <div class="db-stat-icon" style="background:#f5f3ff;">
                <svg width="22" height="22" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            </div>
            <div>
                <div class="db-stat-num"><?php echo count($this->placedRS); ?></div>
                <div class="db-stat-label">Recent Hires</div>
                <div class="db-stat-sub">This month</div>
            </div>
        </a>
        <a class="db-stat amber" href="<?php echo CATSUtility::getIndexName(); ?>?m=calendar">
            <div class="db-stat-icon">
                <svg width="22" height="22" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div>
                <div class="db-stat-num">&mdash;</div>
                <div class="db-stat-label">Upcoming Events</div>
                <div class="db-stat-sub">Next 7 days</div>
            </div>
        </a>
    </div>
    <style>
    /* Fix purple stat card left border inline */
    .db-stats .db-stat:nth-child(3)::before { background: #7c3aed; }
    </style>

    <!-- ── Row 1: Recent Calls + Follow-Ups ────────── -->
    <div class="db-grid-2">

        <!-- My Recent Calls -->
        <div class="db-card">
            <div class="db-card-head">
                <div class="db-card-title">
                    <svg width="16" height="16" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.25 1.22 2 2 0 012.22 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92z"/></svg>
                    My Recent Calls
                </div>
                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=activities&amp;a=listByView" class="db-view-all">View all</a>
            </div>
            <div class="db-card-body" style="max-height:220px;overflow-y:auto;">
                <?php
                // Render datagrid rows as clean list items
                ob_start();
                $this->dataGrid2->drawHTML();
                $callsHTML = ob_get_clean();

                // If datagrid has data, show it; otherwise show empty state
                if ($this->dataGrid2->getNumberOfRows() > 0):
                ?>
                <div style="font-size:13px;">
                    <?php echo $callsHTML; ?>
                </div>
                <?php else: ?>
                <div class="db-empty" style="padding:30px 20px;">
                    <div class="db-empty-icon">
                        <svg width="22" height="22" fill="none" stroke="#9ca3af" stroke-width="1.5" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.25 1.22 2 2 0 012.22 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92z"/></svg>
                    </div>
                    <p>No recent calls</p>
                    <small>Calls logged in the last 30 days will appear here</small>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Upcoming Follow-Ups -->
        <div class="db-card">
            <div class="db-card-head">
                <div class="db-card-title">
                    <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Upcoming Follow-Ups
                </div>
                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=calendar" class="db-view-all">View all</a>
            </div>
            <div class="db-card-body">
                <?php if (!empty(trim($this->upcomingEventsFupHTML))): ?>
                    <div style="font-size:13px;padding:4px 0;">
                        <?php echo $this->upcomingEventsFupHTML; ?>
                    </div>
                <?php else: ?>
                    <div class="db-fup-item" style="cursor:default;">
                        <span style="color:#6b7280;">My Upcoming Calls</span>
                        <span class="db-fup-arrow">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                        </span>
                    </div>
                    <div class="db-empty" style="padding:24px 20px 28px;">
                        <div class="db-empty-icon">
                            <svg width="22" height="22" fill="none" stroke="#9ca3af" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <p>No upcoming follow-ups</p>
                        <small>Scheduled follow-ups will appear here</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ── Row 2: Recent Hires + Hiring Overview ──── -->
    <div class="db-grid-2">

        <!-- Recent Hires -->
        <div class="db-card">
            <div class="db-card-head">
                <div class="db-card-title">
                    <svg width="16" height="16" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Recent Hires
                </div>
                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=listByView" class="db-view-all">View all</a>
            </div>
            <div class="db-card-body">
                <?php if (count($this->placedRS)): ?>
                <table class="db-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Recruiter</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->placedRS as $data): ?>
                        <tr>
                            <td><a href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=show&amp;candidateID=<?php echo $data['candidateID']; ?>"><?php $this->_($data['firstName']); ?> <?php $this->_($data['lastName']); ?></a></td>
                            <td><a href="<?php echo CATSUtility::getIndexName(); ?>?m=companies&amp;a=show&amp;companyID=<?php echo $data['companyID']; ?>"><?php $this->_($data['companyName']); ?></a></td>
                            <td><?php $this->_(StringUtility::makeInitialName($data['userFirstName'], $data['userLastName'], false, LAST_NAME_MAXLEN)); ?></td>
                            <td><?php $this->_($data['date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="db-empty">
                    <div class="db-empty-icon">
                        <svg width="24" height="24" fill="none" stroke="#9ca3af" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <p>No recent hires yet</p>
                    <small style="margin-bottom:14px;">Placed candidates will appear here</small>
                    <a href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=add" class="db-btn-primary">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Candidate
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Hiring Overview -->
        <div class="db-card">
            <div class="db-card-head">
                <div class="db-card-title">
                    <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    Hiring Overview
                </div>
                <select class="db-period-select" id="hiringPeriodSelect" onchange="swapHomeGraph(this.value)">
                    <option value="<?php echo DASHBOARD_GRAPH_WEEKLY; ?>">This Week</option>
                    <option value="<?php echo DASHBOARD_GRAPH_MONTHLY; ?>" selected>This Month</option>
                    <option value="<?php echo DASHBOARD_GRAPH_YEARLY; ?>">This Year</option>
                </select>
            </div>
            <div class="db-card-body">
                <div id="hiringOverviewWrap" style="width:100%;height:200px;position:relative;background:#fafafa;">
                    <img id="homeGraph"
                        src="<?php echo CATSUtility::getIndexName(); ?>?m=graphs&amp;a=miniPlacementStatistics&amp;width=495&amp;height=200&amp;view=<?php echo DASHBOARD_GRAPH_MONTHLY; ?>&amp;t=<?php echo time(); ?>"
                        alt="Hiring Overview"
                        style="display:block;width:100%;height:100%;object-fit:contain;"
                        onerror="document.getElementById('hiringOverviewError').style.display='flex';this.style.display='none';"
                        onload="this.style.display='block';document.getElementById('hiringOverviewError').style.display='none';" />
                    <div id="hiringOverviewError" style="display:none;position:absolute;inset:0;flex-direction:column;align-items:center;justify-content:center;background:#fafafa;">
                        <svg width="40" height="40" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:10px;"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                        <p style="font-size:13px;font-weight:600;color:#374151;margin:0 0 4px;">No hiring data available yet</p>
                        <small style="font-size:12px;color:#9ca3af;margin-bottom:14px;">Start placing candidates to see trends here.</small>
                        <button onclick="retryHiringGraph()" class="db-btn-outline" style="font-size:12px;padding:6px 14px;">Retry loading</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function swapHomeGraph(view) {
        var img = document.getElementById('homeGraph');
        var err = document.getElementById('hiringOverviewError');
        if (!img) return;
        err.style.display = 'none';
        img.style.display = 'block';
        var base = '<?php echo CATSUtility::getIndexName(); ?>?m=graphs&a=miniPlacementStatistics&width=495&height=200&view=';
        img.src = base + view + '&t=' + Date.now();
    }
    function retryHiringGraph() {
        var sel = document.getElementById('hiringPeriodSelect');
        swapHomeGraph(sel ? sel.value : '<?php echo DASHBOARD_GRAPH_MONTHLY; ?>');
    }
    </script>

    <!-- ── Important Candidates (full width) ───────── -->
    <div class="db-card" style="margin-bottom:20px;">
        <div class="db-card-head">
            <div class="db-card-title">
                <svg width="16" height="16" fill="#f59e0b" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                Important Candidates
            </div>
            <div style="display:flex;align-items:center;gap:14px;">
                <span style="font-size:12px;color:#9ca3af;">Page <?php echo $this->dataGrid->getCurrentPageHTML(); ?> &middot; <?php echo $this->dataGrid->getNumberOfRows(); ?> items</span>
                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=listByView" class="db-view-all">View all</a>
            </div>
        </div>
        <div class="db-card-body" style="overflow-x:auto;">
            <?php $this->dataGrid->draw(); ?>
            <?php if ($this->dataGrid->getNumberOfRows() > 0): ?>
            <div style="padding:8px 16px;border-top:1px solid #f3f4f6;display:flex;justify-content:flex-end;font-size:12px;">
                <?php $this->dataGrid->printNavigation(false); ?>
            </div>
            <?php else: ?>
            <div class="db-empty">
                <div class="db-empty-icon">
                    <svg width="24" height="24" fill="none" stroke="#9ca3af" stroke-width="1.5" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <p>No important candidates at the moment</p>
                <small>Mark candidates as "Hot" from their profile to see them here.</small>
            </div>
            <?php endif; ?>
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

    <!-- ── Admin Shortcuts ──────────────────────────── -->
    <?php if ($role === 'admin'): ?>
    <div class="db-grid-3">
        <a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&amp;a=administration" class="db-admin-card">
            <div class="db-admin-icon blue">
                <svg width="20" height="20" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <div class="db-admin-text">
                <strong>System Settings</strong>
                <span>Configure system preferences</span>
            </div>
            <div class="db-admin-arrow"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></div>
        </a>
        <a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&amp;a=manageUsers" class="db-admin-card">
            <div class="db-admin-icon green">
                <svg width="20" height="20" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div class="db-admin-text">
                <strong>Manage Users</strong>
                <span>Add, edit, or remove users</span>
            </div>
            <div class="db-admin-arrow"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></div>
        </a>
        <a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&amp;a=emailTemplates" class="db-admin-card">
            <div class="db-admin-icon purple">
                <svg width="20" height="20" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <div class="db-admin-text">
                <strong>Email Templates</strong>
                <span>Manage email templates</span>
            </div>
            <div class="db-admin-arrow"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></div>
        </a>
    </div>
    <?php endif; ?>

</div><!-- .db-wrap -->

    </div><!-- #contents -->
</div><!-- #main -->
<?php TemplateUtility::printFooter(); ?>
