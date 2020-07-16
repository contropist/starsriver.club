function uc_modify_sid(sid) {
    $('header_uc').href = uclink;
}

function toggleMenu(key, url) {
    menukey = key;
    switchheader(key);
    if (url) {
        parent.main.location = admincpfilename + '?action=' + url;
        var hrefs = $('menu_' + key).getElementsByTagName('a');
        for (var j = 0; j < hrefs.length; j++) {
            hrefs[j].className = j === (key === 'plugin' ? plugindefaultkey : 0) ? 'active' : '';
        }
    }
    if (key === 'uc') {
        parent.main.location = $('header_uc').href + '&a=main&iframe=1';
    }
    setMenuScroll();
}

function switchheader(key) {
    if (!key || !$('header_' + key)) {
        return;
    }
    for (var k in top.headers) {
        if ($('menu_' + headers[k])) {
            $('menu_' + headers[k]).style.display = headers[k] === key ? '' : 'none';
        }
    }
    var lis = $('topmenu').getElementsByTagName('li');
    for (var i = 0; i < lis.length; i++) {
        if (lis[i].className === 'active') lis[i].className = '';
    }
    $('header_' + key).parentNode.className = 'active';
}

/*
var headerST = null;
function previewheader(key) {
    if (key) {
        headerST = setTimeout(function () {
            for (var k in top.headers) {
                if ($('menu_' + headers[k])) {
                    $('menu_' + headers[k]).style.display = headers[k] == key ? '' : 'none';
                }
            }
            var hrefs = $('menu_' + key).getElementsByTagName('a');
            for (var j = 0; j < hrefs.length; j++) {
                hrefs[j].className = '';
            }
        }, 1000);
    } else {
        clearTimeout(headerST);
    }
}
*/

function setMenuScroll() {
    var obj = $('menu_' + menukey);
    if (!obj) {
        return;
    }
    var scrollh = document.body.offsetHeight - 160;
    obj.style.overflow = 'visible';
    obj.style.height = '';
    $('scrolllink').style.display = 'none';
}

function resizeHeadermenu() {
    var lis = $('topmenu').getElementsByTagName('li'),
        maxsize = $('frameuinfo').offsetLeft - 430,
        widths = 0,
        moi = -1,
        mof = '';
    if ($('menu_mof')) {
        $('topmenu').removeChild($('menu_mof'));
    }
    if ($('menu_mof_menu')) {
        $('append_parent').removeChild($('menu_mof_menu'));
    }
    for (var i = 0; i < lis.length; i++) {
        widths += lis[i].offsetWidth;
        if (widths > maxsize) {
            lis[i].style.visibility = 'hidden';
            var sobj = lis[i].childNodes[0];
            if (sobj) {
                mof += '<li><a href="' + sobj.getAttribute('href') + '" onclick="$(\'' + sobj.id + '\').onclick()">' + sobj.innerHTML + '</a></li>';
            }
        } else {
            lis[i].style.visibility = 'visible';
        }
    }
    if (mof) {
        for (var i = 0; i < lis.length; i++) {
            if (lis[i].style.visibility === 'hidden') {
                moi = i;
                break;
            }
        }
        mofli = document.createElement('li');
        mofli.className = 'grid';
        mofli.innerHTML = '<a class="ft-grid"></a>';
        mofli.onmouseover = function () {
            showMenu({'ctrlid': 'menu_mof', 'pos': '43'});
        };
        mofli.id = 'menu_mof';
        mofmli = document.createElement('ul');
        mofmli.className = 'nav-menu';
        mofmli.innerHTML = mof;
        mofmli.id = 'menu_mof_menu';
        mofmli.style.display = 'none';
        $('append_parent').appendChild(mofmli);
        $('topmenu').insertBefore(mofli, lis[moi]);
    }
}

