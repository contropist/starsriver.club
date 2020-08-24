/*
	[StarsRiver!] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: calendar.js 33082 2013-04-18 11:13:53Z zhengqingpeng $
*/

var controlid = null;
var startdate = null;
var endayitemate  = null;
var halfhour = false;
var yy = null;
var mm = null;
var hh = null;
var ii = null;
var currday = null;
var adayitemtime = false;
var today = new Date();
var lastcheckedyear = false;
var lastcheckedmonth = false;
var calendarrecall = null;

function loadcalendar() {
	s = '<div class="shadow-3D" id="calendar" style="display:none; position:absolute; z-index:200;" onclick="doane(event)">'+
			'<div class="header main-color" id="calendar_week">' +
				'<a class="last awe-angle-left" onclick="refreshcalendar(yy, mm-1)"></a>' +
				'<a id="year" onclick="showdiv(\'year\')"></a>' +
				'<a id="month" onclick="showdiv(\'month\')"></a>' +
				'<a class="next awe-angle-right" onclick="refreshcalendar(yy, mm+1)"></a>' +
			'</div>'+
			'<div class="body">';
	s += '<div class="week"><ul class="row top main-color"><li>一</li><li>二</li><li>三</li><li>四</li><li>五</li><li>六</li><li>日</li></ul>' ;
            for(var i = 0; i < 6; i++) {
                s += '<ul class="row day secondary">';
                for(var j = 1; j <= 7; j++){
                    s += "<li><a data-did=" + (i * 7 + j) + "></a></li>";
                }
                s += "</ul>";
            }
    s+='</div>';
    s += '<ul class="year secondary" id="calendar_year" onclick="doane(event)" style="display: none;">';
    for(var k = today.getFullYear() + 100; k >= today.getFullYear() - 99; k--) {
    	s += '<li><a onclick="refreshcalendar(' + k + ', mm)">' + k + '</a></li>';
    }
    s += '</ul>';
    s += '<ul class="month secondary" id="calendar_month" onclick="doane(event)" style="display: none;">';
    for(var k = 1; k <= 12; k++) {
    	s += '<li><a onclick="refreshcalendar(yy, ' + (k - 1) + ')">' + k + '月</a></li>';
    }
    s += '</ul>';

	s +='<div id="hourminute">' +
        '<span class="timebox">'+
		'<input type="text" id="hour" onKeyUp=\'this.value=this.value > 23 ? 23 : zerofill(this.value);controlid.value=controlid.value.replace(/\\d+(\:\\d+)/ig, this.value+"$1")\'>' +
        '<a class="dpointer timetext">点</a>'+
        '</span>';
    s +='<span id="fullhourselector">' +
		'<input type="text" id="minute" onKeyUp=\'this.value=this.value > 59 ? 59 : zerofill(this.value);controlid.value=controlid.value.replace(/(\\d+\:)\\d+/ig, "$1"+this.value)\'>' +
        '<a class="timetext dpointer">分</a>' +
        '</span>';
	s += '<span class="timebox" id="halfhourselector">' +
			'<select id="minutehalfhourly" onchange=\'this.value=this.value > 59 ? 59 : zerofill(this.value);controlid.value=controlid.value.replace(/(\\d+\:)\\d+/ig, "$1"+this.value)\'>' +
           	'<option value="00">00</option>' +
           	'<option value="30">30</option>' +
        	'</select><a class="timetext dpointer">分</a>' +
        '</span>';
	s += '<button onclick="confirmcalendar();">确定</button></div>';
	s += '</div>';
	s += '</div>';

	var div = document.createElement('div');
	div.innerHTML = s;
	$('append_parent').appendChild(div);
	document.onclick = function(event) {
		closecalendar(event);
	};
	$('calendar').onclick = function(event) {
		doane(event);
		$('calendar_year').style.display = 'none';
		$('calendar_month').style.display = 'none';
	};
}
function closecalendar(event) {
	$('calendar').style.display = 'none';
	$('calendar_year').style.display = 'none';
	$('calendar_month').style.display = 'none';
}

function parsedate(s) {
	/(\d+)\-(\d+)\-(\d+)\s*(\d*):?(\d*)/.exec(s);
	var m1 = (RegExp.$1 && RegExp.$1 > 1899 && RegExp.$1 < 2101) ? parseFloat(RegExp.$1) : today.getFullYear();
	var m2 = (RegExp.$2 && (RegExp.$2 > 0 && RegExp.$2 < 13)) ? parseFloat(RegExp.$2) : today.getMonth() + 1;
	var m3 = (RegExp.$3 && (RegExp.$3 > 0 && RegExp.$3 < 32)) ? parseFloat(RegExp.$3) : today.getDate();
	var m4 = (RegExp.$4 && (RegExp.$4 > -1 && RegExp.$4 < 24)) ? parseFloat(RegExp.$4) : 0;
	var m5 = (RegExp.$5 && (RegExp.$5 > -1 && RegExp.$5 < 60)) ? parseFloat(RegExp.$5) : 0;
	/(\d+)\-(\d+)\-(\d+)\s*(\d*):?(\d*)/.exec("0000-00-00 00\:00");
	return new Date(m1, m2 - 1, m3, m4, m5);
}

