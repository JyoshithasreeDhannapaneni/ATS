<?php
/**
 * Career Portal — Built-in Templates Installer
 * Adds 4 professional templates to the career portal settings page.
 *
 * Templates:
 *   1. Modern Blue  — Blue header bar, "Join Our Team" hero with wave, stats, cards
 *   2. Premium      — White nav, hero illustration, features, testimonials, dark footer
 *   3. Starter      — Clean minimal design for easy customization
 *   4. Corporate    — Dark professional theme with emerald accents
 *
 * Run: php install_builtin_templates.php
 */

$pdo = new PDO('pgsql:host=localhost;port=5432;dbname=cats_dev', 'postgres', 'Joshi@515', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$templateNames = ['Modern Blue', 'Premium', 'Starter', 'Corporate'];

// Remove existing templates with these names to allow re-running
$delStmt = $pdo->prepare("DELETE FROM career_portal_template WHERE career_portal_name = :name");
foreach ($templateNames as $name) {
    $delStmt->execute(['name' => $name]);
}
echo "Cleaned up existing templates.\n";

$insStmt = $pdo->prepare("INSERT INTO career_portal_template (career_portal_name, setting, value) VALUES (:name, :setting, :value)");

function insertTemplate($pdo, $insStmt, $templateName, $settings) {
    foreach ($settings as $setting => $value) {
        $insStmt->execute([
            'name' => $templateName,
            'setting' => $setting,
            'value' => $value
        ]);
    }
    echo "  ✓ {$templateName} installed (" . count($settings) . " sections)\n";
}


// ============================================================================
//  SHARED: Common apply page template and thanks page (reused across templates)
// ============================================================================

$sharedApply = <<<'TPL'
<div style="max-width:1100px;margin:0 auto;padding:0 32px;">
    <div style="padding-top:24px;margin-bottom:16px;" class="reveal">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:var(--gray-500);display:inline-flex;align-items:center;gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back to Jobs
        </a>
    </div>
    <div class="reveal" style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;">
        <div>
            <h1 style="font-size:26px;margin-bottom:4px;">Apply for <title></h1>
            <p style="color:var(--gray-500);margin:0;font-size:14px;">Fields marked <span style="color:var(--danger);">*</span> are required.</p>
        </div>
        <div style="text-align:right;min-width:180px;">
            <div class="progress-section">
                <span class="progress-label" id="formProgressLabel">0% complete</span>
            </div>
            <div class="progress-bar-bg" style="margin-top:6px;">
                <div class="progress-bar-fill" id="formProgress"></div>
            </div>
        </div>
    </div>
    <div class="step-indicator reveal">
        <div class="step-item"><div class="step-number active">1</div><span class="step-label">Personal Information</span></div>
        <div class="step-item"><div class="step-number inactive">2</div><span class="step-label inactive">Professional Background</span></div>
        <div class="step-item"><div class="step-number inactive">3</div><span class="step-label inactive">Resume & Documents</span></div>
        <div class="step-item"><div class="step-number inactive">4</div><span class="step-label inactive">Review & Submit</span></div>
    </div>
    <div class="apply-layout">
        <div>
            <div class="form-section reveal">
                <div class="form-section-header">
                    <div class="form-section-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                    <div class="form-section-title"><h2>Personal Information</h2><p>Tell us who you are.</p></div>
                </div>
                <div class="form-grid">
                    <div><label>First Name <span class="req">*</span></label><input-firstName req></div>
                    <div><label>Last Name <span class="req">*</span></label><input-lastName req></div>
                    <div><label>Email <span class="req">*</span></label><input-email req></div>
                    <div><label>Confirm Email <span class="req">*</span></label><input-emailconfirm req></div>
                    <div><label>Phone <span class="req">*</span></label><input-phone req></div>
                    <div><label>City <span class="req">*</span></label><input-city req></div>
                    <div><label>State <span class="req">*</span></label><input-state req></div>
                    <div><label>Zip Code <span class="req">*</span></label><input-zip req></div>
                    <div class="full"><label>Address <span class="req">*</span></label><input-address req></div>
                </div>
            </div>
            <div class="form-section reveal">
                <div class="form-section-header">
                    <div class="form-section-icon" style="background:var(--accent-light,#f0fdf4);"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--accent-icon,#16a34a)" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg></div>
                    <div class="form-section-title"><h2>Professional Background</h2><p>Help us understand your experience.</p></div>
                </div>
                <div class="form-grid">
                    <div><label>Key Skills <span class="req">*</span></label><input-keySkills req></div>
                    <div><label>Current Employer</label><input-employer></div>
                    <div class="full"><label>How did you hear about us? <span class="req">*</span></label><input-source req></div>
                    <div class="full"><label>Additional Information</label><input-extraNotes></div>
                </div>
            </div>
            <div class="form-section reveal">
                <div class="form-section-header">
                    <div class="form-section-icon" style="background:var(--primary-50,#eff6ff);"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary,#2563eb)" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg></div>
                    <div class="form-section-title"><h2>Resume & Documents</h2><p>Upload your resume.</p></div>
                </div>
                <div>
                    <label>Upload Resume <span class="req">*</span></label>
                    <div class="file-upload-area" onclick="document.getElementById('resume')?document.getElementById('resume').click():(document.getElementById('resumeFile')?document.getElementById('resumeFile').click():null)">
                        <div class="upload-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></div>
                        <p>Drag and drop your file here</p>
                        <div class="or-text">or</div>
                        <span class="choose-file-btn">Choose File</span>
                    </div>
                    <input-resumeUpload>
                    <div class="file-types">PDF, DOC, DOCX, TXT, RTF &bull; Max 10MB</div>
                </div>
            </div>
            <div class="form-actions reveal">
                <button type="button" class="btn-draft" onclick="alert('Draft saved!');">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg>
                    Save Draft
                </button>
                <submit value="Save & Continue →" class="btn-submit">
            </div>
        </div>
        <div class="apply-sidebar">
            <div class="summary-card reveal">
                <h3>Application Summary</h3>
                <p class="summary-subtitle">Review your progress</p>
                <div class="summary-item"><div class="summary-dot"></div><div class="summary-item-text"><h4>Personal Information</h4><p>Not started</p></div></div>
                <div class="summary-item"><div class="summary-dot"></div><div class="summary-item-text"><h4>Professional Background</h4><p>Not started</p></div></div>
                <div class="summary-item"><div class="summary-dot"></div><div class="summary-item-text"><h4>Resume & Documents</h4><p>Not started</p></div></div>
                <div class="summary-item"><div class="summary-dot"></div><div class="summary-item-text"><h4>Review & Submit</h4><p>Not started</p></div></div>
                <div class="tip-box">
                    <div class="tip-header"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg><span>Tip</span></div>
                    <p>Complete all sections to increase your chances of getting noticed.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
(function(){
    function updateProgress(){
        var inputs=document.querySelectorAll('#applyToJobForm input[type="text"],#applyToJobForm input[type="email"],#applyToJobForm input[type="tel"],#applyToJobForm textarea,#applyToJobForm select');
        var filled=0,total=0;
        inputs.forEach(function(input){if(input.type!=='hidden'&&input.offsetParent!==null){total++;if(input.value&&input.value.trim()!=='')filled++;}});
        var pct=total>0?Math.round((filled/total)*100):0;
        var bar=document.getElementById('formProgress');var label=document.getElementById('formProgressLabel');
        if(bar)bar.style.width=pct+'%';if(label)label.textContent=pct+'% complete';
        var dots=document.querySelectorAll('.summary-dot');var statuses=document.querySelectorAll('.summary-item-text p');
        var s1=['firstName','lastName','email','phone'].filter(function(id){var el=document.getElementById(id);return el&&el.value.trim();}).length;
        if(s1>=4){dots[0].style.borderColor='#16a34a';dots[0].style.background='#16a34a';statuses[0].textContent='Completed';}
        else if(s1>0){dots[0].style.borderColor='var(--primary)';dots[0].style.background='var(--primary)';statuses[0].textContent='In progress';}
        var s2=['keySkills','source'].filter(function(id){var el=document.getElementById(id);return el&&el.value.trim();}).length;
        if(s2>=2){dots[1].style.borderColor='#16a34a';dots[1].style.background='#16a34a';statuses[1].textContent='Completed';}
        else if(s2>0){dots[1].style.borderColor='var(--primary)';dots[1].style.background='var(--primary)';statuses[1].textContent='In progress';}
        var fileEl=document.getElementById('resume')||document.getElementById('resumeFile');
        if(fileEl&&fileEl.value){dots[2].style.borderColor='#16a34a';dots[2].style.background='#16a34a';statuses[2].textContent='Completed';}
        var steps=document.querySelectorAll('.step-number');
        if(s1>=4&&steps[0]){steps[0].className='step-number completed';steps[0].innerHTML='<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>';}
        if(s2>=2&&steps[1]){steps[1].className='step-number completed';steps[1].innerHTML='<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>';}
    }
    document.addEventListener('DOMContentLoaded',function(){
        document.body.classList.add('page-loaded');
        setInterval(updateProgress,500);
        var els=document.querySelectorAll('.reveal');
        if('IntersectionObserver' in window){var obs=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting){e.target.classList.add('revealed');obs.unobserve(e.target);}});},{threshold:0.1});els.forEach(function(el){obs.observe(el);});}
        else{els.forEach(function(el){el.classList.add('revealed');});}
        var ph={'firstName':'Enter your first name','lastName':'Enter your last name','email':'Enter your email','emailconfirm':'Re-enter email','phone':'Phone number','city':'City','zip':'Zip code','address':'Full address','keySkills':'Key skills (comma separated)','employer':'Current employer','extraNotes':'Additional info'};
        Object.keys(ph).forEach(function(id){var el=document.getElementById(id);if(el)el.placeholder=ph[id];});
    });
})();
</script>
TPL;

