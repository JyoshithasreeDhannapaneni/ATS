<?php /* $Id: ErrorModal.tpl 652 2006-08-25 02:45:43Z will $ */ ?>
<?php TemplateUtility::printModalHeader('Import'); ?>

<div class="form-container">
    <div class="form-header">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <h2>Import: Error</h2>
    </div>
    <div class="form-section">
        <p class="fatalError">
            A fatal error has occurred.<br />
            <br />
            <?php echo($this->errorMessage); ?>
        </p>
    </div>
</div>
    </body>
</html>
