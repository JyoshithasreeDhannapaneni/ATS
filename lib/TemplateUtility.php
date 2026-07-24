<?php
/**
 * CATS
 * Template Utility Library
 *
 * Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 *
 *
 * The contents of this file are subject to the CATS Public License
 * Version 1.1a (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.catsone.com/.
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "CATS Standard Edition".
 *
 * The Initial Developer of the Original Code is Cognizo Technologies, Inc.
 * Portions created by the Initial Developer are Copyright (C) 2005 - 2007
 * (or from the year in which this file was created to the year 2007) by
 * Cognizo Technologies, Inc. All Rights Reserved.
 *
 * In the interest of readability and performance, this file is not wrapped
 * at 80 characters per line, as it contains quite a bit of long HTML strings.
 * Lines should, however, be wrapped at around 120 characters per line to
 * ensure readability on a 1280 x 1024 resolution monitor.
 *
 *
 * @package    CATS
 * @subpackage Library
 * @copyright  Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 * @version    $Id: TemplateUtility.php 3835 2007-12-12 19:08:38Z brian $
 */

include_once('./vendor/autoload.php');
include_once(LEGACY_ROOT . '/lib/Candidates.php');
include_once(LEGACY_ROOT . '/lib/DateUtility.php');
include_once(LEGACY_ROOT . '/lib/SystemInfo.php');

use OpenCATS\UI\QuickActionMenu;

/**
 *	Template Utility Library
 *	@package    CATS
 *	@subpackage Library
 */
class TemplateUtility
{
    /* Prevent this class from being instantiated. */
    private function __construct() {}
    private function __clone() {}


    /**
     * Prints the template header HTML for a non-modal window.
     *
     * @param string page title
     * @param array JavaScript / CSS files to load
     * @return void
     */
    public static function printHeader($pageTitle, $headIncludes = array())
    {
        self::_printCommonHeader($pageTitle, $headIncludes);
        echo '<body style="background: #fff">', "\n";
        self::_printQuickActionMenuHolder();
        self::printPopupContainer();
    }

    /**
     * Prints the template header HTML for a modal window.
     *
     * @param string page title
     * @param array JavaScript / CSS files to load
     * @return void
     */
    public static function printModalHeader($pageTitle, $headIncludes = array(), $title = '')
    {
        self::_printCommonHeader($pageTitle, $headIncludes);
        echo '<body style="background: #eee;">', "\n";
        if ($title != '')
        {
            $title = str_replace('\'', '\\\'', $title);
            echo '<script type="text/javascript">parentSetPopTitle(\''.$title.'\');</script>';
        }
        self::_printQuickActionMenuHolder();
    }

    /**
     * Prints logo and "top-right" header HTML.
     *
     * @return void
     */
    public static function printHeaderBlock($showTopRight = true)
    {
        $username     = $_SESSION['CATS']->getUsername();
        $indexName    = CATSUtility::getIndexName();

        echo '<div id="headerBlock">', "\n";

        /* Left side — Sidebar toggle button (brand lives in the sidebar header now) */
        echo '<div id="headerLogo">', "\n";
        echo '<button class="sidebar-toggle-btn header-burger" onclick="toggleSidebar()" title="Toggle Sidebar">', "\n";
        echo '<svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>', "\n";
        echo '</button>', "\n";
        echo '</div>', "\n";

        if (!eval(Hooks::get('TEMPLATE_LIVE_CHAT'))) return;

        if (!eval(Hooks::get('TEMPLATE_LOGIN_INFO_PRE_TOP_RIGHT'))) return;

        if ($showTopRight)
        {
            // FIXME: Use common functions.
            // FIXME: Isn't the UNIX-name stuff ASP specific? Hook?
            if (strpos($username, '@'.$_SESSION['CATS']->getSiteID()) !== false &&
                substr($username, strpos($username, '@'.$_SESSION['CATS']->getSiteID())) ==
                '@'.$_SESSION['CATS']->getSiteID() )
            {
               $username = str_replace('@'.$_SESSION['CATS']->getSiteID(), '', $username);
            }

            if (!eval(Hooks::get('TEMPLATE_LOGIN_INFO_TOP_RIGHT_1'))) return;

            /* Top Right Corner — Admin badge and Logout only */
            echo '<div id="topRight">', "\n";

            echo '<div style="display: flex; align-items: center; gap: 12px;">';

            if (!eval(Hooks::get('TEMPLATE_LOGIN_INFO_TOP_RIGHT_UPGRADE'))) return;

            if (LicenseUtility::isProfessional() &&
                $_SESSION['CATS']->getAccessLevel(ACL::SECOBJ_ROOT) >= ACCESS_LEVEL_SA)
            {
                if (abs(LicenseUtility::getExpirationDate() - time()) < 60*60*24*30)
                {
                    $daysLeft = abs(LicenseUtility::getExpirationDate() - time())/60/60/24;
                    echo '<span style="font-size: 12px; color: #6b7280;">License expires in ' . number_format($daysLeft, 0) . ' days</span>', "\n";
                }
            }

            if (!eval(Hooks::get('TEMPLATE_LOGIN_INFO_EXTENDED_SITE_NAME'))) return;

            $systemInfo = new SystemInfo();
            $systemInfoData = $systemInfo->getSystemInfo();

            if (isset($systemInfoData['available_version']) &&
                $systemInfoData['available_version'] > CATSUtility::getVersionAsInteger() &&
                isset($systemInfoData['disable_version_check']) &&
                !$systemInfoData['disable_version_check'] &&
                $_SESSION['CATS']->getAccessLevel(ACL::SECOBJ_ROOT) >= ACCESS_LEVEL_SA)
            {
                echo '<a href="http://www.catsone.com/download.php" target="catsdl" style="font-size: 11px; color: #f59e0b; font-weight: 500;">Update available</a>', "\n";
            }

            /* Disabled notice */
            if (!$_SESSION['CATS']->accountActive())
            {
                echo '<span style="font-weight: 600; color: #dc2626; font-size: 11px;">Account Inactive</span>', "\n";
            }
            else if ($_SESSION['CATS']->getAccessLevel(ACL::SECOBJ_ROOT) == ACCESS_LEVEL_READ)
            {
                echo '<span style="font-size: 11px; color: #6b7280;">Read Only</span>', "\n";
            }
            else
            {
                if (!eval(Hooks::get('TEMPLATE_LOGIN_INFO_TOP_RIGHT_2_ELSE'))) return;
            }

            if ($_SESSION['CATS']->getAccessLevel(ACL::SECOBJ_ROOT) >= ACCESS_LEVEL_SA)
            {
                echo '<span style="font-weight: 500; color: #2563eb; padding: 3px 10px; background: #eff6ff; border-radius: 20px; font-size: 11px; border: 1px solid #dbeafe;">Admin</span>', "\n";
            }

            /* Logout moved to sidebar bottom */

            echo '</div>', "\n";

            echo '</div>', "\n";
        }

        echo '</div>', "\n";
    }