$sharedThanks = <<<'TPL'
<div style="max-width:560px;margin:0 auto;text-align:center;padding:80px 24px;">
    <div class="reveal">
        <div style="width:72px;height:72px;background:var(--success-light,#dcfce7);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:20px;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h1 style="font-size:24px;margin-bottom:8px;">Application Submitted!</h1>
        <p style="font-size:15px;color:var(--gray-500);max-width:380px;margin:0 auto 24px;">Thank you for applying. We have received your application and will review it shortly.</p>
        <a href="index.php?m=careers&p=showAll" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:var(--primary);color:#fff !important;font:700 14px var(--font);border-radius:var(--radius,8px);transition:all 0.25s;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back to Positions
        </a>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded',function(){document.body.classList.add('page-loaded');document.querySelectorAll('.reveal').forEach(function(el){el.classList.add('revealed');});});</script>
TPL;

$sharedJobDetails = <<<'TPL'
<div style="max-width:900px;margin:0 auto;padding:32px;">
    <div style="margin-bottom:24px;" class="reveal">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:var(--gray-400);display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:var(--radius,8px);background:#fff;border:1px solid var(--gray-200);font-weight:600;transition:all 0.25s;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            All Positions
        </a>
    </div>
    <div class="job-detail-card reveal">
        <h1 style="font-size:32px;margin-bottom:16px;"><title></h1>
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:32px;">
            <span class="job-tag location">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <city>, <state>
            </span>
            <span class="job-tag type">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                <type>
            </span>
            <span class="job-tag time">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Posted <daysOld> days ago
            </span>
            <span class="job-tag openings">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                <openings> opening(s)
            </span>
        </div>
        <div style="margin-bottom:40px;">
            <a-applyToJob style="display:inline-flex;align-items:center;gap:8px;padding:14px 36px;background:var(--primary);color:#fff !important;font:700 15px var(--font);border-radius:var(--radius,8px);box-shadow:0 4px 16px rgba(37,99,235,0.25);transition:all 0.25s;">
                Apply Now
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div style="border-top:1px solid var(--gray-200);padding-top:32px;">
            <h2 style="font-size:22px;margin:0 0 20px;">About This Role</h2>
            <div style="color:var(--gray-600);line-height:1.9;font-size:15px;"><description></div>
        </div>
        <div style="margin-top:40px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
            <div style="background:var(--gray-50);border-radius:12px;padding:20px;">
                <div style="font:700 11px var(--font);color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Location</div>
                <div style="font:700 14px var(--font);color:var(--gray-800);"><city>, <state></div>
            </div>
            <div style="background:var(--gray-50);border-radius:12px;padding:20px;">
                <div style="font:700 11px var(--font);color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Type</div>
                <div style="font:700 14px var(--font);color:var(--gray-800);"><type></div>
            </div>
            <div style="background:var(--gray-50);border-radius:12px;padding:20px;">
                <div style="font:700 11px var(--font);color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Recruiter</div>
                <div style="font:700 14px var(--font);color:var(--gray-800);"><recruiter></div>
            </div>
            <div style="background:var(--gray-50);border-radius:12px;padding:20px;">
                <div style="font:700 11px var(--font);color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Posted</div>
                <div style="font:700 14px var(--font);color:var(--gray-800);"><created></div>
            </div>
        </div>
        <div style="margin-top:40px;text-align:center;padding:40px;background:var(--primary-50,#eff6ff);border-radius:16px;">
            <h3 style="font-size:20px;margin:0 0 10px;color:var(--gray-900);">Ready to Make an Impact?</h3>
            <p style="margin:0 0 20px;color:var(--gray-500);">Your next chapter starts with a single click.</p>
            <a-applyToJob style="display:inline-flex;align-items:center;gap:8px;padding:14px 36px;background:var(--primary);color:#fff !important;font:700 15px var(--font);border-radius:var(--radius,8px);box-shadow:0 4px 16px rgba(37,99,235,0.25);">
                Submit Your Application &rarr;
            </a>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
    document.body.classList.add('page-loaded');
    var els=document.querySelectorAll('.reveal');
    if('IntersectionObserver' in window){var obs=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting){e.target.classList.add('revealed');obs.unobserve(e.target);}});},{threshold:0.1});els.forEach(function(el){obs.observe(el);});}
    else{els.forEach(function(el){el.classList.add('revealed');});}
});
</script>
TPL;

$sharedMain = <<<'TPL'
<script>window.location.replace('index.php?m=careers&p=showAll');</script>
<div style="text-align:center;padding:120px 24px;"><p>Redirecting...</p></div>
TPL;

