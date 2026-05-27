<?php
$pdo = new PDO('pgsql:host=localhost;port=5432;dbname=cats_dev', 'postgres', 'Joshi@515', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$stmt = $pdo->prepare("UPDATE career_portal_template SET value = :val WHERE career_portal_name = 'Blank Page' AND setting = :setting");

// ===== Apply for Position =====
$apply = <<<'TPL'
<div class="main-content" style="max-width:900px;">
    <div style="margin-bottom:24px;">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:#6b7280;">&larr; Back to all positions</a>
    </div>
    <h1 style="font-size:28px;margin-bottom:4px;">Apply for: <title></h1>
    <p style="margin-bottom:32px;color:#6b7280;">Complete the form below to submit your application.</p>

    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:36px;box-shadow:0 4px 6px rgba(0,0,0,0.07);margin-bottom:32px;">
        <h2 style="font-size:18px;color:#2563eb;margin:0 0 24px;padding-bottom:12px;border-bottom:2px solid #dbeafe;">Personal Information</h2>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div><label>First Name <span style="color:#dc2626;">*</span></label><input-firstName req></div>
            <div><label>Last Name <span style="color:#dc2626;">*</span></label><input-lastName req></div>
            <div><label>Email <span style="color:#dc2626;">*</span></label><input-email req></div>
            <div><label>Confirm Email <span style="color:#dc2626;">*</span></label><input-emailconfirm req></div>
            <div><label>Phone <span style="color:#dc2626;">*</span></label><input-phone req></div>
            <div><label>City</label><input-city></div>
            <div><label>State</label><input-state></div>
            <div><label>Zip Code</label><input-zip></div>
            <div style="grid-column:1/-1;"><label>Address</label><input-address></div>
        </div>
    </div>

    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:36px;box-shadow:0 4px 6px rgba(0,0,0,0.07);margin-bottom:32px;">
        <h2 style="font-size:18px;color:#2563eb;margin:0 0 24px;padding-bottom:12px;border-bottom:2px solid #dbeafe;">Professional Details</h2>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div><label>Key Skills</label><input-keySkills></div>
            <div><label>Current Employer</label><input-employer></div>
            <div style="grid-column:1/-1;"><label>How did you hear about us?</label><input-source></div>
        </div>
    </div>

    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:36px;box-shadow:0 4px 6px rgba(0,0,0,0.07);margin-bottom:32px;">
        <h2 style="font-size:18px;color:#2563eb;margin:0 0 24px;padding-bottom:12px;border-bottom:2px solid #dbeafe;">Resume &amp; Additional Info</h2>
        <div style="margin-bottom:20px;">
            <label>Upload Resume</label>
            <input-resumeUpload>
            <p style="font-size:12px;color:#9ca3af;margin-top:6px;">Accepted formats: PDF, DOC, DOCX, TXT, RTF (Max 10MB)</p>
        </div>
        <div>
            <label>Additional Notes</label>
            <input-extraNotes>
        </div>
    </div>

    <div style="text-align:center;padding:8px 0 32px;">
        <submit value="Submit Application" style="padding:14px 48px;min-width:280px;font-size:16px;border-radius:10px;">
    </div>
</div>
TPL;

$stmt->execute(['val' => $apply, 'setting' => 'Content - Apply for Position']);
echo "Updated: Content - Apply for Position\n";

// ===== Thanks for Submission =====
$thanks = <<<'TPL'
<div class="main-content" style="text-align:center;padding:80px 24px;max-width:700px;">
    <div style="width:88px;height:88px;background:linear-gradient(135deg,#059669,#10b981);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:28px;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
    </div>
    <h1 style="font-size:32px;margin-bottom:12px;color:#111827;">Application Submitted!</h1>
    <p style="font-size:18px;color:#6b7280;max-width:500px;margin:0 auto 16px;line-height:1.7;">Thank you for applying. We have received your application and our team will review it carefully.</p>
    <p style="font-size:15px;color:#9ca3af;max-width:460px;margin:0 auto 36px;">You will receive a confirmation email shortly. If you have questions, feel free to reach out to our recruitment team.</p>
    <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
        <a href="index.php?m=careers&p=showAll" class="nav-btn" style="display:inline-flex;padding:12px 28px;">Browse More Jobs</a>
        <a href="index.php?m=careers" style="display:inline-flex;padding:12px 28px;border:1.5px solid #e5e7eb;border-radius:8px;font-weight:600;font-size:14px;color:#374151;">Back to Home</a>
    </div>
</div>
TPL;

$stmt->execute(['val' => $thanks, 'setting' => 'Content - Thanks for your Submission']);
echo "Updated: Content - Thanks for your Submission\n";

// ===== Job Details =====
$jobDetails = <<<'TPL'
<div class="main-content" style="max-width:960px;">
    <div style="margin-bottom:24px;">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:#6b7280;display:inline-flex;align-items:center;gap:6px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back to all positions
        </a>
    </div>

    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:40px;box-shadow:0 4px 6px rgba(0,0,0,0.07);margin-bottom:24px;">
        <h1 style="font-size:32px;margin-bottom:12px;"><title></h1>
        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:28px;">
            <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#eff6ff;color:#2563eb;border-radius:20px;font-size:13px;font-weight:600;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <city>, <state>
            </span>
            <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#f0fdf4;color:#059669;border-radius:20px;font-size:13px;font-weight:600;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                <type>
            </span>
            <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#fefce8;color:#ca8a04;border-radius:20px;font-size:13px;font-weight:600;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Posted <daysOld> days ago
            </span>
            <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#faf5ff;color:#7c3aed;border-radius:20px;font-size:13px;font-weight:600;">
                <openings> opening(s)
            </span>
        </div>

        <a-applyToJob class="nav-btn" style="display:inline-flex;padding:13px 36px;font-size:15px;border-radius:10px;margin-bottom:32px;">Apply for this Position &rarr;</a>

        <div style="border-top:1px solid #e5e7eb;padding-top:28px;">
            <h2 style="font-size:20px;margin:0 0 16px;">Job Description</h2>
            <div style="color:#374151;line-height:1.9;font-size:15px;"><description></div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:32px;">
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:24px;">
            <h3 style="font-size:13px;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 16px;">Job Details</h3>
            <table style="width:100%;border-collapse:collapse;">
                <tr><td style="padding:10px 0;font-weight:600;color:#374151;width:40%;border-bottom:1px solid #f3f4f6;">Location</td><td style="padding:10px 0;color:#6b7280;border-bottom:1px solid #f3f4f6;"><city>, <state></td></tr>
                <tr><td style="padding:10px 0;font-weight:600;color:#374151;border-bottom:1px solid #f3f4f6;">Type</td><td style="padding:10px 0;color:#6b7280;border-bottom:1px solid #f3f4f6;"><type></td></tr>
                <tr><td style="padding:10px 0;font-weight:600;color:#374151;border-bottom:1px solid #f3f4f6;">Openings</td><td style="padding:10px 0;color:#6b7280;border-bottom:1px solid #f3f4f6;"><openings></td></tr>
                <tr><td style="padding:10px 0;font-weight:600;color:#374151;">Date Posted</td><td style="padding:10px 0;color:#6b7280;"><created></td></tr>
            </table>
        </div>
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:24px;display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <h3 style="font-size:13px;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 16px;">Recruiter</h3>
                <p style="font-size:16px;font-weight:600;color:#111827;margin:0 0 8px;"><recruiter></p>
            </div>
            <a-applyToJob class="nav-btn" style="display:inline-flex;padding:12px 24px;font-size:14px;width:100%;justify-content:center;margin-top:16px;">Apply Now &rarr;</a>
        </div>
    </div>
</div>
TPL;

$stmt->execute(['val' => $jobDetails, 'setting' => 'Content - Job Details']);
echo "Updated: Content - Job Details\n";

// ===== Search Results =====
$search = <<<'TPL'
<div class="main-content">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;flex-wrap:wrap;gap:16px;">
        <div>
            <h1 style="font-size:28px;margin-bottom:4px;">Open Positions</h1>
            <p style="color:#6b7280;margin:0;"><numberOfSearchResults> position(s) currently available</p>
        </div>
    </div>
    <searchResultsTableUnformatted>
</div>
TPL;

$stmt->execute(['val' => $search, 'setting' => 'Content - Search Results']);
echo "Updated: Content - Search Results\n";

echo "\nAll templates updated!\n";
