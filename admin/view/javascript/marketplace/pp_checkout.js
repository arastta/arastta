//05/01/2015
/*global PAYPAL:true, console:true, document:true, self:true, window:true */
var paypal = paypal || {};

//JSON Polyfill for unsupported older browser
if (!window.JSON) {
    window.JSON = {
        parse: function (sJSON) {
            return eval("(" + sJSON + ")");
        },
        stringify: function (vContent) {
            if (vContent instanceof Object) {
                var sOutput = "";
                if (vContent.constructor === Array) {
                    for (var nId = 0; nId < vContent.length; sOutput += this.stringify(vContent[nId]) + ",", nId++);
                    return "[" + sOutput.substr(0, sOutput.length - 1) + "]";
                }
                if (vContent.toString !== Object.prototype.toString) {
                    return "\"" + vContent.toString().replace(/"/g, "\\$&") + "\"";
                }
                for (var sProp in vContent) {
                    sOutput += "\"" + sProp.replace(/"/g, "\\$&") + "\":" + this.stringify(vContent[sProp]) + ",";
                }
                return "{" + sOutput.substr(0, sOutput.length - 1) + "}";
            }
            return typeof vContent === "string" ? "\"" + vContent.replace(/"/g, "\\$&") + "\"" : String(vContent);
        }
    };
}

//Date.now polyfill
if (!Date.now) {
    Date.now = function() { return new Date().getTime(); };
}

(function () {

    'use strict';

    var docCookies = {
        getItem: function (sKey) {
            if (!sKey) {
                return null;
            }
            return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
        },
        setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure) {
            if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) {
                return false;
            }
            var sExpires = "";
            if (vEnd) {
                switch (vEnd.constructor) {
                    case Number:
                        sExpires = vEnd === Infinity ? "; expires=Fri, 31 Dec 9999 23:59:59 GMT" : "; max-age=" + vEnd;
                        break;
                    case String:
                        sExpires = "; expires=" + vEnd;
                        break;
                    case Date:
                        sExpires = "; expires=" + vEnd.toUTCString();
                        break;
                }
            }
            document.cookie = encodeURIComponent(sKey) + "=" + encodeURIComponent(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
            return true;
        },
        removeItem: function (sKey, sPath, sDomain) {
            if (!this.hasItem(sKey)) {
                return false;
            }
            document.cookie = encodeURIComponent(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "");
            return true;
        },
        hasItem: function (sKey) {
            if (!sKey) {
                return false;
            }
            return (new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
        },
        keys: function () {
            var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
            for (var nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) {
                aKeys[nIdx] = decodeURIComponent(aKeys[nIdx]);
            }
            return aKeys;
        }
    };

    /**
     * Creates an instance of the in-context MiniBrowser UI
     * @param {Object} userConfig Overrides to the default configuration
     */
    paypal.checkout = function () {
        var app = {},
            config = {
                name: 'PPFrame',
                bridgeName: 'PayPalBridge',
                trigger: null,
                isSmartPhone: false,
                isWebView: false,
                merchantID: null,
                showMiniB: true,
                sandBox: false,
                devMode: false,
                log: true,
                mbWidth: 450,
                mbHeight: 535,
                cookiedExp: docCookies.getItem("PPXOEXP"),
                debug: docCookies.getItem("PPDEBUG"),
                secureWindowmsg: "Don't see the secure PayPal browser? We'll help you re-launch the window to complete your purchase. <a onclick='paypal.checkout.startFlow();' class='ppbutton'>Continue</a>",
                loadingMsg: "Loading...",
                oldIe: navigator.userAgent.match(/MSIE [87]\./i),
                currentAgent: getAgent(),
                supportedAgents: {
                    "Chrome": 27,
                    "IE": 9,
                    "MSIE": 9,
                    "Firefox": 30,
                    "Safari": 5.1,
                    "Opera": 23
                },
                buttonJSUrl: '//www.paypalobjects.com/api/button.js',
                sandBoxUrlPrefix : 'https://www.sandbox.paypal.com/checkoutnow?token=',
                liveUrlPrefix : 'https://www.paypal.com/checkoutnow?token=',
                merchantConfig: null,
                guid: null,
                staticBtnHiddenStyle: 'paypal-button-hidden'
            },

            btnList = [],

            isOpen = false,

            errMsg = null,

            ecToken = null;

        app.urlPrefix = '';


        /** PRIVATE **/

        /**
         * Logs the msg to the console.
         * @param {string} msg to print in console
         * @returns {null}
         */
        function _log(msg) {
            if (console) {
                console.log(msg);
            }
        }

        /**
         * Tracking various events
         * @param {object} msg to send via beacon
         * @returns {null}
         */
        function _track(msg, isError) {
            //var fpti = "https://t.paypal.com/ts?v=0.1&pgrp=INTEGRATION_JS_LOG&page=INTEGRATION_JS_LOG&tmpl=INTEGRATION_JS_LOG",
            var url = config.sandBox ? "https://www.sandbox.paypal.com" : "https://www.paypal.com",
                msgStr;

            if(config.devMode) {
                url = "http://localhost:8000";
            }

            if (config.log && typeof msg === "object") {
                msg.merchantSite = document.domain;
                msg.merchantID = config.merchantID;
                msg.version = '2.1';
                msg.token = ecToken;
                msg.guid = config.guid;

                msgStr = JSON.stringify(msg);
                msgStr = encodeURIComponent(msgStr);
                url = url + '/webapps/hermes/api/log?event=' + msg.status
                    + '&state=merchant_incontext&merchantID=' + config.merchantID
                    + '&token=' + (ecToken ? ecToken : 'undefined')         //token="undefined" for FEEL logging
                    + '&level=' + (isError ? 'error' : 'info')
                    + '&cb=' + Date.now()
                    + '&msg=' + msgStr;

                var beacon = new Image();
                beacon.src = url;

                if (config.debug) {
                    _log(decodeURIComponent(msgStr));
                }
            }
        }

        /**
         * Creates the DOM nodes and adds them to the document body to showing the loading screen on merchant site
         */
        function _buildDOM(options) {
            var UI = app.UI = {};

            options = options || {};

            UI.wrapper = document.createElement('div');
            UI.wrapper.id = config.name;

            UI.panel = document.createElement('div');
            UI.panel.className = 'panel';
            UI.panel.id = 'PPPanel';

            UI.mask = document.createElement('div');
            UI.mask.className = 'mask';
            UI.mask.id = 'mask';

            addEvent(UI.mask, 'click', paypal.checkout.startFlow, this);

            UI.loading = document.createElement('div');
            UI.loading.className = 'ppmodal';

            var logo = document.createElement('div');
            logo.className = 'pplogo';
            UI.loading.appendChild(logo);

            var message = document.createElement('div');
            message.className = 'message';
            message.id = 'ppmsg';
            message.innerHTML = config.secureWindowmsg;
            UI.loading.appendChild(message);

            var closeButton = document.createElement('a');
            closeButton.className = 'closeButton';
            closeButton.role = 'button';
            closeButton.innerText = 'Close Window';

            addEvent(closeButton, 'click', _destroy, this);

            UI.loading.appendChild(closeButton);

            if (options.error) {
                var text = document.createElement('div');
                text.className = 'text';
                text.innerText = options.error;
                UI.loading.appendChild(text);

            } else {
                UI.loading.className = UI.loading.className + ' loading';
            }


            UI.wrapper.appendChild(UI.mask);
            UI.wrapper.appendChild(UI.loading);

            document.body.className = document.body.className + ' ' + config.name;

            document.body.appendChild(UI.wrapper);
        }

        /**
         * Embeds the CSS for the UI into the document head
         */
        function _addCSS() {
            var css = '',
                styleEl = document.createElement('style');

            // write the styles into the page
            css += 'body.' + config.name + ' { overflow: hidden; }';
            css += '#' + config.name + ' { z-index: 20002; top: 0; left: 0; }';

            css += '#' + config.name + ' .mask { z-index: 20001; position: absolute; top: 0; left: 0; background-color: black; background-image: radial-gradient(circle farthest-corner, #000000, #4A4A4A); opacity: 0.80; filter: alpha(opacity=80); }';
            css += '#' + config.name + ' .ppmodal { font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; color:#fff; z-index: 20003; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: fixed; margin-left: -165px; margin-top: -80px; }';


            css += '#' + config.name + ' .loading .spinner { height:30px; width:30px; position:absolute; left:48%; top:50%; margin:-15px auto auto -15px; opacity:1; filter: alpha(opacity=100); background-color: rgba(255, 255, 255, 0.701961); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left:8px solid rgba(0,0,0,.20); border-right:8px solid rgba(0,0,0,.20); border-bottom:8px solid rgba(0,0,0,.20); border-top:8px solid rgba(33,128,192,1); border-radius:100%; } @-webkit-keyframes rotation { from {-webkit-transform: rotate(0deg);} to {-webkit-transform: rotate(359deg);}} @-moz-keyframes rotation { from {-moz-transform: rotate(0deg);} to {-moz-transform: rotate(359deg);} } @-o-keyframes rotation { from {-o-transform: rotate(0deg);} to {-o-transform: rotate(359deg);} } @keyframes rotation { from {transform: rotate(0deg);} to {transform: rotate(359deg);}}';
            css += '#' + config.name + ' .loading.noanimation .spinner {height: 48px; width: 48px; border:none; background: url(https://www.paypalobjects.com/webstatic/checkout/hermes/icon_loader_med.gif) no-repeat center center;}';
            css += '#' + config.name + ' .ppmodal.loading { min-height: 160px; }';
            css += '#' + config.name + ' .ppmodal .pplogo {background: url(https://www.paypalobjects.com/webstatic/checkout/hermes/mb_sprite.png) no-repeat 0 0; width: 94px; height: 25px; margin:0 0 26px 130px}';
            css += '#' + config.name + ' .ppmodal .closeButton {position:fixed; top:10px; right:10px; display:inline-block; background: url(https://www.paypalobjects.com/webstatic/checkout/hermes/mb_sprite.png) no-repeat 0 -67px; width: 14px; height: 14px; text-indent: -999em; cursor: pointer; }';
            css += '#' + config.name + ' .ppmodal .closeButton:hover {background: url(https://www.paypalobjects.com/webstatic/checkout/hermes/mb_sprite.png) no-repeat -50px -67px; width: 14px; height: 14px;}';
            css += '#' + config.name + ' .ppmodal .text { font-size: 14px; }';
            css += '#' + config.name + ' .ppmodal a.button {display:block; cursor:pointer; margin-top:20px; color: #0088cc;}';
            css += '#' + config.name + ' .ppmodal a.ppbutton {display:block; cursor:pointer; margin-top:20px; color: #0088cc;}';
            //Retina media query
            css += '@media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: ~"2/1"), only screen and (min-device-pixel-ratio: 2)  { #' + config.name + ' .ppmodal .pplogo {background: url(https://www.paypalobjects.com/webstatic/checkout/hermes/mb_sprite_2x.png) no-repeat 0 0; background-size: 100px 75px;}}';

            styleEl.type = 'text/css';

            if (styleEl.styleSheet) {
                styleEl.styleSheet.cssText = css;
            } else {
                styleEl.appendChild(document.createTextNode(css));
            }

            config.css = css;

            document.getElementsByTagName('head')[0].appendChild(styleEl);
        }

        /* Creates the mask */
        function _createMask() {
            var windowWidth, windowHeight, scrollWidth, scrollHeight, width, height;

            var actualWidth = (document.documentElement) ? document.documentElement.clientWidth : window.innerWidth;

            if (window.innerHeight && window.scrollMaxY) {
                scrollWidth = actualWidth + window.scrollMaxX;
                scrollHeight = window.innerHeight + window.scrollMaxY;
            } else if (document.body.scrollHeight > document.body.offsetHeight) {
                scrollWidth = document.body.scrollWidth;
                scrollHeight = document.body.scrollHeight;
            } else {
                scrollWidth = document.body.offsetWidth;
                scrollHeight = document.body.offsetHeight;
            }

            if (window.innerHeight) {
                windowWidth = actualWidth;
                windowHeight = window.innerHeight;
            } else if (document.documentElement && document.documentElement.clientHeight) {
                windowWidth = document.documentElement.clientWidth;
                windowHeight = document.documentElement.clientHeight;
            } else if (document.body) {
                windowWidth = document.body.clientWidth;
                windowHeight = document.body.clientHeight;
            }

            width = (windowWidth > scrollWidth) ? windowWidth : scrollWidth;
            height = (windowHeight > scrollHeight) ? windowHeight : scrollHeight;

            app.UI.mask.style.width = width + 'px';
            app.UI.mask.style.height = height + 'px';

            if (config.name && document.body.className.indexOf(config.name) === -1) {
                document.body.className += ' ' + config.name;
            }
        }


        function _openMiniBrowser() {
            var left, top, win,
                width = config.mbWidth,
                height = config.mbHeight,
                winOpened = false,
                loading = document.querySelector("#PPFrame .ppmodal.loading");


            //Calculate the popup location based on parent window, need to center to the parent window.
            if (window.outerWidth) {
                left = Math.round((window.outerWidth - width) / 2) + window.screenX;
                top = Math.round((window.outerHeight - height) / 2) + window.screenY;
            } else if (window.screen.width) {
                left = Math.round((window.screen.width - width) / 2);
                top = Math.round((window.screen.height - height) / 2);
            }

            win = window.open('about:blank', config.name, 'top=' + top + ', left=' + left + ', width=' + width + ', height=' + height + ', location=1, status=1, toolbar=0, menubar=0, resizable=1, scrollbars=1');
            app.win = win;

            //Popup blocked case
            if (typeof win === "undefined") {
                _track({"status": "IC_CLICK_OPEN_MB_FAILED"}, true);
                return window;
            } else {
                if(ecToken) {   //for sync ajax case
                    _track({"status": "IC_CLICK_OPEN_MB_SUCCESS"});
                }
            }

            if (win && win.focus) {
                win.focus();
            }

            if (loading) {
                loading.className = "ppmodal";
            }

            //Show the loading screen on the opened popup window till merchant does a 302 after setEC call
            try {
                win.document.write('<!DOCTYPE html><html lang="en"><head><title>PayPal</title><style>.mask{z-index: 20001;position: absolute;top: 0;left: 0;background-color: black;opacity: 0.54;filter: alpha(opacity=54);}.ppmodal{display: none;z-index: 20003;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;-ms-box-sizing: border-box;box-sizing: border-box;width: 100%;height: 100%;top: 0;left: 0;overflow: hidden;background-color: #EFEFEF;-moz-background-clip: padding-box;-webkit-background-clip: padding-box;background-clip: padding-box;padding: 2em;font-family: "helvetica heue", arial, helvetica, sans-serif;position: fixed;}.minibrowser .ppmodal{display: block;}.loading .mask{height: 30px;width: 30px;position: absolute;left: 48%;top: 50%;margin: -15px auto auto -15px;opacity: 1;filter: alpha(opacity=100);background-color: rgba(255, 255, 255, 0.701961);}.loading .mask{height: 48px;width: 48px;border: none;background: url(data:image/gif;base64,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) no-repeat center center;}.ppmodal.loading{min-height: 160px;}.ppmodal .pplogo{background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGoAAAAdCAYAAABCBvnuAAAON2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS41LWMwMTIgMS4xNDk2MDIsIDIwMTIvMTAvMTAtMTg6MTA6MjQgICAgICAgICI+CiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiCiAgICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgICB4bWxuczpkYW09Imh0dHA6Ly93d3cuZGF5LmNvbS9kYW0vMS4wIgogICAgeG1sbnM6dGlmZj0iaHR0cDovL25zLmFkb2JlLmNvbS90aWZmLzEuMC8iCiAgICB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIKICAgIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIgogICAgeG1sbnM6UGF5UGFsPSJ3d3cucGF5cGFsLmNvbS9iYXNlL3YxIgogICAgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIgogICBkYzpmb3JtYXQ9ImltYWdlL3BuZyIKICAgZGM6bW9kaWZpZWQ9IjIwMTQtMDUtMDZUMTU6NTg6MzctMDc6MDAiCiAgIGRhbTpzaXplPSIxMjc1IgogICBkYW06UGh5c2ljYWx3aWR0aGluaW5jaGVzPSItMS4wIgogICBkYW06ZXh0cmFjdGVkPSIyMDE0LTA1LTA2VDE1OjU4OjMwLTA3OjAwIgogICBkYW06c2hhMT0iODViZjgxYTJmMzFkYmYzMDA1ZGU2OTJiODFiYThjNjNlZmJmNDA0ZCIKICAgZGFtOk51bWJlcm9mdGV4dHVhbGNvbW1lbnRzPSIwIgogICBkYW06UHJvZ3Jlc3NpdmU9Im5vIgogICBkYW06RmlsZWZvcm1hdD0iUE5HIgogICBkYW06UGh5c2ljYWxoZWlnaHRpbmRwaT0iLTEiCiAgIGRhbTpDb21tZW50cz0iWE1MOmNvbS5hZG9iZS54bXA6ICZsdDs/eHBhY2tldCBiZWdpbj0mcXVvdDvvu78mcXVvdDsgaWQ9JnF1b3Q7VzVNME1wQ2VoaUh6cmVTek5UY3prYzlkJnF1b3Q7PyZndDsmI3hBOyZsdDt4OnhtcG1ldGEgeG1sbnM6eD0mcXVvdDthZG9iZTpuczptZXRhLyZxdW90OyB4OnhtcHRrPSZxdW90O0Fkb2JlIFhNUCBDb3JlIDUuNS1jMDEyIDEuMTQ5NjAyLCAyMDEyLzEwLzEwLTE4OjEwOjI0ICAgICAgICAmcXVvdDsmZ3Q7JiN4QTsgJmx0O3JkZjpSREYgeG1sbnM6cmRmPSZxdW90O2h0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMmcXVvdDsmZ3Q7JiN4QTsgICZsdDtyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSZxdW90OyZxdW90OyYjeEE7ICAgIHhtbG5zOmRjPSZxdW90O2h0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvJnF1b3Q7JiN4QTsgICAgeG1sbnM6ZGFtPSZxdW90O2h0dHA6Ly93d3cuZGF5LmNvbS9kYW0vMS4wJnF1b3Q7JiN4QTsgICAgeG1sbnM6dGlmZj0mcXVvdDtodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyZxdW90OyYjeEE7ICAgIHhtbG5zOlBheVBhbD0mcXVvdDt3d3cucGF5cGFsLmNvbS9iYXNlL3YxJnF1b3Q7JiN4QTsgICBkYzpmb3JtYXQ9JnF1b3Q7aW1hZ2UvcG5nJnF1b3Q7JiN4QTsgICBkYzptb2RpZmllZD0mcXVvdDsyMDE0LTA1LTA2VDE1OjU4OjM3LjkwOS0wNzowMCZxdW90OyYjeEE7ICAgZGFtOnNpemU9JnF1b3Q7MTI3NSZxdW90OyYjeEE7ICAgZGFtOlBoeXNpY2Fsd2lkdGhpbmluY2hlcz0mcXVvdDstMS4wJnF1b3Q7JiN4QTsgICBkYW06ZXh0cmFjdGVkPSZxdW90OzIwMTQtMDUtMDZUMTU6NTg6MzAuNDMyLTA3OjAwJnF1b3Q7JiN4QTsgICBkYW06c2hhMT0mcXVvdDs4NWJmODFhMmYzMWRiZjMwMDVkZTY5MmI4MWJhOGM2M2VmYmY0MDRkJnF1b3Q7JiN4QTsgICBkYW06TnVtYmVyb2Z0ZXh0dWFsY29tbWVudHM9JnF1b3Q7MCZxdW90OyYjeEE7ICAgZGFtOkZpbGVmb3JtYXQ9JnF1b3Q7UE5HJnF1b3Q7JiN4QTsgICBkYW06UHJvZ3Jlc3NpdmU9JnF1b3Q7bm8mcXVvdDsmI3hBOyAgIGRhbTpQaHlzaWNhbGhlaWdodGluZHBpPSZxdW90Oy0xJnF1b3Q7JiN4QTsgICBkYW06TUlNRXR5cGU9JnF1b3Q7aW1hZ2UvcG5nJnF1b3Q7JiN4QTsgICBkYW06TnVtYmVyb2ZpbWFnZXM9JnF1b3Q7MSZxdW90OyYjeEE7ICAgZGFtOkJpdHNwZXJwaXhlbD0mcXVvdDszMiZxdW90OyYjeEE7ICAgZGFtOlBoeXNpY2FsaGVpZ2h0aW5pbmNoZXM9JnF1b3Q7LTEuMCZxdW90OyYjeEE7ICAgZGFtOlBoeXNpY2Fsd2lkdGhpbmRwaT0mcXVvdDstMSZxdW90OyYjeEE7ICAgdGlmZjpJbWFnZUxlbmd0aD0mcXVvdDsyOSZxdW90OyYjeEE7ICAgdGlmZjpJbWFnZVdpZHRoPSZxdW90OzEwNiZxdW90OyYjeEE7ICAgUGF5UGFsOnN0YXR1cz0mcXVvdDtTb3VyY2VBcHByb3ZlZCZxdW90OyYjeEE7ICAgUGF5UGFsOnNvdXJjZU5vZGVQYXRoPSZxdW90Oy9jb250ZW50L2RhbS9QYXlQYWxEaWdpdGFsQXNzZXRzL3NwYXJ0YUltYWdlcy9HbG9iYWxJbWFnZXMvY2hlY2tvdXQvaGVybWVzL2xvZ29fdjMucG5nJnF1b3Q7JiN4QTsgICBQYXlQYWw6aXNTb3VyY2U9JnF1b3Q7dHJ1ZSZxdW90OyZndDsmI3hBOyAgICZsdDtkYzpsYW5ndWFnZSZndDsmI3hBOyAgICAmbHQ7cmRmOkJhZy8mZ3Q7JiN4QTsgICAmbHQ7L2RjOmxhbmd1YWdlJmd0OyYjeEE7ICAmbHQ7L3JkZjpEZXNjcmlwdGlvbiZndDsmI3hBOyAmbHQ7L3JkZjpSREYmZ3Q7JiN4QTsmbHQ7L3g6eG1wbWV0YSZndDsmI3hBOyZsdDs/eHBhY2tldCBlbmQ9JnF1b3Q7ciZxdW90Oz8mZ3Q7JiN4QTsiCiAgIGRhbTpNSU1FdHlwZT0iaW1hZ2UvcG5nIgogICBkYW06TnVtYmVyb2ZpbWFnZXM9IjEiCiAgIGRhbTpCaXRzcGVycGl4ZWw9IjMyIgogICBkYW06UGh5c2ljYWxoZWlnaHRpbmluY2hlcz0iLTEuMCIKICAgZGFtOlBoeXNpY2Fsd2lkdGhpbmRwaT0iLTEiCiAgIHRpZmY6SW1hZ2VMZW5ndGg9IjI5IgogICB0aWZmOkltYWdlV2lkdGg9IjEwNiIKICAgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo3Mjg5NkM2OEU1MzExMUUxOUZDMTlFRTAyOEVGMjkzQyIKICAgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOkVGMjJBREYyMDkyMDY4MTE4RjYyQTdEMThDRTRDMEFBIgogICB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjcyODk2QzY3RTUzMTExRTE5RkMxOUVFMDI4RUYyOTNDIgogICBQYXlQYWw6c3RhdHVzPSJTb3VyY2VBcHByb3ZlZCIKICAgUGF5UGFsOnNvdXJjZU5vZGVQYXRoPSIvY29udGVudC9kYW0vUGF5UGFsRGlnaXRhbEFzc2V0cy9zcGFydGFJbWFnZXMvR2xvYmFsSW1hZ2VzL2NoZWNrb3V0L2hlcm1lcy9sb2dvLnBuZyIKICAgUGF5UGFsOmlzU291cmNlPSJ0cnVlIgogICB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDUzUuMSBNYWNpbnRvc2giPgogICA8eG1wTU06RGVyaXZlZEZyb20KICAgIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MkE4RThGODIzMjIxNjgxMThBNkRGRTg2OEI5RUI5MjgiCiAgICBzdFJlZjpkb2N1bWVudElEPSIwIi8+CiAgPC9yZGY6RGVzY3JpcHRpb24+CiA8L3JkZjpSREY+CjwveDp4bXBtZXRhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCjw/eHBhY2tldCBlbmQ9InIiPz7/o3JBAAAEwklEQVR4Ae3X7WuWfR3H8e85TYfNm+tm17IVKiV0qbmVrWwqnVhSYsUiC1JIWXkxsubSByVUiIOcFXMZzRvMHhRoElLWZQaZZWESpd2BmdOsmItsqSVt3sxP7wfHg4MPnDs4ThecF54HvH5/wO/NefL5xUvxq37Nu85DGe7jbziG96KAqFiHrj2FISjDCP6Er2Ep4v/kRSjl34hMHEDzrnqoDGcwvYJDrYLKcAg1iAl2C0oZzBuqCJXpQAWH6oTKtBkxgV4BmR/nDdUBlekepldoqP1QmS4hJtBKyOzNG2ovZC6iAc9gAToxAjm8uUJDnYXMETyN5/AG9EAlTEFMkM2Q6cgb6jRk9iLMVyC41goNdRMy7QjzMwju5YgJ8lXIFPOGGoRMB8LsgODmIVJejU34AvZjNz6EOgTehGLK6xCYjqJpQIyjCcWUliRSPQT3VoQ5DiFrkS3EFuxBP7rxbkzGJKxAMeVViMQZyNQjMiWRZkFwRYT5IWRuooDAK3EUjyCHf2EjZH6NwAbI/AFRwlrIjGJaclFymIVImYxByJxGJJrwE6iEAXwSMl9HJP4OpdxE5AnVCjnUI1I+ADl8A4G34J9QhjHIHEegAWOQeR5hGjEMmS2I5P9fGXO4Bjshh20IrMMolGEMMp9D4GnInM0bqh0yD9GHffgmfgOVsBTzcQcqU3fGr3Y7IqWAH0HmByggkkUlM4Q+HMQR/BlyuItnsNoD5LQWgWWQ2Z83VC9UpoMI/BQyd9GNZViCLtyGHNYjEhsgcx6R0gWZf6AhNSROQ2X6GOowCJkb6EILluLzeAA5LERgE2Q684Y6CZXhRdTibZC5g9cjTBFyaEYkZmAESnmE2Qgswihk1iAA2CXn0ItAJ2Quox5htkDmAV6GwB7IvCNvqOtQDgP4KAoI9ENmK6KEv8Aj1CJSjkHmBUzFbyHzZYs0C8rpl3gXIvFzyLwdAfdUxoP5FGQaEciWzGXB/Qp9iS/hM/gImlBApFyEzHxECUNQyjWEeR9kTuKLkPkdai1UKwR3Cn2J3diO9XgNImUS7kMp/0ENAu45yBxHJP4KpdxG5Am1BIKbgwAyDUKmEQH3RsicQJipuA2lPIDP/lEsQliodsgMowaBLM9COeZ0O2R2IlAHmV/kDfVhyNxB5HAFMl0IMxMXINODgDsEZfg4Ah6qN+8cNtMguCLCvBZDkFmHQAtkDucN1VNqYeXwbcjcRzdasBgvYACC24iAWwmN43solAh1spw5bAYgM4xPYDFa8GkMQ3DNCGyAzNa8ob4DmcOIHNqgx9CCgKvBDQhuCPWIEqGulzOHzQ6oTGOoRaAHMmvyhroCmW2IHOzxWdI9PIJMHaKEXgjuneNEqoPgViFymIGrUIYRyFxFJE5AZm7eUA8hU0TkNBPfgkq4gOWQ+SOihFr8HjJ7EOOEWgTBzUbkNA/noBK+jw9C5igicQk+Sgp5Q61AW0or4jEsxKfQjwP4LFpRQGAl2lIaESXsg8xFTM0INQmr0ZayHPEYlmMHDqAf27AAgSlYg7bEezATkWhCW8pcRC4cFYkLfz9k/ovnEU8cjko0B7cg04GoDNVQk3EOMt9FVI5qqMXwZXgZz1ZDVVVDVVVDPVH+B74slsxdvI/MAAAAAElFTkSuQmCC) no-repeat;position: relative;top: 33%;left: 38%;width: 106px;height: 29px;}.ppmodal .closeButton{width: 13px;height: 13px;padding: 8px;position: absolute;top: 4px;right: 4px;background: transparent url(https://www.paypalobjects.com/webstatic/checkout/hermes/mb_sprite.png) no-repeat center center;background-size: 13px;text-indent: -999em;-webkit-tap-highlight-color: rgba(0, 0, 0, 0.2);cursor: pointer;}</style></head><body><div><div class="ppmodal loading"><div class="pplogo"></div><div class="mask"></div></div></div></body></html>');
            } catch (err) {
                errMsg = "unable to write to minibrowser";
                _log("unable to write to minibrowser");
            }

            winOpened = true;

            //Polling minibrowser to detect if window is close, if closed redirect the parent window to return/cancel url
            if (winOpened) {
                app.intVal = setInterval(function () {
                    if (win && win.closed) {
                        clearInterval(app.intVal);
                        winOpened = false;
                        return _destroy();
                    }
                }, 500);
            }

            _listenMiniBrowser();

            return win;
        }


        /**
         * Adds post message listener to listen for messages from MiniBrowser
         * @return {undefined}
         */

        function _listenMiniBrowser() {
            var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent",
                eventer = window[eventMethod],
                data,
                messageEvent = eventMethod === "attachEvent" ? "onmessage" : "message";

            //Browsers that support postMessage
            if (window.postMessage) {

                /**
                 * Event to listen for post messages sent from PayPal popup window.
                 * Data from PayPal will contains return url and the localised content.
                 */
                eventer(messageEvent, function (event) {
                    var msg = document.querySelector("#PPFrame .message");

                    //Domain check to accept post messages from PayPal domain only or in dev mode
                    if (event.origin.match(/paypal.com/i) || config.devMode) {
                        try {
                            data = JSON.parse(event.data);
                        } catch(e) {
                            data = event.data;
                        }

                        config.returnUrl = data.returnUrl;
                        config.landingUrl = data.landingUrl;
                        config.secureWindowmsg = data.secureWindowmsg || config.secureWindowmsg;

                        //de-coding the urls
                        if (config.returnUrl) {
                            config.returnUrl = config.returnUrl.replace(/&amp;/g, '&');
                        }

                        if (config.landingUrl) {
                            config.landingUrl = config.landingUrl.replace(/&amp;/g, '&');
                        }

                        if (data.cancelUrl) {
                            config.cancelUrl = data.cancelUrl.replace(/&amp;/g, '&');
                        }

                        //Update the content from the post message response
                        if (msg) {
                            msg.innerHTML = config.secureWindowmsg;
                        }

                        if(!ecToken && config.landingUrl) {
                            ecToken = getECToken(config.landingUrl);
                            //logging for non-ajax case
                            _track({"status": "IC_CLICK_OPEN_MB_SUCCESS"});
                        }

                        if (data.updateParent && config.returnUrl) {
                            if(msg) {
                                msg.innerHTML = config.loadingMsg;
                            }
                            //top.location.href = config.returnUrl;
                            data.cancelUrl = null;
                            //config.cancelUrl = null;
                            _destroy();
                        }

                    } else {
                        _log("Message received from invalid domain");
                        errMsg = "Message received from invalid domain";
                    }

                }, false);
            }
        }

        /**
         * Renders and displays the UI
         *
         * @return {object} The new popup window object the flow will appear in.
         */
        function _render() {

            try {

                var elem = window.event ?
                    (window.event.currentTarget || window.event.target || window.event.srcElement) : this;

                if (window.event) {
                    // if user is holding shift, control, or command, let the link do its thing
                    if (event.ctrlKey || event.shiftKey || event.metaKey) {
                        _track({"status": "IC_RENDER_META_KEYPRESS"});
                        return null;
                    }
                }

                //PXP decision return not to show in-context flow
                if (config.showMiniB === false) {
                    return null;
                }

                if (elem && elem.form) {
                    elem.form.target = config.name;
                } else if (elem && elem.tagName && elem.tagName.toLowerCase() === 'a') {
                    elem.target = config.name;
                } else if (elem && elem.tagName && (elem.tagName.toLowerCase() === 'img' || elem.tagName.toLowerCase() === 'button') && elem.parentNode.tagName.toLowerCase() === 'a') {
                    elem.parentNode.target = config.name;
                } else if (elem && elem.tagName && elem.tagName.toLowerCase() === 'button' && elem.parentNode.parentNode.tagName.toLowerCase() === 'a') {
                    elem.parentNode.parentNode.target = config.name;
                } else if (this && this.hasOwnProperty("target") && typeof this.target !== "undefined") {
                    this.target = config.name;
                }
                //If PayPal mask is not present only
                if (!document.querySelectorAll("#PPFrame").length) {
                    _buildDOM();
                    _createMask();
                    _bindEvents();
                }

                isOpen = true;

                return _openMiniBrowser();

            } catch (err) {
                _track({"status": "IC_RENDER_ERROR", "error_msg": err}, true);
                errMsg = err;
            }

        }

        /**
         * Custom event which does some cleanup: all UI DOM nodes and custom events
         * are removed from the current page
         *
         * @param {Event} e The event object
         */
        function _destroy(e) {
            var UI = app.UI;
            //To avoid double refresh triggering from clear interval
            clearInterval(app.intVal);

            if (isOpen && UI.wrapper && UI.wrapper.parentNode) {
                //Update the content from the post message response
                var msg = document.querySelector("#PPFrame .message");
                if (msg && config.cancelUrl) {
                    msg.innerHTML = config.loadingMsg;

                    //From startFlow or single page apps with # cancel urls
                    if (config.fromStartFlow || (window.location.href.split("#")[0] == config.cancelUrl.split("#")[0])) {
                        UI.wrapper.parentNode.removeChild(UI.wrapper);
                    }

                    if(!config.returnUrl) {
                        _track({"status": "IC_DESTROY_TO_CANCEL_URL"});
                    }

                    //Marketplace.loadweb(baseUrl + 'index.php?route=extension/marketplace/api&api=api/checkout');
                    //window.location.replace(config.cancelUrl);

                } else if(!config.returnUrl){       //if no cancel or return url is passed in
                    UI.wrapper.parentNode.removeChild(UI.wrapper);
                    document.body.className = document.body.className.replace(config.name, '');
                    _track({"status": "IC_DESTROY_NO_CANCEL_RETURN_URL"});
                }
            }

            _removeEvents();

            isOpen = false;

            if (app.win && app.win.close) {
                app.win.close();
            }

            if (config.returnUrl) {
                _track({"status": "IC_DESTROY_TO_RETURN_URL"});
                if (config.fromStartFlow) {
                    if (isOpen && UI.wrapper && UI.wrapper.parentNode) {
                        UI.wrapper.parentNode.removeChild(UI.wrapper);
                    }
                }
                Marketplace.loadweb(baseUrl + 'index.php?route=extension/marketplace/api&api=payment/pp_express/checkoutReturn');
                //window.location.replace(config.returnUrl);
            }
        }

        /**
         *  Hides lightbox when user clicks Esc key.
         */
        function _toggleLightbox(e) {
            if (e.which === null && (e.charCode !== null || e.keyCode !== null)) {
                e.which = e.charCode !== null ? e.charCode : e.keyCode;
            }
            if (e.which === 27) {
                errMsg = 'Buyer cancelled the transaction';
                _destroy();
            }
        }

        /**
         * Sets up the events for an instance
         */
        function _bindEvents() {
            if (window.orientation) {
                addEvent(window, 'orientationchange', _createMask, this);
            }
            addEvent(window, 'resize', _createMask, this);
            //addEvent(window, 'unload', _destroy, this);
            addEvent(window, 'keyup', _toggleLightbox, this);
        }

        /**
         * Remove all the events for an instance
         */
        function _removeEvents() {
            if (window.orientation) {
                removeEvent(window, 'orientationchange', _createMask);
            }
            removeEvent(window, 'resize', _createMask);
            //removeEvent(window, 'unload', _destroy);
            removeEvent(window, 'keyup', _toggleLightbox);
        }

        function _clickHandler(event){
            var merchantConfig = config.merchantConfig;
            if(merchantConfig && merchantConfig.condition && !merchantConfig.condition()) {
                return null;
            }
            var customClick = merchantConfig && merchantConfig.click;
            if(customClick) {
                customClick(event);
            } else {
                _render.call(this, event);
            }
        }

        /*
         * Finds elements with data-paypal-button data attributes and adds click event listeners
         */
        function _setTriggers() {

            var i = btnList.length;
            while (i--) {
                _setTrigger(btnList[i]);
            }
        }

        /*
         * Adds click event listeners
         */
        function _setTrigger(el) {
            addEvent(el, 'click', _clickHandler, this);
        }

        /**
         * Detects if the view is being rendered in mobile.
         * @param userAgent string
         * @returns {boolean} true if the view is loaded in mobile/tablet.
         */

        function _isDevice(userAgent) {
            var isDevice = false;

            if (userAgent.match(/Android|webOS|iPhone|iPad|iPod|bada|Symbian|Palm|CriOS|BlackBerry|IEMobile|WindowsMobile|Opera Mini/i)) {
                isDevice = true;
            }

            return isDevice;
        }

        /**
         * Method to detect if the merchant page is in web view
         * @param userAgent string
         * @returns {boolean} True if web view
         */
        function _isWebView(userAgent) {
            return (/(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i).test(userAgent);
        }

        /**
         * IE doesn't support cross domain post message. Create an iFrame Bridge to communicate between Mini browser and parent window.
         * This is only for IE
         * @returns {undefined}
         */
        function _setUpPayPalBridge() {

            var iframe,
                domain = config.sandBox ? 'https://www.sandbox.paypal.com' : 'https://www.paypal.com';

            // workaround: IE6 + 7 won't let you name an iframe after you create it
            try {
                iframe = document.createElement('<iframe name="' + config.bridgeName + '">');
            } catch (e) {
                iframe = document.createElement('iframe');
                iframe.name = config.bridgeName;
            }

            iframe.src = domain + '/checkoutnow?token=EC-BR&bridge=true';
            if(config.devMode) {
                iframe.src = 'http://localhost:8000/checkoutnow/static/html/cookiecheck.html?token=EC-BR&bridge=true';
            }

            iframe.frameBorder = '0';
            iframe.border = '0';
            iframe.scrolling = 'no';
            iframe.allowTransparency = 'true';
            iframe.style.display = 'none';
            iframe.style.margin = '0';
            iframe.style.padding = '0';
            iframe.style.border = '0px none';
            iframe.style.overflow = 'hidden';
            document.body.appendChild(iframe);

        }

        /**
         * Detects if the browser is MiniBrowser eligible.
         * @param {none}
         * @returns {boolean} true if eligible
         */
        function _isICEligible() {
            var userAgent = navigator.userAgent.toLowerCase();

            config.isSmartPhone = _isDevice(userAgent);
            config.isWebView = _isWebView(userAgent);
            if (typeof config.currentAgent === "object" && config.currentAgent.length === 2) {
                if (parseFloat(config.currentAgent[1]) < config.supportedAgents[config.currentAgent[0]]) {
                    _track({"status": "IC_ELIGIBLITY_BROWSER_NOT_SUPPORTED", "browser": config.currentAgent[0], "browserversion": config.currentAgent[1]});
                    return false;
                }
            }

            return !(config.isSmartPhone || config.isWebView || config.oldIe);
        }


        /**
         * Renders the initial checkout flow on the page
         * Basically an init function
         */
        function _init() {

            window.name = (window.name === config.name) ? '' : window.name;

            //Add event listeners to PayPal trigger elements
            _setTriggers();

            //Do nothing if the device/browser is not eligible for in-context experience
            if (!_isICEligible()) {
                config.showMiniB = false;
                return;
            }

            //SetUp PayPal Bridge
            _setUpPayPalBridge();

            //Add PayPal specific css on the merchant site
            _addCSS();

            var gOldOnError = window.onerror;
            // Override previous handler.
            window.onerror = function errorHandler(errorMsg, url, lineNumber) {
                if(config.debug === "true") {
                    _track({"status": "IC_WINDOW_ERROR", "errmsg": errorMsg, "url": url}, true);
                }

                if (gOldOnError) {
                    return gOldOnError(errorMsg, url, lineNumber);
                }
                return false;
            }
        };

        function _getBtnContainers(el, result) {
            //either an array or nodelist
            if (el.constructor.toString().indexOf('Array') > -1 || ((el.length > 0) && (typeof el.item !== 'undefined'))) {
                for (var i = 0; i < el.length; i++) {
                    _getBtnContainers(el[i], result);
                }
            } else {
                var domEl = (typeof el === 'string') ? document.getElementById(el) : el;
                if(domEl) {
                    result.push(domEl);
                } else {
                    _track({"status": "IC_SETUP_CONTAINER_ERROR", "error_msg": "Can't find element " + el}, true);
                }
            }
        }

        function _addButtonElement (container) {

            var locale = config.merchantConfig.locale || "en_US";
            var buttonDom = paypal.button.create(config.merchantID, { lc: locale },
                {
                    label: 'checkout',
                    type: 'button'
                }
            );

            var buttonEle = buttonDom.el,
                btnTagEle = buttonEle.getElementsByTagName("BUTTON")[0];
            //should bind onclick event in here
            if(container.nodeName === 'A') {
                btnList.push(container);

                //for IE8-, button inside of link doesn't work
                if(config.oldIe){
                    addEvent(btnTagEle, "click", function() { window.location = container.getAttribute("href"); });
                }

            } else {    //a form
                btnList.push(btnTagEle);
            }
            container.appendChild(buttonEle);

        }

        /**
         *   Create Javascript buttons if needed based on merchant's setup and then initialize
         */
        function _initWithButtonGeneration () {

            var jsBtnIds = config.merchantConfig.container,
                customBtnIds = config.merchantConfig.button,
                btnContainers = [];

            //for IE8 and IE9
            if(jsBtnIds && jsBtnIds.length && this.onreadystatechange && !(this.readyState === 'loaded' || this.readyState === 'complete')){
                return;
            }

            //get all container list
            _getBtnContainers(jsBtnIds || customBtnIds, btnContainers);

            if(customBtnIds) {
                for(var i in btnContainers) {
                    btnList.push(btnContainers[i]);
                }

            } else {
                for(var i in btnContainers) {
                    _addButtonElement(btnContainers[i]);
                }
            }

            _track({"status": "IC_SETUP", "button-type": (customBtnIds ? "STATIC" : "JS"), "button-number": btnList.length});

            _init();

            //to ensure users not able to click static buttons before script is loaded
            var hideBtns = document.querySelectorAll("." + config.staticBtnHiddenStyle);
            var hideBtnsLength = hideBtns.length;
            for(var i = 0; i < hideBtnsLength; i++) {
                hideBtns[i].className = hideBtns[i].className.replace(config.staticBtnHiddenStyle, '');
            }

        }

        /** PUBLIC **/
            //paypal.checkout.setup(merchant, {
            //    container: 'myContainer',          // {String|HTMLElement|Array} Optional. `submit` and `click` events are hijacked when possible.
            //    button: 'myButton',                // {String|HTMLElement|Array} Optional. HTMLElement/ID of a custom buttom.
            //    locale: 'en_US',                   // {String} Optional. Local code for localization. Defaults to 'en_US'
            //    environment: 'production',         // {String} Optional. Defaults to 'production'. Possible options are 'sandbox'.
            //    click: function() {}               // {Function} Optional. Overrides the behavior when the button is clicked.
            //    condition: function() {}          // {Function} Optional. Boolean if the PayPal popup should when button is clicked.
            //});

        app.setup = function (merchantId, merchantConfig) {
            config.guid = getGUID();
            config.merchantID = merchantId;

            if(!merchantId) {
                _track({"status": "IC_SETUP_MERCHANTID_ERROR", "error_msg": "Merchant id is required for setup!"}, true);
                console.log("Merchant id is required for setup!");
                return;
            }

            config.merchantConfig = merchantConfig;
            config.sandBox = merchantConfig.environment === 'sandbox' ? true : false;
            app.urlPrefix = config.sandBox ? config.sandBoxUrlPrefix : config.liveUrlPrefix;


            if((merchantConfig && merchantConfig.button) || (paypal.button && paypal.button.create)) {
                _initWithButtonGeneration();
            } else {
                //fetch button.js
                var buttonScriptElement = document.createElement('script');
                buttonScriptElement.async = true;
                buttonScriptElement.src = config.buttonJSUrl;

                if(buttonScriptElement.onload !== undefined) {
                    buttonScriptElement.onload = _initWithButtonGeneration;
                } else {
                    buttonScriptElement.onreadystatechange = _initWithButtonGeneration;
                }

                document.getElementsByTagName('HEAD')[0].appendChild(buttonScriptElement);
            }

        };

        /**
         * Public method to init the XO flow manually for ASYNC AJAX flow
         * This method need to be called before AJAX call is being made on merchant site
         * @param {null}
         */
        app.initXO = function () {

            //For non IC eligible browsers load the url in the same window
            if (!_isICEligible() || !config.showMiniB) {
                return;
            }

            config.win = _render();
        };

        /**
         * Public method to start the flow manually, e.g. from Flash
         * @param {string} url or token of the landing page that needs to be loaded in mini browser
         */
        app.startFlow = function (url) {

            if (app.win) {
                app.win.focus();
                if(!url) {
                    //if mini browser already opens and url is not passed, then it is a restart
                    return;
                }
            }

            url = url || config.landingUrl;

            //url can be either an url or token
            //if token is passed in
            if (url && url.toLowerCase().indexOf("ec-") === 0) {
                url = app.urlPrefix + url;
            }

            if (url && url.match(/paypal.com/i)) {
                config.fromStartFlow = true;
            }

            if (url) {
                url = url.replace(/\s+$/,"");
            }

            try{
                ecToken = getECToken(url);
            } catch (e) {
                _log("EC Token is not passed in url passed by ajax response");
            }

            //For non IC eligible browsers load the url in the same window
            if (!_isICEligible() || !config.showMiniB) {
                if (url) {
                    location.href = url;
                } else {
                    window.name = config.name;
                }
                return;
            }

            var win = config.win || _render();

            //If Mini browser is blocked by popup blocker assign the user to full context as fallback (legacy integrations)
            win = win || window;

            //If already Mini browser window is opened and changing the name in cross domain throws permission denied exception
            try {
                win.name = win.name || config.name;
            } catch (e) {
                _log("Mini browser window already opened and trying to change name");
            }

            if (url) {
                if (win.location) {
                    win.location = url;
                } else {
                    win.src = url;
                }
            }

            if(config.win) {    //for async ajax case.
                _track({"status": "IC_CLICK_OPEN_MB_SUCCESS"});
            }

        };

        /**
         * Public method to close the flow's UI
         */
        app.closeFlow = function (successUrl) {
            _destroy();

            if (successUrl) {
                Marketplace.loadweb(baseUrl + 'index.php?route=extension/marketplace/api&api=checkout/success')
            }
        };

        // Return public methods
        return app;

    }();

    /** HELPER METHODS **/

    /**
     * Storage object for all events; used to obtain exact signature when
     * removing events
     */
    var eventCache = [];

    /**
     * Normalized method of adding an event to an element
     *
     * @param {HTMLElement} obj The object to attach the event to
     * @param {String} type The type of event minus the "on"
     * @param {Function} fn The callback function to add
     * @param {Object} scope A custom scope to use in the callback (optional)
     */
    function addEvent(obj, type, fn, scope) {
        scope = scope || obj;

        var wrappedFn;

        if (obj.addEventListener) {
            wrappedFn = function (e) {
                fn.call(scope, e);
            };
            obj.addEventListener(type, wrappedFn, false);
        } else if (obj.attachEvent) {
            wrappedFn = function () {
                var e = window.event;
                //e.target = e.target || e.srcElement;

                e.preventDefault = function () {
                    window.event.returnValue = false;
                };

                fn.call(scope, e);
            };

            obj.attachEvent('on' + type, wrappedFn);
        }

        eventCache.push([obj, type, fn, wrappedFn]);
    }

    /**
     * Normalized method of removing an event from an element
     *
     * @param {HTMLElement} obj The object to attach the event to
     * @param {String} type The type of event minus the "on"
     * @param {Function} fn The callback function to remove
     */
    function removeEvent(obj, type, fn) {
        var wrappedFn, item, i;

        for (i = 0; i < eventCache.length; i++) {
            item = eventCache[i];

            if (item[0] == obj && item[1] == type && item[2] == fn) {
                wrappedFn = item[3];

                if (wrappedFn) {
                    if (obj.removeEventListener) {
                        obj.removeEventListener(type, wrappedFn, false);
                    } else if (obj.detachEvent) {
                        obj.detachEvent('on' + type, wrappedFn);
                    }
                }
            }
        }
    }

    function getAgent() {
        var ua = navigator.userAgent, tem,
            M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
        if (/trident/i.test(M[1])) {
            tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
            return ['IE', (tem[1] || '')];
        }
        if (M[1] === 'Chrome') {
            tem = ua.match(/\bOPR\/(\d+)/)
            if (tem != null) return ['Opera', tem[1]];
        }
        M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
        if ((tem = ua.match(/version\/(\d+(\.\d{1,2}))/i)) != null) M.splice(1, 1, tem[1]);
        return M;
    }

    /**
     * Generates a GUID string.
     * @returns {String} The generated GUID.
     * @example af8a8416-6e18-a307-bd9c-f2c947bbb3aa
     * @author Slavik Meltser (slavik@meltser.info).
     * @link http://slavik.meltser.info/?p=142
     */
    function getGUID(){
        function _p8(s) {
            var p = (Math.random().toString(16)+"000000000").substr(2,8);
            return s ? "-" + p.substr(0,4) + "-" + p.substr(4,4) : p ;
        }
        return _p8() + _p8(true) + _p8(true) + _p8();
    }

    function getECToken(url) {
        var parts = url.split("EC-");
        return parts.length> 1 ? "EC-" + parts[1].split("&")[0] : null;
    }

    if (typeof window.paypalCheckoutReady === 'function') {
        window.paypalCheckoutReady();
    }

}());

//module.exports = paypal;

//the following will be moved to the shim js
var PAYPAL = PAYPAL || {};

if(!PAYPAL.apps) {
    PAYPAL.apps = {};
}

PAYPAL.apps.Checkout = {
    initXO: paypal.checkout.initXO,
    startFlow: paypal.checkout.startFlow,
    closeFlow: paypal.checkout.closeFlow,
    restartFlow: paypal.checkout.startFlow
};

//for backward compatibility
function callNewSetup() {
    var oldButtonEl = document.querySelectorAll('[data-paypal-button]');
    var oldButtons = oldButtonEl.length ? oldButtonEl : [];
    var sandboxEl = document.querySelectorAll('[data-paypal-sandbox]');
    var environment = sandboxEl.length ? 'sandbox' : 'production';
    var merchantIdEl = document.querySelectorAll('[data-paypal-id]');
    var merchantId = merchantIdEl.length ? merchantIdEl[0].getAttribute('data-paypal-id') : document.domain;

    paypal.checkout.setup(merchantId, {
        environment: environment,
        button: oldButtons
    });
}

if (typeof window.paypalCheckoutReady !== 'function') {
    if (window.addEventListener) {
        window.addEventListener('load', callNewSetup, false);
    }
    else if (window.attachEvent) {
        window.attachEvent('onload', callNewSetup);
    }
}