function menuScroll(op, e) {
    var obj = $('menu_' + menukey);
    var scrollh = document.body.offsetHeight - 160;
    if (op === 1) {
        obj.scrollTop = obj.scrollTop - scrollh;
    } else if (op === 2) {
        obj.scrollTop = obj.scrollTop + scrollh;
    } else if (op === 3) {
        if (!e) e = window.event;
        if (e.wheelDelta <= 0 || e.detail > 0) {
            obj.scrollTop = obj.scrollTop + 20;
        } else {
            obj.scrollTop = obj.scrollTop - 20;
        }
    }
}

function menuNewwin(obj) {
    var href = obj.parentNode.href;
    if (obj.parentNode.href.indexOf(admincpfilename + '?') !== -1) {
        href += '&frames=yes';
    }
    window.open(href);
    doane();
}

function initCpMenus(menuContainerid) {
    var key = '', lastactive1 = null, lastactive2 = null, hrefs = $(menuContainerid).getElementsByTagName('a');
    for (var i = 0; i < hrefs.length; i++) {
        if (menuContainerid === 'leftmenu' && 'lextra'.indexOf(hrefs[i].href.substr(hrefs[i].href.indexOf(admincpfilename + '?') + admincpfilename.length + 1)) !== -1) {
            if (lastactive1) {
                lastactive1.className = '';
            }
            if (hrefs[i].parentNode.parentNode.tagName === 'OL') {
                hrefs[i].parentNode.parentNode.style.display = '';
                hrefs[i].parentNode.parentNode.parentNode.className = 'lsub desc';
                key = hrefs[i].parentNode.parentNode.parentNode.parentNode.parentNode.id.substr(5);
            } else {
                key = hrefs[i].parentNode.parentNode.id.substr(5);
            }
            hrefs[i].className = 'active';
            lastactive1 = hrefs[i];
        }
        if (!hrefs[i].getAttribute('ajaxtarget')) hrefs[i].onclick = function () {
            if (menuContainerid !== 'custommenu') {
                var lis = $(menuContainerid).getElementsByTagName('li');
                for (var k = 0; k < lis.length; k++) {
                    if (lis[k].firstChild && lis[k].firstChild.className !== 'menulink') {
                        if (lis[k].firstChild.tagName !== 'DIV') {
                            lis[k].firstChild.className = '';
                        } else {
                            var subid = lis[k].firstChild.getAttribute('sid');
                            if (subid) {
                                var sublis = $(subid).getElementsByTagName('li');
                                for (var ki = 0; ki < sublis.length; ki++) {
                                    if (sublis[ki].firstChild && sublis[ki].firstChild.className !== 'menulink') {
                                        sublis[ki].firstChild.className = '';
                                    }
                                }
                            }
                        }
                    }
                }
                if (this.className === '') this.className = menuContainerid === 'leftmenu' ? 'active' : '';
            }
            if (menuContainerid !== 'leftmenu') {
                var hk, currentkey;
                var leftmenus = $('leftmenu').getElementsByTagName('a');
                for (var j = 0; j < leftmenus.length; j++) {
                    if (leftmenus[j].parentNode.parentNode.tagName === 'OL') {
                        hk = leftmenus[j].parentNode.parentNode.parentNode.parentNode.parentNode.id.substr(5);
                    } else {
                        hk = leftmenus[j].parentNode.parentNode.id.substr(5);
                    }
                    if (this.href.indexOf(leftmenus[j].href) !== -1) {
                        if (lastactive2) {
                            lastactive2.className = '';
                        }
                        leftmenus[j].className = 'active';
                        if (leftmenus[j].parentNode.parentNode.tagName === 'OL') {
                            leftmenus[j].parentNode.parentNode.style.display = '';
                            leftmenus[j].parentNode.parentNode.parentNode.className = 'lsub desc';
                        }
                        lastactive2 = leftmenus[j];
                        if (hk !== 'index') currentkey = hk;
                    } else {
                        leftmenus[j].className = '';
                    }
                }
                if (currentkey) toggleMenu(currentkey);
                hideMenu();
            }
        }
    }
    return key;
}

