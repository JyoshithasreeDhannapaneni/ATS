<?php /* AI Integration settings — API key(s) used to generate job descriptions. */ ?>
<?php TemplateUtility::printHeader('Settings'); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <table>
                <tr>
                    <td width="3%">
                        <img src="images/settings.gif" width="24" height="24" alt="Settings" style="border: none; margin-top: 3px;" />&nbsp;
                    </td>
                    <td><h2>Settings: Administration</h2></td>
                </tr>
            </table>

            <p class="note">AI Integration — used to generate job descriptions from a few keywords on the Add/Edit Job Order form.</p>

            <table class="searchTable" width="100%">
                <tr>
                    <td>
                        <form action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=administration" id="aiSettingsForm" method="post" autocomplete="off">
                            <input type="hidden" name="postback" value="postback" />
                            <input type="hidden" name="administrationMode" value="saveAiSettings" />

                            <label for="aiProvider">Active Provider:</label>
                            <br />
                            <select name="aiProvider" id="aiProvider" class="selectBox">
                                <option value="anthropic"<?php if ($this->aiProvider == 'anthropic'): ?> selected="selected"<?php endif; ?>>Anthropic (Claude)</option>
                                <option value="openai"<?php if ($this->aiProvider == 'openai'): ?> selected="selected"<?php endif; ?>>OpenAI (GPT)</option>
                            </select>
                            <br /><br />

                            <label id="anthropicApiKeyLabel" for="anthropicApiKey">Anthropic API Key:</label>
                            <br />
                            <input type="password" name="anthropicApiKey" id="anthropicApiKey" value="<?php echo($this->anthropicApiKeyMasked); ?>" style="width:350px;" placeholder="sk-ant-..." /><br />
                            <span style="font-size: 11px; color: #666;">
                                <?php if ($this->anthropicApiKeyConfigured): ?>
                                    A key is currently configured. Leave unchanged to keep it, or enter a new one to replace it.
                                <?php else: ?>
                                    No key configured yet.
                                <?php endif; ?>
                            </span>
                            <br /><br />

                            <label id="openaiApiKeyLabel" for="openaiApiKey">OpenAI API Key:</label>
                            <br />
                            <input type="password" name="openaiApiKey" id="openaiApiKey" value="<?php echo($this->openaiApiKeyMasked); ?>" style="width:350px;" placeholder="sk-..." /><br />
                            <span style="font-size: 11px; color: #666;">
                                <?php if ($this->openaiApiKeyConfigured): ?>
                                    A key is currently configured. Leave unchanged to keep it, or enter a new one to replace it.
                                <?php else: ?>
                                    No key configured yet.
                                <?php endif; ?>
                            </span>
                            <br /><br />

                            <span style="font-size: 11px; color: #666;">
                                "Generate with AI" uses whichever provider is selected above; it shows a friendly notice on the Add/Edit Job Order form until that provider's key is saved here.
                            </span>
                            <br /><br />

                            <input type="submit" name="save" class="button" value="Save" />
                            <input type="button" name="back" class="button" value="Back" onclick="document.location.href='<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=administration';" />
                        </form>
                    </td>
                </tr>
            </table>

        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
