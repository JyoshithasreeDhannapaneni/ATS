<?php TemplateUtility::printHeader("Email History", array()); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
<div id="main">
  <?php TemplateUtility::printQuickSearch(); ?>
  <div id="contents">
    <table width="100%"><tr>
      <td><h2>Email History Log</h2></td>
      <td align="right" style="font-size:12px;color:#666;">Total: <?php echo (int)$this->totalEmails; ?> emails sent</td>
    </tr></table>
    <p style="margin-bottom:8px;">
      <a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&amp;a=emailLog" style="color:#4172E3;">&#8635; Refresh</a>
      &nbsp;&nbsp;<a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings" style="color:#666;">&larr; Back to Settings</a>
    </p>
    <?php if (empty($this->emailLogRS)): ?>
      <p style="padding:20px;color:#666;">No emails have been sent yet.</p>
    <?php else: ?>
    <table class="dataTable" cellspacing="0" cellpadding="4" width="100%">
      <thead><tr>
        <th style="width:140px">Date &amp; Time</th>
        <th style="width:120px">Sent By</th>
        <th style="width:180px">To (Recipient)</th>
        <th>Subject</th>
        <th style="width:70px">Candidate</th>
      </tr></thead>
      <tbody>
      <?php $i=0; foreach ($this->emailLogRS as $row): $i++; ?>
      <tr class="<?php echo ($i%2==0)?"tblRowColor":"tblRowColorAlt"; ?>">
        <td><?php echo htmlspecialchars((string)($row["date"] ?? "")); ?></td>
        <td><?php echo htmlspecialchars((string)($row["sent_by"] ?? "System")); ?></td>
        <td style="font-size:11px;"><?php echo htmlspecialchars((string)($row["recipients"] ?? "")); ?></td>
        <td><?php echo htmlspecialchars((string)($row["subject"] ?? "(no subject)")); ?></td>
        <td><?php if (!empty($row["candidate_id"])): ?><a href="<?php echo CATSUtility::getIndexName(); ?>?m=candidates&amp;a=show&amp;candidateID=<?php echo (int)$row["candidate_id"]; ?>">View</a><?php endif; ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <div style="padding:8px;text-align:center;font-size:12px;">
      <?php if ($this->currentPage > 1): ?><a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&amp;a=emailLog&amp;page=<?php echo $this->currentPage-1; ?>">&laquo; Previous</a>&nbsp;&nbsp;<?php endif; ?>
      Page <?php echo $this->currentPage; ?> of <?php echo $this->totalPages; ?>
      <?php if ($this->currentPage < $this->totalPages): ?>&nbsp;&nbsp;<a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&amp;a=emailLog&amp;page=<?php echo $this->currentPage+1; ?>">Next &raquo;</a><?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php TemplateUtility::printFooter(); ?>