function settime(d) {
	if(!adayitemtime) {
		$('calendar').style.display = 'none';
		$('calendar_month').style.display = 'none';
	}
	controlid.value = yy + "-" + zerofill(mm + 1) + "-" + zerofill(d) + (adayitemtime ? ' ' + zerofill($('hour').value) + ':' + zerofill($((halfhour) ? 'minutehalfhourly' : 'minute').value) : '');
	if(typeof calendarrecall == 'function') {
		calendarrecall();
	} else {
		eval(calendarrecall);
	}
}

function confirmcalendar() {
	if(adayitemtime && controlid.value === '') {
		controlid.value = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate() + ' ' + zerofill($('hour').value) + ':' + zerofill($((halfhour) ? 'minutehalfhourly' : 'minute').value);
	}
	closecalendar();
}

function initclosecalendar() {
	var e = getEvent();
	var aim = e.target || e.srcElement;
	while (aim.parentNode != document.body) {
		if (aim.parentNode.id == 'append_parent') {
			aim.onclick = function () {closecalendar(e);};
		}
		aim = aim.parentNode;
	}
}
function showcalendar(event, controlid1, adayitemtime1, startdate1, endayitemate1, halfhour1, recall) {
	controlid = controlid1;
	adayitemtime = adayitemtime1;
	startdate = startdate1 ? parsedate(startdate1) : false;
	endayitemate = endayitemate1 ? parsedate(endayitemate1) : false;
	currday = controlid.value ? parsedate(controlid.value) : today;
	hh = currday.getHours();
	ii = currday.getMinutes();
	halfhour = halfhour1 ? true : false;
	calendarrecall = recall ? recall : null;
	var p = fetchOffset(controlid);
	$('calendar').style.display = 'block';
	$('calendar').style.left = p['left']+'px';
	$('calendar').style.top	= (p['top'] + 40)+'px';
	doane(event);
	refreshcalendar(currday.getFullYear(), currday.getMonth());
	$('hourminute').style.display = adayitemtime ? '' : 'none';
	lastcheckedyear = currday.getFullYear();
	lastcheckedmonth = currday.getMonth() + 1;
	if(halfhour) {
		$('halfhourselector').style.display = '';
		$('fullhourselector').style.display = 'none';
	} else {
		$('halfhourselector').style.display = 'none';
		$('fullhourselector').style.display = '';
	}
	initclosecalendar();
}

function refreshcalendar(y, m) {
	var st = new Date(y, m, 1);
    var prevmonth = new Date(y, m, 0);
    var thismonth = new Date(y, m + 1, 0);
    var prevmonth_daynum = prevmonth.getDate();
    var thismonth_daynum = thismonth.getDate();
    yy = st.getFullYear();
    mm = st.getMonth();
	$("year").innerHTML =  yy;
	$("month").innerHTML = mm + 1 > 9  ? (mm + 1) : '0' + (mm + 1);
    $('calendar_month').style.display='none';
    $('calendar_year').style.display='none';

    var cday = st.getDate();
    var start = st.getDay() === 0 ? 7 : st.getDay() ;
    var end = start + thismonth_daynum - 1;

    for(i = 1; i <= 42 ; i++){
        dayitem = document.querySelector('[data-did="' + i + '"]');
        dayitem.innerHTML = '';
        dayitem.className = 'disabled';
		if(i >= start && i <= end){
            dayitem.innerHTML = cday;
            dayitem.className = st.getFullYear() === currday.getFullYear() && st.getMonth() === currday.getMonth() && st.getDate() === currday.getDate() ? 'today main-color' : '';
            dayitem.title = st.getFullYear() === today.getFullYear() && st.getMonth() === today.getMonth() && st.getDate() === today.getDate() ? '今天' : '';
            st.setDate(++cday);
            dayitem.onclick = function () {
                settime(this.innerHTML);
                doane();
            };
		} else if (i < start){
            dayitem.innerHTML = prevmonth_daynum - (start - i - 1);
		} else if (i > end){
            dayitem.innerHTML = i - (thismonth_daynum + start - 1);
		}
	}
	if(adayitemtime) {
		$('hour').value = zerofill(hh);
		$('minute').value = zerofill(ii);
	}
}

function showdiv(id) {
    $('calendar_year').style.display='none';
    $('calendar_month').style.display='none';
    doane(event);
	$('calendar_' + id).style.display = 'block';
}

function zerofill(s) {
	var s = parseFloat(s.toString().replace(/(^[\s0]+)|(\s+$)/g, ''));
	s = isNaN(s) ? 0 : s;
	return (s < 10 ? '0' : '') + s.toString();
}

loadcss('forum_calendar');
loadcalendar();
