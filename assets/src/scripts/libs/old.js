var lng;
var navi = window.navigator;
var $cold = {
  version: 1,
  brws: {},
  params: {
    a: 5,
    i: 12,
    e: 15,
    f: 55,
    o: 50,
    s: 10,
    ios: 10,
    c: 60,
    w: 7,
    session: !1,
  },
};

function extend(e, o) {
  for (var t in o) o.hasOwnProperty(t) && (e[t] = o[t]);
  return e;
}

function newElement(e, o, t) {
  var n = document.createElement(e);
  return t && (n.id = t), o && (n.className = o), n;
}

function getEnd(e) {
  var o;
  switch (e) {
    case "inform":
      o = 1;
      break;
    case "unknown":
      o = 3;
      break;
    default:
      o = 2;
  }
  return o;
}
($cold.txt = {
  ru: {
    title: "Студия веб-дизайна Chizz",
    close: "Закрыть",
    small: " или младше",
    "end-1":
      "<br>Сайт может работать неправильно. Мы рекомендуем использовать <b>%b</b>.</p>",
    "end-2":
      "<br>Сайт может работать неправильно. Мы рекомендуем использовать <b>%b</b>.</p>",
    "end-3": "<br>Сайт может работать неправильно.</p>",
    inform: "<p>Вы используете старый браузер - <b>%w</b>!",
    device: "<p>Вы используете - <b>%w</b>!",
    "old-os": "<p>Вы используете устаревшую операционную систему - <b>%w</b>!",
    "use-edge":
      '<p>Сайт может работать неправильно. Мы рекомендуем использовать <a href="https://www.microsoft.com/ru-ru/windows/microsoft-edge" title="Microsoft Edge" rel="noopener" target="_blank">Microsoft Edge</a></p>',
    "ffx-esr":
      '<p>Вы используете <b> <a href="https://www.mozilla.org/en-US/firefox/organizations/" title="Firefox - ESR" rel="noopener" target="_blank">Firefox - ESR</a></b>!',
    unknown: "<p>Вы используете неизвестный нам браузер!",
  },
  ua: {
    title: "Студія веб-дизайну Chizz",
    close: "Закрити",
    small: " або молодше",
    "end-1":
      "<br>Сайт може працювати неправильно. Ми рекомендуємо використовувати <b>%b</b>.</p>",
    "end-2":
      "<br>Сайт може працювати неправильно. Ми рекомендуємо використовувати <b>%b</b>.</p>",
    "end-3": "<br>Сайт може працювати неправильно.</p>",
    inform: "<p>Ви використовуєте старий браузер - <b>%w</b>!",
    device: "<p>Ви використовуєте - <b>%w</b>!",
    "old-os": "<p>Ви використовуєте застарілу операційну систему - <b>%w</b>!",
    "use-edge":
      '<p>Сайт може працювати неправильно. Ми рекомендуємо використовувати <a href="https://www.microsoft.com/uk-ua/windows/microsoft-edge" title="Microsoft Edge" rel="noopener" target="_blank">Microsoft EdgeHTML ' +
      $cold.params.e +
      "</a></p>",
    "ffx-esr":
      '<p>Ви використовуєте <b> <a href="https://www.mozilla.org/en-US/firefox/organizations/" title="Firefox - ESR" rel="noopener" target="_blank">Firefox - ESR</a></b>!',
    unknown: "<p>Ви використовуєте невідомий нам браузер!",
  },
  pl: {
    title: "Studio Chizz",
    close: "Zamknąć",
    small: " lub młodszy",
    "end-1":
      "<br>Strona może nie działać prawidłowo. Zalecamy używanie <b>%b</b>.</p>",
    "end-2":
      "<br>Strona może nie działać prawidłowo. Zalecamy używanie <b>%b</b>.</p>",
    "end-3": "<br>Strona może nie działać prawidłowo.</p>",
    inform: "<p>Używasz starej przeglądarki - <b>%w</b>!",
    device: "<p>Używasz - <b>%w</b>!",
    "old-os": "<p>Używasz przestarzałej system operacyjny - <b>%w</b>!",
    "use-edge":
      '<p>Strona może nie działać prawidłowo. Zalecamy używanie <a href="https://www.microsoft.com/en-us/windows/microsoft-edge" title="Microsoft Edge" rel="noopener" target="_blank">Microsoft EdgeHTML ' +
      $cold.params.e +
      "</a></p>",
    "ffx-esr":
      '<p>Używasz <b> <a href="https://www.mozilla.org/en-US/firefox/organizations/" title="Firefox - ESR" rel="noopener" target="_blank">Firefox - ESR</a></b>!',
    unknown: "<p>Korzystania z nieznanych nam robi!",
  },
  en: {
    title: "Studio Chizz",
    close: "Close",
    small: " or younger",
    "end-1":
      "<br>The site may not work properly. We recommend you to use <b>%b</b>.</p>",
    "end-2":
      "<br>The site may not work properly. We recommend you to use <b>%b</b>.</p>",
    "end-3": "<br>The site may not work properly.</p>",
    inform: "<p>You are using an old browser - <b>%w</b>!",
    device: "<p>You are using - <b>%w</b>!",
    "old-os": "<p>You are using an outdated operating system - <b>%w</b>!",
    "use-edge":
      '<p>The site may not work properly. We recommend you to use <a href="https://www.microsoft.com/en-us/windows/microsoft-edge" title="Microsoft Edge" rel="noopener" target="_blank">Microsoft EdgeHTML ' +
      $cold.params.e +
      "</a></p>",
    "ffx-esr":
      '<p>You are using <b> <a href="https://www.mozilla.org/en-US/firefox/organizations/" title="Firefox - ESR" rel="noopener" target="_blank">Firefox - ESR</a></b>!',
    unknown: "<p>You are using an unknown browser!",
  },
}),
  ($cold.styler =
    ".chzMsg_Wrapp {position:fixed;z-index:9998;bottom:0;left:0;width:100%;height:auto;overflow:visible;padding:0 0 3px;margin:0;background-color:#fcea9c;border-top:1px solid #ababab;font-size:12px;line-height:14px;font-weight:normal;font-style:normal;font-family:sans-serif;color:#000;-webkit-backface-visibility:hidden;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;}.chzMsg_Text {min-height:24px;padding:0 30px 0 10px;margin-left:50px;border-left:1px solid #ababab;}.chzMsg_Text > p {white-space:normal;margin:5px 0;}.chzMsg_Text a {color:#f00;text-decoration:underline;}.chzMsg_Text a:hover {color:#900;}.chzMsg_Link {position:absolute;display:block;top:7px;left:10px;width:32px;height:32px;text-decoration:none !important;outline:none;}.chzMsg_Link img {border:none;}.chzMsg_Close {position:absolute;display:block;top:5px;right:5px;width:26px;height:26px;line-height:26px;font-size:22px;text-align:center;cursor:hand;cursor:pointer;}.chzMsg_Close > span {display:block;position:relative;width:26px;height:26px;line-height:26px;}.chzMsg_Close:hover {background-color:#ead371;}.chzMsg_Close:active {background-color:#beaf6e;}"),
  ($cold.brws_return = function (e, o, t, n) {
    this.brws = {
      tpl: e,
      vers: o,
      name: t,
      info: n,
    };
  }),
  ($cold.alert = function () {
    var e = "\n";
    var o = "";
    (o += "---------- ua ----------\n\n" + navi.userAgent + e + e),
      (o += "---------- min data ----------\n");
    for (var t in this.params) o += t + ": " + this.params[t] + e;
    o += "---------- your data ----------\n";
    for (var n in this.brws) o += n + ": " + this.brws[n] + e;
    for (var i = /\n/g, r = i.exec(o); r; r = i.exec(o))
      o = o.replace(r[0], "<br />");
    var s = newElement("pre");
    (s.innerHTML = o),
      (s.style.whiteSpace = "normal"),
      document.body.appendChild(s);
  }),
  ($cold.brws_get = function () {
    var e;
    var o = !1;
    var t = navi.userAgent;
    var n = {
      a: "Android",
      i: "Internet Explorer",
      e: "Microsoft EdgeHTML",
      f: "Firefox",
      o: "Opera",
      s: "Apple Safari",
      ios: "iPhone / iPod / iPad OS",
      c: "Chrome",
      w: "Windows",
      x: "Other",
    };
    if (/Android/i.test(t)) e = "a";
    else if (/iphone|ipod|ipad|Macintosh/i.test(t)) e = "ios";
    else if (/Trident.*rv:(\d+\.\d+)/i.test(t)) e = "i";
    else if (/Trident.(\d+\.\d+)/i.test(t)) e = "io";
    else if (/MSIE.(\d+\.\d+)/i.test(t)) e = "i";
    else if (/Edge.(\d+\.\d+)/i.test(t)) e = "i";
    else if (/OPR.(\d+\.\d+)/i.test(t)) e = "o";
    else if (/Chrome.(\d+\.\d+)/i.test(t)) e = "c";
    else if (/Firefox.(\d+\.\d+)/i.test(t)) e = "f";
    else if (/Version.(\d+.\d+).{0,10}Safari/i.test(t)) e = "s";
    else if (/Safari.(\d+)/i.test(t)) e = "so";
    else if (/Opera.*Version.(\d+\.\d+)/i.test(t)) e = "o";
    else if (/Opera.(\d+\.?\d+)/i.test(t)) e = "o";
    else {
      if (
        !/bot|googlebot|facebook|slurp|wii|silk|blackberry|maxthon|maxton|mediapartners|dolfin|dolphin|adsbot|silk|phone|bingbot|google web preview|like firefox|chromeframe|seamonkey|opera mini|min|meego|netfront|moblin|maemo|arora|camino|flot|k-meleon|fennec|kazehakase|galeon|mobile|epiphany|konqueror|rekonq|symbian|webos|coolnovo|blackberry|bb10|RIM|PlayBook|PaleMoon|QupZilla|YaBrowser/i.test(
          t
        )
      )
        return void this.brws_return("x", 0, n[e], "unknown");
      e = "x";
    }
    var i = parseFloat(RegExp.$1);
    document.all && !document.addEventListener
      ? ((e = "i"), (i = 8))
      : document.all &&
        !window.atob &&
        document.addEventListener &&
        ((e = "i"), (i = 9)),
      /windows.nt.5.0|windows.nt.4.0|windows.98|os x 10.4|os x 10.5|os x 10.3|os x 10.2/.test(
        t
      ) && (o = "old-os"),
      t.toLowerCase().indexOf("windows nt 4.0") > 0 &&
        ((o = "old-os"), (e = "w"), (i = "95-98")),
      t.toLowerCase().indexOf("windows nt 5.0") > 0 &&
        ((o = "old-os"), (e = "w"), (i = "2000")),
      t.toLowerCase().indexOf("windows nt 5.1") > 0 &&
        $cold.params.w > 5 &&
        ((o = "old-os"), (e = "w"), (i = "XP")),
      t.toLowerCase().indexOf("windows nt 6.0") > 0 &&
        $cold.params.w > 6 &&
        ((o = "old-os"), (e = "w"), (i = "Vista")),
      e !== "f" ||
        (Math.round(i) !== 24 && Math.round(i) !== 31) ||
        (o = "ffx-esr"),
      e === "a" &&
        (i = parseFloat(t.match(/Android\s+([\d\.]+)/)[1])) < this.params[e] &&
        (o = "device"),
      e === "ios" &&
        (i = parseFloat(t.match(/OS\s+X?\s*([\d\.]+)/)[1])) < this.params[e] &&
        (o = "device"),
      e === "x" &&
        ((i = i || 0), (o = "unknown"), this.brws_return(e, i, n[e], o)),
      e === "so" &&
        ((i =
          (i < 100 ? 1 : i < 130 && 1.2) ||
          (i < 320 && 1.3) ||
          (i < 520 && 2) ||
          (i < 524 && 3) ||
          (i < 526 && 3.2) ||
          4),
        (e = "s")),
      e === "i" && i === 7 && window.XDomainRequest && (i = 8),
      document.all &&
        window.atob &&
        document.addEventListener &&
        ((e = "i"), (i = 10)),
      e === "io" &&
        ((e = "i"),
        (i =
          i > 6 ? 11 : i > 5 ? 10 : i > 4 ? 9 : i > 3.1 ? 8 : i > 3 ? 7 : 9));
    var r = t.indexOf("Edge/");
    r > 0 &&
      ((e = "e"),
      (i = parseInt(t.substring(r + 5, t.indexOf(".", r)), 10)) <
        this.params[e] && (o = "inform")),
      this.brws_return(e, i, n[e], o);
  }),
  ($cold.informer = function () {
    var e = this.brws.info;
    var o = this.brws.name;
    var t = this.brws.vers;
    var n = this.brws.tpl;
    var i = getEnd(e);
    var r = this.txt[lng]["end-" + i];
    o === "Internet Explorer" && (r = this.txt[lng]["use-edge"]);
    var s = this.txt[lng][e] + " " + r;
    n === "i" && t === 8 && (t = "8" + this.txt[lng].small),
      (s = (s = (s = (s = s.replace(/%w/, o + " " + t)).replace(
        /%u/,
        this.url
      )).replace(/%d/, o + " " + this.params[n])).replace(
        /%b/,
        o + " " + this.params[n]
      ));
    var a = newElement("div", "chzMsg_Wrapp", "chzMsg_OldInform");
    var l = newElement("div", "chzMsg_Text");
    l.innerHTML = s;
    var d = newElement("a", "chzMsg_Link");
    (d.href = this.chizz),
      (d.target = "_blank"),
      (d.title = this.txt[lng].title);
    var w = newElement("img");
    (w.src = this.image),
      (w.width = 50),
      (w.height = 18),
      (w.alt = this.txt[lng].title),
      d.appendChild(w);
    var p = newElement("div", "chzMsg_Close");
    (p.title = this.txt[lng].close),
      (p.innerHTML =
        "<span onclick='this.parentNode.parentNode.style.display=\"none\"';>&times;</span>");
    var m = newElement("style");
    a.appendChild(l),
      a.appendChild(d),
      a.appendChild(p),
      document.body.appendChild(a),
      document.getElementsByTagName("head")[0].appendChild(m);
    try {
      (m.innerText = this.styler), (m.innerHTML = this.styler);
    } catch (e) {
      try {
        m.styleSheet.cssText = this.styler;
      } catch (e) {
        console.log(e);
      }
    }
  }),
  ($cold.check = function (e, o, t, n) {
    lng = (lng =
      t ||
      document.documentElement.getAttribute("lang") ||
      navi.language ||
      navi.browserLanguage ||
      navi.userLanguage ||
      "ru")
      .toLowerCase()
      .substr(0, 2);
    var i = !0;
    for (var r in this.txt) r === lng && (i = !1);
    if (
      (i && (lng = "en"),
      typeof e === "object" && (this.params = extend(this.params, e)),
      typeof n === "string" && ($cold.styler = n),
      this.params.session)
    ) {
      if (sessionStorage.cold === "once") return;
      sessionStorage.cold = "once";
    }
    this.brws_get(),
      !1 === this.brws.info &&
        this.brws.vers < this.params[this.brws.tpl] &&
        (this.brws.info = "inform"),
      /Google Page Speed/i.test(navi.userAgent) && (this.brws.info = !1),
      !1 !== this.brws.info && this.informer();
  });
var o;
var b;
var s;
var l = !1;
(o = typeof $chzOld_PARAMS !== "undefined" && $chzOld_PARAMS),
  (b = typeof $chzOld_BLOCK !== "undefined" && $chzOld_BLOCK),
  (l = typeof $chzOld_LANG !== "undefined" && $chzOld_LANG),
  typeof $chzOld_URL_IMG !== "undefined"
    ? ($cold.image = $chzOld_URL_IMG)
    : ($cold.image =
        "//chizz.team/wp-content/uploads/2020/01/cropped-favicon-32x32.png"),
  typeof $chzOld_URL_chizz !== "undefined"
    ? ($cold.chizz = $chzOld_URL_chizz)
    : ($cold.chizz = "https://chizz.team/"),
  typeof $chzOld_URL_INFO !== "undefined"
    ? ($cold.url = $chzOld_URL_INFO)
    : ($cold.url = "https://chizz.team/"),
  (s = typeof $chzOld_STYLE !== "undefined" && $chzOld_STYLE),
  $cold.check(o, b, l, s);
