<?php /* OrgChart.tpl - Zoho-style Org Chart */ ?>
<?php TemplateUtility::printHeader('Org Chart', array()); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>

<div id="main">
<?php TemplateUtility::printQuickSearch(); ?>
<div id="contents">

<style>
/* ── Base ── */
*{box-sizing:border-box;}
.oc-page{font-family:'Inter',system-ui,sans-serif;color:#111827;padding:0 24px 40px;}

/* ── Page header ── */
.oc-page-header{display:flex;align-items:center;justify-content:space-between;padding:18px 0 14px;}
.oc-page-title{display:flex;align-items:center;gap:12px;}
.oc-page-title-icon{width:42px;height:42px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;}
.oc-page-title h2{margin:0;font-size:20px;font-weight:700;color:#1e3a8a;}
.oc-page-title p{margin:0;font-size:12px;color:#6b7280;}
.oc-header-actions{display:flex;gap:10px;align-items:center;}
.oc-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;transition:all .2s;}
.oc-btn-outline{background:#fff;border:1px solid #d1d5db;color:#374151;}
.oc-btn-outline:hover{background:#f9fafb;border-color:#9ca3af;}
.oc-btn-primary{background:#2563eb;color:#fff;}
.oc-btn-primary:hover{background:#1d4ed8;}
.oc-btn-danger{background:#dc2626;color:#fff;}
.oc-btn-danger:hover{background:#b91c1c;}
.oc-btn-warning{background:#d97706;color:#fff;}
.oc-btn-warning:hover{background:#b45309;}

/* ── Employee detail banner ── */
.oc-emp-banner{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px 24px;margin-bottom:16px;display:flex;align-items:center;gap:20px;box-shadow:0 1px 4px rgba(0,0,0,.05);}
.oc-emp-banner.hidden{display:none;}
.oc-emp-avatar{width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#fff;flex-shrink:0;}
.oc-emp-info{flex:1;}
.oc-emp-name{font-size:17px;font-weight:700;color:#111827;}
.oc-emp-role{font-size:13px;color:#6b7280;margin-top:1px;}
.oc-emp-meta{display:flex;gap:32px;margin-top:10px;}
.oc-emp-meta-item label{font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:.05em;display:block;}
.oc-emp-meta-item span{font-size:13px;color:#374151;font-weight:500;}
.oc-emp-banner-actions{display:flex;gap:8px;}
.oc-level-badge{display:inline-block;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:600;margin-left:8px;}

/* ── Toolbar ── */
.oc-toolbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;}
.oc-zoom-controls{display:flex;align-items:center;gap:8px;background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:4px 10px;}
.oc-zoom-btn{background:none;border:none;font-size:18px;cursor:pointer;color:#374151;width:28px;height:28px;display:flex;align-items:center;justify-content:center;border-radius:4px;}
.oc-zoom-btn:hover{background:#f3f4f6;}
.oc-zoom-pct{font-size:13px;font-weight:600;color:#374151;min-width:42px;text-align:center;}
.oc-fullscreen-btn{background:#fff;border:1px solid #e5e7eb;border-radius:8px;width:34px;height:34px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#374151;}
.oc-fullscreen-btn:hover{background:#f3f4f6;}

/* ── Chart canvas ── */
.oc-chart-wrap{background:#f8fafc;border:1px solid #e5e7eb;border-radius:12px;overflow:auto;position:relative;min-height:480px;padding:32px;}
.oc-chart-inner{display:inline-block;transform-origin:top center;transition:transform .25s;}

/* ── Tree layout ── */
.oc-tree{display:flex;flex-direction:column;align-items:center;}
.oc-tree-level{display:flex;gap:16px;justify-content:center;position:relative;}
.oc-tree-level::before{content:'';position:absolute;top:0;left:50%;border-left:2px solid #d1d5db;height:32px;transform:translateX(-50%);}
.oc-tree-level:first-child::before{display:none;}

/* connector row */
.oc-connector-row{display:flex;justify-content:center;position:relative;height:32px;}
.oc-connector-v{width:2px;background:#d1d5db;height:100%;margin:0 auto;}
.oc-children-wrap{display:flex;flex-direction:column;align-items:center;}
.oc-h-line{border-top:2px solid #d1d5db;width:100%;margin:0;}

/* ── Node card ── */
.oc-node{position:relative;display:inline-flex;flex-direction:column;align-items:center;cursor:pointer;}
.oc-card{background:#fff;border:2px solid #e5e7eb;border-radius:12px;padding:14px 16px;width:168px;text-align:center;transition:box-shadow .2s,border-color .2s;position:relative;user-select:none;}
.oc-card:hover{box-shadow:0 4px 16px rgba(59,130,246,.15);border-color:#93c5fd;}
.oc-card.selected{border-color:#2563eb;box-shadow:0 4px 20px rgba(37,99,235,.2);}
.oc-card.suspended{opacity:.55;background:#fafafa;}
.oc-card-avatar{width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;margin:0 auto 8px;}
.oc-card-name{font-size:13px;font-weight:600;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.oc-card-title{font-size:11px;color:#6b7280;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.oc-card-level{font-size:10px;font-weight:600;color:#9ca3af;margin-top:4px;}
.oc-card-badge{display:inline-block;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:600;margin-top:6px;}

/* context menu trigger */
.oc-card-menu-btn{position:absolute;top:6px;right:6px;background:none;border:none;cursor:pointer;color:#9ca3af;font-size:16px;line-height:1;padding:2px 4px;border-radius:4px;opacity:0;transition:opacity .15s;}
.oc-card:hover .oc-card-menu-btn{opacity:1;}
.oc-card-menu-btn:hover{background:#f3f4f6;color:#374151;}

/* ── Context menu ── */
.oc-ctx-menu{position:fixed;background:#fff;border:1px solid #e5e7eb;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:9999;min-width:170px;padding:6px 0;display:none;}
.oc-ctx-menu.open{display:block;}
.oc-ctx-item{display:flex;align-items:center;gap:10px;padding:9px 16px;font-size:13px;color:#374151;cursor:pointer;transition:background .15s;}
.oc-ctx-item:hover{background:#f3f4f6;}
.oc-ctx-item.danger{color:#dc2626;}
.oc-ctx-item.danger:hover{background:#fef2f2;}
.oc-ctx-item.warning{color:#d97706;}
.oc-ctx-item.warning:hover{background:#fffbeb;}
.oc-ctx-divider{border:none;border-top:1px solid #f3f4f6;margin:4px 0;}

/* ── Legend ── */
.oc-legend{display:flex;gap:20px;align-items:center;margin-top:16px;flex-wrap:wrap;}
.oc-legend-item{display:flex;align-items:center;gap:6px;font-size:12px;color:#6b7280;}
.oc-legend-dot{width:14px;height:14px;border-radius:3px;border:2px solid;}

/* ── Modal ── */
.oc-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:10000;display:none;align-items:center;justify-content:center;}
.oc-modal-overlay.open{display:flex;}
.oc-modal{background:#fff;border-radius:14px;width:520px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;}
.oc-modal-header{padding:20px 24px 16px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;}
.oc-modal-header h3{margin:0;font-size:16px;font-weight:700;color:#111827;}
.oc-modal-close{background:none;border:none;font-size:20px;cursor:pointer;color:#9ca3af;line-height:1;}
.oc-modal-close:hover{color:#374151;}
.oc-modal-body{padding:20px 24px;}
.oc-form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;}
.oc-form-row.full{grid-template-columns:1fr;}
.oc-form-group{display:flex;flex-direction:column;gap:5px;}
.oc-form-group label{font-size:12px;font-weight:600;color:#374151;}
.oc-form-group input,.oc-form-group select{padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;outline:none;transition:border-color .2s;}
.oc-form-group input:focus,.oc-form-group select:focus{border-color:#3b82f6;}
.oc-modal-footer{padding:16px 24px;border-top:1px solid #f3f4f6;display:flex;gap:10px;justify-content:flex-end;}

/* ── Confirm modal ── */
.oc-confirm-modal{background:#fff;border-radius:14px;width:380px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.2);padding:28px 24px;text-align:center;}
.oc-confirm-modal .icon{font-size:40px;margin-bottom:12px;}
.oc-confirm-modal h3{margin:0 0 8px;font-size:16px;font-weight:700;}
.oc-confirm-modal p{margin:0 0 20px;font-size:13px;color:#6b7280;}
.oc-confirm-modal .actions{display:flex;gap:10px;justify-content:center;}

/* level colors */
.lvl-1{background:#dbeafe;color:#1d4ed8;border-color:#93c5fd;}
.lvl-2{background:#dcfce7;color:#15803d;border-color:#86efac;}
.lvl-3{background:#faf5ff;color:#7e22ce;border-color:#d8b4fe;}
.lvl-4{background:#fff7ed;color:#c2410c;border-color:#fdba74;}
.lvl-5{background:#fef9c3;color:#a16207;border-color:#fde047;}

.avatar-1{background:linear-gradient(135deg,#3b82f6,#1d4ed8);}
.avatar-2{background:linear-gradient(135deg,#22c55e,#15803d);}
.avatar-3{background:linear-gradient(135deg,#a855f7,#7e22ce);}
.avatar-4{background:linear-gradient(135deg,#f97316,#c2410c);}
.avatar-5{background:linear-gradient(135deg,#eab308,#a16207);}
</style>

<?php
$users   = $this->users;
$managers = $this->managers;

// Build lookup
$userMap = array();
foreach ($users as $u) {
    $userMap[$u['userID']] = $u;
}

// Build tree: group children by parent
$children = array();
$roots    = array();
foreach ($users as $u) {
    $pid = $u['reportsTo'] ?? null;
    if (empty($pid) || !isset($userMap[$pid])) {
        $roots[] = $u['userID'];
    } else {
        $children[$pid][] = $u['userID'];
    }
}
if (empty($roots) && !empty($users)) {
    $roots = array($users[0]['userID']);
}

// Level colors
$levelColors = array(1=>'lvl-1',2=>'lvl-2',3=>'lvl-3',4=>'lvl-4',5=>'lvl-5');
$avatarColors = array(1=>'avatar-1',2=>'avatar-2',3=>'avatar-3',4=>'avatar-4',5=>'avatar-5');

// Assign depth levels
// A "Reports To" cycle (direct or through several people) has no server-side
// prevention beyond the single self-reference check in updateEmployee(), so
// this recursion must be able to survive one -- bail out on a node already
// assigned a depth instead of recursing forever.
$depthMap = array();
function assignDepth($uid, $depth, &$depthMap, &$children) {
    if (isset($depthMap[$uid])) { return; }
    $depthMap[$uid] = $depth;
    if (isset($children[$uid])) {
        foreach ($children[$uid] as $cid) {
            assignDepth($cid, $depth + 1, $depthMap, $children);
        }
    }
}
foreach ($roots as $rid) { assignDepth($rid, 1, $depthMap, $children); }

function renderNode($uid, &$userMap, &$children, &$depthMap, &$levelColors, &$avatarColors, &$rendered) {
    if (!isset($userMap[$uid])) return '';
    if (isset($rendered[$uid])) return ''; // already drawn elsewhere in the tree -- breaks cycles
    $rendered[$uid] = true;
    $u = $userMap[$uid];
    $depth = $depthMap[$uid] ?? 1;
    $lvlClass = $levelColors[min($depth, 5)] ?? 'lvl-5';
    $avClass  = $avatarColors[min($depth, 5)] ?? 'avatar-5';
    $initials = strtoupper(substr($u['firstName'],0,1) . substr($u['lastName'],0,1));
    $name     = htmlspecialchars(trim($u['firstName'].' '.$u['lastName']));
    $title    = htmlspecialchars($u['title'] ?? '');
    $suspended = ($u['orgStatus'] ?? 'active') === 'suspended';
    $suspClass = $suspended ? ' suspended' : '';

    $hasKids = isset($children[$uid]) && count($children[$uid]) > 0;

    $html = '<div class="oc-node" data-uid="'.$u['userID'].'">';
    $html .= '<div class="oc-card'.$suspClass.' oc-node-card" data-uid="'.$u['userID'].'"
        data-name="'.htmlspecialchars($name).'"
        data-title="'.htmlspecialchars($title).'"
        data-email="'.htmlspecialchars($u['email']??'').'"
        data-phone="'.htmlspecialchars($u['phone']??'').'"
        data-dept="'.htmlspecialchars($u['department']??'').'"
        data-empid="'.htmlspecialchars($u['employeeID']??'').'"
        data-level="'.$depth.'"
        data-status="'.htmlspecialchars($u['orgStatus']??'active').'"
        onclick="selectNode(this)">';
    $html .= '<button class="oc-card-menu-btn" onclick="openMenu(event,'.$u['userID'].')">&#8942;</button>';
    $html .= '<div class="oc-card-avatar '.$avClass.'">'.$initials.'</div>';
    $html .= '<div class="oc-card-name">'.$name.'</div>';
    $html .= '<div class="oc-card-title">'.($title ?: '&mdash;').'</div>';
    $html .= '<span class="oc-card-badge '.$lvlClass.'">Level '.$depth.'</span>';
    $html .= '</div>'; // .oc-card

    if ($hasKids) {
        $html .= '<div class="oc-connector-row"><div class="oc-connector-v"></div></div>';
        $kidNodes = '';
        foreach ($children[$uid] as $cid) {
            $kidNodes .= '<div class="oc-child-col">'.renderNode($cid, $userMap, $children, $depthMap, $levelColors, $avatarColors, $rendered).'</div>';
        }
        // Horizontal line across kids
        $html .= '<div class="oc-kids-group">';
        $html .= '<div class="oc-kids-hline-wrap"><div class="oc-kids-hline"></div></div>';
        $html .= '<div class="oc-kids-row">'.$kidNodes.'</div>';
        $html .= '</div>';
    }

    $html .= '</div>'; // .oc-node
    return $html;
}
?>

<div class="oc-page">

    <!-- Page header -->
    <div class="oc-page-header">
        <div class="oc-page-title">
            <div class="oc-page-title-icon">&#128101;</div>
            <div>
                <h2>Org Chart</h2>
                <p>Visualize reporting structure and team hierarchy</p>
            </div>
        </div>
        <div class="oc-header-actions">
            <button class="oc-btn oc-btn-outline" onclick="window.print()">&#128438; Export</button>
            <button class="oc-btn oc-btn-primary" onclick="openAddModal()">&#43; Add Employee</button>
        </div>
    </div>

    <!-- Selected employee banner -->
    <div class="oc-emp-banner hidden" id="oc-emp-banner">
        <div class="oc-emp-avatar" id="banner-avatar"></div>
        <div class="oc-emp-info">
            <div class="oc-emp-name" id="banner-name">—</div>
            <div class="oc-emp-role" id="banner-role">—</div>
            <div class="oc-emp-meta">
                <div class="oc-emp-meta-item"><label>Employee ID</label><span id="banner-empid">—</span></div>
                <div class="oc-emp-meta-item"><label>Email</label><span id="banner-email">—</span></div>
                <div class="oc-emp-meta-item"><label>Phone</label><span id="banner-phone">—</span></div>
                <div class="oc-emp-meta-item"><label>Department</label><span id="banner-dept">—</span></div>
            </div>
        </div>
        <div class="oc-emp-banner-actions">
            <button class="oc-btn oc-btn-outline" id="banner-edit-btn" onclick="editSelectedNode()">&#9998; Edit</button>
            <button class="oc-btn oc-btn-warning" id="banner-suspend-btn" onclick="suspendSelectedNode()">&#9208; Suspend</button>
            <button class="oc-btn oc-btn-danger" id="banner-delete-btn" onclick="deleteSelectedNode()">&#128465; Remove</button>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="oc-toolbar">
        <div class="oc-zoom-controls">
            <button class="oc-zoom-btn" onclick="zoomOut()">&#8722;</button>
            <span class="oc-zoom-pct" id="oc-zoom-label">100%</span>
            <button class="oc-zoom-btn" onclick="zoomIn()">&#43;</button>
        </div>
        <button class="oc-fullscreen-btn" onclick="toggleFullscreen()" title="Fullscreen">&#x26F6;</button>
    </div>

    <!-- Chart -->
    <div class="oc-chart-wrap" id="oc-chart-wrap">
        <div class="oc-chart-inner" id="oc-chart-inner">
<style>
/* Tree connectors */
.oc-kids-group{display:flex;flex-direction:column;align-items:center;}
.oc-kids-hline-wrap{position:relative;width:100%;height:0;}
.oc-kids-hline{border-top:2px solid #d1d5db;margin:0 84px;}
.oc-kids-row{display:flex;gap:0;justify-content:center;}
.oc-child-col{display:flex;flex-direction:column;align-items:center;padding:0 8px;}
.oc-child-col::before{content:'';width:2px;height:32px;background:#d1d5db;display:block;margin:0 auto;}
</style>
<?php $rendered = array(); ?>
<?php foreach ($roots as $rid): ?>
    <?php echo renderNode($rid, $userMap, $children, $depthMap, $levelColors, $avatarColors, $rendered); ?>
<?php endforeach; ?>
<?php if (empty($users)): ?>
    <div style="padding:60px;text-align:center;color:#9ca3af;font-size:14px;">No employees found. Add your first employee to build the org chart.</div>
<?php endif; ?>
        </div>
    </div>

    <!-- Legend -->
    <div class="oc-legend">
        <span style="font-size:12px;color:#6b7280;font-weight:600;">Legend:</span>
        <div class="oc-legend-item"><div class="oc-legend-dot lvl-1"></div> Level 1</div>
        <div class="oc-legend-item"><div class="oc-legend-dot lvl-2"></div> Level 2</div>
        <div class="oc-legend-item"><div class="oc-legend-dot lvl-3"></div> Level 3</div>
        <div class="oc-legend-item"><div class="oc-legend-dot lvl-4"></div> Level 4</div>
        <span style="font-size:12px;color:#9ca3af;margin-left:auto;">* Click on any employee to view details</span>
    </div>
</div>

<!-- Context menu -->
<div class="oc-ctx-menu" id="oc-ctx-menu">
    <div class="oc-ctx-item" onclick="editFromMenu()">&#9998;&nbsp; Edit Employee</div>
    <div class="oc-ctx-item warning" onclick="suspendFromMenu()">&#9208;&nbsp; <span id="ctx-suspend-label">Suspend</span></div>
    <hr class="oc-ctx-divider">
    <div class="oc-ctx-item danger" onclick="deleteFromMenu()">&#128465;&nbsp; Remove Employee</div>
</div>

<!-- Edit / Add Modal -->
<div class="oc-modal-overlay" id="oc-edit-modal">
    <div class="oc-modal">
        <div class="oc-modal-header">
            <h3 id="modal-title">Edit Employee</h3>
            <button class="oc-modal-close" onclick="closeModal('oc-edit-modal')">&times;</button>
        </div>
        <div class="oc-modal-body">
            <input type="hidden" id="edit-user-id" value="">
            <div class="oc-form-row">
                <div class="oc-form-group"><label>First Name</label><input type="text" id="edit-first-name"></div>
                <div class="oc-form-group"><label>Last Name</label><input type="text" id="edit-last-name"></div>
            </div>
            <div class="oc-form-row">
                <div class="oc-form-group"><label>Job Title</label><input type="text" id="edit-title" placeholder="e.g. Project Manager"></div>
                <div class="oc-form-group"><label>Department</label><input type="text" id="edit-department" placeholder="e.g. Engineering"></div>
            </div>
            <div class="oc-form-row">
                <div class="oc-form-group"><label>Email</label><input type="email" id="edit-email"></div>
                <div class="oc-form-group"><label>Phone</label><input type="text" id="edit-phone"></div>
            </div>
            <div class="oc-form-row">
                <div class="oc-form-group"><label>Employee ID</label><input type="text" id="edit-empid" placeholder="e.g. EMP001"></div>
                <div class="oc-form-group"><label>Reports To</label>
                    <select id="edit-reports-to">
                        <option value="">— None (Top level) —</option>
                        <?php foreach ($managers as $mid => $mname): ?>
                        <option value="<?php echo $mid; ?>"><?php echo htmlspecialchars($mname); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="oc-modal-footer">
            <button class="oc-btn oc-btn-outline" onclick="closeModal('oc-edit-modal')">Cancel</button>
            <button class="oc-btn oc-btn-primary" onclick="saveEmployee()">Save Changes</button>
        </div>
    </div>
</div>

<!-- Confirm Delete Modal -->
<div class="oc-modal-overlay" id="oc-confirm-modal">
    <div class="oc-confirm-modal">
        <div class="icon" id="confirm-icon">&#9888;</div>
        <h3 id="confirm-title">Remove Employee?</h3>
        <p id="confirm-msg">This action cannot be undone. Their direct reports will be reassigned.</p>
        <div class="actions">
            <button class="oc-btn oc-btn-outline" onclick="closeModal('oc-confirm-modal')">Cancel</button>
            <button class="oc-btn" id="confirm-action-btn" onclick="confirmAction()">Confirm</button>
        </div>
    </div>
</div>

<script>
var _zoom = 1;
var _selectedUID = null;
var _menuUID = null;
var _confirmCallback = null;
var _avatarColors = ['#3b82f6','#22c55e','#a855f7','#f97316','#eab308','#06b6d4','#ec4899'];

function zoomIn()  { _zoom = Math.min(_zoom + .1, 2);   applyZoom(); }
function zoomOut() { _zoom = Math.max(_zoom - .1, 0.3); applyZoom(); }
function applyZoom() {
    document.getElementById('oc-chart-inner').style.transform = 'scale('+_zoom+')';
    document.getElementById('oc-zoom-label').textContent = Math.round(_zoom*100)+'%';
}

function toggleFullscreen() {
    var el = document.getElementById('oc-chart-wrap');
    if (!document.fullscreenElement) el.requestFullscreen();
    else document.exitFullscreen();
}

// ── Select node ──
function selectNode(card) {
    document.querySelectorAll('.oc-card.selected').forEach(function(c){ c.classList.remove('selected'); });
    card.classList.add('selected');
    _selectedUID = card.dataset.uid;

    var name  = card.dataset.name;
    var title = card.dataset.title || '—';
    var email = card.dataset.email || '—';
    var phone = card.dataset.phone || '—';
    var dept  = card.dataset.dept  || '—';
    var empid = card.dataset.empid || '—';
    var status= card.dataset.status|| 'active';
    var level = card.dataset.level || '1';
    var initials = name.split(' ').map(function(p){return p[0]||'';}).join('').toUpperCase().substr(0,2);
    var color = _avatarColors[(parseInt(_selectedUID)||0) % _avatarColors.length];

    var banner = document.getElementById('oc-emp-banner');
    banner.classList.remove('hidden');
    document.getElementById('banner-avatar').textContent = initials;
    document.getElementById('banner-avatar').style.background = color;
    document.getElementById('banner-name').innerHTML = name + '<span class="oc-level-badge lvl-'+level+'">Level '+level+'</span>';
    document.getElementById('banner-role').textContent = title;
    document.getElementById('banner-empid').textContent = empid;
    document.getElementById('banner-email').textContent = email;
    document.getElementById('banner-phone').textContent = phone;
    document.getElementById('banner-dept').textContent = dept;

    var suspBtn = document.getElementById('banner-suspend-btn');
    suspBtn.textContent = status === 'suspended' ? '▶ Activate' : '⏸ Suspend';
}

// ── Context menu ──
function openMenu(e, uid) {
    e.stopPropagation();
    _menuUID = uid;
    var card = document.querySelector('.oc-card[data-uid="'+uid+'"]');
    var status = card ? card.dataset.status : 'active';
    document.getElementById('ctx-suspend-label').textContent = status === 'suspended' ? 'Activate' : 'Suspend';

    var menu = document.getElementById('oc-ctx-menu');
    menu.style.left = (e.clientX)+'px';
    menu.style.top  = (e.clientY)+'px';
    menu.classList.add('open');
}
document.addEventListener('click', function(){ document.getElementById('oc-ctx-menu').classList.remove('open'); });

function editFromMenu()    { if (_menuUID) { _selectedUID = _menuUID; editSelectedNode(); } }
function suspendFromMenu() { if (_menuUID) { _selectedUID = _menuUID; suspendSelectedNode(); } }
function deleteFromMenu()  { if (_menuUID) { _selectedUID = _menuUID; deleteSelectedNode(); } }

// ── Edit ──
function openAddModal() {
    document.getElementById('modal-title').textContent = 'Add Employee';
    document.getElementById('edit-user-id').value = '';
    ['edit-first-name','edit-last-name','edit-title','edit-department','edit-email','edit-phone','edit-empid'].forEach(function(id){
        document.getElementById(id).value = '';
    });
    document.getElementById('edit-reports-to').value = '';
    openModal('oc-edit-modal');
}

function editSelectedNode() {
    if (!_selectedUID) return;
    var card = document.querySelector('.oc-card[data-uid="'+_selectedUID+'"]');
    if (!card) return;

    document.getElementById('modal-title').textContent = 'Edit Employee';
    document.getElementById('edit-user-id').value = _selectedUID;
    var nameParts = (card.dataset.name||'').split(' ');
    document.getElementById('edit-first-name').value = nameParts[0] || '';
    document.getElementById('edit-last-name').value  = nameParts.slice(1).join(' ') || '';
    document.getElementById('edit-title').value      = card.dataset.title || '';
    document.getElementById('edit-department').value = card.dataset.dept  || '';
    document.getElementById('edit-email').value      = card.dataset.email || '';
    document.getElementById('edit-phone').value      = card.dataset.phone || '';
    document.getElementById('edit-empid').value      = card.dataset.empid || '';
    openModal('oc-edit-modal');
}

function saveEmployee() {
    var uid = document.getElementById('edit-user-id').value;
    var data = {
        userID:     uid,
        firstName:  document.getElementById('edit-first-name').value,
        lastName:   document.getElementById('edit-last-name').value,
        title:      document.getElementById('edit-title').value,
        department: document.getElementById('edit-department').value,
        email:      document.getElementById('edit-email').value,
        phone:      document.getElementById('edit-phone').value,
        employeeID: document.getElementById('edit-empid').value,
        reportsTo:  document.getElementById('edit-reports-to').value
    };

    var action = uid ? 'updateEmployee' : 'addEmployee';
    ajaxPost(action, data, function(r) {
        if (r.success) { closeModal('oc-edit-modal'); location.reload(); }
        else alert('Error saving. Please try again.');
    });
}

// ── Suspend ──
function suspendSelectedNode() {
    if (!_selectedUID) return;
    var card = document.querySelector('.oc-card[data-uid="'+_selectedUID+'"]');
    var curStatus = card ? card.dataset.status : 'active';
    var newStatus = curStatus === 'suspended' ? 'active' : 'suspended';
    var name = card ? card.dataset.name : 'this employee';

    _confirmCallback = function() {
        ajaxPost('suspendEmployee', {userID: _selectedUID, status: newStatus}, function(r) {
            if (r.success) { closeModal('oc-confirm-modal'); location.reload(); }
        });
    };
    document.getElementById('confirm-icon').textContent = newStatus === 'suspended' ? '⏸' : '▶';
    document.getElementById('confirm-title').textContent = newStatus === 'suspended' ? 'Suspend Employee?' : 'Activate Employee?';
    document.getElementById('confirm-msg').textContent = newStatus === 'suspended'
        ? name + ' will be marked as suspended in the org chart.'
        : name + ' will be reactivated in the org chart.';
    var btn = document.getElementById('confirm-action-btn');
    btn.textContent = newStatus === 'suspended' ? 'Suspend' : 'Activate';
    btn.className = 'oc-btn ' + (newStatus === 'suspended' ? 'oc-btn-warning' : 'oc-btn-primary');
    openModal('oc-confirm-modal');
}

// ── Delete ──
function deleteSelectedNode() {
    if (!_selectedUID) return;
    var card = document.querySelector('.oc-card[data-uid="'+_selectedUID+'"]');
    var name = card ? card.dataset.name : 'this employee';

    _confirmCallback = function() {
        ajaxPost('deleteEmployee', {userID: _selectedUID}, function(r) {
            if (r.success) { closeModal('oc-confirm-modal'); location.reload(); }
        });
    };
    document.getElementById('confirm-icon').textContent = '🗑️';
    document.getElementById('confirm-title').textContent = 'Remove Employee?';
    document.getElementById('confirm-msg').textContent = name + ' will be removed from the org chart. Their direct reports will be reassigned.';
    var btn = document.getElementById('confirm-action-btn');
    btn.textContent = 'Remove';
    btn.className = 'oc-btn oc-btn-danger';
    openModal('oc-confirm-modal');
}

function confirmAction() { if (_confirmCallback) _confirmCallback(); }

// ── Modal helpers ──
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.oc-modal-overlay').forEach(function(overlay){
    overlay.addEventListener('click', function(e){ if(e.target===overlay) overlay.classList.remove('open'); });
});

// ── AJAX ──
function ajaxPost(action, data, cb) {
    var form = new FormData();
    form.append('m', 'orgchart');
    form.append('a', action);
    Object.keys(data).forEach(function(k){ form.append(k, data[k]); });
    fetch(window.location.pathname + '?m=orgchart&a=' + action, {
        method: 'POST',
        body: new URLSearchParams(data)
    }).then(function(r){ return r.json(); }).then(cb).catch(function(){ alert('Request failed.'); });
}
</script>

</div><!-- #contents -->
</div><!-- #main -->

<?php TemplateUtility::printFooter(); ?>
