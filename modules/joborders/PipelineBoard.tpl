<?php /* Neutara ATS - Kanban Pipeline Board */ ?>
<?php TemplateUtility::printHeader('Pipeline Board', array('js/lib.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>

<div id="main">
    <?php TemplateUtility::printQuickSearch(); ?>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        :root {
            --kb-primary: #2563eb;
            --kb-primary-light: #dbeafe;
            --kb-gray-50: #f9fafb;
            --kb-gray-100: #f3f4f6;
            --kb-gray-200: #e5e7eb;
            --kb-gray-300: #d1d5db;
            --kb-gray-400: #9ca3af;
            --kb-gray-500: #6b7280;
            --kb-gray-600: #4b5563;
            --kb-gray-700: #374151;
            --kb-gray-800: #1f2937;
            --kb-green: #059669;
            --kb-amber: #d97706;
            --kb-red: #dc2626;
            --kb-purple: #7c3aed;
        }

        /* ===== Animations ===== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: calc(200px + 100%) 0; }
        }
        @keyframes dropHighlight {
            0% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(37, 99, 235, 0); }
            100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
        }

        /* ===== Board Header ===== */
        .pipeline-board-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0 16px;
            animation: fadeInUp 0.5s ease;
        }

        .pipeline-board-header h2 {
            font-family: 'Inter', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--kb-gray-800);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pipeline-board-header h2 svg {
            color: var(--kb-primary);
        }

        .board-controls {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .job-select-wrapper {
            position: relative;
        }

        .job-select-wrapper select {
            appearance: none;
            padding: 10px 36px 10px 14px;
            border: 1.5px solid var(--kb-gray-300);
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 500;
            color: var(--kb-gray-700);
            background: #fff;
            cursor: pointer;
            min-width: 280px;
            transition: all 0.2s ease;
            outline: none;
        }

        .job-select-wrapper select:focus {
            border-color: var(--kb-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .job-select-wrapper::after {
            content: '';
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid var(--kb-gray-400);
            pointer-events: none;
        }

        .board-stats {
            display: flex;
            gap: 16px;
            align-items: center;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: var(--kb-gray-500);
        }

        .board-stat {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: var(--kb-gray-100);
            border-radius: 8px;
            font-weight: 500;
        }

        .board-stat .stat-count {
            font-weight: 700;
            color: var(--kb-primary);
        }

        /* ===== Kanban Board ===== */
        .kanban-board {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding: 4px 0 20px;
            min-height: calc(100vh - 260px);
            scrollbar-width: thin;
            scrollbar-color: var(--kb-gray-300) transparent;
        }

        .kanban-board::-webkit-scrollbar {
            height: 6px;
        }

        .kanban-board::-webkit-scrollbar-track {
            background: transparent;
        }

        .kanban-board::-webkit-scrollbar-thumb {
            background: var(--kb-gray-300);
            border-radius: 3px;
        }

        /* ===== Kanban Column ===== */
        .kanban-column {
            flex: 0 0 260px;
            min-width: 260px;
            max-height: calc(100vh - 270px);
            display: flex;
            flex-direction: column;
            background: var(--kb-gray-50);
            border-radius: 12px;
            border: 1px solid var(--kb-gray-200);
            animation: slideIn 0.4s ease;
            transition: all 0.3s ease;
        }

        .kanban-column:nth-child(1) { animation-delay: 0.05s; }
        .kanban-column:nth-child(2) { animation-delay: 0.1s; }
        .kanban-column:nth-child(3) { animation-delay: 0.15s; }
        .kanban-column:nth-child(4) { animation-delay: 0.2s; }
        .kanban-column:nth-child(5) { animation-delay: 0.25s; }
        .kanban-column:nth-child(6) { animation-delay: 0.3s; }
        .kanban-column:nth-child(7) { animation-delay: 0.35s; }
        .kanban-column:nth-child(8) { animation-delay: 0.4s; }

        .kanban-column.drag-over {
            background: var(--kb-primary-light);
            border-color: var(--kb-primary);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
        }

        /* ===== Column Header ===== */
        .column-header {
            padding: 14px 14px 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            border-bottom: 1px solid var(--kb-gray-200);
        }

        .column-title {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: var(--kb-gray-700);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .column-count {
            background: var(--kb-gray-200);
            color: var(--kb-gray-600);
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 10px;
            min-width: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .kanban-column.drag-over .column-count {
            background: var(--kb-primary);
            color: #fff;
        }

        /* Column color indicators */
        .column-header .column-color {
            width: 4px;
            height: 18px;
            border-radius: 2px;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .status-100 .column-color { background: var(--kb-gray-400); }     /* No Contact */
        .status-200 .column-color { background: #3b82f6; }                /* Contacted */
        .status-250 .column-color { background: #8b5cf6; }                /* Responded */
        .status-300 .column-color { background: var(--kb-amber); }        /* Qualifying */
        .status-400 .column-color { background: #f97316; }                /* Submitted */
        .status-500 .column-color { background: var(--kb-primary); }      /* Interviewing */
        .status-600 .column-color { background: #10b981; }                /* Offered */
        .status-650 .column-color { background: var(--kb-gray-300); }     /* Not in Consideration */
        .status-700 .column-color { background: var(--kb-red); }          /* Client Declined */
        .status-800 .column-color { background: var(--kb-green); }        /* Placed */

        /* ===== Card Container ===== */
        .column-cards {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
            scrollbar-width: thin;
            scrollbar-color: var(--kb-gray-200) transparent;
        }

        .column-cards::-webkit-scrollbar {
            width: 4px;
        }

        .column-cards::-webkit-scrollbar-thumb {
            background: var(--kb-gray-200);
            border-radius: 2px;
        }

        /* ===== Candidate Card ===== */
        .candidate-card {
            background: #fff;
            border: 1px solid var(--kb-gray-200);
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 8px;
            cursor: grab;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            font-family: 'Inter', sans-serif;
        }

        .candidate-card:hover {
            border-color: var(--kb-primary);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .candidate-card:active {
            cursor: grabbing;
        }

        .candidate-card.dragging {
            opacity: 0.5;
            transform: rotate(2deg) scale(1.02);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            z-index: 1000;
        }

        .candidate-card.drop-preview {
            border: 2px dashed var(--kb-primary);
            background: var(--kb-primary-light);
            animation: pulse 1s ease infinite;
        }

        /* Card Content */
        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .card-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--kb-primary), #1d4ed8);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
            letter-spacing: -0.02em;
        }

        .card-avatar.hot {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .card-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--kb-gray-800);
            line-height: 1.3;
            text-decoration: none;
            display: block;
        }

        .card-name:hover {
            color: var(--kb-primary);
        }

        .card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 6px;
        }

        .card-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            background: var(--kb-gray-100);
            border-radius: 6px;
            font-size: 11px;
            font-weight: 500;
            color: var(--kb-gray-600);
        }

        .card-tag svg {
            width: 12px;
            height: 12px;
            stroke: var(--kb-gray-400);
        }

        .card-tag.hot-tag {
            background: #fef2f2;
            color: #dc2626;
        }

        .card-tag.hot-tag svg { stroke: #dc2626; }

        .card-tag.resume-tag {
            background: #eff6ff;
            color: var(--kb-primary);
        }

        .card-tag.resume-tag svg { stroke: var(--kb-primary); }

        .card-date {
            font-size: 11px;
            color: var(--kb-gray-400);
            margin-top: 6px;
        }

        .card-actions {
            display: none;
            position: absolute;
            top: 8px;
            right: 8px;
            gap: 4px;
        }

        .candidate-card:hover .card-actions {
            display: flex;
        }

        .card-action-btn {
            width: 26px;
            height: 26px;
            border: none;
            border-radius: 6px;
            background: var(--kb-gray-100);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            padding: 0;
        }

        .card-action-btn:hover {
            background: var(--kb-primary);
        }

        .card-action-btn:hover svg {
            stroke: #fff;
        }

        .card-action-btn svg {
            width: 14px;
            height: 14px;
            stroke: var(--kb-gray-500);
        }

        /* ===== Empty State ===== */
        .column-empty {
            text-align: center;
            padding: 24px 12px;
            color: var(--kb-gray-400);
            font-size: 12px;
            font-family: 'Inter', sans-serif;
        }

        .column-empty svg {
            margin-bottom: 8px;
            opacity: 0.4;
        }

        /* ===== Loading State ===== */
        .loading-overlay {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            font-family: 'Inter', sans-serif;
            color: var(--kb-gray-500);
            gap: 12px;
        }

        .loading-spinner {
            width: 28px;
            height: 28px;
            border: 3px solid var(--kb-gray-200);
            border-top: 3px solid var(--kb-primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ===== No Job Selected ===== */
        .no-job-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            text-align: center;
            animation: fadeInUp 0.6s ease;
        }

        .no-job-state svg {
            margin-bottom: 16px;
            color: var(--kb-gray-300);
        }

        .no-job-state h3 {
            font-family: 'Inter', sans-serif;
            font-size: 18px;
            font-weight: 600;
            color: var(--kb-gray-700);
            margin: 0 0 6px;
        }

        .no-job-state p {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: var(--kb-gray-400);
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pipeline-board-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            .board-controls {
                width: 100%;
            }
            .job-select-wrapper select {
                width: 100%;
                min-width: auto;
            }
        }
    </style>

    <div id="contents">
        <!-- Board Header -->
        <div class="pipeline-board-header">
            <h2>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                Pipeline Board
            </h2>
            <div class="board-controls">
                <div class="board-stats" id="boardStats" style="display: none;">
                    <div class="board-stat">
                        Total: <span class="stat-count" id="totalCandidates">0</span>
                    </div>
                </div>
                <div class="job-select-wrapper">
                    <select id="jobOrderSelect" onchange="loadPipeline(this.value)">
                        <option value="">-- Select a Job Order --</option>
                        <?php foreach ($this->jobOrdersRS as $jo): ?>
                            <option value="<?php echo $jo['jobOrderID']; ?>">
                                <?php echo htmlspecialchars($jo['title']); ?>
                                <?php if (!empty($jo['companyName'])): ?>
                                    (<?php echo htmlspecialchars($jo['companyName']); ?>)
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Board Container -->
        <div id="kanbanContainer">
            <div class="no-job-state" id="noJobState">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                <h3>Select a Job Order</h3>
                <p>Choose a job order above to view its hiring pipeline</p>
            </div>
            <div class="loading-overlay" id="loadingState" style="display: none;">
                <div class="loading-spinner"></div>
                <span>Loading pipeline...</span>
            </div>
            <div class="kanban-board" id="kanbanBoard" style="display: none;"></div>
        </div>
    </div>

    <script>
        var sessionCookie = '<?php echo $_SESSION['CATS']->getCookie(); ?>';
        var currentJobOrderID = null;

        function loadPipeline(jobOrderID) {
            if (!jobOrderID) {
                document.getElementById('kanbanBoard').style.display = 'none';
                document.getElementById('noJobState').style.display = 'flex';
                document.getElementById('loadingState').style.display = 'none';
                document.getElementById('boardStats').style.display = 'none';
                return;
            }

            currentJobOrderID = jobOrderID;
            document.getElementById('noJobState').style.display = 'none';
            document.getElementById('kanbanBoard').style.display = 'none';
            document.getElementById('loadingState').style.display = 'flex';

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'ajax.php?f=getKanbanPipeline&joborderID=' + jobOrderID, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        var data = JSON.parse(xhr.responseText);
                        renderBoard(data);
                    } catch (e) {
                        document.getElementById('loadingState').innerHTML =
                            '<span style="color: #dc2626;">Failed to load pipeline data. Please try again.</span>';
                    }
                }
            };
            xhr.send();
        }

        function renderBoard(data) {
            var board = document.getElementById('kanbanBoard');
            board.innerHTML = '';

            if (!data.columns || data.columns.length === 0) {
                board.innerHTML = '<div class="no-job-state"><h3>No pipeline stages found</h3></div>';
                board.style.display = 'flex';
                document.getElementById('loadingState').style.display = 'none';
                return;
            }

            var totalCandidates = 0;
            data.columns.forEach(function(col) {
                totalCandidates += col.candidates.length;
            });

            // Update stats
            document.getElementById('totalCandidates').textContent = totalCandidates;
            document.getElementById('boardStats').style.display = 'flex';

            // Render columns
            data.columns.forEach(function(col) {
                var column = document.createElement('div');
                column.className = 'kanban-column status-' + col.statusID;
                column.setAttribute('data-status-id', col.statusID);

                // Column header
                var header = document.createElement('div');
                header.className = 'column-header';
                header.innerHTML =
                    '<div style="display:flex;align-items:center;">' +
                        '<div class="column-color"></div>' +
                        '<span class="column-title">' + escapeHtml(col.status) + '</span>' +
                    '</div>' +
                    '<span class="column-count">' + col.candidates.length + '</span>';
                column.appendChild(header);

                // Cards container
                var cardsContainer = document.createElement('div');
                cardsContainer.className = 'column-cards';
                cardsContainer.setAttribute('data-status-id', col.statusID);

                // Drag & drop events on container
                cardsContainer.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    this.closest('.kanban-column').classList.add('drag-over');
                });

                cardsContainer.addEventListener('dragleave', function(e) {
                    if (!this.contains(e.relatedTarget)) {
                        this.closest('.kanban-column').classList.remove('drag-over');
                    }
                });

                cardsContainer.addEventListener('drop', function(e) {
                    e.preventDefault();
                    var targetColumn = this.closest('.kanban-column');
                    targetColumn.classList.remove('drag-over');

                    var cardData = JSON.parse(e.dataTransfer.getData('text/plain'));
                    var newStatusID = this.getAttribute('data-status-id');

                    if (cardData.currentStatusID !== newStatusID) {
                        updateCandidateStatus(
                            cardData.candidateJobOrderID,
                            cardData.candidateID,
                            currentJobOrderID,
                            newStatusID
                        );
                    }
                });

                if (col.candidates.length === 0) {
                    cardsContainer.innerHTML =
                        '<div class="column-empty">' +
                            '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M8 15h8M9 9h.01M15 9h.01"/></svg>' +
                            '<div>No candidates</div>' +
                        '</div>';
                } else {
                    col.candidates.forEach(function(candidate) {
                        cardsContainer.appendChild(createCandidateCard(candidate, col.statusID));
                    });
                }

                column.appendChild(cardsContainer);
                board.appendChild(column);
            });

            board.style.display = 'flex';
            document.getElementById('loadingState').style.display = 'none';
        }

        function createCandidateCard(candidate, statusID) {
            var card = document.createElement('div');
            card.className = 'candidate-card';
            card.setAttribute('draggable', 'true');
            card.setAttribute('data-candidate-id', candidate.candidateID);
            card.setAttribute('data-cjo-id', candidate.candidateJobOrderID);

            // Drag events
            card.addEventListener('dragstart', function(e) {
                this.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', JSON.stringify({
                    candidateJobOrderID: candidate.candidateJobOrderID,
                    candidateID: candidate.candidateID,
                    currentStatusID: String(statusID)
                }));
            });

            card.addEventListener('dragend', function() {
                this.classList.remove('dragging');
                document.querySelectorAll('.kanban-column').forEach(function(col) {
                    col.classList.remove('drag-over');
                });
            });

            var initials = (candidate.firstName.charAt(0) + candidate.lastName.charAt(0)).toUpperCase();
            var isHot = candidate.isHot == 1;

            var html =
                '<div class="card-actions">' +
                    '<a class="card-action-btn" href="index.php?m=candidates&a=show&candidateID=' + candidate.candidateID + '" title="View Profile">' +
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>' +
                    '</a>' +
                '</div>' +
                '<div class="card-header">' +
                    '<div class="card-avatar' + (isHot ? ' hot' : '') + '">' + escapeHtml(initials) + '</div>' +
                    '<div>' +
                        '<a class="card-name" href="index.php?m=candidates&a=show&candidateID=' + candidate.candidateID + '">' +
                            escapeHtml(candidate.firstName) + ' ' + escapeHtml(candidate.lastName) +
                        '</a>' +
                    '</div>' +
                '</div>' +
                '<div class="card-meta">';

            if (candidate.state) {
                html += '<span class="card-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>' + escapeHtml(candidate.state) + '</span>';
            }
            if (isHot) {
                html += '<span class="card-tag hot-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2c.5 3-1.5 5-3 7 1.5 0 3 1 3 3s-1 4-3 5c4 0 7-3 7-7 0-3-2-6-4-8z"/></svg>Hot</span>';
            }
            if (candidate.hasAttachment == 1) {
                html += '<span class="card-tag resume-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Resume</span>';
            }

            html += '</div>';
            html += '<div class="card-date">Added ' + escapeHtml(candidate.dateCreated) + (candidate.ownerName ? ' &middot; ' + escapeHtml(candidate.ownerName) : '') + '</div>';

            card.innerHTML = html;
            return card;
        }

        function updateCandidateStatus(candidateJobOrderID, candidateID, jobOrderID, newStatusID) {
            var xhr = new XMLHttpRequest();
            var params = 'f=updatePipelineStatus' +
                '&candidateJobOrderID=' + candidateJobOrderID +
                '&candidateID=' + candidateID +
                '&jobOrderID=' + jobOrderID +
                '&statusID=' + newStatusID;

            xhr.open('GET', 'ajax.php?' + params, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    // Reload the board to reflect changes
                    loadPipeline(currentJobOrderID);
                }
            };
            xhr.send();
        }

        function escapeHtml(text) {
            if (!text) return '';
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        }
    </script>
</div>

<?php TemplateUtility::printFooter(); ?>
