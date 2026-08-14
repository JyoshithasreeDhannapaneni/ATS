<?php /* $Id: CreateAttachmentModal.tpl 3093 2007-09-24 21:09:45Z brian $ */ ?>
<?php TemplateUtility::printModalHeader('Candidates', array(''), 'Assign candidate tag'); ?>

<style>
    .tag-tree, .tag-tree ul { list-style: none; margin: 0; padding-left: var(--space-4); }
    .tag-tree { padding-left: 0; }
    .tag-tree li { padding: 4px 0; }
    .tag-tree label { cursor: pointer; font-size: 13px; color: var(--gray-800); margin-left: 6px; }
</style>

    <?php if (!$this->isFinishedMode): ?>
		<form class="changeCandidateTags" id="changeCandidateTags" method="post" action="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=addCandidateTags">
			<input type="hidden" name="postback" id="postback" value="postback" />
			<input type="hidden" id="candidateID" name="candidateID" value="<?php echo($this->candidateID); ?>" />

            <div class="form-container">
                <div class="form-section">
                    <ul class="tag-tree">
                    <?php $i=1;

                    function drw($data, $id, $assignedTags){
                        //global $i;
                        foreach($data as $k => $v){
                            if ($v['tag_parent_id'] == $id){
                                ?><li><input type="checkbox" name="candidate_tags[]" id="checkbox<?= $i ?>" value="<?= $v['tag_id'] ?>" <?= in_array($v['tag_id'], $assignedTags)?'checked="checked"':''; ?>><label for="checkbox<?= $i++ ?>"><?= $v['tag_title'] ?></label></li><?php
                                echo "\n<ul>" ;
                                drw($data, $v['tag_id'],$assignedTags);
                                echo "\n</ul>";
                            }
                        }
                    }
                    drw($this->tagsRS, '', $this->assignedTags);
                    ?>
                    </ul>
                </div>
                <div class="form-actions">
                    <button type="submit" class="form-btn form-btn-primary" name="submit" id="submit">Save</button>
                    <button type="button" class="form-btn form-btn-secondary" name="cancel" onclick="parentHidePopWin();">Cancel</button>
                </div>
            </div>
		</form>
    <?php else: ?>
        <div class="form-container">
            <div class="form-section">
                <p>All data has been saved</p>
            </div>
            <div class="form-actions">
                <button type="button" class="form-btn form-btn-primary" name="close" onclick="parentHidePopWinRefresh();">Close</button>
            </div>
        </div>
    <?php endif; ?>
    </body>
</html>
