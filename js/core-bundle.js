/* ============================================================
 * js/core-bundle.js — combined core scripts loaded on every page.
 * Concatenated (not minified) from the files below, in this order,
 * to cut down on per-page request count. Edit the SOURCE files
 * listed here, then re-run the concatenation — do not hand-edit
 * this bundle directly, it will be regenerated and your changes lost.
 * Source files: js/lib.js, js/quickAction.js, js/calendarDateInput.js,
 * js/submodal/subModal.js
 * ============================================================ */

/* ---- js/lib.js ---- */
/*
 * CATS
 * JavaScript Library
 *
 * Portions Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 *
 * EventCache Copyright (C) 2005 Mark Wubben with modifications made
 * by Cognizo Technologies, Inc. EventCache is licensed under the CC-GNU
 * LGPL <http://creativecommons.org/licenses/LGPL/2.1/>.
 *
 * addEvent() Copyright (C) 2001 Scott Andrew LePera with modifications
 * made by Cognizo Technologies, Inc. No license was given; however,
 * modifications made by Cognizo Technologies, Inc. are subject to the
 * terms of the CATS Public License Version 1.1 (see below).
 * http://www.scottandrew.com/weblog/articles/cbs-events
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
 *
 * $Id: lib.js 3488 2007-11-08 02:19:17Z will $
 */

/* Data item type flags. These should match up with the flags
 * from config.php.
 */
var DATA_ITEM_CANDIDATE = 100;
var DATA_ITEM_COMPANY   = 200;
var DATA_ITEM_CONTACT   = 300;
var DATA_ITEM_JOBORDER  = 400;

/* Set by TemplateUtility drawing headers. */
var CATSIndexName;

/* Default timeout for AJAX requests; 15 seconds. */
var AJAX_TIMEOUT = 15000;

function toggleVisibility()
{
    var singleQuickActionMenu = document.getElementById('singleQuickActionMenu');
    singleQuickActionMenu.style.display = singleQuickActionMenu.style.display == 'block' ? 'none' : 'block';
}

/**
 * Returns true if the string is a valid positive integer.
 *
 * @return boolean
 */
function stringIsNumeric(string)
{
    return !isNaN(string);
}

/**
 * Changes a parent document block's style attribute to make it hidden (by id).
 *
 * @return void
 */
function hideParentBlock(elementID)
{
    var element = parent.document.getElementById(elementID);
    element.parentNode.removeChild(element);
}

/**
 * Changes a parent document block's style attribute to make it hidden (by id).
 *
 * @return void
 */
function showParentBlock(elementID)
{
    var element = parent.document.getElementById(elementID);
    element.style.display = 'block';
}

/**
 * Opens a centered popup window.
 *
 * @return void
 */
function openCenteredPopup(url, name, width, height, scrollBars)
{
    var optionString;

    optionString  = 'width=' + width + ',height=' + height;
    optionString += ',top=' + ((screen.availHeight - height) / 2) + ',left=' + ((screen.availWidth - width) / 2);
    optionString += ',scrollbars=';
    optionString += (scrollBars ? 'yes' : 'no');

    /* Open the new window. */
    newWindow = window.open(url, name, optionString);

    /* If this window (parent) has focus, give focus to the popup (child). */
    if (window.focus)
    {
        newWindow.focus();
    }
}

/**
 * Redirects the browser to a url.
 *
 * @return void
 */
function goToURL(url)
{
    window.location = url;
}

/**
 * Redirects the browser to a url.
 *
 * @return void
 */
function parentGoToURL(url)
{
    parent.window.location = url;
}

function parentHidePopWin()
{
    parent.hidePopWin();
}

function parentHidePopWinRefresh()
{
    parent.hidePopWinRefresh();
}

function parentSetPopTitle(title)
{
    parent.setPopTitle(title);
}

/**
 * Replaces HTML special characters in text to be output-safe.
 *
 * @param string text to escape
 * @return string escaped text
 */
