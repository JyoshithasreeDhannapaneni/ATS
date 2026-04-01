<?php /* $Id: Calendar.tpl 3221 2007-10-17 17:13:22Z will $ */ ?>
<?php TemplateUtility::printHeader('Calendar', array('modules/calendar/Calendar.css', 'js/highlightrows.js', 'modules/calendar/Calendar.js', 'modules/calendar/CalendarUI.js', 'modules/calendar/validator.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
    <script type="text/javascript">
        window.CATSUserDateFormat = '<?php echo($_SESSION['CATS']->isDateDMY() ? 'DD-MM-YY' : 'MM-DD-YY'); ?>';
    </script>
    
    <!-- Meeting Link Popup Modal -->
    <?php if (!empty($this->newMeetingLink)): ?>
    <div id="meetingLinkModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 12px; padding: 24px; max-width: 550px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="white"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                </div>
                <div>
                    <h3 style="margin: 0; color: #1f2937; font-size: 18px;">Meeting Created Successfully!</h3>
                    <p style="margin: 4px 0 0 0; color: #6b7280; font-size: 14px;">Your video meeting link is ready</p>
                </div>
            </div>
            
            <div style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; color: #166534; font-weight: 600; margin-bottom: 8px;">MEETING LINK</label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" id="newMeetingLinkInput" value="<?php echo htmlspecialchars($this->newMeetingLink); ?>" readonly 
                        style="flex: 1; padding: 10px 12px; border: 1px solid #86efac; border-radius: 6px; font-size: 13px; background: white; color: #1f2937;" />
                    <button type="button" onclick="copyNewMeetingLink()" id="copyNewLinkBtn"
                        style="padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; white-space: nowrap;">
                        📋 Copy
                    </button>
                </div>
                <div id="newLinkCopySuccess" style="display: none; color: #166534; font-size: 12px; margin-top: 8px; font-weight: 500;">
                    ✓ Link copied to clipboard!
                </div>
            </div>
            
            <div style="display: flex; gap: 12px;">
                <a href="<?php echo htmlspecialchars($this->newMeetingLink); ?>" target="_blank" 
                    style="flex: 1; padding: 12px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px; text-align: center; font-weight: 600; font-size: 14px;">
                    🎥 Join Meeting Now
                </a>
                <button type="button" onclick="closeMeetingLinkModal()" 
                    style="padding: 12px 24px; background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px;">
                    Close
                </button>
            </div>
        </div>
    </div>
    <script>
        function copyNewMeetingLink() {
            var input = document.getElementById('newMeetingLinkInput');
            var successMsg = document.getElementById('newLinkCopySuccess');
            var btn = document.getElementById('copyNewLinkBtn');
            
            input.select();
            input.setSelectionRange(0, 99999);
            
            navigator.clipboard.writeText(input.value).then(function() {
                successMsg.style.display = 'block';
                btn.innerHTML = '✓ Copied!';
                btn.style.background = '#059669';
            }).catch(function() {
                document.execCommand('copy');
                successMsg.style.display = 'block';
                btn.innerHTML = '✓ Copied!';
                btn.style.background = '#059669';
            });
        }
        
        function closeMeetingLinkModal() {
            document.getElementById('meetingLinkModal').style.display = 'none';
            // Remove the meetingLink from URL to prevent showing again on refresh
            var url = new URL(window.location);
            url.searchParams.delete('meetingLink');
            window.history.replaceState({}, '', url);
        }
    </script>
    <?php endif; ?>
    
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <img src="images/calendar.gif" width="24" height="24" alt="Calendar" style="border: none;" />
                    <h2 style="margin: 0;">Calendar</h2>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <?php if ($this->userIsSuperUser == 1): ?>
                        <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280; cursor: pointer;">
                            <input type="checkbox" name="hideNonPublic" id="hideNonPublic" onclick="refreshView();" <?php if ($this->superUserActive): ?>checked<?php endif; ?> style="accent-color: #2563eb;" />
                            Show Entries from Other Users
                        </label>
                    <?php else: ?>
                        <input type="checkbox" style="display:none;" name="hideNonPublic" id="hideNonPublic" onclick="" />
                    <?php endif; ?>
                </div>
            </div>

            <p class="note" id="calendarTitle">Calendar</p>

            <div class="calendar-container">
                <!-- Sidebar -->
                <div class="calendar-sidebar">
                    <!-- Upcoming Events -->
                    <div id="upcomingEventsTD" class="upcoming-events-card" style="margin-bottom: 16px;">
                        <?php echo($this->summaryHTML); ?>
                    </div>

                    <!-- Add Event Form -->
                    <div id="addEventTD" class="event-form-card" style="display:none;">
                        <div class="event-form-header">Add Event</div>
                        <div class="event-form-body">
                            <form name="addEventForm" id="addEventForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=calendar&amp;a=addEvent" method="post" onsubmit="return checkAddForm(document.addEventForm);" autocomplete="off">
                                <input type="hidden" name="postback" id="postbackA" value="postback" />

                                <table class="editTableMini" width="100%">
                                    <tr>
                                        <td class="tdVertical">Title:</td>
                                        <td class="tdData">
                                            <input type="text" class="inputbox" name="title" id="title" />&nbsp;*
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="tdVertical">Type:</td>
                                        <td class="tdData">
                                            <select id="type" name="type" class="inputbox" onchange="toggleMeetingPlatform(this.value);">
                                                <option value="">(Select a Type)</option>
                                                <option value="100">Call</option>
                                                <option value="200">Email</option>
                                                <option value="300">Meeting</option>
                                                <option value="400">L1 Interview</option>
                                                <option value="410">L2 Interview</option>
                                                <option value="420">L3 Interview</option>
                                                <option value="430">HR Interview</option>
                                                <option value="500">Personal</option>
                                                <option value="600">Other</option>
                                            </select>&nbsp;*
                                        </td>
                                    </tr>

                                    <tr id="meetingPlatformRow" style="display: none;">
                                        <td class="tdVertical">Platform:</td>
                                        <td class="tdData">
                                            <input type="hidden" name="meetingPlatform" value="jitsi" />
                                            <div style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: linear-gradient(135deg, #devices 0%, #devices 100%); border-radius: 8px; border: 2px solid #devices;">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="#1a73e8"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>
                                                <span style="font-weight: 600; font-size: 14px; color: #1a73e8;">Video Meeting</span>
                                                <span style="font-size: 11px; color: #16a34a; margin-left: auto; font-weight: 500;">✓ Link will be auto-generated</span>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr id="attendeeEmailRow" style="display: none;">
                                        <td class="tdVertical">Attendee:</td>
                                        <td class="tdData">
                                            <input type="email" name="attendeeEmail" id="attendeeEmail" class="inputbox" placeholder="Enter attendee email" />
                                            <div style="font-size: 11px; color: #6b7280; margin-top: 4px;">
                                                Meeting invite will be sent to this email
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="tdVertical">Public:</td>
                                        <td class="tdData">
                                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                                <input type="checkbox" name="publicEntry" id="publicEntry" <?php if ($this->defaultPublic == 'true'): ?>checked<?php endif; ?> style="accent-color: #2563eb;" />
                                                <span style="font-size: 13px; color: #374151;">Public Entry</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="tdVertical">Date:</td>
                                        <td class="tdData">
                                            <script type="text/javascript">DateInput('dateAdd', true, (typeof window.CATSUserDateFormat !== 'undefined' ? window.CATSUserDateFormat : 'MM-DD-YY'), '<?php echo($_SESSION['CATS']->isDateDMY() ? DateUtility::getAdjustedDate('d-m-y') : $this->currentDateMDY); ?>', -1);</script>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="tdVertical">Time:</td>
                                        <td class="tdData">
                                            <div class="time-selector">
                                                <input type="radio" name="allDay" id="allDay0" value="0" checked onchange="setAddAllDayEnabled();" />
                                                <select id="hour" name="hour" class="inputbox">
                                                    <?php for ($i = 1; $i <= 12; ++$i): ?>
                                                        <option value="<?php echo($i); ?>"><?php echo(sprintf('%02d', $i)); ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                                <span>:</span>
                                                <select id="minute" name="minute" class="inputbox">
                                                    <?php for ($i = 0; $i <= 45; $i = $i + 15): ?>
                                                        <option value="<?php echo(sprintf('%02d', $i)); ?>"><?php echo(sprintf('%02d', $i)); ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                                <select id="meridiem" name="meridiem" class="inputbox">
                                                    <option value="AM">AM</option>
                                                    <option value="PM">PM</option>
                                                </select>
                                            </div>
                                            <div style="margin-top: 8px;">
                                                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                                    <input type="radio" name="allDay" id="allDay1" value="1" onchange="setAddAllDayEnabled();" />
                                                    <span style="font-size: 12px; color: #6b7280;">All Day / No Specific Time</span>
                                                </label>
                                            </div>
                                            <?php if($this->allowEventReminders): ?>
                                            <div style="margin-top: 8px;">
                                                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                                    <input type="checkbox" name="reminderToggle" id="reminderToggle" onclick="considerCheckBox('reminderToggle', 'sendEmailTD');" style="accent-color: #2563eb;" />
                                                    <span style="font-size: 12px; color: #6b7280;">Send e-mail reminder</span>
                                                </label>
                                            </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <tr id="sendEmailTD" style="display:none;">
                                        <td class="tdVertical">E-Mail:</td>
                                        <td class="tdData">
                                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                                <div>
                                                    <label style="font-size: 11px; color: #6b7280; display: block; margin-bottom: 4px;">To:</label>
                                                    <input type="text" id="sendEmail" name="sendEmail" class="inputbox" value="<?php $this->_($this->userEmail); ?>" />
                                                </div>
                                                <div>
                                                    <label style="font-size: 11px; color: #6b7280; display: block; margin-bottom: 4px;">Time:</label>
                                                    <select id="reminderTime" name="reminderTime" class="inputbox">
                                                        <option value="15">15 min early</option>
                                                        <option value="30">30 min early</option>
                                                        <option value="45">45 min early</option>
                                                        <option value="60">1 hour early</option>
                                                        <option value="120">2 hours early</option>
                                                        <option value="1440">1 day early</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="tdVertical">Length:</td>
                                        <td class="tdData">
                                            <select id="duration" name="duration" class="inputbox">
                                                <option value="15">15 minutes</option>
                                                <option value="30">30 minutes</option>
                                                <option value="45">45 minutes</option>
                                                <option value="60" selected="selected">1 hour</option>
                                                <option value="90">1.5 hours</option>
                                                <option value="120">2 hours</option>
                                                <option value="180">3 hours</option>
                                                <option value="240">4 hours</option>
                                                <option value="300">More than 4 hours</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="tdVertical">Desc:</td>
                                        <td class="tdData">
                                            <textarea id="description" name="description" class="inputbox"></textarea>
                                        </td>
                                    </tr>
                                </table>

                                <div style="text-align: center; margin-top: 16px;">
                                    <input type="submit" class="button" name="submit" value="Add Event" />
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Event Form -->
                    <div id="editEventTD" class="event-form-card" style="display:none;">
                        <div class="event-form-header">Edit Event</div>
                        <div class="event-form-body">
                            <form name="editEventForm" id="editEventForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=calendar&amp;a=editEvent" method="post" onsubmit="return checkEditForm(document.editEventForm);" autocomplete="off">
                                <input type="hidden" name="postback" id="postbackB" value="postback" />
                                <input type="hidden" name="eventID" id="eventIDEdit" />
                                <input type="hidden" name="dataItemType" id="dataItemTypeEdit" />
                                <input type="hidden" name="dataItemID" id="dataItemIDEdit" />
                                <input type="hidden" name="jobOrderID" id="jobOrderIDEdit" />

                                <table class="editTableMini" width="100%">
                                    <tr>
                                        <td class="tdVertical">Title:</td>
                                        <td class="tdData">
                                            <input type="text" class="inputbox" name="title" id="titleEdit" />&nbsp;*
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="tdVertical">Type:</td>
                                        <td class="tdData">
                                            <select id="typeEdit" name="type" class="inputbox" onchange="toggleMeetingPlatformEdit(this.value);">
                                                <option value="">(Select a Type)</option>
                                                <option value="100">Call</option>
                                                <option value="200">Email</option>
                                                <option value="300">Meeting</option>
                                                <option value="400">L1 Interview</option>
                                                <option value="410">L2 Interview</option>
                                                <option value="420">L3 Interview</option>
                                                <option value="430">HR Interview</option>
                                                <option value="500">Personal</option>
                                                <option value="600">Other</option>
                                            </select>&nbsp;*
                                        </td>
                                    </tr>

                                    <tr id="meetingPlatformRowEdit" style="display: none;">
                                        <td class="tdVertical">Platform:</td>
                                        <td class="tdData">
                                            <input type="hidden" name="meetingPlatformEdit" value="jitsi" />
                                            <div style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 8px; border: 2px solid #0ea5e9;">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="#1a73e8"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>
                                                <span style="font-weight: 600; font-size: 14px; color: #1a73e8;">Video Meeting</span>
                                                <span style="font-size: 11px; color: #16a34a; margin-left: auto; font-weight: 500;">✓ Link will be auto-generated</span>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr id="attendeeEmailRowEdit" style="display: none;">
                                        <td class="tdVertical">Attendee:</td>
                                        <td class="tdData">
                                            <input type="email" name="attendeeEmailEdit" id="attendeeEmailEdit" class="inputbox" placeholder="Enter attendee email" />
                                            <div style="font-size: 11px; color: #6b7280; margin-top: 4px;">
                                                Meeting invite will be sent to this email
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="tdVertical">Public:</td>
                                        <td class="tdData">
                                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                                <input type="checkbox" name="publicEntry" id="publicEntryEdit" style="accent-color: #2563eb;" />
                                                <span style="font-size: 13px; color: #374151;">Public Entry</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="tdVertical">Date:</td>
                                        <td class="tdData">
                                            <script type="text/javascript">DateInput('dateEdit', true, (typeof window.CATSUserDateFormat !== 'undefined' ? window.CATSUserDateFormat : 'MM-DD-YY'), '<?php echo($_SESSION['CATS']->isDateDMY() ? DateUtility::getAdjustedDate('d-m-y') : $this->currentDateMDY); ?>', -1);</script>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="tdVertical">Time:</td>
                                        <td class="tdData">
                                            <div class="time-selector">
                                                <input type="radio" name="allDay" id="allDayEdit0" value="0" checked onchange="setEditAllDayEnabled();" />
                                                <select id="hourEdit" name="hour" class="inputbox">
                                                    <?php for ($i = 1; $i <= 12; ++$i): ?>
                                                        <option value="<?php echo($i); ?>"><?php echo(sprintf('%02d', $i)); ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                                <span>:</span>
                                                <select id="minuteEdit" name="minute" class="inputbox">
                                                    <?php for ($i = 0; $i <= 45; $i = $i + 15): ?>
                                                        <option value="<?php echo(sprintf('%02d', $i)); ?>"><?php echo(sprintf('%02d', $i)); ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                                <select id="meridiemEdit" name="meridiem" class="inputbox">
                                                    <option value="AM">AM</option>
                                                    <option value="PM">PM</option>
                                                </select>
                                            </div>
                                            <div style="margin-top: 8px;">
                                                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                                    <input type="radio" name="allDay" id="allDayEdit1" value="1" onchange="setEditAllDayEnabled();" />
                                                    <span style="font-size: 12px; color: #6b7280;">All Day / No Specific Time</span>
                                                </label>
                                            </div>
                                            <?php if($this->allowEventReminders): ?>
                                            <div style="margin-top: 8px;">
                                                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                                    <input type="checkbox" name="reminderToggle" id="reminderToggleEdit" onclick="considerCheckBox('reminderToggleEdit', 'sendEmailTDEdit');" style="accent-color: #2563eb;" />
                                                    <span style="font-size: 12px; color: #6b7280;">Send e-mail reminder</span>
                                                </label>
                                            </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <tr id="sendEmailTDEdit" style="display: none;">
                                        <td class="tdVertical">E-Mail:</td>
                                        <td class="tdData">
                                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                                <div>
                                                    <label style="font-size: 11px; color: #6b7280; display: block; margin-bottom: 4px;">To:</label>
                                                    <input type="text" id="sendEmailEdit" name="sendEmail" class="inputbox" value="<?php $this->_($this->userEmail); ?>" />
                                                </div>
                                                <div>
                                                    <label style="font-size: 11px; color: #6b7280; display: block; margin-bottom: 4px;">Time:</label>
                                                    <select id="reminderTimeEdit" name="reminderTime" class="inputbox">
                                                        <option value="15">15 min early</option>
                                                        <option value="30">30 min early</option>
                                                        <option value="45">45 min early</option>
                                                        <option value="60">1 hour early</option>
                                                        <option value="120">2 hours early</option>
                                                        <option value="1440">1 day early</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="tdVertical">Length:</td>
                                        <td class="tdData">
                                            <select id="durationEdit" name="duration" class="inputbox">
                                                <option value="15">15 minutes</option>
                                                <option value="30">30 minutes</option>
                                                <option value="45">45 minutes</option>
                                                <option value="60" selected="selected">1 hour</option>
                                                <option value="90">1.5 hours</option>
                                                <option value="120">2 hours</option>
                                                <option value="180">3 hours</option>
                                                <option value="240">4 hours</option>
                                                <option value="300">More than 4 hours</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="tdVertical">Desc:</td>
                                        <td class="tdData">
                                            <textarea id="descriptionEdit" name="description" class="inputbox"></textarea>
                                        </td>
                                    </tr>
                                </table>

                                <div style="display: flex; justify-content: center; gap: 8px; margin-top: 16px;">
                                    <input type="submit" class="button" name="submit" value="Save" />
                                    <?php if ($this->getUserAccessLevel('calendar.deleteEvent') >= ACCESS_LEVEL_DELETE): ?>
                                        <input type="button" class="button" name="delete" value="Delete" onclick="confirmDeleteEntry();" style="background: #dc2626;" />
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- View Event Panel -->
                    <div id="viewEventTD" class="view-event-card" style="display:none;">
                        <div class="view-event-header">
                            <div class="view-event-title" id="viewEventTitle"></div>
                            <div class="view-event-type">
                                <span id="viewEventType"></span>
                            </div>
                        </div>
                        <div class="view-event-body">
                            <div class="view-event-row">
                                <div class="view-event-label">Entered By:</div>
                                <div class="view-event-value" id="viewEventOwner"></div>
                            </div>
                            <div class="view-event-row">
                                <div class="view-event-label">Date:</div>
                                <div class="view-event-value" id="viewEventDate"></div>
                            </div>
                            <div class="view-event-row">
                                <div class="view-event-label">Time:</div>
                                <div class="view-event-value" id="viewEventTime"></div>
                            </div>
                            <div class="view-event-row">
                                <div class="view-event-label">Duration:</div>
                                <div class="view-event-value" id="viewEventDuration"></div>
                            </div>
                            <div class="view-event-row">
                                <div class="view-event-label">Reminder:</div>
                                <div class="view-event-value" id="viewEventReminder"></div>
                            </div>
                            <div class="view-event-row" id="viewEventMeetingLinkRow" style="display: none;">
                                <div class="view-event-label">Meeting Link:</div>
                                <div class="view-event-value">
                                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                        <input type="text" id="viewEventMeetingLink" readonly 
                                            style="flex: 1; min-width: 200px; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; background: #f9fafb; color: #1f2937;" />
                                        <button type="button" onclick="copyMeetingLink()" 
                                            style="padding: 8px 16px; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 6px;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
                                            Copy Link
                                        </button>
                                    </div>
                                    <div id="copySuccess" style="display: none; color: #16a34a; font-size: 12px; margin-top: 4px;">
                                        ✓ Link copied to clipboard!
                                    </div>
                                </div>
                            </div>
                            <div class="view-event-row" id="viewEventLinkRow" style="display: none;">
                                <div class="view-event-label">Related To:</div>
                                <div class="view-event-value" id="viewEventLink"></div>
                            </div>
                            <div style="margin-top: 12px;">
                                <div class="view-event-label" style="margin-bottom: 8px;">Description:</div>
                                <div id="viewEventDescription" style="font-size: 13px; color: #374151; background: #f9fafb; padding: 12px; border-radius: 6px;"></div>
                            </div>
                        </div>
                        <div class="view-event-actions">
                            <?php if ($this->getUserAccessLevel('calendar.editEvent') >= ACCESS_LEVEL_EDIT): ?>
                                <input type="button" class="button" name="Edit" value="Edit Event" onclick="calendarEditEvent(currentViewedEntry);" />
                            <?php endif; ?>
                            <a href="#" id="joinMeetingBtn" class="btn-join-meeting" style="display: none;" target="_blank">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>
                                Join Meeting
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Calendar Area -->
                <div class="calendar-main">
                    <!-- Month View -->
                    <table id="calendarMonthParent" style="display:none; width: 100%;">
                        <tr>
                            <td>
                                <div class="calendar-nav">
                                    <div class="calendar-nav-buttons">
                                        <button type="button" class="calendar-nav-btn" onclick="userCalendarViewDay()">Day</button>
                                        <button type="button" class="calendar-nav-btn" onclick="userCalendarViewWeek()">Week</button>
                                        <button type="button" class="calendar-nav-btn active" onclick="userCalendarViewMonth()">Month</button>
                                    </div>
                                    <div class="calendar-nav-title">
                                        <div class="calendar-nav-arrows">
                                            <span id="linkMonthBack"></span>
                                        </div>
                                        <h3 id="monthNotice"></h3>
                                        <div class="calendar-nav-arrows">
                                            <span id="linkMonthForeward"></span>
                                        </div>
                                    </div>
                                    <div style="width: 150px;"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table id="calendarMonth" onmouseup="javascript:trackTableSelect(event);">
                                    <tr>
                                        <?php if ($this->firstDayMonday != '1'): ?><th>Sunday</th><?php endif; ?>
                                        <th>Monday</th>
                                        <th>Tuesday</th>
                                        <th>Wednesday</th>
                                        <th>Thursday</th>
                                        <th>Friday</th>
                                        <th>Saturday</th>
                                        <?php if ($this->firstDayMonday == '1'): ?><th>Sunday</th><?php endif; ?>
                                    </tr>

                                    <?php $calendarPosition = 0; ?>
                                    <?php for ($calendarRow = 1; $calendarRow <= 6; ++$calendarRow): ?>
                                        <tr id="calendarRow<?php echo($calendarRow); ?>">
                                            <?php $weekPosition = 1; ?>
                                            <?php for ($weekday = 1; $weekday <= 7; ++$weekday): ?>
                                                <td class="empty" id="calendarMonthCell<?php echo($calendarPosition++); ?>">&nbsp;</td>
                                            <?php endfor; ?>
                                        </tr>
                                    <?php endfor; ?>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <!-- Week View -->
                    <table id="calendarWeekParent" style="display:none; width: 100%;">
                        <tr>
                            <td>
                                <div class="calendar-nav">
                                    <div class="calendar-nav-buttons">
                                        <button type="button" class="calendar-nav-btn" onclick="userCalendarViewDay()">Day</button>
                                        <button type="button" class="calendar-nav-btn active" onclick="userCalendarViewWeek()">Week</button>
                                        <button type="button" class="calendar-nav-btn" onclick="userCalendarViewMonth()">Month</button>
                                    </div>
                                    <div class="calendar-nav-title">
                                        <div class="calendar-nav-arrows">
                                            <span id="linkWeekBack"></span>
                                        </div>
                                        <h3 id="weekNotice"></h3>
                                        <div class="calendar-nav-arrows">
                                            <span id="linkWeekForeward"></span>
                                        </div>
                                    </div>
                                    <div style="width: 150px;"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table id="calendarWeek" onmouseup="javascript:trackTableSelect(event, '#e9e9e9');">
                                    <?php if ($this->firstDayMonday != '1'): ?>
                                        <tr>
                                            <th>Sunday <br /><span id="weekDay0"></span></th>
                                            <td class="empty" id="calendarWeekCell0"></td>
                                        </tr>
                                        <tr>
                                            <th>Monday <br /><span id="weekDay1"></span></th>
                                            <td class="empty" id="calendarWeekCell1"></td>
                                        </tr>
                                        <tr>
                                            <th>Tuesday <br /><span id="weekDay2"></span></th>
                                            <td class="empty" id="calendarWeekCell2"></td>
                                        </tr>
                                        <tr>
                                            <th>Wednesday <br /><span id="weekDay3"></span></th>
                                            <td class="empty" id="calendarWeekCell3"></td>
                                        </tr>
                                        <tr>
                                            <th>Thursday <br /><span id="weekDay4"></span></th>
                                            <td class="empty" id="calendarWeekCell4"></td>
                                        </tr>
                                        <tr>
                                            <th>Friday <br /><span id="weekDay5"></span></th>
                                            <td class="empty" id="calendarWeekCell5"></td>
                                        </tr>
                                        <tr>
                                            <th>Saturday <br /><span id="weekDay6"></span></th>
                                            <td class="empty" id="calendarWeekCell6"></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <th>Monday <br /><span id="weekDay0"></span></th>
                                            <td class="empty" id="calendarWeekCell0"></td>
                                        </tr>
                                        <tr>
                                            <th>Tuesday <br /><span id="weekDay1"></span></th>
                                            <td class="empty" id="calendarWeekCell1"></td>
                                        </tr>
                                        <tr>
                                            <th>Wednesday <br /><span id="weekDay2"></span></th>
                                            <td class="empty" id="calendarWeekCell2"></td>
                                        </tr>
                                        <tr>
                                            <th>Thursday <br /><span id="weekDay3"></span></th>
                                            <td class="empty" id="calendarWeekCell3"></td>
                                        </tr>
                                        <tr>
                                            <th>Friday <br /><span id="weekDay4"></span></th>
                                            <td class="empty" id="calendarWeekCell4"></td>
                                        </tr>
                                        <tr>
                                            <th>Saturday <br /><span id="weekDay5"></span></th>
                                            <td class="empty" id="calendarWeekCell5"></td>
                                        </tr>
                                        <tr>
                                            <th>Sunday <br /><span id="weekDay6"></span></th>
                                            <td class="empty" id="calendarWeekCell6"></td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <!-- Day View -->
                    <table id="calendarDayParent" style="display:none; width: 100%;">
                        <tr>
                            <td>
                                <div class="calendar-nav">
                                    <div class="calendar-nav-buttons">
                                        <button type="button" class="calendar-nav-btn active" onclick="userCalendarViewDay()">Day</button>
                                        <button type="button" class="calendar-nav-btn" onclick="userCalendarViewWeek()">Week</button>
                                        <button type="button" class="calendar-nav-btn" onclick="userCalendarViewMonth()">Month</button>
                                    </div>
                                    <div class="calendar-nav-title">
                                        <div class="calendar-nav-arrows">
                                            <span id="linkDayBack"></span>
                                        </div>
                                        <h3 id="dayNotice"></h3>
                                        <div class="calendar-nav-arrows">
                                            <span id="linkDayForeward"></span>
                                        </div>
                                    </div>
                                    <div style="width: 150px;"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table id="calendarDay" onmouseup="javascript:trackTableSelect(event, '#e9e9e9');">
                                    <tr>
                                        <th>Morning</th>
                                        <td class="empty" id="calendarDayCell0"></td>
                                    </tr>
                                    <?php for ($i = $this->dayHourStart; $i <= $this->dayHourEnd; $i++): ?>
                                    <tr>
                                        <th><?php if (!$this->militaryTime && $i>12):?><?php echo($i - 12); ?><?php else: ?><?php echo($i); ?><?php endif; ?>:00</th>
                                        <td class="empty" id="calendarDayCell<?php echo($i - $this->dayHourStart + 1); ?>"></td>
                                    </tr>
                                    <?php endfor; ?>
                                    <tr>
                                        <th>Evening</th>
                                        <td class="empty" id="calendarDayCell<?php echo($this->dayHourEnd - $this->dayHourStart + 2); ?>"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        /* Settings */
        indexName = '<?php echo(CATSUtility::getIndexName()); ?>';
        todayDay = <?php echo($this->currentDay); ?>;
        todayMonth = <?php echo($this->currentMonth); ?>;
        todayYear = <?php echo($this->currentYear); ?>;
        todayHour = <?php echo($this->currentHour); ?>;
        dayHourStart = <?php echo($this->dayHourStart); ?>;
        dayHourEnd = <?php echo($this->dayHourEnd); ?>;
        dayTotalCells = <?php echo($this->dayHourEnd - $this->dayHourStart + 3); ?>;
        userEmail = '<?php echo($this->userEmail); ?>';
        allowAjax = <?php echo($this->allowAjax ? 'true' : 'false'); ?>;
        defaultPublic = <?php echo($this->defaultPublic); ?>;
        userID = <?php echo($this->userID); ?>;
        userIsSuperUser = <?php echo($this->userIsSuperUser); ?>;
        firstDayMonday =  <?php if ($this->firstDayMonday == 1) echo('1'); else echo('0'); ?>;
        accessLevel =  <?php echo($this->getUserAccessLevel('calendar')); ?>;

        /* Event types array - updated with interview levels */
        entryTypesArray = new Array(
            new Array(100, 'Call', 'images/phone.gif'),
            new Array(200, 'Email', 'images/email.gif'),
            new Array(300, 'Meeting', 'images/meeting.gif'),
            new Array(400, 'L1 Interview', 'images/interview.gif'),
            new Array(410, 'L2 Interview', 'images/interview.gif'),
            new Array(420, 'L3 Interview', 'images/interview.gif'),
            new Array(430, 'HR Interview', 'images/interview.gif'),
            new Array(500, 'Personal', 'images/personal.gif'),
            new Array(600, 'Other', '')
        );

        var ACCESS_LEVEL_DISABLED  = <?php echo(ACCESS_LEVEL_DISABLED); ?>;
        var ACCESS_LEVEL_READ      = <?php echo(ACCESS_LEVEL_READ); ?>;
        var ACCESS_LEVEL_EDIT      = <?php echo(ACCESS_LEVEL_EDIT); ?>;
        var ACCESS_LEVEL_DELETE    = <?php echo(ACCESS_LEVEL_DELETE); ?>;
        var ACCESS_LEVEL_DEMO      = <?php echo(ACCESS_LEVEL_DEMO); ?>;
        var ACCESS_LEVEL_SA        = <?php echo(ACCESS_LEVEL_SA); ?>;
        var ACCESS_LEVEL_ROOT      = <?php echo(ACCESS_LEVEL_ROOT); ?>;

        /* Data */
        calendarDataPopulateString('<?php echo($this->eventsString) ?>');

        /* Action */
        <?php if ($this->view == 'WEEKVIEW'): ?>
            setCalendarViewWeek(<?php echo($this->year) ?>, <?php echo($this->month) ?>, <?php echo($this->week) ?>);
        <?php elseif ($this->view == 'DAYVIEW'): ?>
            setCalendarViewDay(<?php echo($this->year) ?>, <?php echo($this->month) ?>, <?php echo($this->day) ?>);
        <?php else: ?>
            setCalendarViewMonth(<?php echo($this->year) ?>, <?php echo($this->month) ?>);
        <?php endif; ?>

        <?php if ($this->showEvent != null): ?>
            handleClickEntryByID(<?php echo($this->showEvent); ?>);
        <?php endif; ?>

        /* Meeting platform toggle functions */
        function toggleMeetingPlatform(typeValue) {
            var meetingTypes = ['100', '300', '400', '410', '420', '430'];
            var platformRow = document.getElementById('meetingPlatformRow');
            var attendeeRow = document.getElementById('attendeeEmailRow');
            if (meetingTypes.indexOf(typeValue) !== -1) {
                platformRow.style.display = 'table-row';
                attendeeRow.style.display = 'table-row';
            } else {
                platformRow.style.display = 'none';
                attendeeRow.style.display = 'none';
            }
        }

        function toggleMeetingPlatformEdit(typeValue) {
            var meetingTypes = ['100', '300', '400', '410', '420', '430'];
            var platformRow = document.getElementById('meetingPlatformRowEdit');
            var attendeeRow = document.getElementById('attendeeEmailRowEdit');
            if (meetingTypes.indexOf(typeValue) !== -1) {
                platformRow.style.display = 'table-row';
                attendeeRow.style.display = 'table-row';
            } else {
                platformRow.style.display = 'none';
                attendeeRow.style.display = 'none';
            }
        }

        // Copy meeting link to clipboard
        function copyMeetingLink() {
            var linkInput = document.getElementById('viewEventMeetingLink');
            var successMsg = document.getElementById('copySuccess');
            
            if (linkInput && linkInput.value) {
                // Select the text
                linkInput.select();
                linkInput.setSelectionRange(0, 99999); // For mobile
                
                // Copy to clipboard
                navigator.clipboard.writeText(linkInput.value).then(function() {
                    // Show success message
                    successMsg.style.display = 'block';
                    setTimeout(function() {
                        successMsg.style.display = 'none';
                    }, 3000);
                }).catch(function() {
                    // Fallback for older browsers
                    document.execCommand('copy');
                    successMsg.style.display = 'block';
                    setTimeout(function() {
                        successMsg.style.display = 'none';
                    }, 3000);
                });
            }
        }
        
        // Show meeting link in view event panel
        function showMeetingLinkInView(description) {
            var meetingLinkRow = document.getElementById('viewEventMeetingLinkRow');
            var meetingLinkInput = document.getElementById('viewEventMeetingLink');
            var joinBtn = document.getElementById('joinMeetingBtn');
            
            // Extract meeting link from description
            var meetingLink = '';
            
            // Check for Jitsi link
            var jitsiMatch = description.match(/https:\/\/meet\.jit\.si\/[^\s<"]+/);
            if (jitsiMatch) {
                meetingLink = jitsiMatch[0];
            }
            
            // Check for Teams link
            var teamsMatch = description.match(/https:\/\/teams\.microsoft\.com\/[^\s<"]+/);
            if (teamsMatch) {
                meetingLink = teamsMatch[0];
            }
            
            // Check for any meeting link pattern in description
            var genericMatch = description.match(/--- Meeting Link \([^)]+\) ---\s*([^\s<]+)/);
            if (genericMatch) {
                meetingLink = genericMatch[1];
            }
            
            if (meetingLink) {
                meetingLinkRow.style.display = 'flex';
                meetingLinkInput.value = meetingLink;
                
                // Show and update join button
                if (joinBtn) {
                    joinBtn.style.display = 'inline-flex';
                    joinBtn.href = meetingLink;
                }
            } else {
                meetingLinkRow.style.display = 'none';
                if (joinBtn) {
                    joinBtn.style.display = 'none';
                }
            }
        }
    </script>
<?php TemplateUtility::printFooter(); ?>
