function ajax_migu_2300304(options) {
	function createXHR() {
		var xhr = null;
		if (typeof XMLHttpRequest != undefined) {
			xhr = new XMLHttpRequest();
		} else if (typeof ActiveXObject != undefined) {
			var versions = ["MSXML2.XMLHttp.6.0", "MSXML2.XMLHttp.3.0", "MSXML2.XMLHttp"];
			for (var i = 0, len = versions.length; i < len; i++) {
				try {
					xhr = new ActiveXObject(versions[i]);
					break;
				} catch (e) {}
			}
		} else {
			throw new Error("No Xhr object available");
		}
		return xhr;
	}
	function paramString(obj) {
		var str = "";
		if (!!obj && typeof obj == "object") {
			for (var k in obj) {
				str += "&" + k + "=" + obj[k];
			}
			str = str.substr(1);
		}
		return str;
	}

	function getUrlMark(url) {
		return url.indexOf("?") > -1 ? "&" : "?";
	}

	if (!window.JSON) {
		window.JSON = {};
	}
	if (typeof JSON.parse !== "function") {
		JSON.parse = function (text) {
			var j,
			cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
			text = String(text);
			cx.lastIndex = 0;
			if (cx.test(text)) {
				text = text.replace(cx, function (a) {
						return '\\u' +
						('0000' + a.charCodeAt(0).toString(16)).slice(-4);
					});
			}
			if (/^[\],:{}\s]*$/
				.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
					.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
					.replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
				j = eval('(' + text + ')');
				return j;
			}
			throw new SyntaxError('JSON.parse');
		};
	}

	var xhr = createXHR();

	if (!xhr) {
		throw new Error("IE activeXObject version error");
		return;
	}

	options.async = options.async === undefined ? true : options.async;
	options.type = options.type || "GET";
	options.dataType = "json";
	options.cache = options.cache === undefined ? true : options.cache;
	options.error = options.error || function (es, et, xhr) {};
	//url,data
	if (options.type.toLowerCase() == "get") {
		options.url = options.url + getUrlMark(options.url) + paramString(options.data);
		options.data = null;
	} else {
		options.data = paramString(options.data);
	}
	//cache
	if (!options.cache) {
		options.url = options.url + getUrlMark(options.url) + "cache_xmlhttprequest=" + Math.random();
	}

	xhr.onreadystatechange = function () {
		if (xhr.readyState == 4) {
			if (xhr.status >= 200 && xhr.status < 300 || xhr.status === 304) {
				var r = JSON.parse(xhr.responseText);
				options.success(r);
			} else {
				options.error(xhr.status, xhr.responseText, xhr);
			}
		}
	};
	xhr.open(options.type, options.url, options.async);
	xhr.send(options.data);
}

var miguinit_Util = {
    gettokenurl: "",
    addHandler: function(element, type, handler) {
        if (element.addEventListener) {
            element.addEventListener(type, handler, false);
        } else if (element.attachEvent) {
            element.attachEvent("on" + type, handler);
        } else {
            element["on" + type] = handler;
        }
    },
    getElementsClass: function(classnames) {
        var classobj = new Array(),
            classint = 0,
            tags = document.getElementsByTagName("*");
        for (var i in tags) {
            if (tags[i].nodeType == 1) {
                if (tags[i].className == classnames) {
                    classobj[classint] = tags[i];
                    classint++;
                }
            }
        }
        return classobj; //返回组成的数组
    },
    fireMouseEvent: function(type, ele) {
        var evt;
        if (document.createEvent) {
            evt = document.createEvent("MouseEvents");
            evt.initMouseEvent(type, true, true, document.defaultView, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
            ele.dispatchEvent(evt);
        } else {
            evt = document.createEventObject();
            evt.screenX = 100;
            evt.screenY = 0;
            evt.clientX = 0;
            evt.clientY = 0;
            evt.ctrlKey = false;
            evt.altKey = false;
            evt.shiftKey = false;
            evt.button = 0;
            ele.fireEvent("on" + type, evt);
        }
    },
    main: function(a) {
        var isPcEle = document.getElementById("J_IsPc");
        if (!!isPcEle && isPcEle.value != 1) {
            isPcEle = null;
            return;
        }
        isPcEle = null;
        var el = miguinit_Util.getElementsClass(a);
        for (var i = 0, len = el.length; i < len; i++) {
            (function(index) {
                miguinit_Util.addHandler(el[index], "mouseover", function(event) {
                    var me = el[index],
                        getTokenURL;
                    if (/*me.getAttribute("setted_mover") == "setted"||*/!!window.migutip_Util) {
                        return;
                    }
                    /*me.setAttribute("setted_mover", "setted");*/
                    if (typeof(idmp_gettokenurl) != "undefined" && idmp_gettokenurl) {
                        getTokenURL = idmp_gettokenurl;
                    } else {
                        getTokenURL = miguinit_Util.gettokenurl;
                    }
                    if (!getTokenURL) {
                        return;
                    }
                    ajax_migu_2300304({
                        url: getTokenURL,
                        data: {
                            "_t": new Date().getTime()
                        },
                        type: "GET",
                        success: function(data) {
                            if(!data||!data.token){
                                return;
                            }
                            var script = document.createElement("script");
                            var body = document.body || document.getElementsByTagName("body")[0];
                            script.async = "async";
                            script.onreadystatechange = script.onload = function() {
                                if (!script.readyState || /loaded|complete/.test(script.readyState)) {
                                    miguinit_Util.fireMouseEvent("mouseover", me);
                                    script.onreadystatechange = script.onload = script.onerror = null;
                                    body.removeChild(script);
                                    body = script = me = null;
                                }
                            };
                            script.onerror = function() {
                                script.onreadystatechange = script.onload = script.onerror = null;
                                body.removeChild(script);
                                /*me.setAttribute("setted_mover", "unset");*/
                                body = script = me = null;
                            };
                            script.src = "https://passport.migu.cn/user/nav?sourceid=&token=" + data.token;
                            body.appendChild(script);
                        },
                        error: function(es, et, xhr) {

                        }
                    });
                });
            })(i);
        }
    }
};
miguinit_Util.main("migubtn");