    /**
     * Prints the time zone selection dropdown list.
     *
     * @param integer ID and name attributes of the time zone select input
     * @param string style attribute of the time zone select input
     * @param string class attribute of the time zone select input
     * @param integer selected GMT offset
     * @return void
     */
    public static function printTimeZoneSelect($selectID, $selectStyle,
        $selectClass, $selectedTimeZone)
    {
        echo '<select id="', $selectID, '" name="', $selectID, '"';

        if (!empty($selectClass))
        {
            echo ' class="', $selectClass, '"';
        }

        if (!empty($selectStyle))
        {
            echo ' style="', $selectStyle, '"';
        }

        echo '>';

        $currentTimeZone = '';

        foreach ($GLOBALS['timeZones'] as $timeZone)
        {
            echo '<option value="', $timeZone[0], '"';

            if ($timeZone[0] !== $currentTimeZone)
            {
                $currentTimeZone = $timeZone[0];
                if ($timeZone[0] == $selectedTimeZone)
                {
                    echo ' selected="selected"';
                }
            }

            echo '>', htmlspecialchars($timeZone[1]), '</option>';
        }

        echo '</select>';
    }

    /**
     * Prints the Quick Search box and MRU list.
     *
     * @return void
     */
    public static function printQuickSearch($wildCardString = '')
    {
        /* Get the formatted MRU list from Session. */
        $MRU = $_SESSION['CATS']->getMRU()->getFormatted();
        $indexName = CATSUtility::getIndexName();

        /* MRU List */
        echo '<div id="MRUPanel">', "\n";
        echo '<div id="MRUBlock">', "\n";

        if (!empty($MRU))
        {
            echo '<span class="MRUTitle">Recent:&nbsp;</span>&nbsp;', $MRU, "\n";
        }
        else
        {
            echo '<span class="MRUTitle"></span>&nbsp;', "\n";
        }

        echo '</div>', "\n\n";

        /* Quick Search */
        echo '<form id="quickSearchForm" action="', $indexName,
             '" method="get" onsubmit="return checkQuickSearchForm(document.quickSearchForm);">', "\n";
        echo '<div id="quickSearchBlock">', "\n";

        //FIXME:  Abstract into a hook.
        if ($_SESSION['CATS']->hasUserCategory('msa'))
        {
            echo '<input type="hidden" name="m" value="asp" />', "\n";
            echo '<input type="hidden" name="a" value="aspSearch" />', "\n";
            echo '<span class="quickSearchLabel" id="quickSearchLabel">ASP Search:</span>&nbsp;', "\n";
        }
        else
        {
            echo '<input type="hidden" name="m" value="home" />', "\n";
            echo '<input type="hidden" name="a" value="quickSearch" />', "\n";
            echo '<span class="quickSearchLabel" id="quickSearchLabel">Quick Search:</span>&nbsp;', "\n";
        }

        echo '<input name="quickSearchFor" id="quickSearchFor" class="quickSearchBox" value="',
             $wildCardString, '" placeholder="Search..." />&nbsp;', "\n";
        echo '<input type="submit" name="quickSearch" class="button" value="Search" style="padding: 6px 16px; border-radius: 20px; font-size: 12px;" />&nbsp;', "\n";
        echo '</div>', "\n";
        echo '</form>', "\n";
        echo '</div>', "\n";
    }

    /**
     * Prints Advanced Search for search pages.
     *
     * @return void
     */
    public static function printAdvancedSearch($considerFields)
    {
        echo '<input type="button" class="button" name="advancedSearch" id="advancedSearch" value="Advanced"',
             ' onclick="document.getElementById(\'advancedSearchField\').style.display=\'block\'; ',
             'advancedSearchReset();" style="display:none;">', "\n";
        echo '<input type="hidden" id="advancedSearchParser" name="advancedSearchParser" value="">', "\n";

        if (isset($_GET['advancedSearchOn']) && isset($_GET['advancedSearchParser']) &&
            $_GET['advancedSearchOn'] != 0 && !empty($_GET['advancedSearchParser']))
        {
            /* Output an active advanced search. */
            echo '<input type="hidden" id="advancedSearchOn" name="advancedSearchOn" value="',
                  $_GET['advancedSearchOn'], '" />', "\n";
            echo '<span id="advancedSearchField" style="display:block;">', "\n";
            echo '</span>', "\n";

            echo '<script type="text/javascript">', "\n";
            echo '    data = [];', "\n";
            echo '    nodes = [];', "\n";

            $stuff = explode('{[+', $_GET['advancedSearchParser']);
            for ($i = 0; $i < sizeof($stuff); $i++)
            {
                $innerStuff = explode('[|]', $stuff[$i]);

                echo '    data[',  $i, '] = "', $innerStuff[0], '";', "\n";
                echo '    nodes[', $i, '] = "', $innerStuff[1], '";', "\n";
            }
            echo '    data[', sizeof($stuff), '] = "";', "\n";
            echo '    advancedSearchDraw();', "\n";
            echo '</script>', "\n";
        }
        else
        {
            /* Output basic framework to start an advanced search; no search visible. */
            echo '<input type="hidden" id="advancedSearchOn" name="advancedSearchOn" value="0">', "\n";
            echo '<span id="advancedSearchField" style="display:none;">', "\n";
            echo '</span>', "\n";
        }

        /* Tell the script what fields have access to advanced search. */
        if (!empty($considerFields))
        {
            $considerFieldsArray = explode(',', $considerFields);

            echo '<script type="text/javascript">';
            echo '    advancedValidFields = ["', implode('","', $considerFieldsArray), '"];';
            echo '    advancedSearchConsider();';
            echo '</script>';
        }
    }

