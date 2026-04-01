<?php /* Interviewer Dashboard */ ?>
<!DOCTYPE html>
<html>
<head>
    <title>Interviewer Portal - Neutara ATS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo(HTML_ENCODING); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
        }
        
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .header-logo img {
            height: 36px;
            border-radius: 6px;
        }
        
        .header-logo h1 {
            font-size: 18px;
            font-weight: 600;
        }
        
        .header-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .header-user .role-badge {
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .header-user .user-name {
            font-weight: 500;
        }
        
        .header-user .logout-btn {
            background: rgba(255,255,255,0.15);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
        }
        
        .header-user .logout-btn:hover {
            background: rgba(255,255,255,0.25);
        }
        
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 24px;
        }
        
        .welcome-section {
            margin-bottom: 32px;
        }
        
        .welcome-section h2 {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }
        
        .welcome-section p {
            color: #6b7280;
            font-size: 15px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 24px;
        }
        
        .stat-card .stat-icon.blue { background: #dbeafe; }
        .stat-card .stat-icon.green { background: #dcfce7; }
        .stat-card .stat-icon.orange { background: #ffedd5; }
        
        .stat-card .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #111827;
        }
        
        .stat-card .stat-label {
            color: #6b7280;
            font-size: 14px;
            margin-top: 4px;
        }
        
        .section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 24px;
        }
        
        .section-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .section-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
        }
        
        .section-content {
            padding: 0;
        }
        
        .interview-list {
            list-style: none;
        }
        
        .interview-item {
            padding: 20px 24px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.2s;
        }
        
        .interview-item:hover {
            background: #f9fafb;
        }
        
        .interview-item:last-child {
            border-bottom: none;
        }
        
        .interview-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .interview-date {
            text-align: center;
            min-width: 60px;
        }
        
        .interview-date .day {
            font-size: 24px;
            font-weight: 700;
            color: #1e40af;
        }
        
        .interview-date .month {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
        }
        
        .interview-date.today {
            background: #dbeafe;
            padding: 8px 12px;
            border-radius: 8px;
        }
        
        .interview-details h4 {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }
        
        .interview-details .meta {
            font-size: 13px;
            color: #6b7280;
        }
        
        .interview-details .candidate-name {
            color: #1e40af;
            font-weight: 500;
        }
        
        .interview-type-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .interview-type-badge.l1 { background: #dbeafe; color: #1e40af; }
        .interview-type-badge.l2 { background: #dcfce7; color: #166534; }
        .interview-type-badge.l3 { background: #fef3c7; color: #92400e; }
        .interview-type-badge.hr { background: #f3e8ff; color: #7c3aed; }
        
        .interview-actions {
            display: flex;
            gap: 8px;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-primary {
            background: #1e40af;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1e3a8a;
        }
        
        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
        }
        
        .btn-success {
            background: #059669;
            color: white;
        }
        
        .empty-state {
            padding: 48px 24px;
            text-align: center;
            color: #6b7280;
        }
        
        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        
        .empty-state h4 {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        
        .nav-tabs {
            display: flex;
            gap: 8px;
            padding: 16px 24px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .nav-tab {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .nav-tab:hover {
            background: #e5e7eb;
            color: #374151;
        }
        
        .nav-tab.active {
            background: #1e40af;
            color: white;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-logo">
            <img src="images/Neutaralogo.jpg" alt="Neutara ATS" />
            <h1>Interviewer Portal</h1>
        </div>
        <div class="header-user">
            <span class="role-badge">
                <?php echo $this->interviewerType ? $this->interviewerType . ' Interviewer' : 'Interviewer'; ?>
            </span>
            <span class="user-name"><?php echo htmlspecialchars($this->userName); ?></span>
            <a href="<?php echo CATSUtility::getIndexName(); ?>?m=logout" class="logout-btn">Sign Out</a>
        </div>
    </header>
    
    <main class="main-content">
        <div class="welcome-section">
            <h2>Welcome back, <?php echo htmlspecialchars(explode(' ', $this->userName)[0]); ?>!</h2>
            <p>Here's your interview schedule and pending tasks.</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">📅</div>
                <div class="stat-value"><?php echo count($this->upcomingInterviews); ?></div>
                <div class="stat-label">Upcoming Interviews</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">⏳</div>
                <div class="stat-value"><?php echo count($this->pastInterviews); ?></div>
                <div class="stat-label">Pending Feedback</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">✓</div>
                <div class="stat-value">
                    <?php 
                        $todayCount = 0;
                        foreach ($this->upcomingInterviews as $interview) {
                            if ($interview['is_today']) $todayCount++;
                        }
                        echo $todayCount;
                    ?>
                </div>
                <div class="stat-label">Today's Interviews</div>
            </div>
        </div>
        
        <div class="section">
            <div class="section-header">
                <h3>📅 Upcoming Interviews</h3>
            </div>
            <div class="section-content">
                <?php if (empty($this->upcomingInterviews)): ?>
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <h4>No upcoming interviews</h4>
                        <p>You don't have any interviews scheduled yet.</p>
                    </div>
                <?php else: ?>
                    <ul class="interview-list">
                        <?php foreach ($this->upcomingInterviews as $interview): ?>
                            <li class="interview-item">
                                <div class="interview-info">
                                    <div class="interview-date <?php echo $interview['is_today'] ? 'today' : ''; ?>">
                                        <div class="day"><?php echo date('j', strtotime($interview['date'])); ?></div>
                                        <div class="month"><?php echo date('M', strtotime($interview['date'])); ?></div>
                                    </div>
                                    <div class="interview-details">
                                        <h4><?php echo htmlspecialchars($interview['title']); ?></h4>
                                        <div class="meta">
                                            <span><?php echo $interview['formatted_time']; ?></span>
                                            <?php if (!empty($interview['candidate_name'])): ?>
                                                • <span class="candidate-name"><?php echo htmlspecialchars($interview['candidate_name']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if (!empty($interview['event_type'])): ?>
                                        <?php 
                                            $typeClass = 'l1';
                                            $type = strtolower($interview['event_type']);
                                            if (strpos($type, 'l2') !== false) $typeClass = 'l2';
                                            elseif (strpos($type, 'l3') !== false) $typeClass = 'l3';
                                            elseif (strpos($type, 'hr') !== false) $typeClass = 'hr';
                                        ?>
                                        <span class="interview-type-badge <?php echo $typeClass; ?>">
                                            <?php echo htmlspecialchars($interview['event_type']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="interview-actions">
                                    <?php if (!empty($interview['candidate_id'])): ?>
                                        <a href="<?php echo CATSUtility::getIndexName(); ?>?m=interviewer&amp;a=viewCandidate&amp;candidateID=<?php echo $interview['candidate_id']; ?>" class="btn btn-secondary">
                                            View Profile
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($interview['description']) && strpos($interview['description'], 'http') !== false): ?>
                                        <?php 
                                            preg_match('/(https?:\/\/[^\s]+)/', $interview['description'], $matches);
                                            $meetingLink = isset($matches[1]) ? $matches[1] : '';
                                        ?>
                                        <?php if ($meetingLink): ?>
                                            <a href="<?php echo htmlspecialchars($meetingLink); ?>" target="_blank" class="btn btn-primary">
                                                🎥 Join Meeting
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (!empty($this->pastInterviews)): ?>
        <div class="section">
            <div class="section-header">
                <h3>📝 Pending Feedback</h3>
            </div>
            <div class="section-content">
                <ul class="interview-list">
                    <?php foreach ($this->pastInterviews as $interview): ?>
                        <li class="interview-item">
                            <div class="interview-info">
                                <div class="interview-details">
                                    <h4><?php echo htmlspecialchars($interview['title']); ?></h4>
                                    <div class="meta">
                                        <?php echo $interview['formatted_date']; ?>
                                        <?php if (!empty($interview['candidate_name'])): ?>
                                            • <span class="candidate-name"><?php echo htmlspecialchars($interview['candidate_name']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="interview-actions">
                                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=interviewer&amp;a=submitFeedback&amp;eventID=<?php echo $interview['calendar_event_id']; ?>" class="btn btn-success">
                                    Submit Feedback
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>
    </main>
</body>
</html>