function escapeHTML(text)
{
    text = text.replace(/&/g, '&amp;');
    text = text.replace(/</g, '&lt;');
    text = text.replace(/>/g, '&gt;');
    text = text.replace(/"/g, '&quot;');
    text = text.replace(/'/g, '&apos;');

    return text;
}

/**
 * Replaces output-save HTML with real text characters.
 *
 * @param string text to unescape
 * @return string escaped text
 */
function unEscapeHTML(text)
{
    text = text.replace(/&amp;/g, '&');
    text = text.replace(/&lt;/g, '<');
    text = text.replace(/&gt;/g, '>');
    text = text.replace(/&quot;/g, '"');
    text = text.replace(/&apos;/g, "'");

    return text;
}

/**
 * Encodes text for transmission via HTTP.
 *
 * @param string text to encode
 * @return string encoded text
 */
function urlEncode(text)
{
    /* Force JavaScript to always treat 'text' as a string. */
    text += '';

    /* encodeURIComponent() doesn't handle the ' character. */
    text = text.replace(/\'/g, '%27');
    
    /* Don't use escape(), as it doesn't properly handle UTF-8. */
    text = encodeURIComponent(text);

    return text;
}

/**
 * Acts the same as PHP's urldecode.
 *
 * @param string text to unescape
 * @return string escaped text
 */
function urlDecode(text)
{
	while (text.indexOf('+') != -1)
	{
    	text = text.replace('+', '%20');
	}
	
    /* Don't use unescape(), as it doesn't properly handle UTF-8. */
	text = decodeURIComponent(text);

    return text;
}

/**
 * Converts a JavaScript array to a seralize()-formatted PHP array in string
 * format.
 *
 * PHP: $myArray = unserialize(urldecode($_POST['myArray']));
 * Remember this is unsafe input and it should not be trusted!
 *
 * Pass this through urlEncode() (above) before adding to a request.
 */
function serializeArray(array)
{
    var string = 'a:' + array.length + ':{';
    
    for (var i = 0; i < array.length; ++i)
    {
        string += 'i:' + i + ';s:' + String(array[i]).length + ':"'
            + String(array[i]) + '";';
    }
    
    return string + '}';
}

/**
 * Removes leading and trailing whitespace from text.
 *
 * @param string text to clean up
 * @return string cleaned string
 */
function trim(text)
{
    return text.replace(/^\s*|\s*$/g, '');
}

/**
 * Gets an XMLHTTP / XMLHttpRequest object for AJAX use.
 *
 * @return void
 */
function AJAX_getXMLHttpObject()
{
    /* Array of possible names for the Microsoft XMLHTTP ActiveX. */
    var MSXML_XMLHTTP_PROGIDS = new Array(
        'Microsoft.XMLHTTP',
        'MSXML2.XMLHTTP',
        'MSXML2.XMLHTTP.5.0',
        'MSXML2.XMLHTTP.4.0',
        'MSXML2.XMLHTTP.3.0'
    );

    var xmlHttp;

    try
    {
        xmlHttp = new XMLHttpRequest();
    }
    catch (errorA)
    {
        var found = false;

        /* Try to figure out what Microsoft might have called their ActiveX control. */
        for (var i = 0; (i < MSXML_XMLHTTP_PROGIDS.length && !found); i++)
        {
            try
            {
                xmlHttp = new ActiveXObject(MSXML_XMLHTTP_PROGIDS[i]);
                found = true;
            }
            catch (errorB)
            {
            }
        }

        if (!found)
        {
            return null;
        }
    }

    return xmlHttp;
}

/**
 * Sends HTTP content headers for an AJAX POST request.
 *
 * @return void
 */
function AJAX_sendPOSTHeaders(http, contentLength)
{
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    /* No more allowed! */
    //http.setRequestHeader('Content-length', contentLength);
    //http.setRequestHeader('Connection', 'close');
}

/**
 * Returns a random hash to append to an HTTP POST to keep data from being
 * cached (URL-encoded).
 *
 * @return random POST variable hash
 */
function AJAX_getRandomPOSTHash()
{
    return '&rhash=' + urlEncode(parseInt(Math.random() * 99999999).toString());
}

/**
 * Returns a formatted session cookie to append to an HTTP POST.
 *
 * @return formatted cookie
 */
function AJAX_getPOSTSessionID(sessionCookie)
{
    return '&' + sessionCookie;
}

/**
 * Sends an AJAX HTTP POST request back to the specified URL.
 *
 * @return void
 */
function AJAX_POST(http, url, POSTData, callBack, timeout, sessionCookie,
    silentTimeout)
{
    /* Add a random hash to the POST data to keep IE from caching it. */
    POSTData += AJAX_getRandomPOSTHash();

    /* Append the session cookie if we're using secure AJAX. */
    if (sessionCookie != null)
    {
        POSTData += AJAX_getPOSTSessionID(sessionCookie);
    }

    /* Uncomment for debugging. */
    //alert(POSTData);

    /* Open the socket and send POST headers. */
    http.open('POST', url, true);
    AJAX_sendPOSTHeaders(http, POSTData.length);

    /* Callback function. */
    http.onreadystatechange = callBack;

    /* Send the data. */
    http.send(POSTData);

    /* Abort after timeout expires. */
    if (timeout != 0)
    {
        var timeoutCallback = function()
        {
            if (!AJAX_isCallInProgress(http))
            {
                return;
            }

           http.abort();

           if (!silentTimeout)
           {
               alert(
                   'Timeout on AJAX query after ' + (timeout / 1000) +
                   ' seconds. Please refresh the page and try again.'
               );
           }
        }

        window.setTimeout(timeoutCallback, timeout);
    }
}

/**
 * Sends an AJAX HTTP POST request to the CATS AJAX Delegation Module.
 *
 * @return void
 */
function AJAX_callCATSFunction(http, funcName, POSTData, callBack,
    extraTimeout, sessionCookie, silentTimeout, disableBuffering)
{
    /* Prepend the function name to the postdata. */
    var newPOSTData = 'f=' + funcName + POSTData;

    if (disableBuffering)
    {
        newPOSTData += '&nobuffer=true';
    }

    AJAX_POST(
        http,
        'ajax.php',
        newPOSTData,
        callBack,
        (AJAX_TIMEOUT + extraTimeout),
        sessionCookie,
        silentTimeout
    );
}

/**
 * Is an XMLHTTP object being used for an active call?
 *
 * @return boolean is object active
 */
function AJAX_isCallInProgress(http)
{
    switch (http.readyState)
    {
        case 1:
        case 2:
        case 3:
            return true;
            break;
    }

    return false;
}

/**
 * Is a PHP error message contained in responseText?
 *
 * @return boolean is PHP error
 */
function AJAX_isPHPError(responseText)
{
    return (responseText.indexOf('</b> on line <b>') != -1);
}


/*
 ****************************************************************************
 * Notes / Job Description Truncation
 ****************************************************************************
 */


showFullDescription = false;
showFullNotes       = false;

function toggleDescription()
{
    var shortNode = document.getElementById('shortDescription');
    var fullNode  = document.getElementById('fullDescription');

    toggleNode(showFullDescription, shortNode, fullNode);

    if (showFullDescription == true)
    {
        showFullDescription = false;
    }
    else
    {
        showFullDescription = true;
    }
}

function toggleNotes()
{
    var shortNode = document.getElementById('shortNotes');
    var fullNode  = document.getElementById('fullNotes');

    toggleNode(showFullNotes, shortNode, fullNode);

    if (showFullNotes == true)
    {
        showFullNotes = false;
    }
    else
    {
        showFullNotes = true;
    }
}

function toggleNode(showFull, shortNode, fullNode)
{
    if (showFull == true)
    {
        shortNode.style.display = 'block';
        fullNode.style.display  = 'none';
    }
    else
    {
        shortNode.style.display = 'none';
        fullNode.style.display  = 'block';
    }
}

/**
 * Populates a form's City and State from Zip code using AJAX
 *
 * @return void
 */
function CityState_populate(zipEditID, indicatorID)
{
    var http = AJAX_getXMLHttpObject();

    var zip = document.getElementById(zipEditID).value;
    var indicator = document.getElementById(indicatorID);

    indicator.style.visibility = 'visible';

     /* Build HTTP POST data. */
    var POSTData = '&zip=' + urlEncode(zip);

    /* Anonymous callback function triggered when HTTP response is received. */
    var callBack = function ()
    {
        if (http.readyState != 4)
        {
            return;
        }

        if (!http.responseXML)
        {
            var errorMessage = "An error occurred while receiving a response from the server.\n\n"
                             + http.responseText;
            alert(errorMessage);
            indicator.style.visibility = 'hidden';

            return;
        }

        //alert(http.responseText);

        /* Return if we have any errors. */
        var errorCodeNode    = http.responseXML.getElementsByTagName('errorcode').item(0);
        var errorMessageNode = http.responseXML.getElementsByTagName('errormessage').item(0);
        if (!errorCodeNode.firstChild || errorCodeNode.firstChild.nodeValue != '0')
        {
            if (errorCodeNode.firstChild.nodeValue != '-2')
            {
                var errorMessage = "An error occurred while receiving a response from the server.\n\n"
                                 + errorMessageNode.firstChild.nodeValue;
                /* FIXME
                 * Do we have to popup an error dialog, if the zip lookup AJAX request fails?
                 */
                //alert(errorMessage);
                indicator.style.visibility = 'hidden';
            }
            return;
        }

	var addressNode = http.responseXML.getElementsByTagName('address').item(0);
        var cityNode  = http.responseXML.getElementsByTagName('city').item(0);
        var stateNode = http.responseXML.getElementsByTagName('state').item(0);

	if (document.getElementById('address'))
        {
            if (addressNode.firstChild)
            {
                document.getElementById('address').value = addressNode.firstChild.nodeValue;
            }
            else
            {
                document.getElementById('address').value = '';
            }
        }

        if (document.getElementById('city'))
        {
            if (cityNode.firstChild)
            {
                document.getElementById('city').value = cityNode.firstChild.nodeValue;
            }
            else
            {
                document.getElementById('city').value = '';
            }
        }

        if (document.getElementById('state'))
        {
            if (stateNode.firstChild)
            {
                document.getElementById('state').value = stateNode.firstChild.nodeValue;
            }
            else
            {
                document.getElementById('state').value = '';
            }
        }
        indicator.style.visibility = 'hidden';
    }

    AJAX_callCATSFunction(http, 'zipLookup', POSTData, callBack, 0, null, false, false);
}

/* Returns the value of the radio button that is selected from a radio button
 * group.
 */
function getCheckedValue(radioObj)
{
    if (!radioObj)
    {
        return '';
    }

    var radioLength = radioObj.length;
    if (typeof(radioLength) == 'undefined')
    {
        if (radioObj.checked)
        {
            return radioObj.value;
        }

        return '';
    }

    for (var i = 0; i < radioLength; i++)
    {
        if (radioObj[i].checked)
        {
            return radioObj[i].value;
        }
    }

    return '';
}

/* Checks the specified radio button out of the radio button group by value. */
function setCheckedValue(radioObj, newValue)
{
    if (!radioObj)
    {
        return;
    }

    var radioLength = radioObj.length;
    if (typeof(radioLength) == 'undefined')
    {
        radioObj.checked = (radioObj.value == newValue.toString());
        return;
    }

    for (var i = 0; i < radioLength; i++)
    {
        radioObj[i].checked = false;
        if (radioObj[i].value == newValue.toString())
        {
            radioObj[i].checked = true;
        }
    }
}

function docjslib_getRealLeft(imgElem)
{
    var xPos = eval(imgElem).offsetLeft;
    var tempEl = eval(imgElem).offsetParent;

    while (tempEl != null)
    {
        xPos += tempEl.offsetLeft;
        tempEl = tempEl.offsetParent;
    }

    return xPos;
}

function docjslib_getRealTop(imgElem)
{
    var yPos = eval(imgElem).offsetTop;
    var tempEl = eval(imgElem).offsetParent;

    while (tempEl != null)
    {
        yPos += tempEl.offsetTop;
        tempEl = tempEl.offsetParent;
    }

    return yPos;
}

function findValueInArray(array, value)
{
    for (var i = 0; i < array.length; i++)
    {
        if (array[i] == value)
        {
            return i;
        }
    }

    return -1;
}

function findValueInSelectList(selectList, value)
{
    for (var i = 0; i < selectList.length; i++)
    {
        if (selectList[i].value == value)
        {
            return i;
        }
    }

    return -1;
}

if (Array.prototype.inArray == null)
{
    Array.prototype.inArray = function(value)
    {
        var i;

        for (i = 0; i < this.length; i++)
        {
            if (this[i] === value)
            {
                return true;
            }
        }

        return false;
    };
}

if (Array.prototype.push == null)
{
    Array.prototype.push = function()
    {
        for (var i = 0; i < arguments.length; i++)
        {
            this[this.length] = arguments[i];
        };

        return this.length;
    };
}

/* Event Cache uses an anonymous function to create a hidden scope chain.
 * This is to prevent scoping issues.
 */
var EventCache = function()
{
    var listEvents = [];

    /* This open-brace MUST BE on the same line as the return. */
    return {
        listEvents : listEvents,

        add : function (node, eventName, handler, useCapture)
        {
            listEvents.push(arguments);
        },

        flush : function()
        {
            var i, item;

            for (i = listEvents.length - 1; i >= 0; i = i - 1)
            {
                item = listEvents[i];

                if (item[0].removeEventListener)
                {
                    item[0].removeEventListener(item[1], item[2], item[3]);
                };

                /* From this point on we need the event names to be prefixed
                 * with 'on". */
                if (item[1].substring(0, 2) != 'on')
                {
                    item[1] = 'on' + item[1];
                };

                if (item[0].detachEvent)
                {
                    item[0].detachEvent(item[1], item[2]);
                };

                item[0][item[1]] = null;
            };
        }
    };
}();

function addEvent(obj, type, fn, useCapture)
{
    if (obj.addEventListener)
    {
        obj.addEventListener(type, fn, useCapture);
        EventCache.add(obj, type, fn, useCapture);
    }
    else if (obj.attachEvent)
    {
        obj['e' + type + fn] = fn;
        obj[type + fn] = function()
        {
            obj['e' + type + fn](window.event);
        }
        obj.attachEvent('on' + type, obj[type + fn]);
        EventCache.add(obj, type, fn, useCapture);
    }
    else
    {
        //alert('Handler could not be attached.');
    }
}

function removeEvent(obj, type, fn, useCapture)
{
    if (obj.removeEventListener)
    {
        obj.removeEventListener(type, fn, useCapture);
        return true;
    }

    if (obj.detachEvent)
    {
        return obj.detachEvent('on' + type, fn);
    }

    //alert('Handler could not be removed.');
}

function checkQuickSearchForm(form)
{
    var fieldValue = document.getElementById('quickSearchFor').value;
    var fieldLabel = document.getElementById('quickSearchLabel');

    if (fieldValue == '')
    {
        fieldLabel.style.color = '#ff0000';
        return false;
    }

    fieldLabel.style.color = '#000';

    return true;
}

/* This executes all the <script> tags in dynamically loaded JavaScript. */
function execJS(text)
{
    var working = text;

    var pos = working.indexOf('<script');
    while (pos != -1)
    {
        working = working.substring(pos);
        pos = working.indexOf('>');
        if (pos == -1)
        {
            return;
        }
        working = working.substring(pos);
        pos = working.indexOf('</script>');
        var js = working.substring(1,pos);
        working = working.substring(pos);
        pos = working.indexOf('<script');
        eval(js);
    }
}

/**
*
*  MD5 (Message-Digest Algorithm)
*  http://www.webtoolkit.info/
*
**/
var md5 = function (string) {

	function RotateLeft(lValue, iShiftBits) {
		return (lValue<<iShiftBits) | (lValue>>>(32-iShiftBits));
	}

	function AddUnsigned(lX,lY) {
		var lX4,lY4,lX8,lY8,lResult;
		lX8 = (lX & 0x80000000);
		lY8 = (lY & 0x80000000);
		lX4 = (lX & 0x40000000);
		lY4 = (lY & 0x40000000);
		lResult = (lX & 0x3FFFFFFF)+(lY & 0x3FFFFFFF);
		if (lX4 & lY4) {
			return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
		}
		if (lX4 | lY4) {
			if (lResult & 0x40000000) {
				return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
			} else {
				return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
			}
		} else {
			return (lResult ^ lX8 ^ lY8);
		}
 	}

 	function F(x,y,z) { return (x & y) | ((~x) & z); }
 	function G(x,y,z) { return (x & z) | (y & (~z)); }
 	function H(x,y,z) { return (x ^ y ^ z); }
	function I(x,y,z) { return (y ^ (x | (~z))); }

	function FF(a,b,c,d,x,s,ac) {
		a = AddUnsigned(a, AddUnsigned(AddUnsigned(F(b, c, d), x), ac));
		return AddUnsigned(RotateLeft(a, s), b);
	};

	function GG(a,b,c,d,x,s,ac) {
		a = AddUnsigned(a, AddUnsigned(AddUnsigned(G(b, c, d), x), ac));
		return AddUnsigned(RotateLeft(a, s), b);
	};

	function HH(a,b,c,d,x,s,ac) {
		a = AddUnsigned(a, AddUnsigned(AddUnsigned(H(b, c, d), x), ac));
		return AddUnsigned(RotateLeft(a, s), b);
	};

	function II(a,b,c,d,x,s,ac) {
		a = AddUnsigned(a, AddUnsigned(AddUnsigned(I(b, c, d), x), ac));
		return AddUnsigned(RotateLeft(a, s), b);
	};

	function ConvertToWordArray(string) {
		var lWordCount;
		var lMessageLength = string.length;
		var lNumberOfWords_temp1=lMessageLength + 8;
		var lNumberOfWords_temp2=(lNumberOfWords_temp1-(lNumberOfWords_temp1 % 64))/64;
		var lNumberOfWords = (lNumberOfWords_temp2+1)*16;
		var lWordArray=Array(lNumberOfWords-1);
		var lBytePosition = 0;
		var lByteCount = 0;
		while ( lByteCount < lMessageLength ) {
			lWordCount = (lByteCount-(lByteCount % 4))/4;
			lBytePosition = (lByteCount % 4)*8;
			lWordArray[lWordCount] = (lWordArray[lWordCount] | (string.charCodeAt(lByteCount)<<lBytePosition));
			lByteCount++;
		}
		lWordCount = (lByteCount-(lByteCount % 4))/4;
		lBytePosition = (lByteCount % 4)*8;
		lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80<<lBytePosition);
		lWordArray[lNumberOfWords-2] = lMessageLength<<3;
		lWordArray[lNumberOfWords-1] = lMessageLength>>>29;
		return lWordArray;
	};

	function WordToHex(lValue) {
		var WordToHexValue="",WordToHexValue_temp="",lByte,lCount;
		for (lCount = 0;lCount<=3;lCount++) {
			lByte = (lValue>>>(lCount*8)) & 255;
			WordToHexValue_temp = "0" + lByte.toString(16);
			WordToHexValue = WordToHexValue + WordToHexValue_temp.substr(WordToHexValue_temp.length-2,2);
		}
		return WordToHexValue;
	};

	function Utf8Encode(string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	};

	var x=Array();
	var k,AA,BB,CC,DD,a,b,c,d;
	var S11=7, S12=12, S13=17, S14=22;
	var S21=5, S22=9 , S23=14, S24=20;
	var S31=4, S32=11, S33=16, S34=23;
	var S41=6, S42=10, S43=15, S44=21;

	string = Utf8Encode(string);

	x = ConvertToWordArray(string);

	a = 0x67452301; b = 0xEFCDAB89; c = 0x98BADCFE; d = 0x10325476;

	for (k=0;k<x.length;k+=16) {
		AA=a; BB=b; CC=c; DD=d;
		a=FF(a,b,c,d,x[k+0], S11,0xD76AA478);
		d=FF(d,a,b,c,x[k+1], S12,0xE8C7B756);
		c=FF(c,d,a,b,x[k+2], S13,0x242070DB);
		b=FF(b,c,d,a,x[k+3], S14,0xC1BDCEEE);
		a=FF(a,b,c,d,x[k+4], S11,0xF57C0FAF);
		d=FF(d,a,b,c,x[k+5], S12,0x4787C62A);
		c=FF(c,d,a,b,x[k+6], S13,0xA8304613);
		b=FF(b,c,d,a,x[k+7], S14,0xFD469501);
		a=FF(a,b,c,d,x[k+8], S11,0x698098D8);
		d=FF(d,a,b,c,x[k+9], S12,0x8B44F7AF);
		c=FF(c,d,a,b,x[k+10],S13,0xFFFF5BB1);
		b=FF(b,c,d,a,x[k+11],S14,0x895CD7BE);
		a=FF(a,b,c,d,x[k+12],S11,0x6B901122);
		d=FF(d,a,b,c,x[k+13],S12,0xFD987193);
		c=FF(c,d,a,b,x[k+14],S13,0xA679438E);
		b=FF(b,c,d,a,x[k+15],S14,0x49B40821);
		a=GG(a,b,c,d,x[k+1], S21,0xF61E2562);
		d=GG(d,a,b,c,x[k+6], S22,0xC040B340);
		c=GG(c,d,a,b,x[k+11],S23,0x265E5A51);
		b=GG(b,c,d,a,x[k+0], S24,0xE9B6C7AA);
		a=GG(a,b,c,d,x[k+5], S21,0xD62F105D);
		d=GG(d,a,b,c,x[k+10],S22,0x2441453);
		c=GG(c,d,a,b,x[k+15],S23,0xD8A1E681);
		b=GG(b,c,d,a,x[k+4], S24,0xE7D3FBC8);
		a=GG(a,b,c,d,x[k+9], S21,0x21E1CDE6);
		d=GG(d,a,b,c,x[k+14],S22,0xC33707D6);
		c=GG(c,d,a,b,x[k+3], S23,0xF4D50D87);
		b=GG(b,c,d,a,x[k+8], S24,0x455A14ED);
		a=GG(a,b,c,d,x[k+13],S21,0xA9E3E905);
		d=GG(d,a,b,c,x[k+2], S22,0xFCEFA3F8);
		c=GG(c,d,a,b,x[k+7], S23,0x676F02D9);
		b=GG(b,c,d,a,x[k+12],S24,0x8D2A4C8A);
		a=HH(a,b,c,d,x[k+5], S31,0xFFFA3942);
		d=HH(d,a,b,c,x[k+8], S32,0x8771F681);
		c=HH(c,d,a,b,x[k+11],S33,0x6D9D6122);
		b=HH(b,c,d,a,x[k+14],S34,0xFDE5380C);
		a=HH(a,b,c,d,x[k+1], S31,0xA4BEEA44);
		d=HH(d,a,b,c,x[k+4], S32,0x4BDECFA9);
		c=HH(c,d,a,b,x[k+7], S33,0xF6BB4B60);
		b=HH(b,c,d,a,x[k+10],S34,0xBEBFBC70);
		a=HH(a,b,c,d,x[k+13],S31,0x289B7EC6);
		d=HH(d,a,b,c,x[k+0], S32,0xEAA127FA);
		c=HH(c,d,a,b,x[k+3], S33,0xD4EF3085);
		b=HH(b,c,d,a,x[k+6], S34,0x4881D05);
		a=HH(a,b,c,d,x[k+9], S31,0xD9D4D039);
		d=HH(d,a,b,c,x[k+12],S32,0xE6DB99E5);
		c=HH(c,d,a,b,x[k+15],S33,0x1FA27CF8);
		b=HH(b,c,d,a,x[k+2], S34,0xC4AC5665);
		a=II(a,b,c,d,x[k+0], S41,0xF4292244);
		d=II(d,a,b,c,x[k+7], S42,0x432AFF97);
		c=II(c,d,a,b,x[k+14],S43,0xAB9423A7);
		b=II(b,c,d,a,x[k+5], S44,0xFC93A039);
		a=II(a,b,c,d,x[k+12],S41,0x655B59C3);
		d=II(d,a,b,c,x[k+3], S42,0x8F0CCC92);
		c=II(c,d,a,b,x[k+10],S43,0xFFEFF47D);
		b=II(b,c,d,a,x[k+1], S44,0x85845DD1);
		a=II(a,b,c,d,x[k+8], S41,0x6FA87E4F);
		d=II(d,a,b,c,x[k+15],S42,0xFE2CE6E0);
		c=II(c,d,a,b,x[k+6], S43,0xA3014314);
		b=II(b,c,d,a,x[k+13],S44,0x4E0811A1);
		a=II(a,b,c,d,x[k+4], S41,0xF7537E82);
		d=II(d,a,b,c,x[k+11],S42,0xBD3AF235);
		c=II(c,d,a,b,x[k+2], S43,0x2AD7D2BB);
		b=II(b,c,d,a,x[k+9], S44,0xEB86D391);
		a=AddUnsigned(a,AA);
		b=AddUnsigned(b,BB);
		c=AddUnsigned(c,CC);
		d=AddUnsigned(d,DD);
	}

	var temp = WordToHex(a)+WordToHex(b)+WordToHex(c)+WordToHex(d);

	return temp.toLowerCase();
}
/* End of MD5. */

function rot13(theString)
{ 
	return theString.replace(/[a-zA-Z]/g, function(c)
	    {
		    return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
	    }
	);
};

/*
PROJECT: Javascript Based Base64 Encoding and Decoding Engine
DATE: 02/10/2004
AUTHOR: Adrian Bacon
COPYRIGHT: You are free to use this code as you see fit provided
that you send any changes or modifications back to me.
*/
var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZ" +
"abcdefghijklmnopqrstuvwxyz" +
"0123456789+/=";

function decode64(input)
{
   var output = "";
   var chr1, chr2, chr3;
   var enc1, enc2, enc3, enc4;
   var i = 0;

   // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
   input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

   do {
      enc1 = keyStr.indexOf(input.charAt(i++));
      enc2 = keyStr.indexOf(input.charAt(i++));
      enc3 = keyStr.indexOf(input.charAt(i++));
      enc4 = keyStr.indexOf(input.charAt(i++));

      chr1 = (enc1 << 2) | (enc2 >> 4);
      chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
      chr3 = ((enc3 & 3) << 6) | enc4;

      output = output + String.fromCharCode(chr1);

      if (enc3 != 64) {
         output = output + String.fromCharCode(chr2);
      }
      if (enc4 != 64) {
         output = output + String.fromCharCode(chr3);
      }
   } while (i < input.length);

   return output;
}

// Progressive disclosure toggle shared by every Add/Edit form's
// "Show more fields" control (see .form-more-toggle / .form-additional-fields
// in main.css). Pass the additional-fields container id, the toggle button
// id, and optionally override the two label strings shown on the button.
function toggleFormFields(containerID, btnID, showLabel, hideLabel)
{
   showLabel = showLabel || 'Show More Fields';
   hideLabel = hideLabel || 'Show Less Fields';

   var fields = document.getElementById(containerID);
   var btn = document.getElementById(btnID);
   if (!fields || !btn) return;

   if (fields.classList.contains('show'))
   {
      fields.classList.remove('show');
      btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg> ' + showLabel;
   }
   else
   {
      fields.classList.add('show');
      btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/></svg> ' + hideLabel;
   }
}

/* ---- js/quickAction.js ---- */
var quickAction = {};

quickAction.MenuOption = function(title, action)
{
    this.title = title;
    this.action = action;
};

quickAction.MenuOption.prototype.getTitle = function()
{
    return this.title;
};

quickAction.MenuOption.prototype.getAction = function()
{
    return this.action;
};


quickAction.MenuOption.prototype.getHtml = function()
{
    return '<a href="javascript:void(0);" onclick="' + this.getAction() + '">' + this.getTitle() + '</a><br />';
};

quickAction.LinkMenuOption = function(title, action, option)
{
    quickAction.MenuOption.call(this, title, action);
    this.option = option;
};

quickAction.LinkMenuOption.prototype = Object.create(quickAction.MenuOption.prototype);

quickAction.LinkMenuOption.prototype.getOption = function()
{
    return this.option;
};

quickAction.LinkMenuOption.prototype.getHtml = function()
{
    var message = "'Are you sure?'";
    var result;
    switch(this.getOption())
    {
        case 0:
            var itemAction = "'" + this.getAction() + "'";
            result = '<a href=# onclick="showPopWin(' + itemAction + ', 750, 540, null); return false;">' + this.getTitle() + '</a><br />';
            break;
        case 1:
        default:
            result = '<a href="' + this.getAction() + '" onclick="return confirm(' + message + ')">' + this.getTitle() + '</a><br />';
            break;
    }
    return result;
};


quickAction.DefaultMenu = function(menuDataItemType, menuDataItemId, menuX, menuY, permissions)
{
    this.element = document.getElementById('singleQuickActionMenu');
    this.menuDataItemType = menuDataItemType;
    this.menuDataItemId = menuDataItemId;
    this.menuX = menuX;
    this.menuY = menuY;
    this.permissions = permissions;
};

quickAction.DefaultMenu.prototype.getType = function()
{
    return this.menuDataItemType;
};

quickAction.DefaultMenu.prototype.getPermissions = function()
{
    return this.permissions;
};

quickAction.DefaultMenu.prototype.getId = function()
{
    return this.menuDataItemId;
};

quickAction.DefaultMenu.prototype.getOptions = function()
{
    return [
        new quickAction.MenuOption('Add To List', 'showQuickActionAddToList(' +  this.menuDataItemType + ', ' + this.menuDataItemId + ');')
    ];
};

quickAction.DefaultMenu.prototype.toggle = function()
{
    if (this.element.style.display != 'block')
    {
        this.element.style.display = 'block';
        this.element.style.left = this.menuX + 'px';
        this.element.style.top = this.menuY + 'px';
        this.element.innerHTML = '';
        var options = this.getOptions();
        for (var i = 0; i < options.length; ++i)
        {
            this.element.innerHTML += options[i].getHtml();
        }
    }
};

/* Creates and displays a popup menu for an individual data item on the page to do some simple action to. */
function showHideSingleQuickActionMenu(menu)
{
    menu.toggle();
};

/* Shows a popup for adding a item to a list. */
function showQuickActionAddToList(menuDataItemType, menuDataItemId)
{
    /* Create a popup window for adding this data item type to a list (content loaded from server) */
    showPopWin(CATSIndexName + '?m=lists&a=quickActionAddToListModal&dataItemType='+ menuDataItemType +'&dataItemID='+ menuDataItemId, 450, 350, null);
};

/* Shows a popup for adding a item to a list. */
function showQuickActionAddToPipeline(menuDataItemId)
{
    /* Create a popup window for adding this candidate to the job order / pipeline */
    showPopWin(CATSIndexName + '?m=candidates&a=considerForJobSearch&candidateID=' + menuDataItemId, 750, 390, null);
};

/* ---- js/calendarDateInput.js ---- */
/***********************************************
 Fool-Proof Date Input Script with DHTML Calendar
 by Jason Moon - calendar@moonscript.com
 ************************************************/

// Customizable variables
var DefaultDateFormat = 'MM-DD-YY'; // If no date format is supplied, this will be used instead
var HideWait = 1; // Number of seconds before the calendar will disappear
var Y2kPivotPoint = 76; // 2-digit years before this point will be created in the 21st century
var FontSize = 10; // In pixels
var FontFamily = 'Tahoma';
var CellWidth = 18;
var CellHeight = 16;
var ImageURL = 'images/calendar.gif';
var NextURL = 'images/next.gif';
var PrevURL = 'images/prev.gif';
var CalBGColor = 'white';
var TopRowBGColor = 'buttonface';
var DayBGColor = 'lightgrey';

// Global variables
var ZCounter = 100;
var Today = new Date();
var WeekDays = new Array('S','M','T','W','T','F','S');
var MonthDays = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
var MonthNames = new Array('January','February','March','April','May','June','July','August','September','October','November','December');

// Write out the stylesheet definition for the calendar
with (document) {
   writeln('<style>');
   writeln('td.calendarDateInput {letter-spacing:normal;line-height:normal;font-family:' + FontFamily + ',Sans-Serif;font-size:' + FontSize + 'px;}');
   writeln('select.calendarDateInput {letter-spacing:.06em;font-family:Verdana,Sans-Serif;font-size:11px;}');
   writeln('input.calendarDateInput {letter-spacing:.06em;font-family:Verdana,Sans-Serif;font-size:11px;}');
   writeln('</style>');
}

// Only allows certain keys to be used in the date field
function YearDigitsOnly(e)
{
    if (!e)
    {
        var e = window.event;
    }

    if (typeof(window.event) != 'undefined')
    {
        var KeyCode = e.keyCode;
    }
    else
    {
        var KeyCode = e.which;
    }

    return ((KeyCode == 8) // backspace
        || (KeyCode == 9) // tab
        || (KeyCode == 37) // left arrow
        || (KeyCode == 39) // right arrow
        || (KeyCode == 46) // delete
        || ((KeyCode > 47) && (KeyCode < 58)) // 0 - 9
   );
}

// Gets the absolute pixel position of the supplied element
function GetTagPixels(StartTag, Direction) {
   var PixelAmt = (Direction == 'LEFT') ? StartTag.offsetLeft : StartTag.offsetTop;
   while ((StartTag.tagName != 'BODY') && (StartTag.tagName != 'HTML')) {
      StartTag = StartTag.offsetParent;
      PixelAmt += (Direction == 'LEFT') ? StartTag.offsetLeft : StartTag.offsetTop;
   }
   return PixelAmt;
}

// Is the specified select-list behind the calendar?
function BehindCal(SelectList, CalLeftX, CalRightX, CalTopY, CalBottomY, ListTopY) {
   var ListLeftX = GetTagPixels(SelectList, 'LEFT');
   var ListRightX = ListLeftX + SelectList.offsetWidth;
   var ListBottomY = ListTopY + SelectList.offsetHeight;
   return (((ListTopY < CalBottomY) && (ListBottomY > CalTopY)) && ((ListLeftX < CalRightX) && (ListRightX > CalLeftX)));
}

// For IE, hides any select-lists that are behind the calendar
function FixSelectLists(Over) {
   if (navigator.appName == 'Microsoft Internet Explorer') {
      var CalDiv = this.getCalendar();
      var CalLeftX = CalDiv.offsetLeft;
      var CalRightX = CalLeftX + CalDiv.offsetWidth;
      var CalTopY = CalDiv.offsetTop;
      var CalBottomY = CalTopY + (CellHeight * 9);
      var FoundCalInput = false;
      formLoop :
      for (var j=this.formNumber;j<document.forms.length;j++) {
         for (var i=0;i<document.forms[j].elements.length;i++) {
            if (typeof document.forms[j].elements[i].type == 'string') {
               if ((document.forms[j].elements[i].type == 'hidden') && (document.forms[j].elements[i].name == this.hiddenFieldName)) {
                  FoundCalInput = true;
                  i += 3; // 3 elements between the 1st hidden field and the last year input field
               }
               if (FoundCalInput) {
                  if (document.forms[j].elements[i].type.substr(0,6) == 'select') {
                     ListTopY = GetTagPixels(document.forms[j].elements[i], 'TOP');
                     if (ListTopY < CalBottomY) {
                        if (BehindCal(document.forms[j].elements[i], CalLeftX, CalRightX, CalTopY, CalBottomY, ListTopY)) {
                           document.forms[j].elements[i].style.visibility = (Over) ? 'hidden' : 'visible';
                        }
                     }
                     else break formLoop;
                  }
               }
            }
         }
      }
   }
}

// Displays a message in the status bar when hovering over the calendar days
function DayCellHover(Cell, Over, Color, HoveredDay) {
   Cell.style.backgroundColor = (Over) ? DayBGColor : Color;
   if (Over) {
      if ((this.yearValue == Today.getFullYear()) && (this.monthIndex == Today.getMonth()) && (HoveredDay == Today.getDate())) self.status = 'Click to select today';
      else {
         var Suffix = HoveredDay.toString();
         switch (Suffix.substr(Suffix.length - 1, 1)) {
            case '1' : Suffix += (HoveredDay == 11) ? 'th' : 'st'; break;
            case '2' : Suffix += (HoveredDay == 12) ? 'th' : 'nd'; break;
            case '3' : Suffix += (HoveredDay == 13) ? 'th' : 'rd'; break;
            default : Suffix += 'th'; break;
         }
         self.status = 'Click to select ' + this.monthName + ' ' + Suffix;
      }
   }
   else self.status = '';
   return true;
}

// Sets the form elements after a day has been picked from the calendar
function PickDisplayDay(ClickedDay) {
   this.show();
   var MonthList = this.getMonthList();
   var DayList = this.getDayList();
   var YearField = this.getYearField();
   FixDayList(DayList, GetDayCount(this.displayed.yearValue, this.displayed.monthIndex));
   // Select the month and day in the lists
   for (var i=0;i<MonthList.length;i++) {
      if (MonthList.options[i].value == this.displayed.monthIndex) MonthList.options[i].selected = true;
   }
   for (var j=1;j<=DayList.length;j++) {
      if (j == ClickedDay) DayList.options[j-1].selected = true;
   }
   this.setPicked(this.displayed.yearValue, this.displayed.monthIndex, ClickedDay);
   // Change the year, if necessary
   YearField.value = this.picked.yearPad;
   YearField.defaultValue = YearField.value;
}

// Builds the HTML for the calendar days
function BuildCalendarDays() {
   var Rows = 5;
   if (((this.displayed.dayCount == 31) && (this.displayed.firstDay > 4)) || ((this.displayed.dayCount == 30) && (this.displayed.firstDay == 6))) Rows = 6;
   else if ((this.displayed.dayCount == 28) && (this.displayed.firstDay == 0)) Rows = 4;
   var HTML = '<table cellspacing="0" cellpadding="0" style="cursor:default">';
   for (var j=0;j<Rows;j++) {
      HTML += '<tr>';
      for (var i=1;i<=7;i++) {
         Day = (j * 7) + (i - this.displayed.firstDay);
         if ((Day >= 1) && (Day <= this.displayed.dayCount)) {
            if ((this.displayed.yearValue == this.picked.yearValue) && (this.displayed.monthIndex == this.picked.monthIndex) && (Day == this.picked.day)) {
               TextStyle = 'color:white;font-weight:bold;'
               BackColor = DayBGColor;
            }
            else {
               TextStyle = 'color:black;'
               BackColor = CalBGColor;
            }
            if ((this.displayed.yearValue == Today.getFullYear()) && (this.displayed.monthIndex == Today.getMonth()) && (Day == Today.getDate())) TextStyle += 'border:1px solid darkred;padding:0px;';
            HTML += '<td align="center" class="calendarDateInput" style="cursor:default;height:' + CellHeight + ';width:' + CellWidth + ';' + TextStyle + ';background-color:' + BackColor + '" onclick="' + this.objName + '.pickDay(' + Day + ')" onmouseover="return ' + this.objName + '.displayed.dayHover(this,true,\'' + BackColor + '\',' + Day + ')" onmouseout="return ' + this.objName + '.displayed.dayHover(this,false,\'' + BackColor + '\')">' + Day + '</td>';
         }
         else HTML += '<td class="calendarDateInput" style="height:' + CellHeight + '">&nbsp;</td>';
      }
      HTML += '</tr>';
   }
   return HTML += '</table>';
}

// Determines which century to use (20th or 21st) when dealing with 2-digit years
function GetGoodYear(YearDigits) {
   if (YearDigits.length == 4) return YearDigits;
   else {
      /*var Millennium = (YearDigits < Y2kPivotPoint) ? 2000 : 1900;
      return Millennium + parseInt(YearDigits,10);*/
      return 2000 + parseInt(YearDigits,10);
   }
}

// Returns the number of days in a month (handles leap-years)
function GetDayCount(SomeYear, SomeMonth) {
   return ((SomeMonth == 1) && ((SomeYear % 400 == 0) || ((SomeYear % 4 == 0) && (SomeYear % 100 != 0)))) ? 29 : MonthDays[SomeMonth];
}

// Highlights the buttons
function VirtualButton(Cell, ButtonDown) {
   if (ButtonDown) {
      Cell.style.borderLeft = 'buttonshadow 1px solid';
      Cell.style.borderTop = 'buttonshadow 1px solid';
      Cell.style.borderBottom = 'buttonhighlight 1px solid';
      Cell.style.borderRight = 'buttonhighlight 1px solid';
   }
   else {
      Cell.style.borderLeft = 'buttonhighlight 1px solid';
      Cell.style.borderTop = 'buttonhighlight 1px solid';
      Cell.style.borderBottom = 'buttonshadow 1px solid';
      Cell.style.borderRight = 'buttonshadow 1px solid';
   }
}

// Mouse-over for the previous/next month buttons
function NeighborHover(Cell, Over, DateObj) {
   if (Over) {
      VirtualButton(Cell, false);
      self.status = 'Click to view ' + DateObj.fullName;
   }
   else {
      Cell.style.border = 'buttonface 1px solid';
      self.status = '';
   }
   return true;
}

// Adds/removes days from the day list, depending on the month/year
function FixDayList(DayList, NewDays) {
   var DayPick = DayList.selectedIndex + 1;
   if (NewDays != DayList.length) {
      var OldSize = DayList.length;
      for (var k=Math.min(NewDays,OldSize);k<Math.max(NewDays,OldSize);k++) {
         (k >= NewDays) ? DayList.options[NewDays] = null : DayList.options[k] = new Option(k+1, k+1);
      }
      DayPick = Math.min(DayPick, NewDays);
      DayList.options[DayPick-1].selected = true;
   }
   return DayPick;
}

// Resets the year to its previous valid value when something invalid is entered
function FixYearInput(YearField) {
   var YearRE = new RegExp('\\d{' + YearField.defaultValue.length + '}');
   if (!YearRE.test(YearField.value)) YearField.value = YearField.defaultValue;
}

// Displays a message in the status bar when hovering over the calendar icon
function CalIconHover(Over) {
   var Message = (this.isShowing()) ? 'hide' : 'show';
   self.status = (Over) ? 'Click to ' + Message + ' the calendar' : '';
   return true;
}

// Starts the timer over from scratch
function CalTimerReset() {
   eval('clearTimeout(' + this.timerID + ')');
   eval(this.timerID + '=setTimeout(\'' + this.objName + '.show()\',' + (HideWait * 1000) + ')');
}

// The timer for the calendar
function DoTimer(CancelTimer) {
   if (CancelTimer) eval('clearTimeout(' + this.timerID + ')');
   else {
      eval(this.timerID + '=null');
      this.resetTimer();
   }
}

// Show or hide the calendar
function ShowCalendar() {
   if (this.isShowing()) {
      var StopTimer = true;
      this.getCalendar().style.zIndex = --ZCounter;
      this.getCalendar().style.visibility = 'hidden';
      this.fixSelects(false);
   }
   else {
      var StopTimer = false;
      this.fixSelects(true);
      this.getCalendar().style.zIndex = ++ZCounter;
      this.getCalendar().style.visibility = 'visible';
   }
   this.handleTimer(StopTimer);
   self.status = '';
}

// Hides the input elements when the "blank" month is selected
function SetElementStatus(Hide) {
   this.getDayList().style.visibility = (Hide) ? 'hidden' : 'visible';
   this.getYearField().style.visibility = (Hide) ? 'hidden' : 'visible';
   this.getCalendarLink().style.visibility = (Hide) ? 'hidden' : 'visible';
}

// Sets the date, based on the month selected
function CheckMonthChange(MonthList) {
   var DayList = this.getDayList();
   if (MonthList.options[MonthList.selectedIndex].value == '') {
      DayList.selectedIndex = 0;
      this.hideElements(true);
      this.setHidden('');
   }
   else {
      this.hideElements(false);
      if (this.isShowing()) {
         this.resetTimer(); // Gives the user more time to view the calendar with the newly-selected month
         this.getCalendar().style.zIndex = ++ZCounter; // Make sure this calendar is on top of any other calendars
      }
      var DayPick = FixDayList(DayList, GetDayCount(this.picked.yearValue, MonthList.options[MonthList.selectedIndex].value));
      this.setPicked(this.picked.yearValue, MonthList.options[MonthList.selectedIndex].value, DayPick);
   }
}

// Sets the date, based on the day selected
function CheckDayChange(DayList) {
   if (this.isShowing()) this.show();
   this.setPicked(this.picked.yearValue, this.picked.monthIndex, DayList.selectedIndex+1);
}

// Changes the date when a valid year has been entered
function CheckYearInput(YearField) {
   if ((YearField.value.length == YearField.defaultValue.length) && (YearField.defaultValue != YearField.value)) {
      if (this.isShowing()) {
         this.resetTimer(); // Gives the user more time to view the calendar with the newly-entered year
         this.getCalendar().style.zIndex = ++ZCounter; // Make sure this calendar is on top of any other calendars
      }
      var NewYear = GetGoodYear(YearField.value);
      var MonthList = this.getMonthList();
      var NewDay = FixDayList(this.getDayList(), GetDayCount(NewYear, this.picked.monthIndex));
      this.setPicked(NewYear, this.picked.monthIndex, NewDay);
      YearField.defaultValue = YearField.value;
   }
}

// Holds characteristics about a date
function dateObject() {
   if (Function.call) { // Used when 'call' method of the Function object is supported
      var ParentObject = this;
      var ArgumentStart = 0;
   }
   else { // Used with 'call' method of the Function object is NOT supported
      var ParentObject = arguments[0];
      var ArgumentStart = 1;
   }

	if (arguments.length == (ArgumentStart+1))
	{
		//alert('A');
		ParentObject.date = new Date(arguments[ArgumentStart+0]);
	}
	else
	{
		//alert('B - ' + arguments[ArgumentStart+0]);
		ParentObject.date = new Date(arguments[ArgumentStart+0], arguments[ArgumentStart+1], arguments[ArgumentStart+2]);
	}
	
   ParentObject.yearValue = ParentObject.date.getFullYear();
   if (ParentObject.yearValue < 1995) ParentObject.yearValue += 100;
   ParentObject.monthIndex = ParentObject.date.getMonth();

   ParentObject.monthName = MonthNames[ParentObject.monthIndex];
   ParentObject.fullName = ParentObject.monthName + ' ' + ParentObject.yearValue;
   ParentObject.day = ParentObject.date.getDate();
   ParentObject.dayCount = GetDayCount(ParentObject.yearValue, ParentObject.monthIndex);
   var FirstDate = new Date(ParentObject.yearValue, ParentObject.monthIndex, 1);
   ParentObject.firstDay = FirstDate.getDay();
}

// Keeps track of the date that goes into the hidden field
function storedMonthObject(DateFormat, DateYear, DateMonth, DateDay) {
	//alert ('smo - ' + DateYear);
   (Function.call) ? dateObject.call(this, DateYear, DateMonth, DateDay) : dateObject(this, DateYear, DateMonth, DateDay);
   this.yearPad = this.yearValue.toString();
   this.monthPad = (this.monthIndex < 9) ? '0' + String(this.monthIndex + 1) : this.monthIndex + 1;
   this.dayPad = (this.day < 10) ? '0' + this.day.toString() : this.day;

   this.monthShort = this.monthName.substr(0,3).toUpperCase();
   // Formats the year with 2 digits instead of 4
   if (DateFormat.indexOf('YYYY') == -1) this.yearPad = this.yearPad.substr(2);
   // Define the date-part delimiter
   if (DateFormat.indexOf('/') >= 0) var Delimiter = '/';
   else if (DateFormat.indexOf('-') >= 0) var Delimiter = '-';
   else var Delimiter = '';
   // Determine the order of the months and days
   if (/DD?.?((MON)|(MM?M?))/.test(DateFormat)) {
      this.formatted = this.dayPad + Delimiter;
      this.formatted += (RegExp.$1.length == 3) ? this.monthShort : this.monthPad;
   }
   else if (/((MON)|(MM?M?))?.?DD?/.test(DateFormat)) {
      this.formatted = (RegExp.$1.length == 3) ? this.monthShort : this.monthPad;
      this.formatted += Delimiter + this.dayPad;
   }
   // Either prepend or append the year to the formatted date
   this.formatted = (DateFormat.substr(0,2) == 'YY') ? this.yearPad + Delimiter + this.formatted : this.formatted + Delimiter + this.yearPad;
}

// Object for the current displayed month
function displayMonthObject(ParentObject, DateYear, DateMonth, DateDay) {
   (Function.call) ? dateObject.call(this, DateYear, DateMonth, DateDay) : dateObject(this, DateYear, DateMonth, DateDay);
   this.displayID = ParentObject.hiddenFieldName + '_Current_ID';
   this.getDisplay = new Function('return document.getElementById(this.displayID)');
   this.dayHover = DayCellHover;
   this.goCurrent = new Function(ParentObject.objName + '.getCalendar().style.zIndex=++ZCounter;' + ParentObject.objName + '.setDisplayed(Today.getFullYear(),Today.getMonth());');
   if (ParentObject.formNumber >= 0) this.getDisplay().innerHTML = this.fullName;
}

// Object for the previous/next buttons
function neighborMonthObject(ParentObject, IDText, DateMS) {
   (Function.call) ? dateObject.call(this, DateMS) : dateObject(this, DateMS);
   this.buttonID = ParentObject.hiddenFieldName + '_' + IDText + '_ID';
   this.hover = new Function('C','O','NeighborHover(C,O,this)');
   this.getButton = new Function('return document.getElementById(this.buttonID)');
   this.go = new Function(ParentObject.objName + '.getCalendar().style.zIndex=++ZCounter;' + ParentObject.objName + '.setDisplayed(this.yearValue,this.monthIndex);');
   if (ParentObject.formNumber >= 0) this.getButton().title = this.monthName;
}

// Sets the currently-displayed month object
function SetDisplayedMonth(DispYear, DispMonth) {
   this.displayed = new displayMonthObject(this, DispYear, DispMonth, 1);
   // Creates the previous and next month objects
   this.previous = new neighborMonthObject(this, 'Previous', this.displayed.date.getTime() - 86400000);
   this.next = new neighborMonthObject(this, 'Next', this.displayed.date.getTime() + (86400000 * (this.displayed.dayCount + 1)));
   // Creates the HTML for the calendar
   if (this.formNumber >= 0) this.getDayTable().innerHTML = this.buildCalendar();
}

// Sets the current selected date
function SetPickedMonth(PickedYear, PickedMonth, PickedDay) {
//alert('spm - ' + PickedYear);
   this.picked = new storedMonthObject(this.format, PickedYear, PickedMonth, PickedDay);
   this.setHidden(this.picked.formatted);
   this.setDisplayed(PickedYear, PickedMonth);
}

// The calendar object
function calendarObject(DateName, DateFormat, DefaultDate) {

   /* Properties */
   this.hiddenFieldName = DateName;
   this.monthListID = DateName + '_Month_ID';
   this.dayListID = DateName + '_Day_ID';
   this.yearFieldID = DateName + '_Year_ID';
   this.monthDisplayID = DateName + '_Current_ID';
   this.calendarID = DateName + '_ID';
   this.dayTableID = DateName + '_DayTable_ID';
   this.calendarLinkID = this.calendarID + '_Link';
   this.timerID = this.calendarID + '_Timer';
   this.objName = DateName + '_Object';
   this.format = DateFormat;
   this.formNumber = -1;
   this.picked = null;
   this.displayed = null;
   this.previous = null;
   this.next = null;

   /* Methods */
   this.setPicked = SetPickedMonth;
   this.setDisplayed = SetDisplayedMonth;
   this.checkYear = CheckYearInput;
   this.fixYear = FixYearInput;
   this.changeMonth = CheckMonthChange;
   this.changeDay = CheckDayChange;
   this.resetTimer = CalTimerReset;
   this.hideElements = SetElementStatus;
   this.show = ShowCalendar;
   this.handleTimer = DoTimer;
   this.iconHover = CalIconHover;
   this.buildCalendar = BuildCalendarDays;
   this.pickDay = PickDisplayDay;
   this.fixSelects = FixSelectLists;
   this.setHidden = new Function('D','if (this.formNumber >= 0 || this.formNumber == -99) this.getHiddenField().value=D');
   // Returns a reference to these elements
   this.getHiddenField = new Function('if (this.formNumber >= 0) return document.forms[this.formNumber].elements[this.hiddenFieldName]; else if (this.formNumber == -99) return document.getElementById(this.hiddenFieldName);');
   this.getMonthList = new Function('return document.getElementById(this.monthListID)');
   this.getDayList = new Function('return document.getElementById(this.dayListID)');
   this.getYearField = new Function('return document.getElementById(this.yearFieldID)');
   this.getCalendar = new Function('return document.getElementById(this.calendarID)');
   this.getDayTable = new Function('return document.getElementById(this.dayTableID)');
   this.getCalendarLink = new Function('return document.getElementById(this.calendarLinkID)');
   this.getMonthDisplay = new Function('return document.getElementById(this.monthDisplayID)');
   this.isShowing = new Function('return !(this.getCalendar().style.visibility != \'visible\')');

   /* Constructor */
   // Functions used only by the constructor
   function getMonthIndex(MonthAbbr) { // Returns the index (0-11) of the supplied month abbreviation
      for (var MonPos=0;MonPos<MonthNames.length;MonPos++) {
         if (MonthNames[MonPos].substr(0,3).toUpperCase() == MonthAbbr.toUpperCase()) break;
      }
      return MonPos;
   }
   function SetGoodDate(CalObj, Notify) { // Notifies the user about their bad default date, and sets the current system date
      CalObj.setPicked(Today.getFullYear(), Today.getMonth(), Today.getDate());
      if (Notify) alert('WARNING: The supplied date is not in valid \'' + DateFormat + '\' format: ' + DefaultDate + '.\nTherefore, the current system date will be used instead: ' + CalObj.picked.formatted);
   }
   // Main part of the constructor
   if (DefaultDate != '') {
      if ((this.format == 'YYYYMMDD') && (/^(\d{4})(\d{2})(\d{2})$/.test(DefaultDate))) {
         this.setPicked(parseInt(RegExp.$1, 10), parseInt(RegExp.$2, 10)-1, parseInt(RegExp.$3, 10));
      } else {
         // Get the year
         if ((this.format.substr(0,2) == 'YY') && (/^(\d{2,4})(-|\/)/.test(DefaultDate))) { // Year is at the beginning
            var YearPart = parseInt(GetGoodYear(RegExp.$1), 10);
            // Determine the order of the months and days
            if (/(-|\/)(\w{1,3})(-|\/)(\w{1,3})$/.test(DefaultDate)) {
               var MidPart = RegExp.$2;
               var EndPart = RegExp.$4;
               if (/D$/.test(this.format)) { // Ends with days
                  var DayPart = EndPart;
                  var MonthPart = MidPart;
               }
               else {
                  var DayPart = MidPart;
                  var MonthPart = EndPart;
               }
               MonthPart = (/\d{1,2}/i.test(MonthPart)) ? parseInt(MonthPart, 10) - 1 : getMonthIndex(MonthPart);
               this.setPicked(YearPart, MonthPart, parseInt(DayPart, 10));
            }
            else SetGoodDate(this, true);
         }
         else if (/(-|\/)(\d{2,4})$/.test(DefaultDate)) { // Year is at the end
            var YearPart = parseInt(GetGoodYear(RegExp.$2), 10);
            // Determine the order of the months and days
            if (/^(\w{1,3})(-|\/)(\w{1,3})(-|\/)/.test(DefaultDate)) {
               if (this.format.substr(0,1) == 'D') { // Starts with days
                  var DayPart = RegExp.$1;
                  var MonthPart = RegExp.$3;
               }
               else { // Starts with months
                  var MonthPart = RegExp.$1;
                  var DayPart = RegExp.$3;
               }
               MonthPart = (/\d{1,2}/i.test(MonthPart)) ? parseInt(MonthPart, 10) - 1 : getMonthIndex(MonthPart);
               this.setPicked(YearPart, MonthPart, parseInt(DayPart, 10));
            }
            else SetGoodDate(this, true);
         }
         else SetGoodDate(this, true);
      }
   }
}

function DateInput(DateName, Required, DateFormat, DefaultDate, TabIndex)
{
    var CurrentDate = new storedMonthObject(
        DateFormat, Today.getFullYear(), Today.getMonth(), Today.getDate()
    );

    DateFormat = DateFormat.toUpperCase();

    if (!(/^(Y{2,4}(-|\/)?)?((MON)|(MM?M?)|(DD?))(-|\/)?((MON)|(MM?M?)|(DD?))((-|\/)Y{2,4})?$/i.test(DateFormat)))
    {
        alert(
            'Error: The specified date format for the \'' + DateName +
            '\' field, \'' + DateFormat + '\', is invalid.'
        );
        return;
    }

    /* If DefaultDate is required but not specified, use today's date. */
    if (DefaultDate == '' && Required)
    {
        DefaultDate = CurrentDate.formatted;
    }

    /* Create the Calendar object. */
    eval(
        DateName + '_Object = new calendarObject(\'' + DateName +
        '\',\'' + DateFormat + '\',\'' + DefaultDate + '\')'
    );

    /* Get a reference to the object to avoid more ugly eval() hacks. */
    var objectName = DateName + '_Object';
    eval('var object = ' + DateName + '_Object;');

    /* Always show the day/year inputs and calendar icon so a full date can be
     * picked without first choosing a month (previously hidden until a month
     * was selected, which made the picker look month-only). */
    var initialStatus = '';
    if (Required || DefaultDate != '')
    {
        var initialDate = object.picked.formatted;
    }
    else
    {
        var initialDate = '';
        object.setPicked(Today.getFullYear(), Today.getMonth(), Today.getDate());
    }

    /* Calculate tab indexes. */
    if (TabIndex != -1)
    {
        var tabIndexA = ' tabindex="' + TabIndex + '"';
        var tabIndexB = ' tabindex="' + (TabIndex + 1) + '"';
        var tabIndexC = ' tabindex="' + (TabIndex + 2) + '"';
    }
    else
    {
        var tabIndexA = '';
        var tabIndexB = '';
        var tabIndexC = '';
    }

    /* Create form elements; etc. */
    with (document)
    {
        writeln('<input type="hidden" name="' + DateName + '" value="' + initialDate + '" />');

        /* Find the form number of the form we are in. */
        for (var f = 0; f < forms.length; f++)
        {
            for (var e = 0; e < forms[f].elements.length; e++)
            {
                if (typeof(forms[f].elements[e].type) == 'string' &&
                    forms[f].elements[e].type == 'hidden' &&
                    forms[f].elements[e].name == DateName)
                {
                    object.formNumber = f;
                    break;
                }
            }
        }

        writeln('<table style="padding: 0px; border-spacing: 0px; margin: 0px;">');
        writeln('<tr>');

        writeln('<td style="padding: 0px 3px 0px 0px; margin: 0px;">');
        writeln('<select' + tabIndexA + ' class="calendarDateInput" id="' + DateName + '_Month_ID" onchange="' + objectName + '.changeMonth(this);">');

        if (!Required)
        {
            if (DefaultDate == '')
            {
                writeln('<option selected="selected" value="">None</option>');
            }
            else
            {
                writeln('<option value="">None</option>');
            }
        }

        for (var i = 0; i < 12; i++)
        {
            if (object.picked.monthIndex == i && DefaultDate != '')
            {
                MonthSelected = ' selected="selected"';
            }
            else
            {
                MonthSelected = '';
            }

            writeln('<option value="' + i + '"' + MonthSelected + '>' + MonthNames[i].substr(0, 3) + '</option>');
        }

        writeln('</select>');
        writeln('</td>');

        writeln('<td style="padding: 0px 3px 0px 0px; margin: 0px;">');
        writeln('<select' + tabIndexB + initialStatus + ' class="calendarDateInput" id="' + DateName + '_Day_ID" onchange="' + objectName + '.changeDay(this);">');

        for (var j = 1; j <= 31; j++)
        {
            if (object.picked.day == j && DefaultDate != '')
            {
                DaySelected = ' selected="selected"';
            }
            else
            {
                DaySelected = '';
            }

            writeln('<option value="' + j + '"' + DaySelected + '>' + j + '</option>');
        }

        writeln('</select>');
        writeln('</td>');

        writeln('<td style="padding: 0px 3px 0px 0px; margin: 0px;">');
        writeln('<input' + tabIndexC + initialStatus + ' class="calendarDateInput" type="text" id="' + DateName + '_Year_ID" size="' + object.picked.yearPad.length + '" maxlength="' + object.picked.yearPad.length + '" title="Year" value="' + object.picked.yearPad + '" onKeyPress="return YearDigitsOnly(event);" onkeyup="' + objectName + '.checkYear(this);" onBlur="' + objectName + '.fixYear(this);" />');
        writeln('<td style="padding: 0px 3px 0px 0px; margin: 0px;"><a' + initialStatus + ' id="' + DateName + '_ID_Link" href="javascript:' + objectName + '.show();" onmouseover="return ' + objectName + '.iconHover(true);" onmouseout="return ' + objectName + '.iconHover(false);"><img src="' + ImageURL + '" style="vertical-align: middle; border: none;" title="Calendar" /></a>&nbsp;');

        writeln('<span id="' + DateName + '_ID" style="position: absolute; visibility: hidden; width: ' + (CellWidth * 7) + 'px; background-color: ' + CalBGColor + '; border: 1px solid dimgray;" onmouseover="' + objectName + '.handleTimer(true);" onmouseout="' + objectName + '.handleTimer(false);">');

        writeln('<table width="' + (CellWidth * 7) + '" cellspacing="0" cellpadding="1">');

        writeln('<tr style="background-color:' + TopRowBGColor + ';">');
        writeln('<td id="' + DateName + '_Previous_ID" style="cursor: default;" align="center" class="calendarDateInput" style="height: ' + CellHeight + '" onclick="' + objectName + '.previous.go();" onMouseDown="VirtualButton(this, true);" onMouseUp="VirtualButton(this, false);" onmouseover="return ' + objectName + '.previous.hover(this, true)" onmouseout="return ' + objectName + '.previous.hover(this, false);" title="' + object.previous.monthName + '"><img src="' + PrevURL + '"></td>');
        writeln('<td id="' + DateName + '_Current_ID" style="cursor: pointer;" align="center" class="calendarDateInput" style="height: ' + CellHeight + '" colspan="5" onclick="' + objectName + '.displayed.goCurrent();" onmouseover="self.status=\'Click to view ' + CurrentDate.fullName + '\'; return true;" onmouseout="self.status = \'\'; return true;" title="Show Current Month">' + object.displayed.fullName + '</td>');
        writeln('<td id="' + DateName + '_Next_ID" style="cursor: default;" align="center" class="calendarDateInput" style="height: ' + CellHeight + '" onclick="' + objectName + '.next.go();" onMouseDown="VirtualButton(this, true);" onMouseUp="VirtualButton(this, false);" onmouseover="return ' + objectName + '.next.hover(this, true);" onmouseout="return ' + objectName + '.next.hover(this, false);" title="' + object.next.monthName + '"><img src="' + NextURL + '" /></td>');
        writeln('</tr>');

        writeln('<tr>');
        for (var w = 0; w < 7; w++)
        {
            writeln('<td width="' + CellWidth + '" align="center" class="calendarDateInput" style="height:' + CellHeight + '; width:' + CellWidth + '; font-weight: bold; border-top: 1px solid dimgray; border-bottom: 1px solid dimgray;">' + WeekDays[w] + '</td>');
        }
        writeln('</tr>');

        writeln('</table>');

        writeln('<span id="' + DateName + '_DayTable_ID">' + object.buildCalendar() + '</span>');

        writeln('</span>');

        writeln('</td>');
        writeln('</tr>');
        writeln('</table>');
    }
}

function DateInputForDOM(DateName, Required, DateFormat, DefaultDate, TabIndex)
{
    var CurrentDate = new storedMonthObject(
        DateFormat, Today.getFullYear(), Today.getMonth(), Today.getDate()
    );

    DateFormat = DateFormat.toUpperCase();

    if (!(/^(Y{2,4}(-|\/)?)?((MON)|(MM?M?)|(DD?))(-|\/)?((MON)|(MM?M?)|(DD?))((-|\/)Y{2,4})?$/i.test(DateFormat)))
    {
        alert(
            'Error: The specified date format for the \'' + DateName +
            '\' field, \'' + DateFormat + '\', is invalid.'
        );
        return;
    }

    /* If DefaultDate is required but not specified, use today's date. */
    if (DefaultDate == '' && Required)
    {
        DefaultDate = CurrentDate.formatted;
    }

    /* Create the Calendar object. */
    eval(
        DateName + '_Object = new calendarObject(\'' + DateName +
        '\',\'' + DateFormat + '\',\'' + DefaultDate + '\')'
    );

    /* Get a reference to the object to avoid more ugly eval() hacks. */
    var objectName = DateName + '_Object';
    eval('var object = ' + DateName + '_Object;');

    /* Always show the day/year inputs and calendar icon so a full date can be
     * picked without first choosing a month (previously hidden until a month
     * was selected, which made the picker look month-only). */
    var initialStatus = '';
    if (Required || DefaultDate != '')
    {
        var initialDate = object.picked.formatted;
    }
    else
    {
        var initialDate = '';
        object.setPicked(Today.getFullYear(), Today.getMonth(), Today.getDate());
    }

    /* Calculate tab indexes. */
    if (TabIndex != -1)
    {
        var tabIndexA = ' tabindex="' + TabIndex + '"';
        var tabIndexB = ' tabindex="' + (TabIndex + 1) + '"';
        var tabIndexC = ' tabindex="' + (TabIndex + 2) + '"';
    }
    else
    {
        var tabIndexA = '';
        var tabIndexB = '';
        var tabIndexC = '';
    }
    

    var outCode = '';

    outCode +=('<input type="hidden" name="' + DateName + '" id="' + DateName + '" value="' + initialDate + '" />');

    /* Flag. */
    object.formNumber = -99;

    outCode += ('<table style="padding: 0px; border-spacing: 0px; margin: 0px;">');
    outCode += ('<tr>');

    outCode += ('<td style="padding: 0px 3px 0px 0px; margin: 0px;">');
    outCode += ('<select' + tabIndexA + ' class="calendarDateInput" id="' + DateName + '_Month_ID" onchange="' + objectName + '.changeMonth(this);">');

    if (!Required)
    {
        if (DefaultDate == '')
        {
            outCode += ('<option selected="selected" value="">None</option>');
        }
        else
        {
            outCode += ('<option value="">None</option>');
        }
    }

    for (var i = 0; i < 12; i++)
    {
        if (object.picked.monthIndex == i && DefaultDate != '')
        {
            MonthSelected = ' selected="selected"';
        }
        else
        {
            MonthSelected = '';
        }

        outCode += ('<option value="' + i + '"' + MonthSelected + '>' + MonthNames[i].substr(0, 3) + '</option>');
    }

    outCode += ('</select>');
    outCode += ('</td>');

    outCode += ('<td style="padding: 0px 3px 0px 0px; margin: 0px;">');
    outCode += ('<select' + tabIndexB + initialStatus + ' class="calendarDateInput" id="' + DateName + '_Day_ID" onchange="' + objectName + '.changeDay(this);">');

    for (var j = 1; j <= 31; j++)
    {
        if (object.picked.day == j && DefaultDate != '')
        {
            DaySelected = ' selected="selected"';
        }
        else
        {
            DaySelected = '';
        }

        outCode += ('<option value="' + j + '"' + DaySelected + '>' + j + '</option>');
    }

    outCode += ('</select>');
    outCode += ('</td>');

    outCode += ('<td style="padding: 0px 3px 0px 0px; margin: 0px;">');
    outCode += ('<input' + tabIndexC + initialStatus + ' class="calendarDateInput" type="text" id="' + DateName + '_Year_ID" size="' + object.picked.yearPad.length + '" maxlength="' + object.picked.yearPad.length + '" title="Year" value="' + object.picked.yearPad + '" onKeyPress="return YearDigitsOnly(event);" onkeyup="' + objectName + '.checkYear(this);" onBlur="' + objectName + '.fixYear(this);" />');
    outCode += ('<td style="padding: 0px 3px 0px 0px; margin: 0px;"><a' + initialStatus + ' id="' + DateName + '_ID_Link" href="javascript:' + objectName + '.show();" onmouseover="return ' + objectName + '.iconHover(true);" onmouseout="return ' + objectName + '.iconHover(false);"><img src="' + ImageURL + '" style="vertical-align: middle; border: none;" title="Calendar" /></a>&nbsp;');

    outCode += ('<span id="' + DateName + '_ID" style="position: absolute; visibility: hidden; width: ' + (CellWidth * 7) + 'px; background-color: ' + CalBGColor + '; border: 1px solid dimgray;" onmouseover="' + objectName + '.handleTimer(true);" onmouseout="' + objectName + '.handleTimer(false);">');

    outCode += ('<table width="' + (CellWidth * 7) + '" cellspacing="0" cellpadding="1">');

    outCode += ('<tr style="background-color:' + TopRowBGColor + ';">');
    outCode += ('<td id="' + DateName + '_Previous_ID" style="cursor: default;" align="center" class="calendarDateInput" style="height: ' + CellHeight + '" onclick="' + objectName + '.previous.go();" onMouseDown="VirtualButton(this, true);" onMouseUp="VirtualButton(this, false);" onmouseover="return ' + objectName + '.previous.hover(this, true)" onmouseout="return ' + objectName + '.previous.hover(this, false);" title="' + object.previous.monthName + '"><img src="' + PrevURL + '"></td>');
    outCode += ('<td id="' + DateName + '_Current_ID" style="cursor: pointer;" align="center" class="calendarDateInput" style="height: ' + CellHeight + '" colspan="5" onclick="' + objectName + '.displayed.goCurrent();" onmouseover="self.status=\'Click to view ' + CurrentDate.fullName + '\'; return true;" onmouseout="self.status = \'\'; return true;" title="Show Current Month">' + object.displayed.fullName + '</td>');
    outCode += ('<td id="' + DateName + '_Next_ID" style="cursor: default;" align="center" class="calendarDateInput" style="height: ' + CellHeight + '" onclick="' + objectName + '.next.go();" onMouseDown="VirtualButton(this, true);" onMouseUp="VirtualButton(this, false);" onmouseover="return ' + objectName + '.next.hover(this, true);" onmouseout="return ' + objectName + '.next.hover(this, false);" title="' + object.next.monthName + '"><img src="' + NextURL + '" /></td>');
    outCode += ('</tr>');

    outCode += ('<tr>');
    for (var w = 0; w < 7; w++)
    {
        outCode += ('<td width="' + CellWidth + '" align="center" class="calendarDateInput" style="height:' + CellHeight + '; width:' + CellWidth + '; font-weight: bold; border-top: 1px solid dimgray; border-bottom: 1px solid dimgray;">' + WeekDays[w] + '</td>');
    }
    outCode += ('</tr>');

    outCode += ('</table>');

    outCode += ('<span id="' + DateName + '_DayTable_ID">' + object.buildCalendar() + '</span>');

    outCode += ('</span>');

    outCode += ('</td>');
    outCode += ('</tr>');
    outCode += ('</table>');
    
    return outCode;
}

function SetDateInputDate(DateName, DateFormat, NewDate)
{
    if (/^(Y{2,4}(-|\/)?)?((MON)|(MM?M?)|(DD?))(-|\/)?((MON)|(MM?M?)|(DD?))((-|\/)Y{2,4})?$/i.test(DateFormat))
    {
        DateFormat = DateFormat.toUpperCase();
    }
    else
    {
        /* Invalid date format. */
        return;
    }

    /* Get a reference to DateName's object. */
    eval('var object = ' + DateName + '_Object;');
	
	var year;
	var month;
	var day;
	
    if (DateFormat == 'YYYYMMDD' && (/^(\d{4})(\d{2})(\d{2})$/.test(NewDate)))
    {
        year = parseInt(RegExp.$1, 10);
        month = parseInt(RegExp.$2, 10);
        day = parseInt(RegExp.$3, 10);

        object.setPicked(year, (month - 1), day);
    }
    else if (DateFormat == 'YYYY-MM-DD' && (/^(\d{4})-(\d{2})-(\d{2})$/.test(NewDate)))
    {
        year = parseInt(RegExp.$1, 10);
        month = parseInt(RegExp.$2, 10);
        day = parseInt(RegExp.$3, 10);

        object.setPicked(year, (month - 1), day);
    }
    else if (DateFormat == 'MM-DD-YY' && (/^(\d{2})-(\d{2})-(\d{2})$/.test(NewDate)))
    {
        year = parseInt(GetGoodYear(RegExp.$3), 10);
        month = parseInt(RegExp.$1, 10);
        day = parseInt(RegExp.$2, 10);

        object.setPicked(year, (month - 1), day);
    }
    else if (DateFormat == 'DD-MM-YY' && (/^(\d{2})-(\d{2})-(\d{2})$/.test(NewDate)))
    {
        year = parseInt(GetGoodYear(RegExp.$3), 10);
        day = parseInt(RegExp.$1, 10);
        month = parseInt(RegExp.$2, 10);

        object.setPicked(year, (month - 1), day);
    }
    else
    {
        /* Invalid date format. */
        return;
    }

    monthList = object.getMonthList();
    dayList = object.getDayList();
    yearField = object.getYearField();

    /* Select the month. */
    for (var i = 0; i < monthList.length; i++)
    {
        if (monthList.options[i].value == (month - 1))
        {
            monthList.selectedIndex = i;
        }
    }

    /* Select the day. */
    dayList.selectedIndex = (day - 1);

    /* Set the year. */
    var yearString = (year % 100) + '';
    if (yearString.length == 1)
    {
        yearString = '0' + yearString;
    }

    yearField.value = yearString;
}

/* ---- js/submodal/subModal.js ---- */
/**
 * POPUP WINDOW CODE v1.1
 * Used for displaying DHTML only popups instead of using buggy modal windows.
 *
 * By Seth Banks (webmaster at subimage dot com)
 * http://www.subimage.com/
 *
 * Contributions by Eric Angel (tab index code) and Scott (hiding/showing selects for IE users)
 *
 * Up to date code can be found at http://www.subimage.com/dhtml/subModal
 *
 * This code is free for you to use anywhere, just keep this comment block.
 */


/**
 * COMMON DHTML FUNCTIONS
 * These are handy functions I use all the time.
 *
 * By Seth Banks (webmaster at subimage dot com)
 * http://www.subimage.com/
 *
 * Up to date code can be found at http://www.subimage.com/dhtml/
 *
 * This code is free for you to use anywhere, just keep this comment block.
 */

/**
 * Code below taken from - http://www.evolt.org/article/document_body_doctype_switching_and_more/17/30655/
 *
 * Modified 4/22/04 to work with Opera/Moz (by webmaster at subimage dot com)
 *
 * Gets the full width/height because it's different for most browsers.
 */
function getViewportHeight()
{
    if (window.innerHeight != window.undefined)
    {
        return window.innerHeight;
    }

    if (document.compatMode == 'CSS1Compat')
    {
        return document.documentElement.clientHeight;
    }

    if (document.body)
    {
        return document.body.clientHeight;
    }

    return window.undefined;
}

function getViewportWidth()
{
    if (window.innerWidth != window.undefined)
    {
        return window.innerWidth;
    }

    if (document.compatMode == 'CSS1Compat')
    {
        return document.documentElement.clientWidth;
    }

    if (document.body)
    {
        return document.body.clientWidth;
    }

    return window.undefined;
}


// Popup code
var gPopupMask = null;
var gPopupContainer = null;
var gPopFrameIFrame = null;
var gPopFrameDiv = null;
var gReturnFunc;
var gPopupIsShown = false;
var gHideSelects = false;
var gTabIndexes = new Array();

/* List of tags that we want to disable tabbing into (for IE). */
var gTabbableTags = new Array(
    'A', 'BUTTON', 'TEXTAREA', 'INPUT', 'IFRAME'
);

// If using Mozilla or Firefox, use Tab-key trap.
if (!document.all)
{
    document.onkeypress = keyDownHandler;
}

/**
 * Initializes popup code on load.
 */
function initPopUp()
{
    gPopupMask = document.getElementById('popupMask');
    gPopupContainer = document.getElementById('popupContainer');
    gPopFrameIFrame = document.getElementById('popupFrameIFrame');
    gPopFrameDiv = document.getElementById('popupFrameDiv');

    // check to see if this is IE version 6 or lower. hide select boxes if so
    // maybe they'll fix this in version 7?
    var brsVersion = parseInt(window.navigator.appVersion.charAt(0), 10);
    if (brsVersion <= 6 && window.navigator.userAgent.indexOf('MSIE') > -1)
    {
        gHideSelects = true;
    }
}

/**
 * @argument width - int in pixels
 * @argument height - int in pixels
 * @argument url - url to display
 * @argument returnFunc - function to call when returning true from the window.
 */
function showPopWin(url, width, height, returnFunc)
{
    _showPopWin(null, url, width, height, returnFunc);
}

function showPopWinHTML(html, width, height, returnFunc)
{
    _showPopWin(html, '', width, height, returnFunc);
}

function _showPopWin(html, url, width, height, returnFunc)
{
    gPopupIsShown = true;
    disableTabIndexes();
    gPopupMask.style.display = 'block';
    gPopupContainer.style.display = 'block';
    // calculate where to place the window on screen
    centerPopWin(width, height);

    var titleBarHeight = parseInt(document.getElementById('popupTitleBar').offsetHeight, 10);

    gPopupContainer.style.width = width + 'px';
    gPopupContainer.style.height = (height+titleBarHeight) + 'px';
    // need to set the width of the iframe to the title bar width because of the dropshadow
    // some oddness was occuring and causing the frame to poke outside the border in IE6
    gPopFrameIFrame.style.width = parseInt(document.getElementById('popupTitleBar').offsetWidth, 10) + 'px';
    gPopFrameIFrame.style.height = (height) + 'px';
    gPopFrameDiv.style.width = parseInt(document.getElementById('popupTitleBar').offsetWidth, 10) + 'px';
    gPopFrameDiv.style.height = (height) + 'px';

    setPopTitle('');

    // set the url
    if (html == null)
    {
        gPopFrameDiv.style.display = 'none';
        gPopFrameIFrame.style.display = '';

        gPopFrameIFrame.src = url;
    }
    else
    {
        gPopFrameDiv.style.display = '';
        gPopFrameIFrame.style.display = 'none';

        gPopFrameDiv.innerHTML = html;
        gPopFrameDiv.innerHTML += '';
    }

    gReturnFunc = returnFunc;
    // for IE
    if (gHideSelects == true)
    {
        hideSelectBoxes();
    }
}

function setPopTitle(title)
{
    document.getElementById('popupTitle').innerHTML = title;
}

//
var gi = 0;
function centerPopWin(width, height)
{
    if (gPopupIsShown == true)
    {
        if (width == null || isNaN(width))
        {
            width = gPopupContainer.offsetWidth;
        }

        if (height == null)
        {
            height = gPopupContainer.offsetHeight;
        }

        var fullHeight = getViewportHeight();
        var fullWidth = getViewportWidth();

        gPopupMask.style.height = '100%';
        gPopupMask.style.width = '100%';
        gPopupMask.style.top = '0';
        gPopupMask.style.left = '0';

        window.status = gPopupMask.style.top + ' ' + gPopupMask.style.left + ' ' + gi++;

        // Use CSS transform for centering (set in main.css)
        // Just reset top/left to 50% to work with transform: translate(-50%, -50%)
        gPopupContainer.style.top = '50%';
        gPopupContainer.style.left = '50%';
    }
}

/**
 * @argument callReturnFunc - bool - determines if we call the return function specified
 * @argument returnVal - anything - return value
 */
function hidePopWin(callReturnFunc)
{
    gPopupIsShown = false;
    restoreTabIndexes();

    if (gPopupMask == null)
    {
        return;
    }

    gPopupMask.style.display = 'none';
    gPopupContainer.style.display = 'none';
    if (callReturnFunc == true && gReturnFunc != null)
    {
        gReturnFunc(window.frames['popupFrameIFrame'].returnVal);
    }

    gPopFrameIFrame.src = 'js/submodal/loading.html';

    // display all select boxes
    if (gHideSelects == true)
    {
        displaySelectBoxes();
    }
}

function hidePopWinRefresh(callReturnFunc)
{
    hidePopWin(callReturnFunc);

    var sURL = window.location.href;
    window.location.href = (sURL+' ');
}

// Tab key trap. if popup is shown and key was [TAB], suppress it.
// @argument e - event - keyboard event that caused this function to be called.
function keyDownHandler(e)
{
    if (gPopupIsShown && e.keyCode == 9)
    {
        return false;
    }
}

/**
 * Disable all tab indexes for elements in gTabbableTags (for IE).
 *
 * @return void
 */
function disableTabIndexes()
{
    if (!document.all)
    {
        return;
    }

    var i = 0;
    for (var j = 0; j < gTabbableTags.length; j++)
    {
        var tagElements = document.getElementsByTagName(gTabbableTags[j]);

        for (var k = 0 ; k < tagElements.length; k++)
        {
            gTabIndexes[i] = tagElements[k].tabIndex;
            tagElements[k].tabIndex = '-1';
            i++;
        }
    }
}

/**
 * Re-enable all tab indexes for elements in gTabbableTags (for IE).
 *
 * @return void
 */
function restoreTabIndexes()
{
    if (!document.all)
    {
        return;
    }

    var i = 0;
    for (var j = 0; j < gTabbableTags.length; j++)
    {
        var tagElements = document.getElementsByTagName(gTabbableTags[j]);

        for (var k = 0 ; k < tagElements.length; k++)
        {
            tagElements[k].tabIndex = gTabIndexes[i];
            tagElements[k].tabEnabled = true;
            i++;
        }
    }
}


/**
* Hides all drop down form select boxes on the screen so they do not appear above the mask layer.
* IE has a problem with wanted select form tags to always be the topmost z-index or layer
*
* Thanks for the code Scott!
*/
function hideSelectBoxes()
{
    for (var i = 0; i < document.forms.length; i++)
    {
        for (var j = 0; j < document.forms[i].length; j++)
        {
            if (document.forms[i].elements[j].tagName == 'SELECT')
            {
                document.forms[i].elements[j].style.visibility = 'hidden';
            }
        }
    }
}

/**
* Makes all drop down form select boxes on the screen visible so they do not reappear after the dialog is closed.
* IE has a problem with wanted select form tags to always be the topmost z-index or layer
*/
function displaySelectBoxes()
{
    for (var i = 0; i < document.forms.length; i++)
    {
        for (var j = 0; j < document.forms[i].length; j++)
        {
            if (document.forms[i].elements[j].tagName == 'SELECT')
            {
                document.forms[i].elements[j].style.visibility='visible';
            }
        }
    }
}

addEvent(window, 'load', initPopUp, false);
addEvent(window, 'unload', EventCache.flush, false);
addEvent(window, 'resize', centerPopWin, false);
//addEvent(window, 'scroll', centerPopWin, false);
//window.onscroll = centerPopWin;