$sharedJS = <<<'JS'
<script>
(function(){
    document.addEventListener('DOMContentLoaded',function(){
        document.body.classList.add('page-loaded');
        var els=document.querySelectorAll('.reveal');
        if('IntersectionObserver' in window){
            var obs=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting){e.target.classList.add('revealed');obs.unobserve(e.target);}});},{threshold:0.08,rootMargin:'0px 0px -40px 0px'});
            els.forEach(function(el){obs.observe(el);});
        } else { els.forEach(function(el){el.classList.add('revealed');}); }
        var staggerContainers=document.querySelectorAll('.stagger-children');
        if('IntersectionObserver' in window){
            staggerContainers.forEach(function(container){
                var sobs=new IntersectionObserver(function(entries){
                    if(entries[0].isIntersecting){
                        var children=container.children;
                        for(var i=0;i<children.length;i++){(function(idx){setTimeout(function(){children[idx].classList.add('stagger-visible');},idx*120);})(i);}
                        sobs.unobserve(container);
                    }
                },{threshold:0.1});
                sobs.observe(container);
            });
        }
        var counters=document.querySelectorAll('.counter');
        counters.forEach(function(el){
            var target=parseInt(el.getAttribute('data-target'))||0;
            var suffix=el.getAttribute('data-suffix')||'';
            if(!target)return;
            el.textContent='0'+suffix;
            var cobs=new IntersectionObserver(function(entries){
                if(entries[0].isIntersecting){
                    var start=null,duration=1200;
                    function animate(ts){if(!start)start=ts;var p=Math.min((ts-start)/duration,1);var e=1-Math.pow(1-p,3);el.textContent=Math.floor(e*target)+suffix;if(p<1)requestAnimationFrame(animate);}
                    requestAnimationFrame(animate);cobs.unobserve(el);
                }
            },{threshold:0.5});
            cobs.observe(el);
        });
    });
})();
</script>
JS;


// ============================================================================
//  SHARED: Base CSS (form styles, common elements) — appended to each template
// ============================================================================

$sharedFormCSS = <<<'CSS'

/* ===== REVEALS ===== */
.reveal { opacity:0; transform:translateY(30px); transition: opacity 0.8s cubic-bezier(0.22,1,0.36,1), transform 0.8s cubic-bezier(0.22,1,0.36,1); }
.reveal.revealed { opacity:1; transform:translateY(0); }
.stagger-children > * { opacity:0; transform:translateY(20px); transition: opacity 0.6s ease, transform 0.6s ease; }
.stagger-children > *.stagger-visible { opacity:1; transform:translateY(0); }

/* ===== FORM STYLING ===== */
.form-section { background:#fff; border:1px solid var(--gray-200); border-radius:var(--radius-xl); padding:32px; margin-bottom:24px; transition:box-shadow 0.25s; }
.form-section:hover { box-shadow:var(--shadow-md); }
.form-section::before { display:none; }
.form-section h2 { font:700 18px var(--font); color:var(--gray-900); margin:0 0 24px; border:none; padding:0; }
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
.form-grid .full { grid-column:1/-1; }
label { font:600 13px var(--font); color:var(--gray-700); display:block; margin-bottom:6px; }
label .req { color:var(--danger); margin-left:2px; }
input.inputbox,input.inputBoxName,input.inputBoxNormal,input.inputBoxArea,
input.inputBoxFile,input#documentFile,input[type="text"],input[type="email"],input[type="tel"] {
    width:100%; padding:11px 16px; border:1px solid var(--gray-300); border-radius:var(--radius);
    font:400 14px var(--font); color:var(--gray-800); transition:all 0.25s; background:#fff; outline:none;
}
input.inputbox:focus,input.inputBoxName:focus,input.inputBoxNormal:focus,
input[type="text"]:focus,input[type="email"]:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
textarea,textarea.inputBoxArea,textarea.inputboxlarge {
    width:100%; padding:11px 16px; min-height:100px; border:1px solid var(--gray-300);
    border-radius:var(--radius); font:400 14px var(--font); color:var(--gray-800);
    resize:vertical; transition:all 0.25s; background:#fff; outline:none;
}
textarea:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
select,select.inputBoxNormal {
    width:100%; padding:11px 16px; border:1px solid var(--gray-300); border-radius:var(--radius);
    font:400 14px var(--font); color:var(--gray-800); background:#fff; outline:none; cursor:pointer;
    appearance:none; -webkit-appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M2 4l4 4 4-4'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 14px center; padding-right:36px; transition:all 0.25s;
}
select:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
input.submitbutton,input.submitButton,input[type="submit"] {
    padding:14px 40px; width:auto; min-width:240px; background:var(--primary); color:#fff;
    font:700 15px var(--font); border:none; border-radius:var(--radius); cursor:pointer;
    transition:all 0.25s; box-shadow:0 4px 16px rgba(37,99,235,0.25);
}
input.submitbutton:hover,input[type="submit"]:hover { background:var(--primary-dark); transform:translateY(-2px); }
div.applyBoxLeft,div.applyBoxRight { float:none; width:100%; max-width:100%; border:none; box-shadow:none; padding:0; background:transparent; margin:0; }
div.applyBoxLeft div,div.applyBoxRight div { display:none; }
div.applyBoxLeft table,div.applyBoxRight table { display:none; }
td.label { display:none; }
.progress-bar-bg { width:100%; height:4px; background:var(--gray-200); border-radius:2px; overflow:hidden; margin-bottom:24px; }
.progress-bar-fill { height:100%; width:0%; background:var(--primary); border-radius:2px; transition:width 0.5s; }

