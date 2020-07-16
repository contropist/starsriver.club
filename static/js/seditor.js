/*
	[StarsRiver!] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: seditor.js 28601 2012-03-06 02:49:55Z monkey $
*/

function seditor_showimgmenu(seditorkey) {
	var imgurl = $(seditorkey + '_image_param_1').value;
	var width = parseInt($(seditorkey + '_image_param_2').value);
	var height = parseInt($(seditorkey + '_image_param_3').value);
	var extparams = '';
	if(width || height) {
		extparams = '=' + width + ',' + height
	}
	seditor_insertunit(seditorkey, '[img' + extparams + ']' + imgurl, '[/img]', null, 1);
	$(seditorkey + '_image_param_1').value = '';
	hideMenu();
}

function seditor_menu(seditorkey, tag) {
	var sel = false;
	if(!isUndefined($(seditorkey + 'message').selectionStart)) {
		sel = $(seditorkey + 'message').selectionEnd - $(seditorkey + 'message').selectionStart;
	} else if(document.selection && document.selection.createRange) {
		$(seditorkey + 'message').focus();
		var sel = document.selection.createRange();
		$(seditorkey + 'message').sel = sel;
		sel = sel.text ? true : false;
	}
	if(sel) {
		seditor_insertunit(seditorkey, '[' + tag + ']', '[/' + tag + ']');
		return;
	}
	var ctrlid = seditorkey + tag;
	var menuid = ctrlid + '_menu';
	var menuname;
	if(!$(menuid)) {
        var menu = document.createElement('div');
        menu.id = menuid;
        menu.style.display = 'none';
        menu.className = 'fwin-lite';
		switch(tag) {
			case 'at':
				curatli = 0;
				menuname = '@朋友';
                menu.style.width = '270px';
				atsubmitid = ctrlid + '_submit';
				setTimeout(function() {atFilter('', 'at_list','atListSet');$('atkeyword').focus();}, 100);
				str = '<div class="input-block"><span>用户：</span><input type="text" id="atkeyword" onkeydown="atFilter(this.value, \'at_list\',\'atListSet\',event);" /></div><div class="p_pop" id="at_list" style="width:250px;"><ul><li></li></ul></div>';
				submitstr = 'seditor_insertunit(\'' + seditorkey + '\', \'@\' + $(\'atkeyword\').value.replace(/<\\/?b>/g, \'\')+\' \'); hideMenu();';
				break;
			case 'url':
                menuname = '插入链接';
                menu.style.width = '240px';
				str = '<div class="input-block"><span>链接：</span><input type="text" id="' + ctrlid + '_param_1" sautocomplete="off"/></div>' +
					'<div class="input-block"><span>文字：</span><input type="text" id="' + ctrlid + '_param_2"/></div>';
				submitstr = "$('" + ctrlid + "_param_2').value !== '' ? seditor_insertunit('" + seditorkey + "', '[url='+seditor_squarestrip($('" + ctrlid + "_param_1').value)+']'+$('" + ctrlid + "_param_2').value, '[/url]', null, 1) : seditor_insertunit('" + seditorkey + "', '[url]'+$('" + ctrlid + "_param_1').value, '[/url]', null, 1);hideMenu();";
				break;
			case 'code':
			case 'quote':
			    var tagl = {'quote' : '插入引用', 'code' : '插入代码'};
                menuname =  tagl[tag];
                menu.style.width = '390px';
					str = '<textarea id="' + ctrlid + '_param_1" rows="5" class="txtarea"></textarea>';
				submitstr = "seditor_insertunit('" + seditorkey + "', '[" + tag + "]'+$('" + ctrlid + "_param_1').value, '[/" + tag + "]', null, 1);hideMenu();";
				break;
			case 'img':
                menuname = '插入图片';
                menu.style.width = '240px';
				str = '<div class="input-block"><span>地址：</span><input type="text" id="' + ctrlid + '_param_1" onchange="loadimgsize(this.value, \'' + seditorkey + '\', \'' + tag + '\')" /></div>' +
					'<input class="hidden" type="text" id="' + ctrlid + '_param_2"/><input class="hidden" type="text" id="\' + ctrlid + \'_param_3"/>';
				submitstr = "seditor_insertunit('" + seditorkey + "', '[img' + ($('" + ctrlid + "_param_2').value !== '' && $('" + ctrlid + "_param_3').value !== '' ? '='+$('" + ctrlid + "_param_2').value+','+$('" + ctrlid + "_param_3').value : '')+']'+seditor_squarestrip($('" + ctrlid + "_param_1').value), '[/img]', null, 1);hideMenu();";
				break;
		}
		$('append_parent').appendChild(menu);
		menu.innerHTML = '' +
			'<div class="header" onmousedown="dragMenu($(\'' + menuid +'\'), event, 1)"><span class="title">' + menuname + '</span><a onclick="hideMenu()" class="close" href="javascript:;">×</a></div>' +
			'<div class="body"><form style="width: 100%" onsubmit="' + submitstr + ';return false;" autocomplete="off">' + str +
			'<div class="footer" style="margin: 10px 0 -10px -10px; width: calc(100% + 20px)" >' +
			'<button type="submit" id="' + ctrlid + '_submit" class="btn btn-micro btn-success px4-radius glass">提交</button>' +
			'</div></form></div>';
	}
	showMenu({'ctrlid':ctrlid,'evt':'click','duration':3,'cache':0,'drag':0});
}

