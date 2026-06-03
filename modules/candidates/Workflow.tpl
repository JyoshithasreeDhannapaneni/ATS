<?php /* Workflow.tpl - Candidate Pipeline Workflow Rules */ ?>
<?php TemplateUtility::printHeader('Candidate Workflow', array()); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>

<div id="main">
<?php TemplateUtility::printQuickSearch(); ?>
<div id="contents">

<style>
*{box-sizing:border-box;}
.wf-page{font-family:'Inter',system-ui,sans-serif;color:#111827;padding:0 24px 48px;}

/* Header */
.wf-header{display:flex;align-items:center;justify-content:space-between;padding:18px 0 20px;}
.wf-title-wrap{display:flex;align-items:center;gap:12px;}
.wf-title-icon{width:44px;height:44px;background:linear-gradient(135deg,#7c3aed,#4f46e5);border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;}
.wf-title{margin:0;font-size:20px;font-weight:700;color:#1e1b4b;}
.wf-subtitle{margin:0;font-size:12px;color:#6b7280;}
.wf-back-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;background:#fff;border:1px solid #d1d5db;font-size:13px;color:#374151;text-decoration:none;font-weight:500;}
.wf-back-btn:hover{background:#f9fafb;}

/* Tabs */
.wf-tabs{display:flex;gap:4px;background:#f3f4f6;border-radius:10px;padding:4px;margin-bottom:28px;width:fit-content;}
.wf-tab{padding:7px 18px;border-radius:7px;font-size:13px;font-weight:500;cursor:pointer;border:none;background:none;color:#6b7280;transition:all .2s;}
.wf-tab.active{background:#fff;color:#4f46e5;font-weight:600;box-shadow:0 1px 3px rgba(0,0,0,.1);}

/* Section */
.wf-section{margin-bottom:36px;}
.wf-section-title{font-size:15px;font-weight:700;color:#111827;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.wf-section-title::after{content:'';flex:1;border-top:1px solid #e5e7eb;}

/* ── Pipeline Flow ── */
.wf-flow{display:flex;align-items:flex-start;gap:0;flex-wrap:wrap;position:relative;}
.wf-stage{display:flex;align-items:center;gap:0;}
.wf-stage-box{display:flex;flex-direction:column;align-items:center;position:relative;}
.wf-stage-card{background:#fff;border:2px solid #e5e7eb;border-radius:12px;padding:14px 18px;min-width:140px;text-align:center;position:relative;transition:box-shadow .2s,border-color .2s;cursor:pointer;}
.wf-stage-card:hover{box-shadow:0 4px 16px rgba(79,70,229,.12);border-color:#a5b4fc;}
.wf-stage-icon{font-size:24px;margin-bottom:6px;}
.wf-stage-name{font-size:13px;font-weight:600;color:#111827;}
.wf-stage-id{font-size:10px;color:#9ca3af;margin-top:2px;}
.wf-stage-count{position:absolute;top:-8px;right:-8px;background:#4f46e5;color:#fff;font-size:10px;font-weight:700;border-radius:20px;padding:2px 7px;min-width:20px;}
.wf-stage-flags{display:flex;gap:4px;justify-content:center;margin-top:8px;flex-wrap:wrap;}
.wf-flag{display:inline-block;padding:2px 7px;border-radius:8px;font-size:10px;font-weight:600;}
.flag-email{background:#dbeafe;color:#1d4ed8;}
.flag-schedule{background:#dcfce7;color:#15803d;}
.flag-reject{background:#fee2e2;color:#dc2626;}
.flag-offer{background:#fef3c7;color:#d97706;}
.flag-hired{background:#d1fae5;color:#065f46;}
.flag-custom{background:#f3e8ff;color:#7e22ce;}
.flag-inactive{background:#f3f4f6;color:#9ca3af;}

/* Arrow */
.wf-arrow{width:36px;height:2px;background:#d1d5db;position:relative;flex-shrink:0;margin-top:36px;}
.wf-arrow::after{content:'▶';position:absolute;right:-6px;top:-8px;color:#d1d5db;font-size:14px;}
.wf-arrow.branch{background:none;border-top:2px dashed #fca5a5;}
.wf-arrow.branch::after{color:#fca5a5;}

/* Reject branch */
.wf-reject-branch{position:absolute;bottom:-48px;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:0;}
.wf-reject-line{width:2px;height:24px;background:#fca5a5;}
.wf-reject-card{background:#fef2f2;border:1.5px solid #fca5a5;border-radius:8px;padding:6px 12px;font-size:11px;font-weight:600;color:#dc2626;white-space:nowrap;}

/* ── Rules table ── */
.wf-rules-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:14px;}
.wf-rule-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px 18px;box-shadow:0 1px 3px rgba(0,0,0,.05);}
.wf-rule-header{display:flex;align-items:center;gap:10px;margin-bottom:10px;}
.wf-rule-icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
.wf-rule-title{font-size:13px;font-weight:700;color:#111827;}
.wf-rule-subtitle{font-size:11px;color:#6b7280;}
.wf-rule-body{font-size:12px;color:#374151;line-height:1.7;}
.wf-rule-body ul{margin:0;padding-left:16px;}
.wf-rule-body li{margin-bottom:2px;}
.wf-tag{display:inline-block;padding:1px 8px;border-radius:6px;font-size:11px;font-weight:600;margin:1px;}

/* ── Status table ── */
.wf-table{width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.06);}
.wf-table th{background:#f8fafc;padding:11px 16px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;text-align:left;border-bottom:1px solid #e5e7eb;}
.wf-table td{padding:11px 16px;font-size:13px;color:#374151;border-bottom:1px solid #f3f4f6;vertical-align:middle;}
.wf-table tr:last-child td{border-bottom:none;}
.wf-table tr:hover td{background:#f9fafb;}
.wf-status-dot{width:8px;height:8px;border-radius:50%;display:inline-block;margin-right:6px;}

/* Stage color classes */
.stage-new    {border-color:#93c5fd;} .stage-new .wf-stage-icon{color:#2563eb;}
.stage-screen {border-color:#a5b4fc;} .stage-screen .wf-stage-icon{color:#4f46e5;}
.stage-l1     {border-color:#86efac;} .stage-l1 .wf-stage-icon{color:#16a34a;}
.stage-l2     {border-color:#d8b4fe;} .stage-l2 .wf-stage-icon{color:#7e22ce;}
.stage-l3     {border-color:#fde047;} .stage-l3 .wf-stage-icon{color:#ca8a04;}
.stage-offer  {border-color:#fdba74;} .stage-offer .wf-stage-icon{color:#c2410c;}
.stage-hired  {border-color:#6ee7b7;} .stage-hired .wf-stage-icon{color:#065f46;}
.stage-reject {border-color:#fca5a5;} .stage-reject .wf-stage-icon{color:#dc2626;}

.panel{display:none;} .panel.active{display:block;}
</style>

<?php
$statuses  = $this->statuses;
$countMap  = $this->countMap;
$indexName = CATSUtility::getIndexName();

// Define the main hiring pipeline flow
$pipelineFlow = array(
    array('id'=>100,  'name'=>'No Contact',         'icon'=>'📥', 'cls'=>'stage-new',    'desc'=>'Candidate just added, not yet contacted.'),
    array('id'=>200,  'name'=>'Contacted',           'icon'=>'📞', 'cls'=>'stage-new',    'desc'=>'Recruiter has reached out to the candidate.'),
    array('id'=>250,  'name'=>'Candidate Responded', 'icon'=>'💬', 'cls'=>'stage-screen', 'desc'=>'Candidate responded to initial outreach.'),
    array('id'=>300,  'name'=>'Qualifying',          'icon'=>'🔍', 'cls'=>'stage-screen', 'desc'=>'Initial screening call / profile review.'),
    array('id'=>1000, 'name'=>'Screen Select',       'icon'=>'✅', 'cls'=>'stage-screen', 'desc'=>'Passed screening — proceed to interviews.', 'custom'=>true),
    array('id'=>400,  'name'=>'Submitted',           'icon'=>'📄', 'cls'=>'stage-l1',     'desc'=>'Resume submitted to hiring manager.'),
    array('id'=>500,  'name'=>'Interviewing',        'icon'=>'🗣️', 'cls'=>'stage-l1',     'desc'=>'Active in interview rounds.'),
    array('id'=>1020, 'name'=>'L1 Select',           'icon'=>'1️⃣', 'cls'=>'stage-l1',     'desc'=>'Passed L1 interview round.', 'custom'=>true),
    array('id'=>1030, 'name'=>'L2 Select',           'icon'=>'2️⃣', 'cls'=>'stage-l2',     'desc'=>'Passed L2 interview round.', 'custom'=>true),
    array('id'=>1040, 'name'=>'L3 Select',           'icon'=>'3️⃣', 'cls'=>'stage-l3',     'desc'=>'Passed L3 interview round.', 'custom'=>true),
    array('id'=>600,  'name'=>'Offered',             'icon'=>'📩', 'cls'=>'stage-offer',  'desc'=>'Offer letter sent to candidate.'),
    array('id'=>1050, 'name'=>'Offer Release',       'icon'=>'📋', 'cls'=>'stage-offer',  'desc'=>'Formal offer released and accepted.', 'custom'=>true),
    array('id'=>800,  'name'=>'Placed',              'icon'=>'🏆', 'cls'=>'stage-hired',  'desc'=>'Candidate placed / joined the role.'),
    array('id'=>1060, 'name'=>'Onboarded',           'icon'=>'🎉', 'cls'=>'stage-hired',  'desc'=>'Candidate fully onboarded.', 'custom'=>true),
    array('id'=>1080, 'name'=>'Hired',               'icon'=>'✨', 'cls'=>'stage-hired',  'desc'=>'Marked as hired in the system.', 'custom'=>true),
);

// Rejection / exit statuses
$rejectStatuses = array(
    array('id'=>1010, 'name'=>'Screen Reject',       'icon'=>'❌', 'desc'=>'Rejected during screening stage.'),
    array('id'=>700,  'name'=>'Client Declined',     'icon'=>'🚫', 'desc'=>'Client / hiring manager declined the candidate.'),
    array('id'=>1070, 'name'=>'No Show',             'icon'=>'👻', 'desc'=>'Candidate did not show up for scheduled interview.'),
    array('id'=>650,  'name'=>'Not in Consideration','icon'=>'⛔', 'desc'=>'Removed from consideration without specific reason.'),
);

// Build status ID map
$statusMap = array();
foreach ($statuses as $s) { $statusMap[$s['statusID']] = $s; }
?>

<div class="wf-page">

    <!-- Header -->
    <div class="wf-header">
        <div class="wf-title-wrap">
            <div class="wf-title-icon">⚡</div>
            <div>
                <h2 class="wf-title">Candidate Workflow &amp; Rules</h2>
                <p class="wf-subtitle">Pipeline stages, status transitions, and hiring rules</p>
            </div>
        </div>
        <a href="<?php echo $indexName; ?>?m=candidates" class="wf-back-btn">← Back to Candidates</a>
    </div>

    <!-- Tabs -->
    <div class="wf-tabs">
        <button class="wf-tab active" onclick="switchTab('flow',this)">🔄 Pipeline Flow</button>
        <button class="wf-tab" onclick="switchTab('rules',this)">📋 Workflow Rules</button>
        <button class="wf-tab" onclick="switchTab('statuses',this)">🏷️ All Statuses</button>
    </div>

    <!-- ── TAB 1: Pipeline Flow ── -->
    <div class="panel active" id="tab-flow">

        <div class="wf-section">
            <div class="wf-section-title">🟢 Main Hiring Pipeline</div>

            <!-- Flow visual (wrap into rows of 5) -->
            <?php
            $rowSize = 5;
            $chunks  = array_chunk($pipelineFlow, $rowSize);
            foreach ($chunks as $ci => $chunk):
            ?>
            <div class="wf-flow" style="margin-bottom:<?php echo $ci < count($chunks)-1 ? '48px' : '0'; ?>">
                <?php foreach ($chunk as $i => $stage):
                    $cnt = $countMap[$stage['id']] ?? 0;
                    $s   = $statusMap[$stage['id']] ?? null;
                    $trigEmail  = $s && $s['triggersEmail'] ? true : false;
                    $canSched   = $s && $s['canSchedule']   ? true : false;
                    $isCustom   = $stage['custom'] ?? false;
                ?>
                <div class="wf-stage">
                    <div class="wf-stage-box">
                        <div class="wf-stage-card <?php echo $stage['cls']; ?>" title="<?php echo htmlspecialchars($stage['desc']); ?>">
                            <?php if ($cnt > 0): ?>
                            <div class="wf-stage-count"><?php echo $cnt; ?></div>
                            <?php endif; ?>
                            <div class="wf-stage-icon"><?php echo $stage['icon']; ?></div>
                            <div class="wf-stage-name"><?php echo htmlspecialchars($stage['name']); ?></div>
                            <div class="wf-stage-id">#<?php echo $stage['id']; ?></div>
                            <div class="wf-stage-flags">
                                <?php if ($isCustom): ?><span class="wf-flag flag-custom">Custom</span><?php endif; ?>
                                <?php if ($trigEmail): ?><span class="wf-flag flag-email">📧 Email</span><?php endif; ?>
                                <?php if ($canSched): ?><span class="wf-flag flag-schedule">📅 Schedulable</span><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($i < count($chunk)-1): ?>
                    <div class="wf-arrow"></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>

                <?php if ($ci < count($chunks)-1): ?>
                <!-- Row connector -->
                <div style="width:100%;display:flex;justify-content:flex-end;padding-right:0;margin-top:8px;">
                    <div style="display:flex;flex-direction:column;align-items:center;">
                        <div style="width:2px;height:28px;background:#d1d5db;"></div>
                        <div style="font-size:12px;color:#9ca3af;">↓</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="wf-section" style="margin-top:20px;">
            <div class="wf-section-title">🔴 Exit / Rejection Statuses</div>
            <div style="display:flex;gap:14px;flex-wrap:wrap;">
                <?php foreach ($rejectStatuses as $rs):
                    $cnt = $countMap[$rs['id']] ?? 0;
                ?>
                <div class="wf-stage-card stage-reject" style="min-width:160px;border-color:#fca5a5;" title="<?php echo htmlspecialchars($rs['desc']); ?>">
                    <?php if ($cnt > 0): ?>
                    <div class="wf-stage-count" style="background:#dc2626;"><?php echo $cnt; ?></div>
                    <?php endif; ?>
                    <div class="wf-stage-icon"><?php echo $rs['icon']; ?></div>
                    <div class="wf-stage-name"><?php echo htmlspecialchars($rs['name']); ?></div>
                    <div class="wf-stage-id">#<?php echo $rs['id']; ?></div>
                    <div class="wf-stage-flags"><span class="wf-flag flag-reject">Exit</span></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Legend -->
        <div style="display:flex;gap:20px;flex-wrap:wrap;margin-top:24px;padding:14px 18px;background:#f8fafc;border-radius:10px;align-items:center;">
            <span style="font-size:12px;font-weight:700;color:#374151;">Legend:</span>
            <span style="font-size:12px;color:#6b7280;">🔢 Number badge = active candidates in that stage</span>
            <span class="wf-flag flag-custom">Custom</span> <span style="font-size:12px;color:#6b7280;">= Added by Neutara ATS</span>
            <span class="wf-flag flag-email">📧 Email</span> <span style="font-size:12px;color:#6b7280;">= Auto-email triggered</span>
            <span class="wf-flag flag-schedule">📅 Schedulable</span> <span style="font-size:12px;color:#6b7280;">= Can schedule interview</span>
        </div>
    </div>

    <!-- ── TAB 2: Workflow Rules ── -->
    <div class="panel" id="tab-rules">
        <div class="wf-rules-grid">

            <div class="wf-rule-card">
                <div class="wf-rule-header">
                    <div class="wf-rule-icon" style="background:#dbeafe;">📥</div>
                    <div><div class="wf-rule-title">Candidate Entry Rules</div><div class="wf-rule-subtitle">How candidates enter the pipeline</div></div>
                </div>
                <div class="wf-rule-body"><ul>
                    <li>Candidates added via <strong>Career Portal</strong> are tagged as <span class="wf-tag flag-custom">Portal Application</span> and start at <strong>No Contact (100)</strong></li>
                    <li>Candidates manually added by recruiters start at <strong>No Contact (100)</strong></li>
                    <li>Bulk import via CSV/resume also defaults to <strong>No Contact</strong></li>
                    <li>A candidate can be in multiple job order pipelines simultaneously</li>
                </ul></div>
            </div>

            <div class="wf-rule-card">
                <div class="wf-rule-header">
                    <div class="wf-rule-icon" style="background:#f3e8ff;">🔄</div>
                    <div><div class="wf-rule-title">Status Transition Rules</div><div class="wf-rule-subtitle">Moving candidates through stages</div></div>
                </div>
                <div class="wf-rule-body"><ul>
                    <li>Recruiters can move a candidate to <strong>any status</strong> at any time — no forced linear flow</li>
                    <li>Every status change is logged in the <strong>Activity Timeline</strong></li>
                    <li>Moving to certain statuses (300, 400, 500, 600, 800) can <strong>trigger an email notification</strong></li>
                    <li>Status history is permanently stored in <code>candidate_joborder_status_history</code></li>
                </ul></div>
            </div>

            <div class="wf-rule-card">
                <div class="wf-rule-header">
                    <div class="wf-rule-icon" style="background:#dcfce7;">🗓️</div>
                    <div><div class="wf-rule-title">Interview Scheduling Rules</div><div class="wf-rule-subtitle">Calendar &amp; interview rounds</div></div>
                </div>
                <div class="wf-rule-body"><ul>
                    <li>Interviews can be scheduled when candidate is at <strong>Qualifying</strong> or later</li>
                    <li>Interview stages: <strong>L1 → L2 → L3</strong> (tracked via Interview Feedback module)</li>
                    <li>Each round requires an <strong>interviewer assignment</strong> and feedback submission</li>
                    <li>Passing L1 → set status to <strong>L1 Select (1020)</strong>, repeat for L2/L3</li>
                    <li><strong>No Show (1070)</strong> can be set if candidate misses scheduled interview</li>
                </ul></div>
            </div>

            <div class="wf-rule-card">
                <div class="wf-rule-header">
                    <div class="wf-rule-icon" style="background:#fef3c7;">📩</div>
                    <div><div class="wf-rule-title">Offer &amp; Hiring Rules</div><div class="wf-rule-subtitle">Final pipeline stages</div></div>
                </div>
                <div class="wf-rule-body"><ul>
                    <li>After all interview rounds pass → set <strong>Offered (600)</strong> → send offer letter</li>
                    <li>Set <strong>Offer Release (1050)</strong> when offer is formally accepted</li>
                    <li><strong>Placed (800)</strong> = Candidate has joined the role</li>
                    <li><strong>Onboarded (1060)</strong> = Completed onboarding process</li>
                    <li><strong>Hired (1080)</strong> = Final hired state; document upload link can be sent</li>
                </ul></div>
            </div>

            <div class="wf-rule-card">
                <div class="wf-rule-header">
                    <div class="wf-rule-icon" style="background:#fee2e2;">❌</div>
                    <div><div class="wf-rule-title">Rejection Rules</div><div class="wf-rule-subtitle">Exit paths from pipeline</div></div>
                </div>
                <div class="wf-rule-body"><ul>
                    <li><strong>Screen Reject (1010)</strong> — failed initial screening</li>
                    <li><strong>Client Declined (700)</strong> — hiring manager rejected after submission</li>
                    <li><strong>Not in Consideration (650)</strong> — general removal</li>
                    <li><strong>No Show (1070)</strong> — missed scheduled interview</li>
                    <li>Rejected candidates <strong>remain in the database</strong> and can be reconsidered for future roles</li>
                    <li>Cooling period rules prevent same candidate from re-applying too quickly</li>
                </ul></div>
            </div>

            <div class="wf-rule-card">
                <div class="wf-rule-header">
                    <div class="wf-rule-icon" style="background:#e0e7ff;">📄</div>
                    <div><div class="wf-rule-title">Document Rules</div><div class="wf-rule-subtitle">Resume &amp; document handling</div></div>
                </div>
                <div class="wf-rule-body"><ul>
                    <li>Resumes uploaded at any stage are stored under the candidate profile</li>
                    <li>After <strong>Hired / Onboarded</strong> — HR can generate a <strong>Document Upload Link</strong></li>
                    <li>Upload link is valid for <strong>7 days</strong> with a max of <strong>20 files</strong></li>
                    <li>Accepted document types: ID Proof, Education, Experience Letter, Payslip, Offer Letter, Relieving Letter, etc.</li>
                    <li>Duplicate file detection via <strong>SHA-256 hash</strong></li>
                </ul></div>
            </div>

            <div class="wf-rule-card">
                <div class="wf-rule-header">
                    <div class="wf-rule-icon" style="background:#f0fdf4;">🔐</div>
                    <div><div class="wf-rule-title">Access Control Rules</div><div class="wf-rule-subtitle">Who can do what</div></div>
                </div>
                <div class="wf-rule-body"><ul>
                    <li><span class="wf-tag" style="background:#dbeafe;color:#1d4ed8;">Read Only (100)</span> — View candidates &amp; pipeline only</li>
                    <li><span class="wf-tag" style="background:#dcfce7;color:#15803d;">Add/Edit (200)</span> — Add candidates, change pipeline status</li>
                    <li><span class="wf-tag" style="background:#fef3c7;color:#d97706;">Add/Edit/Delete (300)</span> — Full candidate management</li>
                    <li><span class="wf-tag" style="background:#f3e8ff;color:#7e22ce;">Site Admin (400)</span> — Manage users &amp; settings</li>
                    <li><span class="wf-tag" style="background:#fee2e2;color:#dc2626;">Root Admin (500)</span> — Full system access</li>
                </ul></div>
            </div>

            <div class="wf-rule-card">
                <div class="wf-rule-header">
                    <div class="wf-rule-icon" style="background:#fff7ed;">🌐</div>
                    <div><div class="wf-rule-title">Career Portal Rules</div><div class="wf-rule-subtitle">Public job application flow</div></div>
                </div>
                <div class="wf-rule-body"><ul>
                    <li>Public portal shows all <strong>Active</strong> job orders marked as <strong>Public</strong></li>
                    <li>Applicants fill a 7-step form: personal info → experience → education → skills → resume → references → submit</li>
                    <li>On submission, candidate is created and tagged with <strong>source = "Career Portal"</strong></li>
                    <li>Duplicate check: same email = updates existing candidate record</li>
                    <li>Visible in the <strong>Portal Applications</strong> tab on candidate profile</li>
                </ul></div>
            </div>

        </div>
    </div>

    <!-- ── TAB 3: All Statuses Table ── -->
    <div class="panel" id="tab-statuses">
        <table class="wf-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Status Name</th>
                    <th>Type</th>
                    <th>Triggers Email</th>
                    <th>Schedulable</th>
                    <th>Active Candidates</th>
                    <th>Stage</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $allStatuses = array_merge($pipelineFlow, $rejectStatuses);
            usort($allStatuses, function($a,$b){ return $a['id'] - $b['id']; });
            $stageLabel = function($id) {
                if (in_array($id, [650, 700, 1010, 1070])) return array('Exit','#fee2e2','#dc2626');
                if ($id <= 300) return array('Sourcing','#dbeafe','#1d4ed8');
                if ($id <= 500) return array('Screening','#f3e8ff','#7e22ce');
                if ($id <= 1040) return array('Interviews','#dcfce7','#15803d');
                if ($id <= 1060) return array('Offer','#fef3c7','#d97706');
                if ($id >= 1070 && $id != 1080) return array('Exit','#fee2e2','#dc2626');
                return array('Hired','#d1fae5','#065f46');
            };
            foreach ($allStatuses as $stage):
                $s   = $statusMap[$stage['id']] ?? null;
                $cnt = $countMap[$stage['id']] ?? 0;
                $sl  = $stageLabel($stage['id']);
                $isCustom = isset($stage['custom']) && $stage['custom'];
            ?>
            <tr>
                <td><code style="font-size:12px;color:#6b7280;"><?php echo $stage['id']; ?></code></td>
                <td>
                    <span style="font-size:16px;margin-right:6px;"><?php echo $stage['icon']; ?></span>
                    <strong><?php echo htmlspecialchars($stage['name']); ?></strong>
                </td>
                <td><?php if ($isCustom): ?><span class="wf-flag flag-custom">Custom</span><?php else: ?><span class="wf-flag" style="background:#f3f4f6;color:#374151;">Default</span><?php endif; ?></td>
                <td><?php echo ($s && $s['triggersEmail']) ? '<span style="color:#2563eb;font-weight:600;">✓ Yes</span>' : '<span style="color:#9ca3af;">—</span>'; ?></td>
                <td><?php echo ($s && $s['canSchedule'])  ? '<span style="color:#16a34a;font-weight:600;">✓ Yes</span>' : '<span style="color:#9ca3af;">—</span>'; ?></td>
                <td>
                    <?php if ($cnt > 0): ?>
                    <span style="background:#4f46e5;color:#fff;padding:2px 10px;border-radius:12px;font-size:12px;font-weight:700;"><?php echo $cnt; ?></span>
                    <?php else: ?>
                    <span style="color:#9ca3af;">0</span>
                    <?php endif; ?>
                </td>
                <td><span style="background:<?php echo $sl[1]; ?>;color:<?php echo $sl[2]; ?>;padding:2px 10px;border-radius:8px;font-size:11px;font-weight:600;"><?php echo $sl[0]; ?></span></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div><!-- .wf-page -->

<script>
function switchTab(id, btn) {
    document.querySelectorAll('.panel').forEach(function(p){ p.classList.remove('active'); });
    document.querySelectorAll('.wf-tab').forEach(function(b){ b.classList.remove('active'); });
    document.getElementById('tab-'+id).classList.add('active');
    btn.classList.add('active');
}
</script>

</div><!-- #contents -->
</div><!-- #main -->

<?php TemplateUtility::printFooter(); ?>
