function $OldInit() {
	function extend(e, t) {
		for (var o in t) t.hasOwnProperty(o) && (e[o] = t[o]);
		return e
	}

	function newElement(e, t, o) {
		var n = document.createElement(e);
		return o && (n.id = o), t && (n.className = t), n
	}

	function getEnd(e) {
		var t;
		switch (e) {
			case "inform":
				t = 1;
				break;
			case "unknown":
				t = 3;
				break;
			default:
				t = 2
		}
		return t
	}

	function objToString(e) {
		var t = "";
		for (var o in e) e.hasOwnProperty(o) && (t += o + "::" + e[o] + "\n");
		return t
	}
	var lng, navi = window.navigator,
		$wOld = {
			version: 1,
			brws: {},
			params: {
				a: 4.4,
				i: 11,
				f: 25,
				o: 23,
				s: 7,
				ios: 7,
				c: 30,
				w: 7,
				session: false
			}
		};
	$wOld.txt = {
		ru: {
			title: "",
			close: "Закрыть",
			small: " или младше",
			"end-1": "Сайт может работать неправильно. Мы рекомендуем использовать <b>%b</b>.</p>",
			"end-2": "Сайт может работать неправильно. Мы рекомендуем использовать <b>%b</b>.</p>",
			"end-3": "Сайт может работать неправильно.</p>",
			inform: "<p>Вы используете старый браузер - <b>%w</b>!",
			device: "<p>Вы используете - <b>%w</b>!",
			"old-os": "<p>Вы используете устаревшую операционную систему - <b>%w</b>!",
			"ffx-esr": '<p>Вы используете <b> <a href="https://www.mozilla.org/en-US/firefox/organizations/" title="Firefox - ESR" target="_blank">Firefox - ESR</a></b>!',
			unknown: "<p>Вы используете неизвестный нам браузер!"
		},
		ua: {
			title: "",
			close: "Закрити",
			small: " або молодше",
			"end-1": "Сайт може працювати неправильно. Ми рекомендуємо використовувати <b>%b</b>.</p>",
			"end-2": "Сайт може працювати неправильно. Ми рекомендуємо використовувати <b>%b</b>.</p>",
			"end-3": "Сайт може працювати неправильно.</p>",
			inform: "<p>Ви використовуєте старий браузер - <b>%w</b>!",
			device: "<p>Ви використовуєте - <b>%w</b>!",
			"old-os": "<p>Ви використовуєте застарілу операційну систему - <b>%w</b>!",
			"ffx-esr": '<p>Ви використовуєте <b> <a href="https://www.mozilla.org/en-US/firefox/organizations/" title="Firefox - ESR" target="_blank">Firefox - ESR</a></b>!',
			unknown: "<p>Ви використовуєте невідомий нам браузер!"
		},
		pl: {
			title: "",
			close: "Zamknąć",
			small: " lub młodszy",
			"end-1": "Strona może nie działać prawidłowo. Zalecamy używanie <b>%b</b>.</p>",
			"end-2": "Strona może nie działać prawidłowo. Zalecamy używanie <b>%b</b>.</p>",
			"end-3": "Strona może nie działać prawidłowo.</p>",
			inform: "<p>Używasz starej przeglądarki - <b>%w</b>!",
			device: "<p>Używasz - <b>%w</b>!",
			"old-os": "<p>Używasz przestarzałej system operacyjny - <b>%w</b>!",
			"ffx-esr": '<p>Używasz <b> <a href="https://www.mozilla.org/en-US/firefox/organizations/" title="Firefox - ESR" target="_blank">Firefox - ESR</a></b>!',
			unknown: "<p>Korzystania z nieznanych nam robi!"
		},
		en: {
			title: "",
			close: "Close",
			small: " or younger",
			"end-1": "The site may not work properly. We recommend you to use <b>%b</b>.</p>",
			"end-2": "The site may not work properly. We recommend you to use <b>%b</b>.</p>",
			"end-3": "The site may not work properly.</p>",
			inform: "<p>You are using an old browser - <b>%w</b>!",
			device: "<p>You are using - <b>%w</b>!",
			"old-os": "<p>You are using an outdated operating system - <b>%w</b>!",
			"ffx-esr": '<p>You are using <b> <a href="https://www.mozilla.org/en-US/firefox/organizations/" title="Firefox - ESR" target="_blank">Firefox - ESR</a></b>!',
			unknown: "<p>You are using an unknown browser!"
		}
	}, $wOld.styler = ".wzmMsg_Wrapp {position:fixed;z-index:9998;top:0;left:0;width:100%;height:auto;overflow:visible;padding:0 0 3px;margin:0;background-color:#fcea9c;border-bottom:1px solid #ababab;font-size:12px;line-height:14px;font-weight:normal;font-style:normal;font-family:sans-serif;color:#000;-webkit-backface-visibility:hidden;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;}.wzmMsg_Text {min-height:24px;padding:0 30px 0 10px;border-left:1px solid #ababab;}.wzmMsg_Text > p {white-space:normal;margin:5px 0;}.wzmMsg_Text a {color:#f00;text-decoration:underline;}.wzmMsg_Text a:hover {color:#900;}.wzmMsg_Link {position:absolute;display:block;top:7px;left:10px;width:50px;height:18px;text-decoration:none !important;outline:none;}.wzmMsg_Link img {border:none;}.wzmMsg_Close {position:absolute;display:block;top:5px;right:5px;width:26px;height:26px;line-height:26px;font-size:22px;text-align:center;cursor:hand;cursor:pointer;}.wzmMsg_Close > span {display:block;position:relative;width:26px;height:26px;line-height:26px;}.wzmMsg_Close:hover {background-color:#ead371;}.wzmMsg_Close:active {background-color:#beaf6e;}", $wOld.brws_return = function(e, t, o, n) {
		this.brws = {
			tpl: e,
			vers: t,
			name: o,
			info: n
		}
	}, $wOld.alert = function() {
		var e = "\n",
			t = "";
		t += "---------- ua ----------" + e + e + navi.userAgent + e + e, t += "---------- min data ----------" + e;
		for (k in this.params) t += k + ": " + this.params[k] + e;
		t += "---------- your data ----------" + e;
		for (k in this.brws) t += k + ": " + this.brws[k] + e;
		var o = /\n/g;
		for (m = o.exec(t); m; m = o.exec(t)) t = t.replace(m[0], "<br />");
		var n = newElement("pre");
		n.innerHTML = t, n.style.whiteSpace = "normal", document.body.appendChild(n)
	}, $wOld.brws_get = function() {
		var e, t, o = !1,
			n = navi.userAgent,
			i = {
				a: "Android",
				i: "Internet Explorer",
				f: "Firefox",
				o: "Opera",
				s: "Apple Safari",
				ios: "iPhone / iPod / iPad OS",
				c: "Chrome",
				w: "Windows",
				x: "Other"
			};
		/Android/i.test(n) ? e = "a" : /iphone|ipod|ipad/i.test(n) ? e = "ios" : /Trident.*rv:(\d+\.\d+)/i.test(n) ? e = "i" : /Trident.(\d+\.\d+)/i.test(n) ? e = "io" : /MSIE.(\d+\.\d+)/i.test(n) ? e = "i" : /Edge.(\d+\.\d+)/i.test(n) ? e = "i" : /OPR.(\d+\.\d+)/i.test(n) ? e = "o" : /Chrome.(\d+\.\d+)/i.test(n) ? e = "c" : /Firefox.(\d+\.\d+)/i.test(n) ? e = "f" : /Version.(\d+.\d+).{0,10}Safari/i.test(n) ? e = "s" : /Safari.(\d+)/i.test(n) ? e = "so" : /Opera.*Version.(\d+\.\d+)/i.test(n) ? e = "o" : /Opera.(\d+\.?\d+)/i.test(n) ? e = "o" : /bot|googlebot|facebook|slurp|wii|silk|blackberry|maxthon|maxton|mediapartners|dolfin|dolphin|adsbot|silk|phone|bingbot|google web preview|like firefox|chromeframe|seamonkey|opera mini|min|meego|netfront|moblin|maemo|arora|camino|flot|k-meleon|fennec|kazehakase|galeon|mobile|epiphany|konqueror|rekonq|symbian|webos|coolnovo|blackberry|bb10|RIM|PlayBook|PaleMoon|QupZilla|YaBrowser/i.test(n) ? e = "x" : this.brws_return("x", 0, i[e], "unknown");
		var t = parseFloat(RegExp.$1);
		if (document.all && !document.addEventListener ? (e = "i", t = 8) : document.all && !window.atob && document.addEventListener && (e = "i", t = 9), /windows.nt.5.0|windows.nt.4.0|windows.98|os x 10.4|os x 10.5|os x 10.3|os x 10.2/.test(n) && (o = "old-os"), n.toLowerCase().indexOf("windows nt 4.0") > 0 && (o = "old-os", e = "w", t = "95-98"), n.toLowerCase().indexOf("windows nt 5.0") > 0 && (o = "old-os", e = "w", t = "2000"), n.toLowerCase().indexOf("windows nt 5.1") > 0 && $wOld.params.w > 5 && (o = "old-os", e = "w", t = "XP"), n.toLowerCase().indexOf("windows nt 6.0") > 0 && $wOld.params.w > 6 && (o = "old-os", e = "w", t = "Vista"), "f" != e || 24 != Math.round(t) && 31 != Math.round(t) || (o = "ffx-esr"), "a" == e) {
			var t = parseFloat(n.match(/Android\s+([\d\.]+)/)[1]);
			t < this.params[e] && (o = "device")
		}
		if ("ios" == e) {
			var t = parseFloat(n.match(/OS\s+([\d\.]+)/)[1]);
			t < this.params[e] && (o = "device")
		}
		"x" == e && (t = t || 0, o = "unknown", this.brws_return(e, t, i[e], o)), "so" == e && (t = 100 > t && 1 || 130 > t && 1.2 || 320 > t && 1.3 || 520 > t && 2 || 524 > t && 3 || 526 > t && 3.2 || 4, e = "s"), "i" == e && 7 == t && window.XDomainRequest && (t = 8), "io" == e && (e = "i", t = t > 6 ? 11 : t > 5 ? 10 : t > 4 ? 9 : t > 3.1 ? 8 : t > 3 ? 7 : 9), this.brws_return(e, t, i[e], o)
	}, $wOld.informer = function() {
		var e = this.brws.info,
			t = this.brws.name,
			o = this.brws.vers,
			n = this.brws.tpl,
			i = getEnd(e);
		str = this.txt[lng][e] + " " + this.txt[lng]["end-" + i], "i" === n && 8 === o && (o = "8" + this.txt[lng].small), str = str.replace(/%w/, t + " " + o), str = str.replace(/%u/, this.url), str = str.replace(/%d/, t + " " + this.params[n]), str = str.replace(/%b/, t + " " + this.params[n]);
		var r = newElement("div", "wzmMsg_Wrapp", "wzmMsg_OldInform"),
			s = newElement("div", "wzmMsg_Text");
		s.innerHTML = str;
		var a = newElement("a", "wzmMsg_Link");
		a.href = this.wezom, a.target = "_blank", a.title = this.txt[lng].title;
		var l = newElement("img");
		l.src = this.image, l.width = 50, l.height = 18, l.alt = this.txt[lng].title, a.appendChild(l);
		var d = newElement("div", "wzmMsg_Close");
		d.title = this.txt[lng].close, d.innerHTML = "<span onclick='this.parentNode.parentNode.style.display=\"none\"';>&times;</span>";
		var w = newElement("style");
		r.appendChild(s), r.appendChild(a), r.appendChild(d);
		var p = document.body;
		p.appendChild(r), document.getElementsByTagName("head")[0].appendChild(w);
		try {
			w.innerText = this.styler, w.innerHTML = this.styler
		} catch (m) {
			try {
				w.styleSheet.cssText = this.styler
			} catch (m) {
				return
			}
		}
	}, $wOld.check = function(e, t, o, n) {
		lng = o || document.documentElement.getAttribute("lang") || navi.language || navi.browserLanguage || navi.userLanguage || "ru", lng = lng.toLowerCase().substr(0, 2);
		var i = !0;
		for (var r in this.txt) r === lng && (i = !1);
		if (i && (lng = "en"), "object" == typeof e && (this.params = extend(this.params, e)), "string" == typeof n && ($wOld.styler = n), this.params.session) {
			if ("once" == sessionStorage.wOld) return;
			sessionStorage.wOld = "once"
		}
		this.brws_get(), this.brws.info === !1 && this.brws.vers < this.params[this.brws.tpl] && (this.brws.info = "inform"), /Google Page Speed/i.test(navi.userAgent) && (this.brws.info = !1), this.brws.info !== !1 && this.informer()
	};
	var o, b, l = !1,
		s;
	o = "undefined" != typeof $wzmOld_PARAMS ? $wzmOld_PARAMS : !1, b = "undefined" != typeof $wzmOld_BLOCK ? $wzmOld_BLOCK : !1, l = "undefined" != typeof $wzmOld_LANG ? $wzmOld_LANG : !1, "undefined" != typeof $wzmOld_URL_IMG ? $wOld.image = $wzmOld_URL_IMG : $wOld.image = "", "undefined" != typeof $wzmOld_URL_WEZOM ? $wOld.wezom = $wzmOld_URL_WEZOM : $wOld.wezom = "", "undefined" != typeof $wzmOld_URL_INFO ? $wOld.url = $wzmOld_URL_INFO : $wOld.url = "", s = "undefined" != typeof $wzmOld_STYLE ? $wzmOld_STYLE : !1, $wOld.check(o, b, l, s);
}

try {
	document.addEventListener("DOMContentLoaded", $OldInit, false);
} catch (e) {
	window.attachEvent("onload", $OldInit);
}
