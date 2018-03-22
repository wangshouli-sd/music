(function() {
    function addHandler(element, type, handler) {
        if (!!element.addEventListener) {
            element.addEventListener(type, handler, false);
        } else if (!!element.attachEvent) {
            element.attachEvent("on" + type, handler);
        } else {
            element["on" + type] = handler;
        }
    }
    var sys = {},
        ua = navigator.userAgent;
    sys.iphone = ua.indexOf("iPhone") > -1;
    sys.ipod = ua.indexOf("iPod") > -1;
    sys.ipad = ua.indexOf("iPad") > -1;
    sys.nokiaN = ua.indexOf("NokiaN") > -1;
    /Win(?:dows )?([^do]{2})/.test(ua);
    sys.winMobile = /^CE|Ph$/.test(RegExp["$1"]);
    sys.ios = navigator.platform.indexOf("Mac") == 0 && ua.indexOf("Mobile") > -1;
    sys.android = ua.indexOf("Android") > -1;

    var isNoPC = sys.iphone || sys.ipod || sys.ipad || sys.nokiaN || sys.winMobile || sys.ios || sys.android;
    if (!isNoPC) {
        document.write('<div id="J_Mark53645"></div>' +
            '<div id="J_LoginIframeWarp53645" class="">' +
            '<p>正在处理，请稍后...</p>' +
            '<iframe frameborder="0" name="loginIframe53645" id="loginIframe53645" allowtransparency="true" scrolling="no" seamless="seamless" style="position:absolute; top:0; left:0; width:100%; height:529px; z-index:2;"></iframe>' +
            '<a href="javascript:;" id="J_LoginClose53645">×</a>' + // src="http://127.0.0.1:3006/login?debug=true" style="width:100%; height:100%;"
            '</div>');
        var css = '#J_Mark53645{display:none; position:fixed; margin:0; padding:0; top:0; left:0; width:100%; height:100%; background-color:#000; filter:alpha(opacity=30); opacity:0.3; z-index:1982;}' +
            '#J_LoginIframeWarp53645{position:fixed; margin:0; padding:0; top:-9999px; left:-9999px; width:470px; height:529px; margin-left:-235px; border:1px solid #b1b1b1; z-index:1984; display:block; font-size:12px; line-height:1; font-family: 微软雅黑,Tahoma,Helvetica,Arial,sans-serif; background:#fff url(https://passport.migu.cn/images/redirect.png) no-repeat center 40%;}' +
            '#J_LoginIframeWarp53645.music{background-image:url(https://passport.migu.cn/images/redirect-music.png);}' +
            '#J_LoginIframeWarp53645.read{background-image:url(https://passport.migu.cn/images/redirect-read.png);}' +
            '#J_LoginIframeWarp53645.comic{background-image:url(https://passport.migu.cn/images/redirect-comic.png);}' +
            '#J_LoginIframeWarp53645.video{background-image:url(https://passport.migu.cn/images/redirect-video.png);}' +
            '#J_LoginIframeWarp53645.game{background-image:url(https://passport.migu.cn/images/redirect-game.png);}' +
            '#J_LoginIframeWarp53645 p{position:absolute; margin:0; padding:0; top:40%; left:0; margin-top:55px; width:100%; font-size:24px; color:#444; text-align:center; z-index:1;}' +
            '#J_LoginClose53645{position:absolute; top:1px; right:1px; width:33px; height:33px; border-left:1px solid #b1b1b1; color:#e11377; text-align:center; font-weight:bold; text-decoration:none; outline:none; cursor:pointer; font-size:24px; line-height:30px; font-family: 微软雅黑,Tahoma,Helvetica,Arial,sans-serif; z-index:3;}' +
            '#J_LoginClose53645:hover{background-color:#e7e7e7;}';
        var head = document.head || document.getElementsByTagName("head")[0] || document.documentElement;
        var style = document.createElement("style");
        style.type = "text/css";
        try {
            style.appendChild(document.createTextNode(css));
        } catch (e) {
            style.styleSheet.cssText = css;
        }
        head.insertBefore(style, head.firstChild);
        /*iframe 跨域传值处理 */
        if (!!window.postMessage) {
            addHandler(window, "message", function(evt) {
                evt = evt || window.event;
                var ifr = document.getElementById("loginIframe53645"),
                    ifrWin = ifr.contentWindow || ifr.contentDocument;
                var warp = document.getElementById("J_LoginIframeWarp53645");
                if (evt.source != ifrWin) {
                    return;
                }
                ifr.style.height = evt.data + "px";
                warp.style.height = evt.data + "px";
            });
        } else {
            setTimeout(function() {
                var _hash = location.hash;
                if (_hash != "") {
                    _hash = _hash.substr(1);
                    var hArrs = _hash.split("&"),
                        height;
                    for (var i = 0, len = hArrs.length; i < len; i++) {
                        var hArr = hArrs[i].split("=");
                        if (hArr[0] == "height") {
                            height = hArr[1];
                            break;
                        }
                    }
                    if (height != undefined) {
                        var ifr = document.getElementById("loginIframe53645");
                        var warp = document.getElementById("J_LoginIframeWarp53645");
                        var ifrHeight = parseInt(ifr.style.height);
                        if (height != ifrHeight) {
                            ifr.style.height = height + "px";
                            warp.style.height = height + "px";
                        }
                    }
                }
                setTimeout(arguments.callee, 100);
            }, 100);
        }
        addHandler(document.getElementById("J_LoginBtn"), "click", function() {
            var warp = document.getElementById("J_LoginIframeWarp53645"),
                ifr = document.getElementById("loginIframe53645"),
                mark = document.getElementById("J_Mark53645");
            warp.style.top = "50%";
            warp.style.left = "50%";
            warp.style.marginTop = -parseInt(ifr.style.height) / 2 + "px";
            warp.style.display = "block";
            mark.style.display = "block";
        
           ifr.src = "https://passport.migu.cn/login?sourceid=100001&apptype=0&forceAuthn=false&isPassive=false&authType=MiguPassport&passwordControl=0&display=web&callbackURL=&relayState=&referer=null&logintype=1&phoneNumber=";
        });
        addHandler(document.getElementById("J_LoginClose53645"), "click", function() {
            var warp = document.getElementById("J_LoginIframeWarp53645"),
            ifr = document.getElementById("loginIframe53645"),
                mark = document.getElementById("J_Mark53645");
            ifr.style.height="529px";
            warp.style.height="529px";
            warp.style.display = "none";
            mark.style.display = "none";
        });
    } else {
        addHandler(document.getElementById("J_LoginBtn"), "click", function() {
            top.location.href = "https://passport.migu.cn/login?sourceid=100001&apptype=0&forceAuthn=false&isPassive=false&authType=MiguPassport&passwordControl=0&display=web&callbackURL=&relayState=&referer=null&logintype=1&phoneNumber=";
        });
    }
})();