    /**
     * Prints the HTML for a saved search from a response array.
     *
     * @param response array
     * @return void
     */
    public static function printSavedSearch($savedSearchRS)
    {
        $savedSearchRecent = array();
        $savedSearchSaved = array();

        foreach ($savedSearchRS as $savedSearchRow)
        {
            if ($savedSearchRow['isCustom'] == 1)
            {
                $savedSearchSaved[] = $savedSearchRow;
            }
            else
            {
                $savedSearchRecent[] = $savedSearchRow;
            }
        }

        $currentUrlGET = array();
        foreach ($_GET as $key => $value)
        {
            if ($key != 'savedSearchID')
            {
                $currentUrlGET[] = $key . '=' . urlencode($value);
            }
        }

        $currentUrlGETString = urlencode(implode('&', $currentUrlGET));
        $indexName = CATSUtility::getIndexName();

        echo '<div class="recentSearchResults">';
        echo '<table style="vertical-align: top; border-collapse: collapse;"><tr style="vertical-align: top;"><td>';

        echo 'Recent Searches&nbsp;&nbsp;';
        echo '<img title="To save a recent search, press the + button below."',
             ' src="images/information.gif" alt="" width="16" height="16" />';

        echo '<div id="searchRecent" class="recentSearchResultsHidden">';

        /* Recent Search Results */
        if (count($savedSearchRecent) == 0)
        {
           echo '(None)';
        }
        else
        {
            foreach ($savedSearchRecent as $savedSearchRow)
            {
                if (strlen($savedSearchRow['dataItemText']) > 35)
                {
                    $savedSearchRow['dataItemText'] = substr($savedSearchRow['dataItemText'], 0, 35) . '...';
                }

                if (count($savedSearchSaved) >= RECENT_SEARCH_MAX_ITEMS)
                {
                    echo '<a href="javascript:void(0);" onclick="alert(\'The maximum amount of saved searches is ',
                         RECENT_SEARCH_MAX_ITEMS, '. To save this search, delete another saved search.\');">';
                }
                else
                {
                    echo '<a href="', $indexName, '?m=home&amp;a=addSavedSearch&amp;searchID=',
                         $savedSearchRow['searchID'], '&amp;currentURL=', $currentUrlGETString, '">';
                }

                echo '<img src="images/actions/add_small.gif" alt="" style="border: none;" title="Save This Search" /></a>&nbsp;', "\n";

                $escapedURL  = htmlspecialchars($savedSearchRow['URL']);

                /* Remove leading slashes. */
                while (substr($escapedURL, 0, 1) == '/')
                {
                    $escapedURL = substr($escapedURL, 1);
                }
                $escapedURL = '/'.$escapedURL;


                $escapedText = htmlspecialchars($savedSearchRow['dataItemText']);

                echo '<a href="', $escapedURL,
                     '" onclick="gotoSearch(\'', $escapedText, "', '", $escapedURL, '\');"',
                     ' onmouseover="this.className += \'recentSearchResultsHighlight\';" ',
                     ' onmouseout="this.className = this.className.replace(\'recentSearchResultsHighlight\', \'\');">',
                     $escapedText, '</a>', '<br />', "\n";
            }
        }

        echo '</div>';
        echo '</td><td>&nbsp;</td><td>';

        echo 'Saved Searches&nbsp;&nbsp;';
        echo '<img title="To delete a recent search, press the - button."',
             ' src="images/information.gif" alt="" width="16" height="16" />';

        echo '<div id="searchSaved" class="savedSearchResultsHidden">';

        /* Saved Search Results */
        if (count($savedSearchSaved) == 0)
        {
           echo '(None)';
        }
        else
        {
            foreach ($savedSearchSaved as $savedSearchRow)
            {
                if (strlen($savedSearchRow['dataItemText']) > 35)
                {
                    $savedSearchRow['dataItemText'] = substr($savedSearchRow['dataItemText'], 0, 35) . '...';
                }

                $escapedURL  = htmlspecialchars($savedSearchRow['URL']);
                $escapedText = htmlspecialchars($savedSearchRow['dataItemText']);

                /* Remove leading slashes. */
                while (substr($escapedURL, 0, 1) == '/')
                {
                    $escapedURL = substr($escapedURL, 1);
                }
                $escapedURL = '/'.$escapedURL;

                echo '<a href="', $indexName, '?m=home&amp;a=deleteSavedSearch&amp;searchID=',
                     $savedSearchRow['searchID'], '&currentURL=', $currentUrlGETString, '">',
                     '<img src="images/actions/delete_small.gif" style="border: none;" title="Delete This Search" /></a>&nbsp;';

                echo '<a href="', $escapedURL, '&amp;savedSearchID=', $savedSearchRow['searchID'],
                     '" onclick="gotoSearch(\'', $escapedText, "', '", $escapedURL,
                     '&amp;savedSearchID=', $savedSearchRow['searchID'], '\');"',
                     ' onmouseover="this.className += \'recentSearchResultsHighlight\';" ',
                     ' onmouseout="this.className = this.className.replace(\'recentSearchResultsHighlight\', \'\');">',
                     $escapedText,'</a><br />', "\n";
            }
        }

        echo '</div>', "\n";

        echo '</td></tr></table></div>';
        echo '<br /><br />';
        echo '<script type="text/javascript">syncRowHeightsSaved();</script>';
    }

