<?php /* Interviewer - View Candidate */ ?>
<!DOCTYPE html>
<html>
<head>
    <title>Candidate Profile - Interviewer Portal</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo(HTML_ENCODING); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
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
        }
        .header h1 { font-size: 18px; font-weight: 600; }
        .back-btn {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        .main-content {
            max-width: 800px;
            margin: 32px auto;
            padding: 0 24px;
        }
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header {
            padding: 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .avatar {
            width: 64px;
            height: 64px;
            background: #dbeafe;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 600;
            color: #1e40af;
        }
        .candidate-name {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
        }
        .candidate-title {
            color: #6b7280;
            font-size: 14px;
            margin-top: 4px;
        }
        .card-body { padding: 24px; }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        .info-item label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .info-item p {
            font-size: 15px;
            color: #111827;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin: 24px 0 16px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .skill-tag {
            background: #dbeafe;
            color: #1e40af;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
        }
        .notes-box {
            background: #f9fafb;
            border-radius: 8px;
            padding: 16px;
            font-size: 14px;
            color: #374151;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="<?php echo CATSUtility::getIndexName(); ?>?m=interviewer" class="back-btn">
            ← Back to Dashboard
        </a>
        <h1>Candidate Profile</h1>
        <div></div>
    </header>
    
    <main class="main-content">
        <div class="card">
            <div class="card-header">
                <div class="avatar">
                    <?php echo strtoupper(substr($this->candidate['first_name'], 0, 1) . substr($this->candidate['last_name'], 0, 1)); ?>
                </div>
                <div>
                    <h2 class="candidate-name">
                        <?php echo htmlspecialchars($this->candidate['first_name'] . ' ' . $this->candidate['last_name']); ?>
                    </h2>
                    <?php if (!empty($this->candidate['current_employer'])): ?>
                        <p class="candidate-title"><?php echo htmlspecialchars($this->candidate['current_employer']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Email</label>
                        <p><?php echo htmlspecialchars($this->candidate['email1'] ?: 'Not provided'); ?></p>
                    </div>
                    <div class="info-item">
                        <label>Phone</label>
                        <p><?php echo htmlspecialchars($this->candidate['phone_cell'] ?: $this->candidate['phone_home'] ?: 'Not provided'); ?></p>
                    </div>
                    <div class="info-item">
                        <label>Location</label>
                        <p>
                            <?php 
                                $location = array_filter(array($this->candidate['city'], $this->candidate['state']));
                                echo htmlspecialchars(implode(', ', $location) ?: 'Not provided');
                            ?>
                        </p>
                    </div>
                    <div class="info-item">
                        <label>Current Employer</label>
                        <p><?php echo htmlspecialchars($this->candidate['current_employer'] ?: 'Not provided'); ?></p>
                    </div>
                </div>
                
                <?php if (!empty($this->candidate['key_skills'])): ?>
                    <h3 class="section-title">Key Skills</h3>
                    <div class="skills-list">
                        <?php 
                            $skills = explode(',', $this->candidate['key_skills']);
                            foreach ($skills as $skill):
                                $skill = trim($skill);
                                if (!empty($skill)):
                        ?>
                            <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                        <?php 
                                endif;
                            endforeach; 
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($this->candidate['notes'])): ?>
                    <h3 class="section-title">Notes</h3>
                    <div class="notes-box">
                        <?php echo nl2br(htmlspecialchars($this->candidate['notes'])); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
