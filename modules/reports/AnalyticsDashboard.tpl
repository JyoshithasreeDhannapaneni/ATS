<?php /* $Id: AnalyticsDashboard.tpl $ */ ?>
<?php TemplateUtility::printHeader('Analytics Dashboard', array('modules/reports/analytics.css')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>

<div id="analyticsDashboard">
    <!-- Period Selector -->
    <div class="analytics-controls">
        <div class="period-selector">
            <label>Period:</label>
            <select id="periodDays" onchange="loadAllAnalytics()">
                <option value="30">Last 30 Days</option>
                <option value="90" selected>Last 90 Days</option>
                <option value="180">Last 6 Months</option>
                <option value="365">Last Year</option>
            </select>
        </div>
        <button class="analytics-refresh-btn" onclick="loadAllAnalytics()">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 4v6h-6"/><path d="M1 20v-6h6"/><path d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15"/></svg>
            Refresh
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards" id="summaryCards">
        <div class="summary-card blue">
            <div class="summary-icon">
                <svg width="24" height="24" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div class="summary-value" id="statCandidates">—</div>
            <div class="summary-label">Active Candidates</div>
        </div>
        <div class="summary-card green">
            <div class="summary-icon">
                <svg width="24" height="24" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
            </div>
            <div class="summary-value" id="statJobOrders">—</div>
            <div class="summary-label">Active Job Orders</div>
        </div>
        <div class="summary-card purple">
            <div class="summary-icon">
                <svg width="24" height="24" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="summary-value" id="statPlacements">—</div>
            <div class="summary-label">Placements This Month</div>
        </div>
        <div class="summary-card amber">
            <div class="summary-icon">
                <svg width="24" height="24" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="summary-value" id="statInterviews">—</div>
            <div class="summary-label">Upcoming Interviews</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="analytics-grid">
        <!-- Pipeline Funnel -->
        <div class="analytics-card full-width">
            <div class="card-header">
                <h3>Pipeline Funnel</h3>
            </div>
            <div class="card-body" id="funnelChart">
                <div class="loading-spinner">Loading...</div>
            </div>
        </div>

        <!-- Conversion Rates -->
        <div class="analytics-card">
            <div class="card-header">
                <h3>Stage Conversion Rates</h3>
            </div>
            <div class="card-body" id="conversionChart">
                <div class="loading-spinner">Loading...</div>
            </div>
        </div>

        <!-- Time to Hire -->
        <div class="analytics-card">
            <div class="card-header">
                <h3>Time to Hire</h3>
            </div>
            <div class="card-body" id="timeToHireChart">
                <div class="loading-spinner">Loading...</div>
            </div>
        </div>

        <!-- Recruiter Performance -->
        <div class="analytics-card full-width">
            <div class="card-header">
                <h3>Recruiter Performance</h3>
            </div>
            <div class="card-body" id="recruiterTable">
                <div class="loading-spinner">Loading...</div>
            </div>
        </div>

        <!-- Hiring Trend -->
        <div class="analytics-card">
            <div class="card-header">
                <h3>Hiring Trend (12 Months)</h3>
            </div>
            <div class="card-body" id="hiringTrendChart">
                <div class="loading-spinner">Loading...</div>
            </div>
        </div>

        <!-- Source Effectiveness -->
        <div class="analytics-card">
            <div class="card-header">
                <h3>Candidate Source Effectiveness</h3>
            </div>
            <div class="card-body" id="sourceChart">
                <div class="loading-spinner">Loading...</div>
            </div>
        </div>
    </div>
</div>

<script>
const COLORS = ['#2563eb','#059669','#7c3aed','#d97706','#dc2626','#0891b2','#4f46e5','#be185d','#065f46','#92400e'];

async function fetchAnalytics(type, params = {}) {
    const period = document.getElementById('periodDays').value;
    const query = new URLSearchParams({f: 'getAnalytics', type, period, ...params});
    const resp = await fetch('ajax.php?' + query.toString());
    return await resp.json();
}

async function loadAllAnalytics() {
    loadSummary();
    loadFunnel();
    loadConversions();
    loadTimeToHire();
    loadRecruiterPerformance();
    loadHiringTrend();
    loadSources();
}

async function loadSummary() {
    const data = await fetchAnalytics('summary');
    if (data.error !== 0) return;
    const d = data.data;
    document.getElementById('statCandidates').textContent = d.activeCandidates;
    document.getElementById('statJobOrders').textContent = d.activeJobOrders;
    document.getElementById('statPlacements').textContent = d.placementsThisMonth;
    document.getElementById('statInterviews').textContent = d.upcomingInterviews;
}

async function loadFunnel() {
    const container = document.getElementById('funnelChart');
    const data = await fetchAnalytics('funnel');
    if (data.error !== 0) { container.innerHTML = '<p class="empty-state">Failed to load data</p>'; return; }

    const stages = data.data.filter(s => s.count > 0 || s.statusID > 0);
    const maxCount = Math.max(...stages.map(s => parseInt(s.count) || 0), 1);

    let html = '<div class="funnel-chart">';
    stages.forEach((stage, i) => {
        const count = parseInt(stage.count) || 0;
        const pct = Math.max(5, (count / maxCount) * 100);
        html += `<div class="funnel-stage" style="animation-delay:${i*0.1}s">
            <div class="funnel-label">${stage.status}</div>
            <div class="funnel-bar-wrap">
                <div class="funnel-bar" style="width:${pct}%;background:${COLORS[i % COLORS.length]}"></div>
            </div>
            <div class="funnel-count">${count}</div>
        </div>`;
    });
    html += '</div>';
    container.innerHTML = html;
}

async function loadConversions() {
    const container = document.getElementById('conversionChart');
    const data = await fetchAnalytics('conversions');
    if (data.error !== 0) { container.innerHTML = '<p class="empty-state">No data</p>'; return; }

    let html = '<div class="conversion-list">';
    data.data.forEach((conv, i) => {
        const rateColor = conv.rate >= 50 ? '#059669' : conv.rate >= 25 ? '#d97706' : '#dc2626';
        html += `<div class="conversion-item" style="animation-delay:${i*0.08}s">
            <div class="conversion-stages">${conv.from} → ${conv.to}</div>
            <div class="conversion-bar-wrap">
                <div class="conversion-bar" style="width:${Math.max(2, conv.rate)}%;background:${rateColor}"></div>
            </div>
            <div class="conversion-rate" style="color:${rateColor}">${conv.rate}%</div>
        </div>`;
    });
    html += '</div>';
    container.innerHTML = html || '<p class="empty-state">No conversion data available</p>';
}

async function loadTimeToHire() {
    const container = document.getElementById('timeToHireChart');
    const data = await fetchAnalytics('timeToHire');
    if (data.error !== 0) { container.innerHTML = '<p class="empty-state">No data</p>'; return; }

    const d = data.data;
    container.innerHTML = `
        <div class="tth-grid">
            <div class="tth-card">
                <div class="tth-value">${d.avgDays || '—'}</div>
                <div class="tth-label">Avg Days to Hire</div>
            </div>
            <div class="tth-card">
                <div class="tth-value">${d.minDays || '—'}</div>
                <div class="tth-label">Fastest (Days)</div>
            </div>
            <div class="tth-card">
                <div class="tth-value">${d.maxDays || '—'}</div>
                <div class="tth-label">Slowest (Days)</div>
            </div>
            <div class="tth-card">
                <div class="tth-value">${d.totalPlacements}</div>
                <div class="tth-label">Total Placements</div>
            </div>
        </div>
    `;
}

async function loadRecruiterPerformance() {
    const container = document.getElementById('recruiterTable');
    const data = await fetchAnalytics('recruiterPerformance');
    if (data.error !== 0 || !data.data.length) {
        container.innerHTML = '<p class="empty-state">No recruiter performance data</p>';
        return;
    }

    let html = `<table class="analytics-table">
        <thead><tr>
            <th>Recruiter</th><th>Candidates Added</th><th>Submitted</th>
            <th>Interviewing</th><th>Placed</th><th>Conversion</th>
        </tr></thead><tbody>`;

    data.data.forEach(r => {
        const conv = r.candidatesAdded > 0 ? Math.round((r.placed / r.candidatesAdded) * 100) : 0;
        html += `<tr>
            <td><strong>${r.firstName} ${r.lastName}</strong></td>
            <td>${r.candidatesAdded}</td><td>${r.submitted}</td>
            <td>${r.interviewing}</td><td>${r.placed}</td>
            <td><span class="conv-badge" style="background:${conv >= 20 ? '#059669' : conv >= 10 ? '#d97706' : '#dc2626'}">${conv}%</span></td>
        </tr>`;
    });

    html += '</tbody></table>';
    container.innerHTML = html;
}

async function loadHiringTrend() {
    const container = document.getElementById('hiringTrendChart');
    const data = await fetchAnalytics('hiringTrend');
    if (data.error !== 0 || !data.data.length) {
        container.innerHTML = '<p class="empty-state">No hiring trend data</p>';
        return;
    }

    const maxVal = Math.max(...data.data.map(d => parseInt(d.placements)), 1);
    let html = '<div class="bar-chart">';
    data.data.forEach((d, i) => {
        const pct = (parseInt(d.placements) / maxVal) * 100;
        const monthLabel = d.month.substring(5); // "MM" from "YYYY-MM"
        html += `<div class="bar-col" style="animation-delay:${i*0.05}s">
            <div class="bar-value">${d.placements}</div>
            <div class="bar" style="height:${Math.max(4, pct)}%;background:${COLORS[i % COLORS.length]}"></div>
            <div class="bar-label">${monthLabel}</div>
        </div>`;
    });
    html += '</div>';
    container.innerHTML = html;
}

async function loadSources() {
    const container = document.getElementById('sourceChart');
    const data = await fetchAnalytics('sources');
    if (data.error !== 0 || !data.data.length) {
        container.innerHTML = '<p class="empty-state">No source data available</p>';
        return;
    }

    let html = '<div class="source-list">';
    const maxTotal = Math.max(...data.data.map(s => parseInt(s.totalCandidates)), 1);
    data.data.forEach((s, i) => {
        const pct = (parseInt(s.totalCandidates) / maxTotal) * 100;
        html += `<div class="source-item" style="animation-delay:${i*0.08}s">
            <div class="source-name">${s.source}</div>
            <div class="source-bar-wrap">
                <div class="source-bar" style="width:${Math.max(3, pct)}%;background:${COLORS[i % COLORS.length]}"></div>
            </div>
            <div class="source-stats">
                <span>${s.totalCandidates} total</span>
                <span>${s.interviewed} interviews</span>
                <span>${s.placed} placed</span>
            </div>
        </div>`;
    });
    html += '</div>';
    container.innerHTML = html;
}

// Load on page ready
document.addEventListener('DOMContentLoaded', loadAllAnalytics);
</script>

<?php TemplateUtility::printFooter(); ?>
