<?php /* $Id: Home.tpl 3563 2007-11-12 07:41:54Z will $ */ ?>
<?php TemplateUtility::printHeader('Home', array('js/sweetTitles.js', 'js/dataGrid.js', 'js/dataGridFilters.js', 'js/home.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
    <div id="main" class="home">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents" style="padding-top: 10px;">

            <table>
                <tr>
                    <td align="left" valign="top" style="text-align: left; height:50px;">
                        <div class="noteUnsizedSpan">My Recent Calls</div>
                        <?php $this->dataGrid2->drawHTML();  ?>
                    </td>

                    <td align="center" valign="top" style="text-align: left; font-size:11px; height:50px;">
                        <?php echo($this->upcomingEventsFupHTML); ?>
                    </td>

                    <td align="center" valign="top" style="text-align: left;font-size:11px; height:50px;">
                        <?php echo($this->upcomingEventsHTML); ?>
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td align="left" valign="top" style="text-align: left; width: 50%; height: 240px;">
                        <div class="noteUnsizedSpan">Recent Hires</div>

                        <table class="sortable" style="margin: 0 0 4px 0;">
                            <tr>
                                <th align="left" style="font-size:11px;">Name</th>
                                <th align="left" style="font-size:11px;">Company</th>
                                <th align="left" style="font-size:11px;">Recruiter</th>
                                <th align="left" style="font-size:11px;">Date</th>
                            </tr>
                            <?php foreach($this->placedRS as $index => $data): ?>
                            <tr class="<?php TemplateUtility::printAlternatingRowClass($index); ?>">
                                <td style="font-size:11px;"><a href="<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;candidateID=<?php echo($data['candidateID']); ?>"style="font-size:11px;" class="<?php echo($data['candidateClassName']); ?>"><?php $this->_($data['firstName']); ?> <?php $this->_($data['lastName']); ?></a></td>
                                <td style="font-size:11px;"><a href="<?php echo(CATSUtility::getIndexName()); ?>?m=companies&amp;a=show&amp;companyID=<?php echo($data['companyID']); ?>"  style="font-size:11px;" class="<?php echo($data['companyClassName']); ?>"><?php $this->_($data['companyName']); ?></td>
                                <td style="font-size:11px;"><?php $this->_(StringUtility::makeInitialName($data['userFirstName'], $data['userLastName'], false, LAST_NAME_MAXLEN)); ?></td>
                                <td style="font-size:11px;"><?php $this->_($data['date']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>

                        <?php if (!count($this->placedRS)): ?>
                            <div style="height: 207px; border: 1px solid #c0c0c0; background: #E7EEFF url(images/nodata/dashboardNoHiresWhite.jpg);">
                                &nbsp;
                            </div>
                        <?php endif; ?>
                    </td>

                    <td align="center" valign="top" style="text-align: left; width: 50%; height: 240px;">
                        <div class="noteUnsizedSpan">Hiring Overview</div>
                        <map name="dashboardmap" id="dashboardmap">
                           <area href="#" alt="Weekly" title="Weekly"
                                 shape="rect" coords="398,0,461,24" onclick="swapHomeGraph(<?php echo(DASHBOARD_GRAPH_WEEKLY); ?>);" />
                           <area href="#" alt="Monthly" title="Monthly"
                                 shape="rect" coords="398,25,461,48" onclick="swapHomeGraph(<?php echo(DASHBOARD_GRAPH_MONTHLY); ?>);" />
                            <area href="#" alt="Yearly" title="Yearly"
                                 shape="rect" coords="398,49,461,74" onclick="swapHomeGraph(<?php echo(DASHBOARD_GRAPH_YEARLY); ?>);" />
                        </map>
                        <div id="hiringOverviewContainer" style="width: 495px; height: 230px; border: 1px solid #c0c0c0; background: #E7EEFF; position: relative; overflow: hidden;">
                            <img src="<?php echo(CATSUtility::getIndexName()); ?>?m=graphs&amp;a=miniPlacementStatistics&amp;width=495&amp;height=230&amp;view=<?php echo(DASHBOARD_GRAPH_WEEKLY); ?>&amp;t=<?php echo(time()); ?>" id="homeGraph" onclick="" alt="Hiring Overview" usemap="#dashboardmap" border="0" style="display: block; width: 100%; height: 100%; object-fit: contain;" onerror="handleGraphError(this);" onload="handleGraphLoad(this);" />
                            <div id="hiringOverviewError" style="display: none; text-align: center; padding-top: 100px; color: #666; font-size: 12px; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #E7EEFF;">
                                Unable to load hiring overview graph.<br />
                                <a href="javascript:void(0);" onclick="retryGraph(); return false;" style="color: #0066cc; text-decoration: underline;">Retry</a>
                            </div>
                            <div id="hiringOverviewLoading" style="display: none; text-align: center; padding-top: 100px; color: #666; font-size: 12px; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #E7EEFF;">
                                Loading graph...
                            </div>
                        </div>
                        <script type="text/javascript">
                        function handleGraphError(img) {
                            img.style.display = 'none';
                            document.getElementById('hiringOverviewError').style.display = 'block';
                            document.getElementById('hiringOverviewLoading').style.display = 'none';
                        }
                        function handleGraphLoad(img) {
                            img.style.display = 'block';
                            document.getElementById('hiringOverviewError').style.display = 'none';
                            document.getElementById('hiringOverviewLoading').style.display = 'none';
                        }
                        function retryGraph() {
                            var img = document.getElementById('homeGraph');
                            var errorDiv = document.getElementById('hiringOverviewError');
                            var loadingDiv = document.getElementById('hiringOverviewLoading');
                            
                            errorDiv.style.display = 'none';
                            loadingDiv.style.display = 'block';
                            img.style.display = 'none';
                            
                            // Add timestamp to force reload
                            var src = img.src.split('&t=')[0];
                            img.src = src + '&t=' + new Date().getTime();
                            img.style.display = 'block';
                        }
                        </script>
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td align="left" valign="top" style="text-align: left; width: 50%; height: 260px;">
                        <div class="noteUnsizedSpan">Important Candidates (Submitted, Interviewing, Offered in Active Job Orders) - Page <?php echo($this->dataGrid->getCurrentPageHTML()); ?> (<?php echo($this->dataGrid->getNumberOfRows()); ?> Items)</div>
                        <?php $this->dataGrid->draw(); ?>
                        <div style="float:right;"><?php $this->dataGrid->printNavigation(false); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php $this->dataGrid->printShowAll(); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>

                        <?php if (!$this->dataGrid->getNumberOfRows()): ?>
                        <div style="height: 208px; border: 1px solid #c0c0c0; background: #E7EEFF url(images/nodata/dashboardNoCandidatesWhite.jpg);">
                            &nbsp;
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