/* Apply page classes */
.step-indicator { display:flex; align-items:center; gap:0; padding:24px 0; border-bottom:1px solid var(--gray-200); margin-bottom:32px; overflow-x:auto; }
.step-item { display:flex; align-items:center; gap:10px; white-space:nowrap; flex:1; }
.step-item:not(:last-child)::after { content:''; flex:1; height:1px; background:var(--gray-200); margin:0 16px; min-width:24px; }
.step-number { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font:600 13px var(--font); flex-shrink:0; }
.step-number.active { background:var(--primary); color:#fff; }
.step-number.inactive { background:var(--gray-100); color:var(--gray-500); border:1.5px solid var(--gray-300); }
.step-number.completed { background:var(--success); color:#fff; }
.step-label { font:500 14px var(--font); color:var(--gray-700); }
.step-label.inactive { color:var(--gray-400); }
.apply-layout { display:grid; grid-template-columns:1fr 340px; gap:32px; align-items:start; }
.progress-section { display:flex; align-items:center; gap:12px; margin-bottom:8px; }
.progress-label { font:500 13px var(--font); color:var(--gray-500); white-space:nowrap; }
.form-section-header { display:flex; align-items:flex-start; gap:12px; margin-bottom:24px; }
.form-section-icon { width:40px; height:40px; background:var(--primary-50); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.form-section-icon svg { color:var(--primary); }
.form-section-title h2 { font:600 16px var(--font); color:var(--gray-900); margin-bottom:2px; }
.form-section-title p { font:400 13px var(--font); color:var(--gray-500); margin:0; }
.file-upload-area { border:2px dashed var(--gray-300); border-radius:var(--radius-lg); padding:32px 24px; text-align:center; transition:all 0.2s; cursor:pointer; background:var(--gray-50); }
.file-upload-area:hover { border-color:var(--primary); background:var(--primary-50); }
.file-upload-area .upload-icon { margin-bottom:8px; color:var(--gray-400); }
.file-upload-area p { font:400 14px var(--font); color:var(--gray-500); margin:0 0 4px; }
.file-upload-area .or-text { font:400 13px var(--font); color:var(--gray-400); margin:4px 0 12px; }
.choose-file-btn { display:inline-block; padding:8px 20px; background:var(--primary); color:#fff; font:600 13px var(--font); border-radius:6px; cursor:pointer; transition:all 0.2s; border:none; }
.choose-file-btn:hover { background:var(--primary-dark); }
.file-types { font:400 12px var(--font); color:var(--gray-400); margin-top:12px; }
input.inputBoxFile,input[type="file"] { display:none; }
.form-actions { display:flex; align-items:center; justify-content:space-between; padding:24px 0 48px; }
.btn-draft { display:inline-flex; align-items:center; gap:8px; padding:12px 24px; border:1px solid var(--gray-300); border-radius:var(--radius); background:#fff; color:var(--gray-700); font:600 14px var(--font); cursor:pointer; transition:all 0.2s; }
.btn-draft:hover { background:var(--gray-50); }
.btn-submit { display:inline-flex; align-items:center; gap:8px; padding:12px 32px; background:var(--primary); color:#fff; font:600 14px var(--font); border:none; border-radius:var(--radius); cursor:pointer; transition:all 0.2s; }
.btn-submit:hover { background:var(--primary-dark); }
.apply-sidebar { position:sticky; top:80px; }
.summary-card { background:#fff; border:1px solid var(--gray-200); border-radius:var(--radius-xl); padding:24px; }
.summary-card h3 { font:600 16px var(--font); color:var(--gray-900); margin-bottom:4px; }
.summary-card .summary-subtitle { font:400 13px var(--font); color:var(--gray-500); margin-bottom:20px; }
.summary-item { display:flex; align-items:flex-start; gap:12px; padding:12px 0; }
.summary-item:not(:last-child) { border-bottom:1px solid var(--gray-100); }
.summary-dot { width:20px; height:20px; border-radius:50%; border:2px solid var(--gray-300); flex-shrink:0; margin-top:2px; }
.summary-item-text h4 { font:500 14px var(--font); color:var(--gray-700); margin:0 0 2px; }
.summary-item-text p { font:400 12px var(--font); color:var(--gray-400); margin:0; }
.tip-box { margin-top:20px; padding:16px; background:var(--primary-50); border-radius:var(--radius-lg); border:1px solid var(--primary-light); }
.tip-box .tip-header { display:flex; align-items:center; gap:8px; margin-bottom:8px; }
.tip-box .tip-header svg { color:var(--primary); }
.tip-box .tip-header span { font:600 13px var(--font); color:var(--primary-dark); }
.tip-box p { font:400 13px/1.5 var(--font); color:var(--primary-dark); margin:0; }

/* Job detail */
.job-detail-card { background:#fff; border:1px solid var(--gray-200); border-radius:24px; padding:48px; box-shadow:var(--shadow-md); }
.job-tag { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:50px; font:600 13px var(--font); transition:all 0.25s; }
.job-tag:hover { transform:translateY(-2px); }
.job-tag.location { background:#eff6ff; color:#2563eb; }
.job-tag.type { background:#f0fdf4; color:#059669; }
.job-tag.time { background:#fefce8; color:#ca8a04; }
.job-tag.openings { background:#faf5ff; color:#7c3aed; }
#detailsTable { display:none; }
div#discriptive { float:none; width:100%; margin:0; }
div#detailsTools { display:none; }

/* Table */
table.sortable { width:100%; border-collapse:separate; border-spacing:0; background:#fff; border:1px solid var(--gray-200); border-radius:var(--radius-xl); overflow:hidden; box-shadow:var(--shadow-sm); }
tr.rowHeading { background:var(--gray-50) !important; }
tr.rowHeading th { padding:14px 24px; color:var(--gray-500); font:700 11px var(--font); text-transform:uppercase; letter-spacing:0.08em; border:none; border-bottom:1px solid var(--gray-200); text-align:left; background:transparent; }
tr.evenTableRow,tr.oddTableRow { background:#fff !important; transition:all 0.2s; cursor:pointer; }
tr.evenTableRow:hover,tr.oddTableRow:hover { background:var(--primary-50) !important; }
tr.evenTableRow td,tr.oddTableRow td { padding:18px 24px; border:none; border-bottom:1px solid var(--gray-100); font:500 14px var(--font); color:var(--gray-600); vertical-align:middle; background:transparent; }
tr.evenTableRow:last-child td,tr.oddTableRow:last-child td { border-bottom:none; }
tr.evenTableRow td a,tr.oddTableRow td a { color:var(--primary); font-weight:700; -webkit-text-fill-color:var(--primary); background:none; }

/* Generic */
h1 { font:800 32px var(--font); color:var(--gray-900); letter-spacing:-0.03em; margin:0 0 12px; background:none; -webkit-text-fill-color:var(--gray-900); }
h2 { font:700 24px var(--font); color:var(--gray-800); margin:0 0 12px; border:none; padding:0; }
h3 { font:600 16px var(--font); color:var(--gray-700); margin:0 0 6px; }
p { font:400 14px/1.7 var(--font); color:var(--gray-500); }
strong { font-weight:700; color:var(--gray-800); }
#careerContent { clear:both; padding:0; flex:1; }
#poweredCATS { display:none; }

/* Responsive */
@media (max-width:900px) {
    .apply-layout { grid-template-columns:1fr; }
    .apply-sidebar { position:static; }
    .form-grid { grid-template-columns:1fr; }
    .step-label { display:none; }
}
@media (max-width:600px) {
    .form-section { padding:20px; }
    .form-actions { flex-direction:column; gap:12px; }
}
CSS;


// ============================================================================
//  1. MODERN BLUE — Blue header bar, hero with wave, stats, cards, blue footer
// ============================================================================
echo "\n--- Installing Template 1: Modern Blue ---\n";

$modernBlueCSS = <<<'CSS'
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
:root {
    --primary:#2563eb; --primary-dark:#1d4ed8; --primary-darker:#1e40af;
    --primary-light:#dbeafe; --primary-50:#eff6ff;
    --accent:#06b6d4; --violet:#7c3aed; --emerald:#059669;
    --success:#16a34a; --success-light:#dcfce7; --warning:#f59e0b; --danger:#dc2626;
    --gray-50:#f8fafc; --gray-100:#f1f5f9; --gray-200:#e2e8f0; --gray-300:#cbd5e1;
    --gray-400:#94a3b8; --gray-500:#64748b; --gray-600:#475569; --gray-700:#334155;
    --gray-800:#1e293b; --gray-900:#0f172a;
    --font:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
    --radius:8px; --radius-lg:12px; --radius-xl:16px; --radius-2xl:24px;
    --shadow-sm:0 1px 2px rgba(0,0,0,0.05); --shadow-md:0 4px 12px rgba(0,0,0,0.06);
    --shadow-lg:0 12px 40px rgba(0,0,0,0.08); --shadow-xl:0 24px 60px rgba(0,0,0,0.1);
}
*,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
html { scroll-behavior:smooth; }
body,html { font-family:var(--font); color:var(--gray-800); background:#fff; line-height:1.6; -webkit-font-smoothing:antialiased; overflow-x:hidden; }
body { display:flex; flex-direction:column; min-height:100vh; opacity:0; transition:opacity 0.5s; }
body.page-loaded { opacity:1; }
a { color:var(--primary); text-decoration:none; transition:all 0.25s; }
a:hover { color:var(--primary-dark); }
a:visited { color:var(--primary); }
img { border:none; }

/* Header */
.career-header { background:var(--primary); position:sticky; top:0; z-index:1000; }
.header-inner { max-width:1200px; margin:0 auto; padding:0 32px; display:flex; align-items:center; justify-content:space-between; height:56px; }
.brand { display:flex; align-items:center; gap:10px; text-decoration:none !important; }
.brand-icon { width:32px; height:32px; background:rgba(255,255,255,0.2); border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:15px; }
.brand-text { font:600 16px var(--font); color:#fff; }
.brand-text span { font-weight:400; color:rgba(255,255,255,0.8); margin-left:4px; }
.header-nav { display:flex; align-items:center; gap:16px; }
.header-nav a { color:rgba(255,255,255,0.9) !important; font:500 14px var(--font); }
.header-nav a:hover { color:#fff !important; }

/* Hero */
.hero-banner { background:linear-gradient(160deg,#1e40af 0%,#2563eb 50%,#3b82f6 100%); padding:60px 40px 90px; text-align:center; position:relative; overflow:hidden; display:flex; flex-direction:column; align-items:center; justify-content:center; }
.hero-banner::after { content:''; position:absolute; bottom:0; left:0; right:0; height:40px; background:#fff; clip-path:ellipse(55% 100% at 50% 100%); z-index:3; }
.hero-banner h1 { font:800 42px var(--font) !important; color:#fff !important; letter-spacing:-0.03em; margin:0 0 12px; position:relative; z-index:2; -webkit-text-fill-color:#fff !important; background:none !important; line-height:1.2; max-width:600px; }
.hero-banner p { font:400 16px/1.6 var(--font); color:rgba(255,255,255,0.85); max-width:500px; margin:0 auto; position:relative; z-index:2; }

/* Stats */
.stats-row { max-width:900px; margin:0 auto 64px; padding:0 32px; display:grid; grid-template-columns:repeat(4,1fr); gap:8px; text-align:center; }
.stat-item { padding:24px 16px; }
.stat-icon { width:48px; height:48px; margin:0 auto 12px; display:flex; align-items:center; justify-content:center; color:var(--primary); }
.stat-num { font:800 28px var(--font); color:var(--gray-900); letter-spacing:-0.02em; display:block; margin-bottom:4px; }
.stat-label { font:500 12px var(--font); color:var(--gray-400); text-transform:uppercase; letter-spacing:0.06em; }

/* Section common */
.main-content { max-width:1100px; margin:0 auto; padding:0 32px; width:100%; }
.content-section { padding:48px 0; }
.section-center { text-align:center; margin-bottom:48px; }
.section-tag { display:inline-flex; align-items:center; gap:6px; padding:6px 16px; border-radius:50px; background:var(--primary-50); color:var(--primary); font:700 11px var(--font); text-transform:uppercase; letter-spacing:0.1em; margin-bottom:16px; border:1px solid var(--primary-light); }
.section-title { font:800 32px var(--font); color:var(--gray-900); letter-spacing:-0.03em; margin-bottom:12px; line-height:1.2; }
.section-subtitle { font:400 15px/1.7 var(--font); color:var(--gray-500); max-width:560px; margin:0 auto; }

/* Feature cards */
.cards-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:24px; margin-bottom:48px; }
.feature-card { background:#fff; border:1px solid var(--gray-200); border-radius:var(--radius-xl); padding:32px 24px; text-align:center; transition:all 0.35s; }
.feature-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-lg); border-color:transparent; }
.feature-card-icon { width:56px; height:56px; margin:0 auto 20px; display:flex; align-items:center; justify-content:center; color:var(--primary); }
.feature-card h3 { font:700 16px var(--font); color:var(--gray-900); margin:0 0 10px; }
.feature-card p { font:400 13px/1.6 var(--font); color:var(--gray-500); margin:0; }

/* Footer */
.career-footer { background:var(--primary); margin-top:auto; }
.footer-inner { max-width:1200px; margin:0 auto; padding:0 32px; display:flex; align-items:center; justify-content:space-between; height:56px; }
.footer-inner .brand-text { color:#fff; font:600 15px var(--font); }
.footer-inner .brand-text span { font-weight:400; color:rgba(255,255,255,0.8); }
.footer-inner a { color:rgba(255,255,255,0.9) !important; font:500 14px var(--font); }
.career-copyright { background:var(--gray-50); text-align:center; padding:16px 32px; font:400 13px var(--font); color:var(--gray-400); border-top:1px solid var(--gray-200); }

@media (max-width:900px) { .cards-grid { grid-template-columns:repeat(2,1fr); } .stats-row { grid-template-columns:repeat(2,1fr); } .hero-banner { padding:40px 20px 70px; } }
@media (max-width:600px) { .hero-banner h1 { font-size:28px !important; } .cards-grid { grid-template-columns:1fr; } .header-inner,.main-content,.footer-inner { padding:0 16px; } .section-title { font-size:24px; } }
CSS;

$modernBlueSearch = <<<'TPL'
<div class="hero-banner">
    <h1>Join Our Team</h1>
    <p>Explore open positions and find your next opportunity.</p>
</div>
<div class="stats-row reveal">
    <div class="stat-item">
        <div class="stat-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></div>
        <span class="stat-num counter" data-target="10" data-suffix="+">0</span>
        <span class="stat-label">Team Members</span>
    </div>
    <div class="stat-item">
        <div class="stat-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg></div>
        <span class="stat-num counter" data-target="12" data-suffix="+">0</span>
        <span class="stat-label">Countries</span>
    </div>
    <div class="stat-item">
        <div class="stat-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        <span class="stat-num counter" data-target="98" data-suffix="%">0</span>
        <span class="stat-label">Satisfaction</span>
    </div>
    <div class="stat-item">
        <div class="stat-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
        <span class="stat-num">4.8&#9733;</span>
        <span class="stat-label">Glassdoor</span>
    </div>
</div>
<div class="main-content">
    <div class="content-section">
        <div class="section-center reveal">
            <span class="section-tag">&#10024; Why Join Us</span>
            <h2 class="section-title">Where Great Careers Are Built</h2>
            <p class="section-subtitle">We create a launchpad for extraordinary careers with world-class benefits.</p>
        </div>
        <div class="cards-grid stagger-children">
            <div class="feature-card">
                <div class="feature-card-icon"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg></div>
                <h3>Continuous Learning</h3>
                <p>Annual learning budgets, conference sponsorships, and premium platforms for every team member.</p>
            </div>
            <div class="feature-card">
                <div class="feature-card-icon"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 20V10M12 20V4M6 20v-6"/></svg></div>
                <h3>Rapid Growth</h3>
                <p>Clear advancement paths with quarterly reviews and dedicated mentorship from senior leaders.</p>
            </div>
            <div class="feature-card">
                <div class="feature-card-icon"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg></div>
                <h3>Remote First</h3>
                <p>Work from anywhere. Great work happens when you have freedom to choose your environment.</p>
            </div>
            <div class="feature-card">
                <div class="feature-card-icon"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
                <h3>Premium Benefits</h3>
                <p>Top-tier health coverage, equity packages, unlimited PTO, and family-friendly policies.</p>
            </div>
        </div>
    </div>
    <div class="content-section" id="positions" style="scroll-margin-top:80px;">
        <div class="section-center reveal">
            <span class="section-tag">&#128188; Open Roles</span>
            <h2 class="section-title">Find Your Perfect Role</h2>
            <p class="section-subtitle"><numberOfSearchResults> position(s) currently available.</p>
        </div>
        <div class="reveal"><searchResultsTableUnformatted></div>
    </div>
</div>
TPL;
$modernBlueSearch .= $sharedJS;

insertTemplate($pdo, $insStmt, 'Modern Blue', [
    'CSS' => $modernBlueCSS . $sharedFormCSS,
    'Header' => '<div class="career-header"><div class="header-inner"><a href="index.php?m=careers" class="brand"><div class="brand-icon">N</div><div class="brand-text">default_site<span>Careers</span></div></a><nav class="header-nav"><a href="index.php?m=careers&p=showAll">All Jobs</a></nav></div></div>',
    'Footer' => '<div class="career-footer"><div class="footer-inner"><div style="display:flex;align-items:center;gap:10px;"><div class="brand-icon">N</div><div class="brand-text">default_site<span>Careers</span></div></div><a href="index.php?m=careers&p=showAll">All Jobs</a></div></div><div class="career-copyright">&copy; 2026 default_site &mdash; Powered by Neutara ATS</div>',
    'Content - Main' => $sharedMain,
    'Content - Search Results' => $modernBlueSearch,
    'Content - Job Details' => $sharedJobDetails,
    'Content - Apply for Position' => $sharedApply,
    'Content - Thanks for your Submission' => $sharedThanks,
    'Content - Questionnaire' => '<div style="max-width:800px;margin:0 auto;padding:40px 32px;"><questionnaire><br><br><div style="text-align:right;"><submit value="Continue"></div></div>',
    'Content - Candidate Registration' => '',
    'Content - Candidate Profile' => '',
    'Left' => ''
]);


// ============================================================================
//  2. PREMIUM — White nav, hero illustration, features, testimonials, dark footer
// ============================================================================
echo "\n--- Installing Template 2: Premium ---\n";

// Read existing V7 template data from Blank Page
$readStmt = $pdo->prepare("SELECT setting, value FROM career_portal_template WHERE career_portal_name = 'Blank Page'");
$readStmt->execute();
$blankPageData = [];
while ($row = $readStmt->fetch(PDO::FETCH_ASSOC)) {
    $blankPageData[$row['setting']] = $row['value'];
}

// The V7 Premium design is currently deployed in Blank Page — copy it as "Premium" built-in
$premiumSettings = [
    'CSS' => $blankPageData['CSS'] ?? '',
    'Header' => $blankPageData['Header'] ?? '',
    'Footer' => $blankPageData['Footer'] ?? '',
    'Content - Main' => $blankPageData['Content - Main'] ?? $sharedMain,
    'Content - Search Results' => $blankPageData['Content - Search Results'] ?? '',
    'Content - Job Details' => $blankPageData['Content - Job Details'] ?? $sharedJobDetails,
    'Content - Apply for Position' => $blankPageData['Content - Apply for Position'] ?? $sharedApply,
    'Content - Thanks for your Submission' => $blankPageData['Content - Thanks for your Submission'] ?? $sharedThanks,
    'Content - Questionnaire' => '<div style="max-width:800px;margin:0 auto;padding:40px 32px;"><questionnaire><br><br><div style="text-align:right;"><submit value="Continue"></div></div>',
    'Content - Candidate Registration' => '',
    'Content - Candidate Profile' => '',
    'Left' => ''
];

insertTemplate($pdo, $insStmt, 'Premium', $premiumSettings);


// ============================================================================
//  3. STARTER — Minimal clean design for easy customization
// ============================================================================
echo "\n--- Installing Template 3: Starter ---\n";

$starterCSS = <<<'CSS'
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
:root {
    --primary:#3b82f6; --primary-dark:#2563eb; --primary-darker:#1d4ed8;
    --primary-light:#dbeafe; --primary-50:#eff6ff;
    --success:#16a34a; --success-light:#dcfce7; --danger:#dc2626;
    --gray-50:#f8fafc; --gray-100:#f1f5f9; --gray-200:#e2e8f0; --gray-300:#cbd5e1;
    --gray-400:#94a3b8; --gray-500:#64748b; --gray-600:#475569; --gray-700:#334155;
    --gray-800:#1e293b; --gray-900:#0f172a;
    --font:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
    --radius:8px; --radius-lg:12px; --radius-xl:16px; --radius-2xl:24px;
    --shadow-sm:0 1px 2px rgba(0,0,0,0.05); --shadow-md:0 4px 12px rgba(0,0,0,0.06);
    --shadow-lg:0 12px 40px rgba(0,0,0,0.08);
}
*,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
html { scroll-behavior:smooth; }
body,html { font-family:var(--font); color:var(--gray-800); background:#fff; line-height:1.6; -webkit-font-smoothing:antialiased; overflow-x:hidden; }
body { display:flex; flex-direction:column; min-height:100vh; opacity:0; transition:opacity 0.5s; }
body.page-loaded { opacity:1; }
a { color:var(--primary); text-decoration:none; transition:all 0.2s; }
a:hover { color:var(--primary-dark); }
a:visited { color:var(--primary); }
img { border:none; }

/* Header */
.career-header { background:#fff; border-bottom:1px solid var(--gray-200); position:sticky; top:0; z-index:1000; }
.header-inner { max-width:960px; margin:0 auto; padding:0 24px; display:flex; align-items:center; justify-content:space-between; height:60px; }
.brand { display:flex; align-items:center; gap:8px; text-decoration:none !important; }
.brand-icon { width:28px; height:28px; background:var(--primary); border-radius:6px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size:14px; }
.brand-text { font:700 16px var(--font); color:var(--gray-900); }
.header-nav a { font:500 14px var(--font); color:var(--gray-600) !important; }
.header-nav a:hover { color:var(--gray-900) !important; }

/* Page hero (simple) */
.page-hero { padding:48px 24px; text-align:center; background:var(--gray-50); border-bottom:1px solid var(--gray-200); }
.page-hero h1 { font:800 36px var(--font) !important; color:var(--gray-900) !important; -webkit-text-fill-color:var(--gray-900) !important; background:none !important; margin:0 0 8px; }
.page-hero p { font:400 16px var(--font); color:var(--gray-500); max-width:480px; margin:0 auto; }

/* Content */
.content-wrap { max-width:960px; margin:0 auto; padding:48px 24px; }
.result-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; }
.result-count { font:600 14px var(--font); color:var(--gray-500); }

/* Footer */
.career-footer { background:var(--gray-50); border-top:1px solid var(--gray-200); margin-top:auto; text-align:center; padding:24px; }
.career-footer p { font:400 13px var(--font); color:var(--gray-400); margin:0; }

@media (max-width:600px) { .page-hero h1 { font-size:26px !important; } .content-wrap { padding:32px 16px; } .header-inner { padding:0 16px; } }
CSS;

$starterSearch = <<<'TPL'
<div class="page-hero">
    <h1>Open Positions</h1>
    <p>Browse our current openings and apply today.</p>
</div>
<div class="content-wrap">
    <div class="result-header reveal">
        <span class="result-count"><numberOfSearchResults> position(s) available</span>
    </div>
    <div class="reveal"><searchResultsTableUnformatted></div>
</div>
TPL;
$starterSearch .= $sharedJS;

insertTemplate($pdo, $insStmt, 'Starter', [
    'CSS' => $starterCSS . $sharedFormCSS,
    'Header' => '<div class="career-header"><div class="header-inner"><a href="index.php?m=careers" class="brand"><div class="brand-icon">N</div><div class="brand-text">default_site</div></a><nav class="header-nav"><a href="index.php?m=careers&p=showAll">Jobs</a></nav></div></div>',
    'Footer' => '<div class="career-footer"><p>&copy; 2026 default_site &mdash; All rights reserved</p></div>',
    'Content - Main' => $sharedMain,
    'Content - Search Results' => $starterSearch,
    'Content - Job Details' => $sharedJobDetails,
    'Content - Apply for Position' => $sharedApply,
    'Content - Thanks for your Submission' => $sharedThanks,
    'Content - Questionnaire' => '<div style="max-width:800px;margin:0 auto;padding:40px 24px;"><questionnaire><br><br><div style="text-align:right;"><submit value="Continue"></div></div>',
    'Content - Candidate Registration' => '',
    'Content - Candidate Profile' => '',
    'Left' => ''
]);


// ============================================================================
//  4. CORPORATE — Dark professional theme with emerald accents
// ============================================================================
echo "\n--- Installing Template 4: Corporate ---\n";

$corporateCSS = <<<'CSS'
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
:root {
    --primary:#059669; --primary-dark:#047857; --primary-darker:#065f46;
    --primary-light:#d1fae5; --primary-50:#ecfdf5;
    --accent:#0891b2; --violet:#7c3aed; --emerald:#059669;
    --success:#16a34a; --success-light:#dcfce7; --warning:#f59e0b; --danger:#dc2626;
    --gray-50:#f8fafc; --gray-100:#f1f5f9; --gray-200:#e2e8f0; --gray-300:#cbd5e1;
    --gray-400:#94a3b8; --gray-500:#64748b; --gray-600:#475569; --gray-700:#334155;
    --gray-800:#1e293b; --gray-900:#0f172a;
    --font:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
    --radius:8px; --radius-lg:12px; --radius-xl:16px; --radius-2xl:24px;
    --shadow-sm:0 1px 2px rgba(0,0,0,0.05); --shadow-md:0 4px 12px rgba(0,0,0,0.06);
    --shadow-lg:0 12px 40px rgba(0,0,0,0.08); --shadow-xl:0 24px 60px rgba(0,0,0,0.1);
}
*,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
html { scroll-behavior:smooth; }
body,html { font-family:var(--font); color:var(--gray-800); background:#fff; line-height:1.6; -webkit-font-smoothing:antialiased; overflow-x:hidden; }
body { display:flex; flex-direction:column; min-height:100vh; opacity:0; transition:opacity 0.5s; }
body.page-loaded { opacity:1; }
a { color:var(--primary); text-decoration:none; transition:all 0.25s; }
a:hover { color:var(--primary-dark); }
a:visited { color:var(--primary); }
img { border:none; }

/* Header */
.career-header { background:var(--gray-900); position:sticky; top:0; z-index:1000; }
.header-inner { max-width:1200px; margin:0 auto; padding:0 32px; display:flex; align-items:center; justify-content:space-between; height:64px; }
.brand { display:flex; align-items:center; gap:10px; text-decoration:none !important; }
.brand-icon { width:36px; height:36px; background:var(--primary); border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size:16px; }
.brand-text { font:700 18px var(--font); color:#fff; }
.header-nav { display:flex; align-items:center; gap:24px; }
.header-nav a { color:rgba(255,255,255,0.7) !important; font:500 14px var(--font); transition:color 0.2s; }
.header-nav a:hover { color:#fff !important; }
.header-actions { display:flex; gap:12px; }
.btn-primary-sm { display:inline-flex; align-items:center; gap:6px; padding:10px 22px; background:var(--primary); color:#fff !important; font:600 14px var(--font); border-radius:var(--radius); transition:all 0.25s; }
.btn-primary-sm:hover { background:var(--primary-dark); transform:translateY(-1px); }

/* Hero */
.hero-banner { background:linear-gradient(160deg,#0f172a 0%,#1e293b 40%,#1e3a5f 100%); padding:80px 40px; text-align:center; position:relative; overflow:hidden; }
.hero-banner::before { content:''; position:absolute; top:0; left:0; right:0; bottom:0; background:radial-gradient(circle at 70% 30%,rgba(5,150,105,0.15) 0%,transparent 50%); }
.hero-banner h1 { font:900 48px var(--font) !important; color:#fff !important; -webkit-text-fill-color:#fff !important; background:none !important; letter-spacing:-0.04em; margin:0 0 16px; line-height:1.1; position:relative; z-index:2; max-width:700px; display:inline-block; }
.hero-banner h1 .highlight { color:#34d399; -webkit-text-fill-color:#34d399; }
.hero-banner p { font:400 18px/1.7 var(--font); color:rgba(255,255,255,0.6); max-width:540px; margin:0 auto 32px; position:relative; z-index:2; }
.hero-cta { display:flex; gap:16px; justify-content:center; position:relative; z-index:2; flex-wrap:wrap; }
.hero-cta .btn-hero { display:inline-flex; align-items:center; gap:8px; padding:14px 36px; font:700 15px var(--font); border-radius:var(--radius-lg); transition:all 0.25s; }
.hero-cta .btn-hero.primary { background:var(--primary); color:#fff !important; box-shadow:0 4px 16px rgba(5,150,105,0.3); }
.hero-cta .btn-hero.primary:hover { background:#047857; transform:translateY(-2px); }
.hero-cta .btn-hero.outline { background:transparent; color:#fff !important; border:2px solid rgba(255,255,255,0.2); }
.hero-cta .btn-hero.outline:hover { border-color:rgba(255,255,255,0.4); background:rgba(255,255,255,0.05); }

/* Stats */
.stats-bar { background:var(--gray-50); border-bottom:1px solid var(--gray-200); }
.stats-inner { max-width:1000px; margin:0 auto; padding:0 32px; display:grid; grid-template-columns:repeat(4,1fr); text-align:center; }
.stat-block { padding:28px 16px; border-right:1px solid var(--gray-200); }
.stat-block:last-child { border-right:none; }
.stat-block .num { font:800 28px var(--font); color:var(--gray-900); display:block; margin-bottom:4px; }
.stat-block .label { font:500 12px var(--font); color:var(--gray-400); text-transform:uppercase; letter-spacing:0.08em; }

/* Content */
.main-content { max-width:1100px; margin:0 auto; padding:0 32px; width:100%; }
.content-section { padding:56px 0; }
.section-center { text-align:center; margin-bottom:48px; }
.section-tag { display:inline-flex; align-items:center; gap:6px; padding:6px 16px; border-radius:50px; background:var(--primary-50); color:var(--primary); font:700 11px var(--font); text-transform:uppercase; letter-spacing:0.1em; margin-bottom:16px; border:1px solid var(--primary-light); }
.section-title { font:800 32px var(--font); color:var(--gray-900); letter-spacing:-0.03em; margin-bottom:12px; line-height:1.2; }
.section-subtitle { font:400 15px/1.7 var(--font); color:var(--gray-500); max-width:560px; margin:0 auto; }

/* Cards */
.cards-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
.info-card { background:#fff; border:1px solid var(--gray-200); border-radius:var(--radius-xl); padding:32px; transition:all 0.35s; }
.info-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-lg); border-color:transparent; }
.info-card-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:20px; }
.info-card-icon.green { background:var(--primary-50); color:var(--primary); }
.info-card-icon.blue { background:#eff6ff; color:#2563eb; }
.info-card-icon.purple { background:#faf5ff; color:#7c3aed; }
.info-card h3 { font:700 17px var(--font); color:var(--gray-900); margin:0 0 8px; }
.info-card p { font:400 14px/1.6 var(--font); color:var(--gray-500); margin:0; }

/* Footer */
.career-footer { background:var(--gray-900); margin-top:auto; }
.footer-main { max-width:1200px; margin:0 auto; padding:48px 32px; display:grid; grid-template-columns:1.5fr 1fr 1fr; gap:48px; }
.footer-brand .brand-text { color:#fff; font:700 18px var(--font); margin-bottom:12px; display:block; }
.footer-brand p { font:400 13px/1.7 var(--font); color:var(--gray-400); max-width:260px; }
.footer-col h4 { font:700 13px var(--font); color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:16px; }
.footer-col a { display:block; font:500 14px var(--font); color:var(--gray-400) !important; margin-bottom:10px; }
.footer-col a:hover { color:#fff !important; }
.footer-bottom { max-width:1200px; margin:0 auto; padding:20px 32px; border-top:1px solid rgba(255,255,255,0.06); display:flex; justify-content:space-between; align-items:center; font:400 13px var(--font); color:var(--gray-500); }
.footer-legal a { font:500 13px var(--font); color:var(--gray-500) !important; margin-left:24px; }
.footer-legal a:hover { color:#fff !important; }

@media (max-width:900px) { .cards-grid { grid-template-columns:1fr; } .stats-inner { grid-template-columns:repeat(2,1fr); } .footer-main { grid-template-columns:1fr; } .hero-banner { padding:48px 24px; } }
@media (max-width:600px) { .hero-banner h1 { font-size:30px !important; } .hero-cta { flex-direction:column; align-items:center; } .header-nav,.header-actions { display:none; } .main-content { padding:0 16px; } .footer-bottom { flex-direction:column; gap:12px; text-align:center; } }
CSS;

$corporateSearch = <<<'TPL'
<div class="hero-banner">
    <h1>Shape the Future of <span class="highlight">Innovation</span></h1>
    <p>Join an award-winning team building products that impact millions. Discover your next chapter.</p>
    <div class="hero-cta">
        <a href="#positions" class="btn-hero primary">Explore Opportunities</a>
        <a href="#culture" class="btn-hero outline">Learn About Us</a>
    </div>
</div>
<div class="stats-bar reveal">
    <div class="stats-inner">
        <div class="stat-block"><span class="num counter" data-target="500" data-suffix="+">0</span><span class="label">Employees</span></div>
        <div class="stat-block"><span class="num counter" data-target="20" data-suffix="+">0</span><span class="label">Countries</span></div>
        <div class="stat-block"><span class="num counter" data-target="95" data-suffix="%">0</span><span class="label">Retention Rate</span></div>
        <div class="stat-block"><span class="num">4.9&#9733;</span><span class="label">Employee Rating</span></div>
    </div>
</div>
<div class="main-content" id="culture">
    <div class="content-section">
        <div class="section-center reveal">
            <span class="section-tag">&#127942; Culture &amp; Values</span>
            <h2 class="section-title">Why Top Talent Chooses Us</h2>
            <p class="section-subtitle">We invest in our people, foster innovation, and build a culture of excellence and trust.</p>
        </div>
        <div class="cards-grid stagger-children">
            <div class="info-card">
                <div class="info-card-icon green"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                <h3>Trust &amp; Transparency</h3>
                <p>Open communication, honest feedback, and decisions rooted in shared values define how we work.</p>
            </div>
            <div class="info-card">
                <div class="info-card-icon blue"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg></div>
                <h3>Innovation First</h3>
                <p>Hackathons, R&amp;D time, and a bold product vision ensure you work on problems that matter.</p>
            </div>
            <div class="info-card">
                <div class="info-card-icon purple"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></div>
                <h3>World-Class Team</h3>
                <p>Work alongside industry leaders and brilliant minds from top companies around the globe.</p>
            </div>
        </div>
    </div>
    <div class="content-section" id="positions" style="scroll-margin-top:80px;">
        <div class="section-center reveal">
            <span class="section-tag">&#128640; Open Roles</span>
            <h2 class="section-title">Current Opportunities</h2>
            <p class="section-subtitle"><numberOfSearchResults> position(s) waiting for the right candidate.</p>
        </div>
        <div class="reveal"><searchResultsTableUnformatted></div>
    </div>
</div>
TPL;
$corporateSearch .= $sharedJS;

insertTemplate($pdo, $insStmt, 'Corporate', [
    'CSS' => $corporateCSS . $sharedFormCSS,
    'Header' => '<div class="career-header"><div class="header-inner"><a href="index.php?m=careers" class="brand"><div class="brand-icon">N</div><div class="brand-text">default_site</div></a><nav class="header-nav"><a href="index.php?m=careers&p=showAll">Careers</a><a href="#culture">Culture</a><a href="#positions">Positions</a></nav><div class="header-actions"><a href="index.php?m=careers&p=showAll#positions" class="btn-primary-sm">Apply Now</a></div></div></div>',
    'Footer' => '<div class="career-footer"><div class="footer-main"><div class="footer-brand"><span class="brand-text">default_site</span><p>Building the future of technology, one great hire at a time.</p></div><div class="footer-col"><h4>Company</h4><a href="index.php?m=careers&p=showAll">Careers</a><a href="#">About</a><a href="#">Blog</a></div><div class="footer-col"><h4>Support</h4><a href="#">Contact</a><a href="#">FAQ</a><a href="#">Privacy</a></div></div><div class="footer-bottom"><span>&copy; 2026 default_site. All rights reserved.</span><div class="footer-legal"><a href="#">Privacy Policy</a><a href="#">Terms of Service</a></div></div></div>',
    'Content - Main' => $sharedMain,
    'Content - Search Results' => $corporateSearch,
    'Content - Job Details' => $sharedJobDetails,
    'Content - Apply for Position' => $sharedApply,
    'Content - Thanks for your Submission' => $sharedThanks,
    'Content - Questionnaire' => '<div style="max-width:800px;margin:0 auto;padding:40px 32px;"><questionnaire><br><br><div style="text-align:right;"><submit value="Continue"></div></div>',
    'Content - Candidate Registration' => '',
    'Content - Candidate Profile' => '',
    'Left' => ''
]);


// ============================================================================
//  Done!
// ============================================================================
echo "\n========================================\n";
echo "  Built-in Templates Installed!\n";
echo "========================================\n";
echo "Templates added:\n";
echo "  1. Modern Blue  — Blue header, hero with wave, stats & feature cards\n";
echo "  2. Premium      — White nav, hero illustration, testimonials, dark footer\n";
echo "  3. Starter      — Clean minimal design for easy customization\n";
echo "  4. Corporate    — Dark professional theme with emerald accents\n";
echo "\nGo to Settings > Career Portal to select a template.\n";
