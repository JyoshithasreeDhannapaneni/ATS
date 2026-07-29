<?php /* Interviewer - Submit Feedback */ ?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Feedback - Interviewer Portal</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo(HTML_ENCODING); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="inter.css" rel="stylesheet" />
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
            max-width: 720px;
            margin: 32px auto;
            padding: 0 24px 48px;
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
        }
        .card-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }
        .card-header p {
            color: #6b7280;
            font-size: 14px;
            margin-top: 4px;
        }
        .card-body { padding: 24px; }
        .form-group { margin-bottom: 24px; }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .rating-group {
            display: flex;
            gap: 8px;
        }
        .rating-option {
            flex: 1;
        }
        .rating-option input[type="radio"] {
            display: none;
        }
        .rating-option label {
            display: block;
            text-align: center;
            padding: 10px 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            margin-bottom: 0;
        }
        .rating-option input[type="radio"]:checked + label {
            background: #1e40af;
            color: white;
            border-color: #1e40af;
        }
        textarea, select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: #111827;
        }
        textarea { min-height: 80px; resize: vertical; }
        .form-actions {
            display: flex;
            gap: 12px;
            padding-top: 8px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .btn-primary { background: #1e40af; color: white; }
        .btn-primary:hover { background: #1e3a8a; }
        .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
        .btn-secondary:hover { background: #e5e7eb; }
    </style>
</head>
<body>
    <header class="header">
        <a href="<?php echo CATSUtility::getIndexName(); ?>?m=interviewer" class="back-btn">
            &larr; Back to Dashboard
        </a>
        <h1>Submit Feedback</h1>
        <div></div>
    </header>

    <main class="main-content">
        <div class="card">
            <div class="card-header">
                <h2><?php echo htmlspecialchars($this->candidate['first_name'] . ' ' . $this->candidate['last_name']); ?></h2>
                <p><?php echo htmlspecialchars($this->event['title']); ?> &middot; <?php echo date('D, M j, Y', strtotime($this->event['date'])); ?></p>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo CATSUtility::getIndexName(); ?>?m=interviewer&amp;a=submitFeedback">
                    <input type="hidden" name="feedbackID" value="<?php echo intval($this->feedbackID); ?>" />

                    <?php
                        $ratingFields = array(
                            'overallRating'        => 'Overall Rating',
                            'technicalRating'      => 'Technical Skills',
                            'communicationRating'  => 'Communication',
                            'culturalFitRating'    => 'Cultural Fit',
                            'problemSolvingRating' => 'Problem Solving',
                        );
                    ?>
                    <?php foreach ($ratingFields as $fieldName => $fieldLabel): ?>
                        <div class="form-group">
                            <label><?php echo $fieldLabel; ?></label>
                            <div class="rating-group">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <div class="rating-option">
                                        <input type="radio" name="<?php echo $fieldName; ?>" id="<?php echo $fieldName . $i; ?>" value="<?php echo $i; ?>" />
                                        <label for="<?php echo $fieldName . $i; ?>"><?php echo $i; ?></label>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="form-group">
                        <label for="strengths">Strengths</label>
                        <textarea name="strengths" id="strengths"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="weaknesses">Areas for Improvement</label>
                        <textarea name="weaknesses" id="weaknesses"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="notes">Additional Notes</label>
                        <textarea name="notes" id="notes"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="recommendation">Recommendation</label>
                        <select name="recommendation" id="recommendation">
                            <option value="">Select a recommendation&hellip;</option>
                            <option value="strong_hire">Strong Hire</option>
                            <option value="hire">Hire</option>
                            <option value="maybe">Maybe</option>
                            <option value="no_hire">No Hire</option>
                            <option value="strong_no_hire">Strong No Hire</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Submit Feedback</button>
                        <a href="<?php echo CATSUtility::getIndexName(); ?>?m=interviewer" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
