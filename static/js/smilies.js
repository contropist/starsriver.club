/*
	[StarsRiver!] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: smilies.js 29684 2012-04-25 04:00:58Z zhangguosheng $
*/

function _smilies_show(id, smcols, seditorkey) {
	if(seditorkey && !$(seditorkey + 'sml_menu')) {
		var div = document.createElement("div");
		div.id = seditorkey + 'sml_menu';
		div.style.display = 'none';
		div.className = 'smilies-box shadow-2D';
		$('append_parent').appendChild(div);
	}
	if(typeof smilies_type == 'undefined') {
		var scriptNode = document.createElement("script");
		scriptNode.type = "text/javascript";
		scriptNode.charset = charset ? charset : (BROWSER.firefox ? document.characterSet : document.charset);
		scriptNode.src = 'data/cache/common_smilies_var.js?' + VERHASH;
		$('append_parent').appendChild(scriptNode);
		if(BROWSER.ie) {
			scriptNode.onreadystatechange = function() {
				smilies_onload(seditorkey + 'sml_menu', smcols, seditorkey);
			};
		} else {
			scriptNode.onload = function() {
				smilies_onload(seditorkey + 'sml_menu', smcols, seditorkey);
			};
		}
	} else {
		smilies_onload(seditorkey + 'sml_menu', smcols, seditorkey);
	}
}

function smilies_onload(id, smcols, seditorkey) {
	seditorkey = !seditorkey ? '' : seditorkey;
	smile = getcookie('smile').split('D');
	if(typeof smilies_type == 'object') {
		if(smile[0] && smilies_array[smile[0]]) {
			CURRENTSTYPE = smile[0];
		} else {
			for(i in smilies_array) {
				CURRENTSTYPE = i;break;
			}
		}
		smiliestype = '<div class="tab" id="'+id+'_tab"><ul>';
		for(i in smilies_type) {
			key = i.substring(1);
			if(smilies_type[i][0]) {
				smiliestype += '<li ' + (CURRENTSTYPE == key ? 'class="current"' : '') + ' id="'+seditorkey+'stype_'+key+'" onclick="smilies_switch(\'' + id + '\', \'' + smcols + '\', '+key+', 1, \'' + seditorkey + '\');if(CURRENTSTYPE) {$(\''+seditorkey+'stype_\'+CURRENTSTYPE).className=\'\';}this.className=\'current\';CURRENTSTYPE='+key+';doane(event);"><a hidefocus="true">'+smilies_type[i][0]+'</a></li>';
			}
		}
		smiliestype += '</ul></div>';
		$(id).innerHTML = smiliestype + '<div id="' + id + '_data"></div><div class="sllt_p" id="' + id + '_page"></div>';
		smilies_switch(id, smcols, CURRENTSTYPE, smile[1], seditorkey);
		smilies_fastdata = '';
		if(seditorkey == 'fastpost' && $('fastsmilies') && smilies_fast) {
			var j = 0;
			for(i = 0;i < smilies_fast.length; i++) {
				j = ++j > 3 ? 0 : j;
				s = smilies_array[smilies_fast[i][0]][smilies_fast[i][1]][smilies_fast[i][2]];
				smilieimg = STATICURL + 'image/smiley/' + smilies_type['_' + smilies_fast[i][0]][1] + '/' + s[2];
				img[k] = new Image();
				img[k].src = smilieimg;
				smilies_fastdata += s ? '<i' + (s[5] > 22 ? 'onmouseenter="smilies_preview(\'' + seditorkey + '\', \'' + id + '\', this, ' + s[5] + ')" onmouseout="$(\'smilies_preview\').style.display = \'none\'" ' : '') + ' onclick="' + (typeof wysiwyg != 'undefined' ? 'insertSmiley(' + s[0] + ')': 'seditor_insertunit(\'' + seditorkey + '\', \'' + s[1].replace(/'/, '\\\'') + '\')') + '" id="' + seditorkey + 'smilie_' + s[0] + '"><img id="smilie_' + s[0] + '" src="' + smilieimg + '" alt="' + s[1] + '" />' : '<td>';
			}
			$('fastsmilies').innerHTML = '<div class="smilies">' + smilies_fastdata + '</div>';
		}
	}
}

function smilies_switch(id, smcols, type, page, seditorkey) {
	page = page ? page : 1;
	if(!smilies_array[type] || !smilies_array[type][page]) return;
	setcookie('smile', type + 'D' + page, 31536000);
	smiliesdata = '';
	j = k = 0;
	img = [];
	for(var i = 0; i < smilies_array[type][page].length; i++) {
		s = smilies_array[type][page][i];
		smilieimg = STATICURL + 'image/smiley/' + smilies_type['_' + type][1] + '/' + s[2];
		img[k] = new Image();
		img[k].src = smilieimg;
		smiliesdata += s && s[0] ? '<i' + (s[5] > 22 ? ' onmouseenter="smilies_preview(\'' + seditorkey + '\', \'' + id + '\', this, ' + s[5] + ')" onmouseout="$(\'smilies_preview\').style.display = \'none\'" ' : '') + ' onclick="' + (typeof wysiwyg != 'undefined' ? 'insertSmiley(' + s[0] + ')': 'seditor_insertunit(\'' + seditorkey + '\', \'' + s[1].replace(/'/, '\\\'') + '\')')+'"><img id="smilie_' + s[0] + '" src="' + smilieimg + '" /></i>' : '';
		j++;k++;
	}
	smiliespage = '';
	if(smilies_array[type].length > 2) {
		prevpage = ((prevpage = parseInt(page) - 1) < 1) ? smilies_array[type].length - 1 : prevpage;
		nextpage = ((nextpage = parseInt(page) + 1) == smilies_array[type].length) ? 1 : nextpage;
		smiliespage = '<div>' +
            '<a class="next r ft-chevron-right " onclick="smilies_switch(\'' + id + '\', \'' + smcols + '\', ' + type + ', ' + nextpage + ', \'' + seditorkey + '\');doane(event);"></a>' + '<span>' + page + '/' + (smilies_array[type].length - 1) + '</span>' +
			'<a class="prew r ft-chevron-left " onclick="smilies_switch(\'' + id + '\', \'' + smcols + '\', ' + type + ', ' + prevpage + ', \'' + seditorkey + '\');doane(event);"></a>' +
			'</div>';
	}
	$(id + '_data').className = 'smilies';
	$(id + '_data').innerHTML = smiliesdata;
	$(id + '_page').className = 'page';
	$(id + '_page').innerHTML = smiliespage;
}

function smilies_preview(seditorkey, id, obj, w) {
    if( w > 22 ){
        var menu = $('smilies_preview');
        if(!menu) {
            menu = document.createElement('div');
            menu.id = 'smilies_preview';
            menu.style.display = 'none';
            $('append_parent').appendChild(menu);
        }
        menu.innerHTML = '<img width="' + w + '" src="' + obj.childNodes[0].src + '" />';
        mpos = fetchOffset($(id + '_data'));
        spos = fetchOffset(obj);
        pos = spos['left'] >= mpos['left'] + $(id + '_data').offsetWidth / 2 ? '13' : '24';
        showMenu({'ctrlid':obj.id,'showid':id + '_data','menuid':menu.id,'pos':pos,'layer':3});
    }
}