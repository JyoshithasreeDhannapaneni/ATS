<?php /* $Id: NoCookiesModal.tpl 1927 2007-02-22 06:03:24Z will $ */ ?>
<?php TemplateUtility::printModalHeader('Login'); ?>

<div class="form-container">
    <div class="form-header">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <h2>Cookies Required</h2>
    </div>
    <div class="form-section" style="text-align: center;">
        <p>Cookies are not enabled on your browser. This application requires cookies in order to log in.</p>
        <p>Please enable cookies in your web browser, then revisit this page.</p>
    </div>
    <div class="form-actions" style="justify-content: center;">
        <button type="button" class="form-btn form-btn-primary" onclick="parentGoToURL(parent.document.location.href);">Retry</button>
    </div>
</div>
    </body>
</html>