    /**
     * Outputs a tester which checks if cookies are enabled in the user's
     * browser.
     *
     * @return void
     */
    public static function printCookieTester()
    {
        $indexName = CATSUtility::getIndexName();

        echo '<script type="text/javascript">
            if (navigator.cookieEnabled)
            {
                var cookieEnabled = true;
            }
            else
            {
                var cookieEnabled = false;
            }

            if (typeof(navigator.cookieEnabled) == "undefined" && !cookieEnabled)
            {
                document.cookie = \'testcookie\';
                cookieEnabled = (document.cookie.indexOf(\'testcookie\') != -1) ? true : false;
            }

            if (!cookieEnabled)
            {
                showPopWin(\'' . $indexName . '?m=login&amp;a=noCookiesModal\', 400, 225, null);
            }
            </script>';
    }

    /**
     * Outputs a popup container for use with JavaScript based popups like
     * ListEditor.js and other subModal.js-based dialogs.
     *
     * @return void
     */
    public static function printPopupContainer()
    {
        echo '<div id="popupMask">&nbsp;</div><div id="popupContainer">',
             '<div id="popupInner"><div id="popupTitleBar">',
             '<div id="popupTitle"></div><div id="popupControls">',
             '<img src="js/submodal/close.gif" alt="X" width="16" height="16"',
             ' onclick="hidePopWin(false);" /></div></div>';

        echo '<div style="width: 100%; height: 100%; background-color:',
             ' transparent; display: none;" id="popupFrameDiv"></div>';

        echo '<iframe src="js/submodal/loading.html" style="width: 100%; height: 100%;',
             ' background-color: transparent; display: none;" scrolling="auto"',
             ' frameborder="0" allowtransparency="true" id="popupFrameIFrame"',
             ' width="100%" height="100%"></iframe>';

        echo '</div></div>';
    }

    /**
     * Prints the module tabs.
     *
     * @param UserInterface active module interface
     * @param string active subtab name
     * @param string module name to forcibly highlight
     * @return void
     */
    public static function printTabs($active, $subActive = '', $forceHighlight = '')
    {
        /* Special tab behaviors:
         *
         * Tab text = 'something*al=somenumber' where somenumber is an access level -
         *      Only display tab if current user userlevel >= somenumber.
         * Tab text = 'something*al=somenumber@somesecuredobject' where somenumber is an access level and somesecuredobject is secured objec name -
         *      Only display tab if current user userlevel_for_securedobject >= somenumber.
         *
         * Subtab url = 'url*al=somenumber' where somenumber is an access level -
         *      Only display subtab if current user userlevel >= somenumber.
         * Subtab url = 'url*al=somenumber@somesecuredobject' where somenumber is an access level and somesecuredobject is secured objec name -
         *      Only display subtab if current user userlevel_for_securedobject >= somenumber.
         *
         * Subtab url = 'url*js=javascript code' where javascript code is JS commands -
         *      JS code to execute for button OnClick event.
         */

         /* FIXME:  There is too much logic going on here, there should be something that loads settings or evaluates what tabs
                    shouldn't be drawn. */

        echo '<div id="header">', "\n";

        $indexNameForBrand = CATSUtility::getIndexName();
        echo '<div class="sidebar-brand">', "\n";
        echo '<a href="', $indexNameForBrand, '?m=home" class="sidebar-brand-link">', "\n";
        echo '<span class="sidebar-brand-mark"><img src="images/Neutaralogo.jpg" alt="" onerror="this.parentNode.textContent=\'N\';" /></span>', "\n";
        echo '<span class="sidebar-brand-text">', "\n";
        echo '<span class="sidebar-brand-title">Neutara ATS</span>', "\n";
        echo '<span class="sidebar-brand-subtitle">Recruiting Suite</span>', "\n";
        echo '</span>', "\n";
        echo '</a>', "\n";
        echo '</div>', "\n";

        echo '<ul id="primary">', "\n";
        
        /* Nav icons — flat line-style SVGs (no emoji, renders consistently
         * across platforms and matches the minimal sidebar aesthetic). */
        $svgAttrs = 'width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"';
        $navIcons = array(
            'home' => '<svg '.$svgAttrs.'><path d="M3 11l9-8 9 8"/><path d="M5 10v10a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V10"/></svg>',
            'activity' => '<svg '.$svgAttrs.'><polyline points="3 12 8 12 10 18 14 6 16 12 21 12"/></svg>',
            'joborders' => '<svg '.$svgAttrs.'><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>',
            'candidates' => '<svg '.$svgAttrs.'><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'companies' => '<svg '.$svgAttrs.'><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/></svg>',
            'contacts' => '<svg '.$svgAttrs.'><rect x="2" y="4" width="20" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="M15 8h4"/><path d="M15 12h4"/><path d="M5 16c1-2 3-3 4-3s3 1 4 3"/></svg>',
            'lists' => '<svg '.$svgAttrs.'><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>',
            'calendar' => '<svg '.$svgAttrs.'><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
            'reports' => '<svg '.$svgAttrs.'><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>',
            'settings' => '<svg '.$svgAttrs.'><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
            'orgchart' => '<svg '.$svgAttrs.'><circle cx="12" cy="5" r="2.5"/><circle cx="5" cy="19" r="2.5"/><circle cx="19" cy="19" r="2.5"/><path d="M12 7.5v5"/><path d="M12 12.5H5v4"/><path d="M12 12.5h7v4"/></svg>'
        );

        $indexName = CATSUtility::getIndexName();

        $modules = ModuleUtility::getModules();
        foreach ($modules as $moduleName => $parameters)
        {
            $tabText = $parameters[1];

            /* Don't display a module's tab if $tabText is empty. */
            if (empty($tabText))
            {
                continue;
            }

            /* If name = Companies and HR mode is on, change tab name to My Company. */
            if ($_SESSION['CATS']->isHrMode() && $tabText == 'Companies')
            {
                $tabText = 'My Company';
            }

            /* Allow a hook to prevent a module from being displayed. */
            $displayTab = true;

            if (!eval(Hooks::get('TEMPLATE_UTILITY_EVALUATE_TAB_VISIBLE'))) return;

            if (!$displayTab)
            {
                continue;
            }

            /* Inactive Tab? */
            if ($active === null || $moduleName != $active->getModuleName())
            {
                if ($moduleName == $forceHighlight)
                {
                    $className = 'active';
                }
                else
                {
                    $className = 'inactive';
                }

                $alPosition = strpos($tabText, "*al=");
                if ($alPosition === false)
                {
                    $icon = isset($navIcons[strtolower($moduleName)]) ? $navIcons[strtolower($moduleName)] : '';
                    echo '<li><a class="', $className, '" href="', $indexName,
                         '?m=', $moduleName, '" data-tooltip="', htmlspecialchars($tabText), '"><span class="nav-icon">', $icon, '</span>', $tabText, '</a></li>', "\n";
                }
                else
                {
                     $al = substr($tabText, $alPosition + 4);
                     $soPosition = strpos($al, "@");
                     $soName = '';
                     if( $soPosition !== false )		
                     {		
                         $soName = substr($al, $soPosition + 1);		
                         $al = substr($al, 0, $soPosition);		
                     }		
                     if ($_SESSION['CATS']->getAccessLevel($soName) >= $al ||
                         $_SESSION['CATS']->isDemo())
                     {
                        $icon = isset($navIcons[strtolower($moduleName)]) ? $navIcons[strtolower($moduleName)] : '';
                        $displayText = substr($tabText, 0, $alPosition);
                        echo '<li><a class="', $className, '" href="', $indexName, '?m=', $moduleName, '" data-tooltip="', htmlspecialchars($displayText), '"><span class="nav-icon">', $icon, '</span>',
                             $displayText, '</a></li>', "\n";
                    }
                }

                continue;
            }

            $alPosition = strpos($tabText, "*al=");
            if ($alPosition !== false)
            {
                $tabText = substr($tabText, 0, $alPosition);
            }

            /* Start the <li> block for the active tab. The secondary <ul>
             * for subtabs MUST be contained within this block. It is
             * closed after subtabs are printed. */
            echo '<li>';

            $icon = isset($navIcons[strtolower($moduleName)]) ? $navIcons[strtolower($moduleName)] : '';
            echo '<a class="active" href="', $indexName, '?m=', $moduleName,
                 '" data-tooltip="', htmlspecialchars($tabText), '"><span class="nav-icon">', $icon, '</span>', $tabText, '</a>', "\n";

            $subTabs = $active->getSubTabs($modules);
            if ($subTabs)
            {
                echo '<ul id="secondary">';

                foreach ($subTabs as $subTabText => $link)
                {
                    if ($subTabText == $subActive)
                    {
                        $style = "color:#cccccc;";
                    }
                    else
                    {
                        $style = "";
                    }

                    /* Check HR mode for displaying tab. */
                    $hrmodePosition = strpos($link, "*hrmode=");
                    if ($hrmodePosition !== false)
                    {
                        /* Access level restricted subtab. */
                        $hrmode = substr($link, $hrmodePosition + 8);
                        if ((!$_SESSION['CATS']->isHrMode() && $hrmode == 0) ||
                            ($_SESSION['CATS']->isHrMode() && $hrmode == 1))
                        {
                            $link =  substr($link, 0, $hrmodePosition);
                        }
                        else
                        {
                            $link = '';
                        }
                    }

                    /* Check access level for displaying tab. */
                    $alPosition = strpos($link, "*al=");
                    if ($alPosition !== false)
                    {
                        /* Access level restricted subtab. */
                        $al = substr($link, $alPosition + 4);
                        $soPosition = strpos($al, "@");
                        $soName = '';
                        if( $soPosition !== false )		
                        {		
                            $soName = substr($al, $soPosition + 1);		
                            $al = substr($al, 0, $soPosition);		
                        }		
                        if ($_SESSION['CATS']->getAccessLevel($soName) >= $al ||
                            $_SESSION['CATS']->isDemo())
                        {
                            $link =  substr($link, 0, $alPosition);
                        }
                        else
                        {
                            $link = '';
                        }
                    }

                    $jsPosition = strpos($link, "*js=");
                    if ($jsPosition !== false)
                    {
                        /* Javascript subtab. */
                        echo '<li><a href="', substr($link, 0, $jsPosition), '" onclick="',
                             substr($link, $jsPosition + 4), '" style="'.$style.'">', $subTabText, '</a></li>', "\n";
                    }

                    /* A few subtabs have special logic to decide if they display or not. */
                    /* FIXME:  Put the logic for these somewhere else.  Perhaps the definitions of the subtabs
                               themselves should have an eval()uatable rule?
                               Brian 6-14-07:  Second.  */
                    else if (strpos($link, 'a=internalPostings') !== false)
                    {
                        /* Default company subtab. */
                        include_once(LEGACY_ROOT . '/lib/Companies.php');

                        $companies = new Companies($_SESSION['CATS']->getSiteID());
                        $defaultCompanyID = $companies->getDefaultCompany();
                        if ($defaultCompanyID !== false)
                        {
                            echo '<li><a href="', $link, '" style="'.$style.'">', $subTabText, '</a></li>', "\n";
                        }
                    }
                    else if (strpos($link, 'a=administration') !== false)
                    {
                        /* Administration subtab. */
                        if ($_SESSION['CATS']->getAccessLevel('settings.administration') >= ACCESS_LEVEL_DEMO)
                        {
                            echo '<li><a href="', $link, '" style="'.$style.'">', $subTabText, '</a></li>', "\n";
                        }
                    }
                    else if (strpos($link, 'a=customizeEEOReport') !== false)
                    {
                        /* EEO Report subtab.  Shouldn't be visible if EEO tracking is disabled. */
                        $EEOSettings = new EEOSettings($_SESSION['CATS']->getSiteID());
                        $EEOSettingsRS = $EEOSettings->getAll();

                        if ($EEOSettingsRS['enabled'] == 1)
                        {
                            echo '<li><a href="', $link, '" style="'.$style.'">', $subTabText, '</a></li>', "\n";
                        }
                    }


                    /* Tab is ok to draw. */
                    else if ($link != '')
                    {
                        /* Normal subtab. */
                        echo '<li><a href="', $link, '" style="'.$style.'">', $subTabText, '</a></li>', "\n";
                    }
                }

                if (!eval(Hooks::get('TEMPLATE_UTILITY_DRAW_SUBTABS'))) return;

                echo '</ul>';
            }

            echo '</li>';
        }
        echo '</ul>', "\n";

        /* Logout button at bottom of sidebar */
        $indexName = CATSUtility::getIndexName();
        echo '<div class="sidebar-logout">';
        echo '<a href="', $indexName, '?m=logout" class="sidebar-logout-btn">';
        echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>';
        echo ' Logout';
        echo '</a>';
        echo '</div>', "\n";

        echo '</div>', "\n";
    }

    /**
     * Prints the breadcrumb + title row used at the top of detail/show
     * pages ("‹ List Name / Current Record›"), so every module reuses one
     * markup shape instead of hand-copying its own breadcrumb HTML.
     *
     * @param string $listURL      href for the "back to list" link
     * @param string $listLabel    label for the "back to list" link (e.g. 'Job Orders')
     * @param string $currentLabel current record's display name (e.g. a candidate's full name)
     * @param string $iconPath     optional module icon image path
     * @return void
     */
    public static function printBreadcrumb($listURL, $listLabel, $currentLabel, $iconPath = null)
    {
        echo '<div class="page-header-row">', "\n";
        echo '    <div class="page-header-left">', "\n";
        if ($iconPath !== null)
        {
            echo '        <img src="', $iconPath, '" width="24" height="24" alt="" class="page-header-icon" />', "\n";
        }
        echo '        <h2 class="page-breadcrumb">';
        echo '<a href="', $listURL, '">', htmlspecialchars($listLabel), '</a>';
        echo '<span class="page-breadcrumb-sep">&rsaquo;</span>';
        echo '<span class="page-breadcrumb-current">', htmlspecialchars($currentLabel), '</span>';
        echo '</h2>', "\n";
        echo '    </div>', "\n";
        echo '</div>', "\n";
    }

    /**
     * Prints footer HTML for non-report pages.
     *
     * @return void
     */
    public static function printFooter()
    {
        $build    = $_SESSION['CATS']->getCachedBuild();
        $loadTime = $_SESSION['CATS']->getExecutionTime();

        if ($build > 0)
        {
            $buildString = ' build ' . $build;
        }
        else
        {
            $buildString = '';
        }

        /* THE MODIFICATION OF THE COPYRIGHT AND 'Powered by OpenCATS' LINES IS NOT ALLOWED
           BY THE TERMS OF THE CPL FOR OpenCATS OPEN SOURCE EDITION.

             II) The following copyright notice must be retained and clearly legible
             at the bottom of every rendered HTML document: Copyright (C) 2007-2023
             OpenCATs All rights reserved.

             III) The "Powered by OpenCATS" text or logo must be retained and clearly
             legible on every rendered HTML document. The logo, or the text
             "OpenCATS", must be a hyperlink to the CATS Project website, currently
             http://www.opencats.org/.
       */

        /* Mobile sidebar overlay */
        echo '<div class="sidebar-overlay"></div>', "\n";

        echo '<div class="footerBlock">', "\n";
        echo '<p id="footerText"><span id="toolbarVersion"></span>Powered by <a href="https://www.neutara.com/"><strong>Neutara ATS Tool</strong></a>.</p>', "\n";
        echo '<span id="footerCopyright">', COPYRIGHT_HTML, '</span>', "\n";
        if (!eval(Hooks::get('TEMPLATEUTILITY_SHOWPRIVACYPOLICY'))) return;
        echo '</div>', "\n";

        eval(Hooks::get('TEMPLATE_UTILITY_PRINT_FOOTER'));

        /* Sidebar toggle JavaScript */
        echo '<script>
function toggleSidebar() {
    document.body.classList.toggle("sidebar-collapsed");
    
    // Save preference to localStorage
    var isCollapsed = document.body.classList.contains("sidebar-collapsed");
    localStorage.setItem("sidebarCollapsed", isCollapsed ? "1" : "0");
}

// Restore sidebar state on page load
document.addEventListener("DOMContentLoaded", function() {
    var isCollapsed = localStorage.getItem("sidebarCollapsed");
    if (isCollapsed === "1") {
        document.body.classList.add("sidebar-collapsed");
    }
    
    // Close sidebar overlay on mobile
    var overlay = document.querySelector(".sidebar-overlay");
    if (overlay) {
        overlay.addEventListener("click", function() {
            document.body.classList.remove("sidebar-collapsed");
            localStorage.setItem("sidebarCollapsed", "0");
        });
    }
});
</script>', "\n";

        echo '</body>', "\n";
        echo '</html>', "\n";

        if (LicenseUtility::isProfessional() && !rand(0,10))
        {
            if (!LicenseUtility::validateProfessionalKey(LICENSE_KEY))
            {
                CATSUtility::changeConfigSetting('LICENSE_KEY', "''");
            }
        }
    }

    /**
     * Prints footer HTML for report pages.
     *
     * @return void
     */
    public static function printReportFooter()
    {
        $build = $_SESSION['CATS']->getCachedBuild();

        // FIXME: LOCAL TIME ZONE!
        $date  = date('l, F jS, Y \a\t h:i:s A T');

        if ($build > 0)
        {
            $buildString = ' build ' . $build;
        }
        else
        {
            $buildString = '';
        }

        echo '<div class="footerBlock">', "\n";
        echo '<p id="footerText">Report generated on ', $date, '.<br />', "\n";
        echo 'Neutara ATS Tool Version ', CATS_VERSION, $buildString,
             '. Powered by <a href="http://www.catsone.com/"><strong>Neutara ATS Tool</strong></a>.</p>', "\n";
        echo '<span id="footerCopyright">', COPYRIGHT_HTML, '</span>', "\n";
        echo '</div>', "\n";

        echo '</body>', "\n";
        echo '</html>', "\n";
    }

    /**
     * Prints HTML for pipeline candidate-joborder match rating stars.
     *
     * @param integer rating (0-5)
     * @param integer candidate-joborder ID
     * @param string PHP session cookie
     * @return string HTML
     */
    public static function getRatingObject($rating, $candidateJobOrderID, $sessionCookie)
    {
        static $firstCall = true;

        /* These usually come straight from the database; make sure it's an
         * integer.
         */
        $rating = (int) $rating;

        $ratings = self::_getRatingImages();
        $indexName = CATSUtility::getIndexName();

        if ($_SESSION['CATS']->getAccessLevel('pipelines.editRating') < ACCESS_LEVEL_EDIT)
        {
            $HTML = '<img src="' . $ratings[$rating] . '" style="border: none;" alt="" id="moImage' . $candidateJobOrderID . '" />';
            return $HTML;
        }

        $HTML  = '<!--MATCHROW moImageValue' . $candidateJobOrderID . '-->';
        if ($rating >= 0)
        {
            $HTML .= '<img src="' . $ratings[$rating] . '" style="border: none;" alt="" id="moImage' . $candidateJobOrderID . '" usemap="#moImageMapPos' . $candidateJobOrderID . '" />';
        }
        else
        {
            $HTML .= '<img src="' . $ratings[$rating] . '" style="border: none;" alt="" id="moImage' . $candidateJobOrderID . '" usemap="#moImageMapNeg' . $candidateJobOrderID . '" />';
            $HTML .= '<map id ="moImageMapNeg' . $candidateJobOrderID . '" name="moImageMapNeg' . $candidateJobOrderID . '">';
            $HTML .= '<area shape="rect" coords="0,0,3,12"  href="javascript:void(0);" onmouseout="showImage(\'moImage' . $candidateJobOrderID . '\', moImageValue' . $candidateJobOrderID . ');" onmouseover="showImage(\'moImage' . $candidateJobOrderID . '\', 0);" onclick="moImageValue' . $candidateJobOrderID . ' = 0; setRating(' . $candidateJobOrderID . ', 0, \'moImage' . $candidateJobOrderID . '\', \'' . $sessionCookie . '\');" alt="">';
            $HTML .= '<area shape="rect" coords="4,1,12,12"  href="javascript:void(0);" onmouseout="showImage(\'moImage' . $candidateJobOrderID . '\', moImageValue' . $candidateJobOrderID . ');" onmouseover="showImage(\'moImage' . $candidateJobOrderID . '\', 7);" onclick="moImageValue' . $candidateJobOrderID . ' = -2; setRating(' . $candidateJobOrderID . ', -2, \'moImage' . $candidateJobOrderID . '\', \'' . $sessionCookie . '\');" alt="">';
            $HTML .= '<area shape="rect" coords="13,1,23,12" href="javascript:void(0);" onmouseout="showImage(\'moImage' . $candidateJobOrderID . '\', moImageValue' . $candidateJobOrderID . ');" onmouseover="showImage(\'moImage' . $candidateJobOrderID . '\', 8);" onclick="moImageValue' . $candidateJobOrderID . ' = -3; setRating(' . $candidateJobOrderID . ', -3, \'moImage' . $candidateJobOrderID . '\', \'' . $sessionCookie . '\');" alt="">';
            $HTML .= '<area shape="rect" coords="24,1,34,12" href="javascript:void(0);" onmouseout="showImage(\'moImage' . $candidateJobOrderID . '\', moImageValue' . $candidateJobOrderID . ');" onmouseover="showImage(\'moImage' . $candidateJobOrderID . '\', 9);" onclick="moImageValue' . $candidateJobOrderID . ' = -4; setRating(' . $candidateJobOrderID . ', -4, \'moImage' . $candidateJobOrderID . '\', \'' . $sessionCookie . '\');" alt="">';
            $HTML .= '<area shape="rect" coords="35,1,45,12" href="javascript:void(0);" onmouseout="showImage(\'moImage' . $candidateJobOrderID . '\', moImageValue' . $candidateJobOrderID . ');" onmouseover="showImage(\'moImage' . $candidateJobOrderID . '\', 10);" onclick="moImageValue' . $candidateJobOrderID . ' = -5; setRating(' . $candidateJobOrderID . ', -5, \'moImage' . $candidateJobOrderID . '\', \'' . $sessionCookie . '\');" alt="">';
            $HTML .= '<area shape="rect" coords="46,1,56,12" href="javascript:void(0);" onmouseout="showImage(\'moImage' . $candidateJobOrderID . '\', moImageValue' . $candidateJobOrderID . ');" onmouseover="showImage(\'moImage' . $candidateJobOrderID . '\', 11);" onclick="moImageValue' . $candidateJobOrderID . ' = -6; setRating(' . $candidateJobOrderID . ', -6, \'moImage' . $candidateJobOrderID . '\', \'' . $sessionCookie . '\');" alt="">';
            $HTML .= '</map>';
        }
        $HTML .= '<map id ="moImageMapPos' . $candidateJobOrderID . '" name="moImageMapPos' . $candidateJobOrderID . '">';
        $HTML .= '<area shape="rect" coords="0,0,3,12"  href="javascript:void(0);" onmouseout="showImage(\'moImage' . $candidateJobOrderID . '\', moImageValue' . $candidateJobOrderID . ');" onmouseover="showImage(\'moImage' . $candidateJobOrderID . '\', 0);" onclick="moImageValue' . $candidateJobOrderID . ' = 0; setRating(' . $candidateJobOrderID . ', 0, \'moImage' . $candidateJobOrderID . '\', \'' . $sessionCookie . '\');" alt="">';
        $HTML .= '<area shape="rect" coords="4,1,12,12"  href="javascript:void(0);" onmouseout="showImage(\'moImage' . $candidateJobOrderID . '\', moImageValue' . $candidateJobOrderID . ');" onmouseover="showImage(\'moImage' . $candidateJobOrderID . '\', 1);" onclick="moImageValue' . $candidateJobOrderID . ' = 1; setRating(' . $candidateJobOrderID . ', 1, \'moImage' . $candidateJobOrderID . '\', \'' . $sessionCookie . '\');" alt="">';
        $HTML .= '<area shape="rect" coords="13,1,23,12" href="javascript:void(0);" onmouseout="showImage(\'moImage' . $candidateJobOrderID . '\', moImageValue' . $candidateJobOrderID . ');" onmouseover="showImage(\'moImage' . $candidateJobOrderID . '\', 2);" onclick="moImageValue' . $candidateJobOrderID . ' = 2; setRating(' . $candidateJobOrderID . ', 2, \'moImage' . $candidateJobOrderID . '\', \'' . $sessionCookie . '\');" alt="">';
        $HTML .= '<area shape="rect" coords="24,1,34,12" href="javascript:void(0);" onmouseout="showImage(\'moImage' . $candidateJobOrderID . '\', moImageValue' . $candidateJobOrderID . ');" onmouseover="showImage(\'moImage' . $candidateJobOrderID . '\', 3);" onclick="moImageValue' . $candidateJobOrderID . ' = 3; setRating(' . $candidateJobOrderID . ', 3, \'moImage' . $candidateJobOrderID . '\', \'' . $sessionCookie . '\');" alt="">';
        $HTML .= '<area shape="rect" coords="35,1,45,12" href="javascript:void(0);" onmouseout="showImage(\'moImage' . $candidateJobOrderID . '\', moImageValue' . $candidateJobOrderID . ');" onmouseover="showImage(\'moImage' . $candidateJobOrderID . '\', 4);" onclick="moImageValue' . $candidateJobOrderID . ' = 4; setRating(' . $candidateJobOrderID . ', 4, \'moImage' . $candidateJobOrderID . '\', \'' . $sessionCookie . '\');" alt="">';
        $HTML .= '<area shape="rect" coords="46,1,56,12" href="javascript:void(0);" onmouseout="showImage(\'moImage' . $candidateJobOrderID . '\', moImageValue' . $candidateJobOrderID . ');" onmouseover="showImage(\'moImage' . $candidateJobOrderID . '\', 5);" onclick="moImageValue' . $candidateJobOrderID . ' = 5; setRating(' . $candidateJobOrderID . ', 5, \'moImage' . $candidateJobOrderID . '\', \'' . $sessionCookie . '\');" alt="">';
        $HTML .= '</map>';

        $HTML .= '<script type="text/javascript">';
        $HTML .= 'moImageValue' . $candidateJobOrderID . ' = ' . $rating . ';';

        $HTML .= '</script>';

        /* Only on the first call... */
        if ($firstCall)
        {
            $HTML .= self::getRatingsArrayJS();
        }

        return $HTML;
    }

    /**
     * Prints out the image array of ratings for associated JavaScript.
     *
     * @param integer table row number
     * @return void
     */
    public static function getRatingsArrayJS()
    {
        $ratings = self::_getRatingImages();

        $HTML = '<script type="text/javascript">';

        foreach ($ratings as $rating)
        {
            $ratingsQuoted[] = '"' . $rating . '"';
        }

        $ratingsQuotedString = implode(',', $ratingsQuoted);
        $HTML .= "\n" . 'defineImages(new Array(' . $ratingsQuotedString . '));';

        $HTML .= "\n" . '</script>';

        return $HTML;
    }

    // TODO: Document me.
    public static function getDataItemTypeDescription($dataItemType)
    {
        switch ($dataItemType)
        {
            case DATA_ITEM_CANDIDATE:
                return 'Candidate';
                break;

            case DATA_ITEM_COMPANY:
                return 'Company';
                break;

            case DATA_ITEM_CONTACT:
                return 'Contact';
                break;

            case DATA_ITEM_JOBORDER:
                return 'Joborder';
                break;

            default:
                return '';
        }
    }

    /**
     * Prints out the class name for the current row number (for tables where
     * row color alternates). Even row numbers get the 'evenTableRow' class;
     * odd numbers get the 'oddTableRow' class.
     *
     * @param integer table row number
     * @return void
     */
    public static function printAlternatingRowClass($rowNumber)
    {
        /* Is the row number even? */
        if (($rowNumber % 2) == 0)
        {
            echo 'evenTableRow';
            return;
        }

        echo 'oddTableRow';
    }

    /**
     * Prints out the class name for the current row number (for div pairs where
     * row color alternates). Even row numbers get the 'evenTableRow' class;
     * odd numbers get the 'oddTableRow' class.
     *
     * @param integer div row number
     * @return void
     */
    public static function printAlternatingDivClass($rowNumber)
    {
        /* Is the row number even? */
        if (($rowNumber % 2) == 0)
        {
            echo 'evenDivRow';
            return;
        }

        echo 'oddDivRow';
    }

    /**
     * Returns the class name for the current row number (for tables where
     * row color alternates). Even row numbers get the 'evenTableRow' class;
     * odd numbers get the 'oddTableRow' class.
     *
     * @param integer table row number
     * @return void
     */
    public static function getAlternatingRowClass($rowNumber)
    {
        /* Is the row number even? */
        if (($rowNumber % 2) == 0)
        {
            return 'evenTableRow';
        }
        else
        {
            return 'oddTableRow';
        }
    }

    /**
     * Removes from $text everything from starting block through ending block.
     * Optionally also removes a following piece of text indicated by closing
     * tag.
     *
     * For example, lets say you had the following text:
     *
     *   <a href="blah/blah.html?id=55"><b>My Link</b></a>
     *
     * If you wanted to remove the hyperlink from the text for every occurrence
     * of this format of link, you could use:
     *
     *   $HTML = filterRemoveTextBlock(
     *       $HTML, '<a href="blah/blah.html?id=', '>', '</a>'
     *   );
     *
     * and the link would be replaced with '<b>My Link</b>' in the returned
     * text / HTML.
     *
     * @param string output HTML to filter
     * @param string text at start of text to be removed
     * @param string text at end of text to be removed
     * @param string closing tag to be removed
     * @return string filtered HTML output
     */
    public static function filterRemoveTextBlock($text, $startBlock, $endBlock, $closingTag = '')
    {
        $startPos = strpos($text, $startBlock);
        if ($startPos !== false)
        {
            $endPos = strpos(substr($text, $startPos + strlen($startBlock)), $endBlock);
        }
        else
        {
            $endPos = false;
        }

        while ($startPos !== false || $endPos !== false)
        {
            if ($startPos === false)
            {
                $startPos = 0;
            }

            if ($endPos === false)
            {
                $endPos = 0;
            }
            else
            {
                $endPos += strlen($endBlock);
            }

            $text = substr_replace($text, '', $startPos, $endPos + strlen($startBlock));

            if ($closingTag != '')
            {
                $closingPos = strpos(substr($text, $startPos), $closingTag);

                if ($closingPos !== false)
                {
                    $text = substr_replace($text, '', $closingPos + $startPos, strlen($closingTag));
                }
            }

            $startPos = strpos($text, $startBlock);
            if ($startPos !== false)
            {
                $endPos = strpos(substr($text, $startPos + strlen($startBlock)), $endBlock);
            }
            else
            {
                $endPos = false;
            }
        }

        return $text;
    }

    public static function printSingleQuickActionMenu(QuickActionMenu $menu)
    {
        return $menu->getHtml();
    }

    public static function _printQuickActionMenuHolder()
    {
        echo '<div class="ajaxSearchResults" id="singleQuickActionMenu" align="left" style="width:200px;" onclick="toggleVisibility()">';

        echo '</div>';
    }

    /**
     * Prints template header HTML.
     *
     * @param string page title
     * @param array JavaScript / CSS files to load
     * @return void
     */
    private static function _printCommonHeader($pageTitle, $headIncludes = array())
    {
        if (!is_array($headIncludes))
        {
            $headIncludes = array($headIncludes);
        }

        $siteID = $_SESSION['CATS']->getSiteID();

        /* This prevents caching problems when SVN updates are preformed. */
        if ($_SESSION['CATS']->getCachedBuild() > 0)
        {
            $javascriptAntiCache = '?b=' . $_SESSION['CATS']->getCachedBuild();
        }
        else
        {
            $javascriptAntiCache = '?v=' . CATSUtility::getVersionAsInteger();
        }

        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"', "\n";
        echo '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">', "\n";
        echo '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">', "\n";
        echo '<head>', "\n";
        echo '<title>Neutara ATS Tool - ', $pageTitle, '</title>', "\n";
        echo '<meta http-equiv="Content-Type" content="text/html; charset=', HTML_ENCODING, '" />', "\n";
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0" />', "\n";
        $faviconSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><rect width="32" height="32" rx="8" fill="#0c18d4"/><text x="16" y="22" font-family="Arial, sans-serif" font-size="18" font-weight="800" fill="#fff" text-anchor="middle">N</text></svg>';
        echo '<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,' . rawurlencode($faviconSvg) . '" />', "\n";
        echo '<link href="inter.css'.$javascriptAntiCache.'" rel="stylesheet" />', "\n";
        echo '<link rel="alternate" type="application/rss+xml" title="RSS" href="',
             CATSUtility::getIndexName(), '?m=rss" />', "\n";

        /* Core JS files — lib.js, quickAction.js, calendarDateInput.js,
         * and submodal/subModal.js are combined into one bundle
         * (js/core-bundle.js) since they load unconditionally on every page;
         * see that file's header comment for how to regenerate it. jQuery
         * stays separate since it's vendored third-party code. */
        echo '<script type="text/javascript" src="js/core-bundle.js'.$javascriptAntiCache.'"></script>', "\n";
        echo '<script type="text/javascript" src="js/jquery-1.3.2.min.js'.$javascriptAntiCache.'"></script>', "\n";
        echo '<script type="text/javascript">CATSIndexName = "'.CATSUtility::getIndexName().'";</script>', "\n";

       $headIncludes[] = 'main.css';

        foreach ($headIncludes as $key => $filename)
        {
            $extension = substr($filename, strrpos($filename, '.') + 1);

            /* main.css changes far more often than the version string does
             * (which is what $javascriptAntiCache is normally keyed on), so
             * a stale browser cache can silently keep serving an old
             * stylesheet indefinitely. Key it on the file's own mtime instead
             * so every edit busts the cache automatically. */
            if ($filename === 'main.css' && file_exists($filename))
            {
                $filename .= '?m=' . filemtime($filename);
            }
            else
            {
                $filename .= $javascriptAntiCache;
            }

            if ($extension == 'js')
            {
                echo '<script type="text/javascript" src="', $filename, '"></script>', "\n";
            }
            else if ($extension == 'css')
            {
                echo '<style type="text/css" media="all">@import "', $filename, '";</style>', "\n";
            }
        }

        echo '<!--[if IE]><link rel="stylesheet" type="text/css" href="ie.css" /><![endif]-->', "\n";
        echo '<![if !IE]><link rel="stylesheet" type="text/css" href="not-ie.css" /><![endif]>', "\n";
        echo '</head>', "\n\n";
    }


    /**
     * Returns an array of "star" images for rating values.
     *
     * @return array rating values and associated image paths
     */
    private static function _getRatingImages()
    {
        return array(
            0  => 'images/stars/star0.gif',
            1  => 'images/stars/star1.gif',
            2  => 'images/stars/star2.gif',
            3  => 'images/stars/star3.gif',
            4  => 'images/stars/star4.gif',
            5  => 'images/stars/star5.gif',
            -1 => 'images/stars/starneg1.gif',
            -2 => 'images/stars/starneg2.gif',
            -3 => 'images/stars/starneg3.gif',
            -4 => 'images/stars/starneg4.gif',
            -5 => 'images/stars/starneg5.gif',
            -6 => 'images/stars/starneg6.gif'
        );
    }
}

?>