function seditor_squarestrip(str) {
	str = str.replace('[', '%5B');
	str = str.replace(']', '%5D');
	return str;
}

function seditor_insertunit(key, text, textend, moveend, selappend) {
	if($(key + 'message')) {
		$(key + 'message').focus();
	}
	textend = isUndefined(textend) ? '' : textend;
	moveend = isUndefined(textend) ? 0 : moveend;
	selappend = isUndefined(selappend) ? 1 : selappend;
	startlen = strlen(text);
	endlen = strlen(textend);
	if(!isUndefined($(key + 'message').selectionStart)) {
		if(selappend) {
			var opn = $(key + 'message').selectionStart + 0;
			if(textend != '') {
				text = text + $(key + 'message').value.substring($(key + 'message').selectionStart, $(key + 'message').selectionEnd) + textend;
			}
			$(key + 'message').value = $(key + 'message').value.substr(0, $(key + 'message').selectionStart) + text + $(key + 'message').value.substr($(key + 'message').selectionEnd);
			if(!moveend) {
				$(key + 'message').selectionStart = opn + strlen(text) - endlen;
				$(key + 'message').selectionEnd = opn + strlen(text) - endlen;
			}
		} else {
			text = text + textend;
			$(key + 'message').value = $(key + 'message').value.substr(0, $(key + 'message').selectionStart) + text + $(key + 'message').value.substr($(key + 'message').selectionEnd);
		}
	} else if(document.selection && document.selection.createRange) {
		var sel = document.selection.createRange();
		if(!sel.text.length && $(key + 'message').sel) {
			sel = $(key + 'message').sel;
			$(key + 'message').sel = null;
		}
		if(selappend) {
			if(textend != '') {
				text = text + sel.text + textend;
			}
			sel.text = text.replace(/\r?\n/g, '\r\n');
			if(!moveend) {
				sel.moveStart('character', -endlen);
				sel.moveEnd('character', -endlen);
			}
			sel.select();
		} else {
			sel.text = text + textend;
		}
	} else {
		$(key + 'message').value += text;
	}
	hideMenu(2);
}

function seditor_ctlent(event, script) {
	if(event.ctrlKey && event.keyCode == 13 || event.altKey && event.keyCode == 83) {
		eval(script);
	}
}

function loadimgsize(imgurl, editor, p) {
	var editor = !editor ? editorid : editor;
	var s = new Object();
	var p = !p ? '_image' : p;
	s.img = new Image();
	s.img.src = imgurl;
	s.loadCheck = function () {
		if(s.img.complete) {
			$(editor + p + '_param_2').value = s.img.width ? s.img.width : '';
			$(editor + p + '_param_3').value = s.img.height ? s.img.height : '';
		} else {
			setTimeout(function () {s.loadCheck();}, 100);
		}
	};
	s.loadCheck();
}