function lsub(id, obj) {
    display(id);
    obj.className = obj.className !== 'lsub' ? 'lsub' : 'lsub desc';
    if (obj.className !== 'lsub') {
        setcookie('cpmenu_' + id, '');
    } else {
        setcookie('cpmenu_' + id, 1, 31536000);
    }
    setMenuScroll();
}

var header_key = initCpMenus('leftmenu');
toggleMenu(header_key ? header_key : 'index');

function initCpMap() {
    var ul, hrefs, s = '', count = 0;
    for (var k in headers) {
        if (headers[k] !== 'index' && headers[k] !== 'uc' && $('header_' + headers[k])) {
            s += '<tr><td valign="top"><span class="mod_title">' + $('header_' + headers[k]).innerHTML + '</span></td><td valign="top">';
            ul = $('menu_' + headers[k]);
            if (!ul) {
                continue;
            }
            hrefs = ul.getElementsByTagName('a');
            for (var i = 0; i < hrefs.length; i++) {
                s += '<a href="' + hrefs[i].href + '" target="' + hrefs[i].target + '" k="' + headers[k] + '">' + hrefs[i].innerHTML + '</a>';
            }
            s += '</td></tr>';
            count++;
        }
    }
    var width = 720;
    s = '<div class="cnote" style="width:' + width + 'px"><span class="title">' + cplangs.admincp_maptitle + '</span><a class="awe-close right" onclick="hideMenu();return false;">×</a></div>' +
        '<div class="cmlist" style="width:' + width + 'px;height: 410px"><table id="mapmenu" cellspacing="0" cellpadding="0">' + s +
        '</table></div>';
    $('cmain').innerHTML = s;
    $('cmain').style.width = (width > 1000 ? 1000 : width) + 'px';
}

initCpMap();
initCpMenus('mapmenu');
var cmcache = false;

function showMap() {
    showMenu({'ctrlid': 'cpmap', 'evt': 'click', 'duration': 3, 'pos': '11'});
}

function resetEscAndF5(e) {
    e = e ? e : window.event;
    actualCode = e.keyCode ? e.keyCode : e.charCode;
    if (actualCode === 27) {
        if ($('cpmap_menu').style.display === 'none') {
            showMap();
        } else {
            hideMenu();
        }
    }
    if (actualCode === 116 && parent.main) {
        parent.main.location.reload();
        if (document.all) {
            e.keyCode = 0;
            e.returnValue = false;
        } else {
            e.cancelBubble = true;
            e.preventDefault();
        }
    }
}

function uc_left_menu(uc_menu_data) {
    var leftmenu = $('menu_uc');
    leftmenu.innerHTML = '';
    var html_str = '';
    for (var i = 0; i < uc_menu_data.length; i += 2) {
        html_str += '<li><a href="' + uc_menu_data[(i + 1)] + '" hidefocus="true" onclick="uc_left_switch(this)" target="main"><em onclick="menuNewwin(this)" title="' + cplangs.nav_newwin + '"></em>' + uc_menu_data[i] + '</a></li>';
    }
    leftmenu.innerHTML = html_str;
}

var uc_left_last = null;

function uc_left_switch(obj) {
    if (uc_left_last) {
        uc_left_last.className = '';
    }
    obj.className = 'active';
    uc_left_last = obj;
}


_attachEvent(document.documentElement, 'keydown', resetEscAndF5);
_attachEvent(window, 'resize', setMenuScroll, document);
_attachEvent(window, 'resize', resizeHeadermenu, document);
if (BROWSER.ie) {
    $('leftmenu').onmousewheel = function (e) {
        menuScroll(3, e)
    };
} else {
    $('leftmenu').addEventListener("DOMMouseScroll", function (e) {
        menuScroll(3, e)
    }, false);
}
resizeHeadermenu();