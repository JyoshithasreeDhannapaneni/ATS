<?php /* $Id: Candidates.tpl 3445 2007-11-06 23:17:04Z will $ */ ?>
<?php TemplateUtility::printHeader('Candidates', array( 'js/highlightrows.js', 'js/export.js', 'js/dataGrid.js', 'js/dataGridFilters.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
<?php $md5InstanceName = md5($this->dataGrid->getInstanceName());?>
    <style type="text/css">
    /* Hide the legacy DataGrid column chooser icon content — but keep the TH in layout so checkbox column aligns */
    #cellHideShow<?php echo $md5InstanceName; ?> {
        width: 28px !important;
        min-width: 28px !important;
        max-width: 28px !important;
        padding: 0 !important;
        border-right: none !important;
        background: #f9fafb !important;
        border-bottom: 1px solid #e5e7eb !important;
    }
    #cellHideShow<?php echo $md5InstanceName; ?> * { display: none !important; }

    /* ── DataGrid table: compact cells ──────────────────────────── */
    #table<?php echo $md5InstanceName; ?> {
        border-collapse: collapse;
        width: 100%;
        font-size: 13px;
    }
    /* Checkbox column td: tight width, centered */
    #table<?php echo $md5InstanceName; ?> tbody tr td:first-child {
        width: 28px !important;
        min-width: 28px !important;
        max-width: 28px !important;
        padding: 0 4px !important;
        text-align: center !important;
    }
    /* All header cells */
    #table<?php echo $md5InstanceName; ?> thead th {
        padding: 8px 10px !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        color: #6b7280 !important;
        background: #f9fafb !important;
        border-bottom: 1px solid #e5e7eb !important;
        white-space: nowrap;
    }
    /* All data cells */
    #table<?php echo $md5InstanceName; ?> tbody td {
        padding: 8px 10px !important;
        border-bottom: 1px solid #f3f4f6 !important;
        vertical-align: middle !important;
        color: #374151;
        max-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    #table<?php echo $md5InstanceName; ?> tbody tr:hover td {
        background: #f9fafb !important;
    }
    /* Key skills cell: allow wrapping */
    #table<?php echo $md5InstanceName; ?> tbody td:nth-child(5) {
        white-space: normal;
        max-width: 220px;
        font-size: 12px;
        color: #6b7280;
    }
    /* Checkbox input inside cells */
    #table<?php echo $md5InstanceName; ?> input[type="checkbox"] {
        margin: 0;
        cursor: pointer;
        accent-color: #2563eb;
        width: 14px;
        height: 14px;
    }

    .cand-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .cand-page-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .cand-page-title h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        letter-spacing: -0.01em;
    }
    .cand-page-title .page-icon {
        width: 36px; height: 36px;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
    }
    .cand-action-btns {
        display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    }
    .cand-btn-primary,
    .cand-btn-primary:link,
    .cand-btn-primary:visited {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff !important; border: none; border-radius: 8px;
        font-size: 13px !important; font-weight: 600; cursor: pointer;
        text-decoration: none !important; transition: all 0.2s;
        box-shadow: 0 1px 3px rgba(37,99,235,0.3);
    }
    .cand-btn-primary:hover { background: linear-gradient(135deg, #1d4ed8, #1e40af); transform: translateY(-1px); color: #fff !important; }
    .cand-btn-secondary,
    .cand-btn-secondary:link,
    .cand-btn-secondary:visited {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px;
        background: #fff; color: #374151 !important;
        border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 13px !important; font-weight: 500; cursor: pointer;
        text-decoration: none !important; transition: all 0.2s;
    }
    .cand-btn-secondary:hover { background: #f9fafb; border-color: #9ca3af; color: #111827 !important; }

    .cand-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px;
        padding: 10px 14px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        margin-bottom: 12px;
    }
    .cand-toolbar-left { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
    .cand-toolbar-right { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

    .cand-filter-toggle {
        display: flex; align-items: center; gap: 6px;
        font-size: 13px; color: #4b5563; cursor: pointer;
        padding: 5px 10px; border-radius: 6px;
        border: 1px solid transparent;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .cand-filter-toggle input[type="checkbox"] { cursor: pointer; accent-color: #2563eb; }
    .cand-filter-toggle:hover { background: #f3f4f6; border-color: #e5e7eb; }

    .cand-count-badge {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 13px; color: #6b7280; font-weight: 500;
        white-space: nowrap;
    }
    .cand-count-badge strong { color: #111827; }

    /* ── Source tabs ─────────────────────────────────────────────── */
    .cand-source-tabs {
        display: flex; align-items: stretch; gap: 0;
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 10px; margin-bottom: 12px;
        overflow: hidden;
    }
    .cand-source-tab {
        display: flex; align-items: center; gap: 8px;
        padding: 12px 22px; font-size: 13px; font-weight: 600;
        color: #6b7280; cursor: pointer; border: none; background: none;
        border-right: 1px solid #f3f4f6; transition: all .15s;
        white-space: nowrap; position: relative;
    }
    .cand-source-tab:last-child { border-right: none; }
    .cand-source-tab:hover { background: #f9fafb; color: #374151; }
    .cand-source-tab.active {
        color: #2563eb; background: #eff6ff;
    }
    .cand-source-tab.active::after {
        content: ''; position: absolute; bottom: 0; left: 0; right: 0;
        height: 3px; background: #2563eb; border-radius: 3px 3px 0 0;
    }
    .cand-source-tab.active-upload { color: #7c3aed; background: #f5f3ff; }
    .cand-source-tab.active-upload::after { background: #7c3aed; }
    .cand-tab-badge {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 20px; height: 20px; border-radius: 10px;
        font-size: 11px; font-weight: 700; padding: 0 5px;
    }
    .cand-tab-badge-blue  { background: #dbeafe; color: #1d4ed8; }
    .cand-tab-badge-purple{ background: #ede9fe; color: #6d28d9; }
    .cand-tab-badge-gray  { background: #f3f4f6; color: #4b5563; }

    /* Override DataGrid .button inside toolbar so it looks subtle, not blue */
    .cand-toolbar .button,
    .cand-toolbar a.button,
    .cand-toolbar a.button:link,
    .cand-toolbar a.button:visited {
        background: #f3f4f6 !important;
        color: #374151 !important;
        border: 1px solid #d1d5db !important;
        border-radius: 6px !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        padding: 6px 12px !important;
        box-shadow: none !important;
    }
    .cand-toolbar .button:hover,
    .cand-toolbar a.button:hover {
        background: #e5e7eb !important;
        border-color: #9ca3af !important;
        color: #111827 !important;
    }

    /* Empty state */
    .cand-empty-state {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 80px 40px; text-align: center;
        background: #fff; border-radius: 16px;
        border: 2px dashed #e5e7eb;
        margin-top: 16px;
    }
    .cand-empty-icon {
        width: 72px; height: 72px;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 20px;
    }
    .cand-empty-state h3 {
        font-size: 18px; font-weight: 700; color: #111827;
        margin: 0 0 8px;
    }
    .cand-empty-state p {
        font-size: 14px; color: #6b7280; margin: 0 0 28px;
        max-width: 380px; line-height: 1.6;
    }
    .cand-empty-actions { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; }
    </style>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <?php if ($this->totalCandidates): ?>

            <!-- Page Header -->
            <div class="cand-page-header">
                <div class="cand-page-title">
                    <div class="page-icon">
                        <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    </div>
                    <div>
                        <h2>Candidates</h2>
                        <div style="font-size:12px;color:#6b7280;margin-top:1px;"><?php echo($this->dataGrid->getNumberOfRows()); ?> total &middot; Page <?php echo($this->dataGrid->getCurrentPageHTML()); ?></div>
                    </div>
                </div>
                <div class="cand-action-btns">
                    <?php if ($this->getUserAccessLevel('candidates.add') >= ACCESS_LEVEL_EDIT): ?>
                    <a href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=add" class="cand-btn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Candidate
                    </a>
                    <a href="<?php echo CATSUtility::getIndexName(); ?>?m=import&amp;a=bulkImport" class="cand-btn-secondary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        Bulk Import
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=workflow" class="cand-btn-secondary" style="background:#f5f3ff;border-color:#c4b5fd;color:#7c3aed;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/></svg>
                        Workflow
                    </a>
                </div>
            </div>

            <?php if ($this->topLog != ''): ?>
            <div style="margin-bottom: 14px;"><?php echo $this->topLog; ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['bulkDeleteMessage']) && !empty($_SESSION['bulkDeleteMessage'])): ?>
            <div style="padding: 12px 16px; border-left: 4px solid #16a34a; background: #f0fdf4; margin-bottom: 14px; border-radius: 0 8px 8px 0; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span style="font-size:13px;font-weight:500;color:#166534;"><?php echo htmlspecialchars($_SESSION['bulkDeleteMessage']); ?></span>
                </div>
                <button onclick="this.parentElement.style.display='none';" style="background:none;border:none;cursor:pointer;color:#16a34a;padding:2px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <?php unset($_SESSION['bulkDeleteMessage']); ?>
            <?php endif; ?>

            <?php if ($this->errMessage != ''): ?>
            <div style="padding: 12px 16px; border-left: 4px solid #dc2626; background: #fef2f2; margin-bottom: 14px; border-radius: 0 8px 8px 0; display: flex; align-items: flex-start; gap: 10px;">
                <svg width="18" height="18" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>
                    <div style="font-size:13px;font-weight:600;color:#dc2626;margin-bottom:2px;">There was a problem with your request:</div>
                    <div style="font-size:13px;color:#991b1b;"><?php echo $this->errMessage; ?></div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Source Tabs -->
            <?php
                $activePortalFilter  = $this->dataGrid->getFilterValue('PortalApplicant');
                $activePortalOperator = $this->dataGrid->getFilterOperator('PortalApplicant');
                $isPortalTab  = ($activePortalOperator === '==' && strpos($activePortalFilter, 'Career Portal') !== false);
                $isDirectTab  = ($activePortalOperator === '!=' && strpos($activePortalFilter, 'Career Portal') !== false);
                $isAllTab     = !$isPortalTab && !$isDirectTab;
                $totalCount   = (int)$this->totalCandidates;
                $portalCount  = (int)($this->portalCandidatesCount ?? 0);
                $directCount  = (int)($this->directCandidatesCount ?? 0);
            ?>
            <div class="cand-source-tabs">
                <!-- All Candidates -->
                <button type="button" class="cand-source-tab <?php echo $isAllTab ? 'active' : ''; ?>"
                        id="tabAll"
                        onclick="setCandSourceTab('all')">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    All Candidates
                    <span class="cand-tab-badge cand-tab-badge-gray"><?php echo $totalCount; ?></span>
                </button>

                <!-- Portal Applications -->
                <button type="button" class="cand-source-tab <?php echo $isPortalTab ? 'active' : ''; ?>"
                        id="tabPortal"
                        onclick="setCandSourceTab('portal')">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>
                    Portal Applications
                    <?php if ($portalCount > 0): ?>
                    <span class="cand-tab-badge cand-tab-badge-blue"><?php echo $portalCount; ?></span>
                    <?php endif; ?>
                </button>

                <!-- Direct Uploads -->
                <button type="button" class="cand-source-tab <?php echo $isDirectTab ? 'active-upload' : ''; ?>"
                        id="tabDirect"
                        onclick="setCandSourceTab('direct')">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Resume Uploads
                    <?php if ($directCount > 0): ?>
                    <span class="cand-tab-badge cand-tab-badge-purple"><?php echo $directCount; ?></span>
                    <?php endif; ?>
                </button>
            </div>
            <script>
            function setCandSourceTab(tab) {
                // Use the DataGrid JS API already on the page
                if (tab === 'portal') {
                    // Apply portal filter: PortalApplicant == 'Career Portal'
                    <?php echo $this->dataGrid->getJSAddFilter('PortalApplicant', '==', "'Career Portal'"); ?>;
                } else if (tab === 'direct') {
                    // Apply direct filter: PortalApplicant != 'Career Portal' (reuse field, sentinel value)
                    <?php echo $this->dataGrid->getJSAddFilter('PortalApplicant', '!=', "'Career Portal'"); ?>;
                } else {
                    // Remove portal filter entirely
                    <?php echo $this->dataGrid->getJSRemoveFilter('PortalApplicant'); ?>;
                }
            }
            </script>

            <!-- Toolbar -->
            <div class="cand-toolbar">
                <div class="cand-toolbar-left">
                    <form name="candidatesViewSelectorForm" id="candidatesViewSelectorForm" action="<?php echo(CATSUtility::getIndexName()); ?>" method="get" style="display:contents;">
                        <input type="hidden" name="m" value="candidates" />
                        <input type="hidden" name="a" value="listByView" />
                        <label class="cand-filter-toggle">
                            <input type="checkbox" name="onlyMyCandidates" id="onlyMyCandidates" <?php if ($this->dataGrid->getFilterValue('OwnerID') == $this->userID): ?>checked<?php endif; ?> onclick="<?php echo $this->dataGrid->getJSAddRemoveFilterFromCheckbox('OwnerID', '==', $this->userID); ?>" />
                            My Candidates
                        </label>
                        <label class="cand-filter-toggle">
                            <input type="checkbox" name="onlyHotCandidates" id="onlyHotCandidates" <?php if ($this->dataGrid->getFilterValue('IsHot') == '1'): ?>checked<?php endif; ?> onclick="<?php echo $this->dataGrid->getJSAddRemoveFilterFromCheckbox('IsHot', '==', '\'1\''); ?>" />
                            <svg width="13" height="13" fill="#f59e0b" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            Hot Only
                        </label>
                        <!-- Career Portal checkbox hidden — replaced by source tabs above -->
                        <input type="hidden" name="onlyPortalCandidates" value="" />
                    </form>

                    <!-- Tag filter -->
                    <div style="position:relative;">
                        <a href="javascript:void(0);" onclick="toggleHideShowControls('<?= $md5InstanceName ?>-tags'); return false;"
                            style="display:inline-flex;align-items:center;gap:5px;font-size:13px;color:#4b5563;padding:5px 10px;border-radius:6px;border:1px solid transparent;transition:all 0.15s;text-decoration:none;"
                            onmouseover="this.style.background='#f3f4f6';this.style.borderColor='#e5e7eb';" onmouseout="this.style.background='';this.style.borderColor='transparent';">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                            Filter by Tag
                        </a>
                        <div id="tagsContainer" style="position:relative;">
                            <div class="ajaxSearchResults" id="ColumnBox<?= $md5InstanceName ?>-tags" align="left" style="position:absolute;width:220px;left:0;top:calc(100% + 4px);z-index:9999;<?= isset($this->globalStyle)?$this->globalStyle:"" ?>">
                                <table width="100%"><tr>
                                    <td style="font-weight:bold;color:#000;">Tag list</td>
                                    <td align="right">
                                        <input type="button" onclick="applyTagFilter()" value="Save &amp; Close" />
                                        <input type="button" onclick="document.getElementById('ColumnBox<?= $md5InstanceName?>-tags').style.display='none';" value="Close" />
                                    </td>
                                </tr></table>
                                <ul>
                                <script type="text/javascript">
                                function applyTagFilter(){
                                    var arrValues=[];
                                    var tags=document.getElementsByName('candidate_tags[]');
                                    for(var el in tags){ if(tags[el].checked) arrValues.push(tags[el].value); }
                                    <?php echo $this->dataGrid->getJSAddFilter('Tags', '=#', "arrValues.join('/')"); ?>;
                                }
                                </script>
                                <?php $i=1;
                                function drw($data, $id){
                                    global $i;
                                    foreach($data as $k => $v){
                                        if($v['tag_parent_id'] == $id){
                                            ?><li><input type="checkbox" name="candidate_tags[]" id="checkbox<?= $i ?>" value="<?= $v['tag_id'] ?>"><label for="checkbox<?= $i++ ?>"><?= $v['tag_title'] ?></label></li><?php
                                            echo "\n<ul>";
                                            drw($data, $v['tag_id']);
                                            echo "\n</ul>";
                                        }
                                    }
                                }
                                drw($this->tagsRS, '');
                                ?></ul>
                            </div>
                        </div>
                        <span style="display:none;" id="ajaxTableIndicator<?= $md5InstanceName ?>"><img src="images/indicator_small.gif" alt="" /></span>
                    </div>
                </div>

                <div class="cand-toolbar-right">
                    <?php $this->dataGrid->drawRowsPerPageSelector(); ?>
                    <?php $this->dataGrid->drawShowFilterControl(); ?>

                    <?php
                        $dgInstanceName = $this->dataGrid->getInstanceName();
                        $dgParams       = $this->dataGrid->getParameters();
                        $dgOptCols      = $this->dataGrid->getOptionalColumns();
                        $dgCurrentCols  = $this->dataGrid->getCurrentColumnNames();
                        $dgParamKey     = urlencode('parameters' . $dgInstanceName);
                    ?>
                    <div style="position:relative;display:inline-block;">
                        <button type="button" id="colChooserBtn"
                            onclick="var d=document.getElementById('colChooserDrop');d.style.display=d.style.display==='none'?'block':'none';event.stopPropagation();"
                            style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:#f3f4f6;border:1px solid #d1d5db;border-radius:6px;font-size:12px;color:#374151;cursor:pointer;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                            Columns
                        </button>
                        <div id="colChooserDrop" style="display:none;position:absolute;top:calc(100% + 4px);right:0;background:#fff;border:1px solid #e5e7eb;border-radius:8px;box-shadow:0 4px 16px rgba(0,0,0,.12);z-index:99999;min-width:200px;max-height:360px;overflow-y:auto;padding:8px 0;">
                            <div style="padding:8px 14px 6px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #f3f4f6;margin-bottom:4px;">Show Columns</div>
                            <?php foreach ($dgOptCols as $colName => $colDef): ?>
                                <?php
                                    $isActive = in_array($colName, $dgCurrentCols);
                                    $newP = $dgParams;
                                    if ($isActive) { $newP['removeColumn'] = $colName; unset($newP['addColumn']); }
                                    else { $newP['addColumn'] = $colName; unset($newP['removeColumn']); }
                                    $colUrl = CATSUtility::getIndexName() . '?m=candidates&a=listByView&' . $dgParamKey . '=' . urlencode(json_encode($newP));
                                ?>
                                <a href="<?php echo htmlspecialchars($colUrl); ?>"
                                    style="display:flex;align-items:center;gap:8px;padding:7px 14px;font-size:13px;color:#1f2937;text-decoration:none;white-space:nowrap;"
                                    onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='';">
                                    <span style="width:16px;height:16px;border:2px solid <?php echo $isActive ? '#2563eb' : '#d1d5db'; ?>;border-radius:3px;background:<?php echo $isActive ? '#2563eb' : '#fff'; ?>;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <?php if ($isActive): ?><svg width="10" height="10" viewBox="0 0 12 12" fill="none" stroke="#fff" stroke-width="2.5"><polyline points="2,6 5,9 10,3"/></svg><?php endif; ?>
                                    </span>
                                    <?php echo htmlspecialchars($colName); ?>
                                </a>
                            <?php endforeach; ?>
                            <div style="border-top:1px solid #f3f4f6;margin-top:4px;padding-top:4px;">
                                <?php
                                    $resetP = $dgParams; $resetP['resetColumns'] = true;
                                    unset($resetP['addColumn']); unset($resetP['removeColumn']);
                                    $resetUrl = CATSUtility::getIndexName() . '?m=candidates&a=listByView&' . $dgParamKey . '=' . urlencode(json_encode($resetP));
                                ?>
                                <a href="<?php echo htmlspecialchars($resetUrl); ?>"
                                    style="display:flex;align-items:center;gap:8px;padding:7px 14px;font-size:13px;color:#6b7280;text-decoration:none;"
                                    onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='';">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 019-9 9.75 9.75 0 016.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                                    Reset to Default
                                </a>
                            </div>
                        </div>
                    </div>
                    <script>
                    document.addEventListener('click', function(e) {
                        var drop = document.getElementById('colChooserDrop');
                        var btn  = document.getElementById('colChooserBtn');
                        if (drop && btn && !btn.contains(e.target) && !drop.contains(e.target)) drop.style.display = 'none';
                    });
                    </script>

                    <!-- View Toggle: Table / Cards -->
                    <div style="display:inline-flex;border:1px solid #d1d5db;border-radius:6px;overflow:hidden;background:#f3f4f6;">
                        <button type="button" id="viewToggleTable" onclick="setCandView('table')"
                            title="Table view"
                            style="padding:5px 10px;border:none;background:#2563eb;color:#fff;cursor:pointer;display:inline-flex;align-items:center;gap:4px;font-size:12px;transition:all .15s;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
                        </button>
                        <button type="button" id="viewToggleCards" onclick="setCandView('cards')"
                            title="Card view"
                            style="padding:5px 10px;border:none;background:transparent;color:#6b7280;cursor:pointer;display:inline-flex;align-items:center;gap:4px;font-size:12px;transition:all .15s;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        </button>
                    </div>

                    <?php $this->dataGrid->printNavigation(false); ?>
                </div>
            </div>

            <?php $this->dataGrid->drawFilterArea(); ?>
            <div id="candTableView">
            <?php $this->dataGrid->draw(); ?>
            </div>
            <div id="candCardView" style="display:none;"></div>

            <style>
            .cand-card-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 16px;
                padding: 4px 0 16px;
            }
            .cand-card {
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 14px;
                padding: 20px;
                transition: box-shadow 0.2s, transform 0.2s;
                cursor: pointer;
                text-decoration: none;
                display: flex;
                flex-direction: column;
                gap: 12px;
                position: relative;
                overflow: hidden;
            }
            .cand-card:hover {
                box-shadow: 0 8px 24px rgba(37,99,235,0.10);
                transform: translateY(-2px);
                border-color: #bfdbfe;
            }
            .cand-card-top { display: flex; align-items: center; gap: 14px; }
            .cand-card-avatar {
                width: 48px; height: 48px; border-radius: 50%;
                background: linear-gradient(135deg, #2563eb, #7c3aed);
                color: #fff; font-size: 17px; font-weight: 700;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0; letter-spacing: -.5px;
            }
            .cand-card-name {
                font-size: 15px; font-weight: 700; color: #111827;
                line-height: 1.2; margin: 0;
                white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            }
            .cand-card-sub { font-size: 12px; color: #6b7280; margin-top: 2px; }
            .cand-card-badge-portal {
                display: inline-flex; align-items: center; gap: 3px;
                padding: 2px 7px; background: #dbeafe; color: #1d4ed8;
                border-radius: 4px; font-size: 10px; font-weight: 700;
                letter-spacing: .03em; margin-top: 4px;
            }
            .cand-card-badge-hot {
                position: absolute; top: 12px; right: 12px;
                color: #f59e0b; font-size: 13px;
            }
            .cand-card-info { display: flex; flex-direction: column; gap: 5px; }
            .cand-card-info-row {
                display: flex; align-items: center; gap: 7px;
                font-size: 12px; color: #4b5563;
                white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            }
            .cand-card-info-row svg { flex-shrink: 0; color: #9ca3af; }
            .cand-card-skills {
                font-size: 11px; color: #6b7280;
                border-top: 1px solid #f3f4f6;
                padding-top: 10px;
                line-height: 1.5;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .cand-card-footer {
                display: flex; align-items: center; justify-content: space-between;
                border-top: 1px solid #f3f4f6; padding-top: 10px; margin-top: auto;
            }
            .cand-card-date { font-size: 11px; color: #9ca3af; }
            .cand-card-action {
                display: inline-flex; align-items: center; gap: 4px;
                font-size: 11px; font-weight: 600; color: #2563eb;
                text-decoration: none;
            }
            .cand-card-action:hover { color: #1d4ed8; text-decoration: underline; }
            </style>

            <script>
            (function(){
                var STORE_KEY = 'cats_cand_view';
                function setCandView(mode) {
                    localStorage.setItem(STORE_KEY, mode);
                    var tv = document.getElementById('candTableView');
                    var cv = document.getElementById('candCardView');
                    var tb = document.getElementById('viewToggleTable');
                    var cb = document.getElementById('viewToggleCards');
                    if (mode === 'cards') {
                        tv.style.display = 'none';
                        cv.style.display = '';
                        tb.style.background = 'transparent'; tb.style.color = '#6b7280';
                        cb.style.background = '#2563eb'; cb.style.color = '#fff';
                        buildCards();
                    } else {
                        tv.style.display = '';
                        cv.style.display = 'none';
                        tb.style.background = '#2563eb'; tb.style.color = '#fff';
                        cb.style.background = 'transparent'; cb.style.color = '#6b7280';
                    }
                }
                window.setCandView = setCandView;

                function getAvatarColor(name) {
                    var colors = [
                        ['#2563eb','#7c3aed'],['#0891b2','#0e7490'],['#059669','#047857'],
                        ['#7c3aed','#a21caf'],['#dc2626','#b91c1c'],['#d97706','#b45309']
                    ];
                    var i = 0;
                    for (var j=0;j<name.length;j++) i += name.charCodeAt(j);
                    return colors[i % colors.length];
                }

                function buildCards() {
                    var cv = document.getElementById('candCardView');
                    // Find the data table rendered by DataGrid
                    var tbl = document.querySelector('#candTableView table tbody');
                    if (!tbl) { cv.innerHTML = '<p style="color:#6b7280;padding:20px;">No data to display.</p>'; return; }
                    var rows = tbl.querySelectorAll('tr');
                    if (!rows.length) { cv.innerHTML = '<p style="color:#6b7280;padding:20px;">No candidates found.</p>'; return; }

                    var grid = document.createElement('div');
                    grid.className = 'cand-card-grid';

                    rows.forEach(function(row) {
                        var cells = row.querySelectorAll('td');
                        if (cells.length < 3) return;

                        // Try to find the candidate link in cells (First/Last name)
                        var link = row.querySelector('a[href*="candidateID"]');
                        if (!link) return;
                        var href = link.href;

                        // Gather cell text values by scanning all tds
                        var cellTexts = [];
                        cells.forEach(function(c){ cellTexts.push(c.innerText.trim()); });

                        // First name + last name usually in cols 1,2 (col 0 = checkbox/icons)
                        var firstName = '', lastName = '', email = '', phone = '', city = '', skills = '', dateStr = '', isPortal = false, isHot = false;

                        // Check for portal badge in any cell
                        isPortal = !!row.querySelector('.cand-portal-badge, [data-portal]') ||
                                   row.innerHTML.indexOf('Career Portal') > -1 ||
                                   row.innerHTML.indexOf('>Portal<') > -1;

                        // Check for hot star
                        isHot = !!row.querySelector('.jobLinkHot') || row.querySelector('img[title*="Hot"]');

                        // Find links for name
                        var nameLinks = row.querySelectorAll('a[href*="candidateID"]');
                        if (nameLinks.length >= 2) {
                            firstName = nameLinks[0].textContent.trim();
                            lastName  = nameLinks[1].textContent.trim();
                        } else if (nameLinks.length === 1) {
                            var parts = nameLinks[0].textContent.trim().split(' ');
                            firstName = parts[0] || '';
                            lastName  = parts.slice(1).join(' ') || '';
                        }

                        // Scan cells for email/phone patterns
                        cellTexts.forEach(function(t){
                            if (!email && t.indexOf('@') > -1 && t.indexOf('.') > -1 && t.length < 80) email = t;
                            else if (!phone && /^[\d\s\-\+\(\)\.]{7,20}$/.test(t)) phone = t;
                            else if (!city && /^[A-Z][a-z]/.test(t) && t.length < 40 && t.indexOf('@') < 0) {
                                if (!firstName || t !== firstName) if (!lastName || t !== lastName) city = t;
                            }
                        });

                        // Skills: look for a cell with comma-separated words or longer text
                        cellTexts.forEach(function(t){
                            if (!skills && t.length > 15 && t.indexOf(',') > -1) skills = t;
                        });

                        // Date: look for MM-DD-YY pattern
                        cellTexts.forEach(function(t){
                            if (!dateStr && /\d{2}-\d{2}-\d{2}/.test(t)) dateStr = t.replace(/\s+/g,' ').trim();
                        });

                        var fullName = (firstName + ' ' + lastName).trim() || 'Unknown';
                        var initials = ((firstName[0]||'') + (lastName[0]||'')).toUpperCase() || '?';
                        var colors = getAvatarColor(fullName);

                        var card = document.createElement('a');
                        card.href = href;
                        card.className = 'cand-card';

                        var hotBadge = isHot ? '<span class="cand-card-badge-hot" title="Hot Candidate">★</span>' : '';
                        var portalBadge = isPortal ? '<span class="cand-card-badge-portal"><svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg> Portal</span>' : '';
                        var emailRow = email ? '<div class="cand-card-info-row"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,12 2,6"/></svg><span style="overflow:hidden;text-overflow:ellipsis;">'+email+'</span></div>' : '';
                        var phoneRow = phone ? '<div class="cand-card-info-row"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.06 1.18 2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92v2z"/></svg>'+phone+'</div>' : '';
                        var cityRow = city ? '<div class="cand-card-info-row"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>'+city+'</div>' : '';
                        var skillsSection = skills ? '<div class="cand-card-skills">'+skills+'</div>' : '';
                        var dateSection = dateStr ? '<span class="cand-card-date">Modified: '+dateStr+'</span>' : '<span></span>';

                        card.innerHTML =
                            hotBadge +
                            '<div class="cand-card-top">' +
                                '<div class="cand-card-avatar" style="background:linear-gradient(135deg,'+colors[0]+','+colors[1]+')">'+initials+'</div>' +
                                '<div style="min-width:0;">' +
                                    '<div class="cand-card-name">'+fullName+'</div>' +
                                    portalBadge +
                                '</div>' +
                            '</div>' +
                            '<div class="cand-card-info">' + emailRow + phoneRow + cityRow + '</div>' +
                            skillsSection +
                            '<div class="cand-card-footer">' +
                                dateSection +
                                '<span class="cand-card-action">View Profile <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></span>' +
                            '</div>';

                        grid.appendChild(card);
                    });

                    cv.innerHTML = '';
                    if (grid.children.length === 0) {
                        cv.innerHTML = '<p style="color:#6b7280;padding:20px;">No candidates to display as cards.</p>';
                    } else {
                        cv.appendChild(grid);
                    }
                }

                // Restore saved view on load
                window.addEventListener('DOMContentLoaded', function(){
                    var saved = localStorage.getItem(STORE_KEY) || 'table';
                    setCandView(saved);
                });
            })();
            </script>

            <!-- Bottom action bar -->
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-top:12px;padding:10px 14px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <!-- Select-all checkbox + Action dropdown from DataGrid -->
                    <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:#374151;">
                        <?php $this->dataGrid->printActionArea(); ?>
                    </div>
                    <?php if ($this->getUserAccessLevel('candidates.delete') >= ACCESS_LEVEL_DELETE): ?>
                    <button type="button" id="bulkDeleteBtn" onclick="confirmBulkDelete<?php echo $md5InstanceName; ?>();"
                        style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-radius:6px;font-size:12px;font-weight:500;cursor:pointer;transition:all 0.15s;line-height:1.4;"
                        onmouseover="this.style.background='#fee2e2';" onmouseout="this.style.background='#fef2f2';">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        Delete Selected
                    </button>
                    <?php endif; ?>
                </div>
                <!-- A–Z alphabetical navigation from DataGrid -->
                <div style="font-size:13px;color:#6b7280;letter-spacing:0.03em;">
                    <?php $this->dataGrid->printNavigation(true); ?>
                </div>
            </div>

            <?php else: ?>

            <!-- Empty state -->
            <div class="cand-empty-state">
                <div class="cand-empty-icon">
                    <svg width="36" height="36" fill="none" stroke="#2563eb" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <h3>No Candidates Yet</h3>
                <p>Start building your talent pool by adding candidates manually or importing them in bulk from a spreadsheet.</p>
                <?php if ($this->getUserAccessLevel('candidates.add') >= ACCESS_LEVEL_EDIT): ?>
                <div class="cand-empty-actions">
                    <a href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=add" class="cand-btn-primary" style="padding:10px 22px;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Candidate
                    </a>
                    <a href="<?php echo CATSUtility::getIndexName(); ?>?m=import&amp;a=bulkImport" class="cand-btn-secondary" style="padding:10px 22px;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        Bulk Import
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <?php endif; ?>
        </div>
    </div>

<!-- Bulk Delete Confirmation Modal -->
<div id="bulkDeleteModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; max-width: 420px; width: 90%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
        <div style="text-align: center; margin-bottom: 16px;">
            <div style="width: 56px; height: 56px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
                    <path d="M3 6h18"/>
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                    <line x1="10" x2="10" y1="11" y2="17"/>
                    <line x1="14" x2="14" y1="11" y2="17"/>
                </svg>
            </div>
            <h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 600; color: #111827;">Delete Selected Candidates?</h3>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">
                You are about to delete <strong id="bulkDeleteCount" style="color: #dc2626;">0</strong> candidate(s). 
                This action cannot be undone and will remove all associated data including resumes, activities, and pipeline entries.
            </p>
        </div>
        <div style="display: flex; gap: 12px; justify-content: center;">
            <button onclick="closeBulkDeleteModal();" style="padding: 10px 20px; border: 1px solid #d1d5db; background: white; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; color: #374151;">
                Cancel
            </button>
            <button id="confirmBulkDeleteBtn" style="padding: 10px 20px; border: none; background: #dc2626; color: white; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer;">
                Delete Candidates
            </button>
        </div>
    </div>
</div>

<!-- Hidden form for bulk delete submission -->
<form id="bulkDeleteForm" method="post" action="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&a=bulkDelete" style="display: none;">
    <input type="hidden" name="candidateIDs" id="bulkDeleteCandidateIDs" value="" />
</form>

<script type="text/javascript">
function confirmBulkDelete<?php echo $md5InstanceName; ?>() {
    // Get all checked checkboxes
    var checkboxes = document.querySelectorAll('input[type="checkbox"][name^="checked_"]:checked');
    var candidateIDs = [];
    
    checkboxes.forEach(function(checkbox) {
        var name = checkbox.name;
        var id = name.replace('checked_', '');
        if (id && !isNaN(id)) {
            candidateIDs.push(id);
        }
    });
    
    if (candidateIDs.length === 0) {
        alert('Please select at least one candidate to delete.');
        return;
    }
    
    // Update modal with count
    document.getElementById('bulkDeleteCount').textContent = candidateIDs.length;
    document.getElementById('bulkDeleteCandidateIDs').value = candidateIDs.join(',');
    
    // Show modal
    var modal = document.getElementById('bulkDeleteModal');
    modal.style.display = 'flex';
    
    // Set up confirm button
    document.getElementById('confirmBulkDeleteBtn').onclick = function() {
        document.getElementById('bulkDeleteForm').submit();
    };
}

function closeBulkDeleteModal() {
    document.getElementById('bulkDeleteModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('bulkDeleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBulkDeleteModal();
    }
});
</script>

<?php TemplateUtility::printFooter(); ?>
