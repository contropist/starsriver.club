<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: modcp_log.php 25246 2011-11-02 03:34:53Z zhangguosheng $
 */

if(!defined('IN_DISCUZ') || !defined('IN_MODCP')) {
	exit('Access Denied');
}

if(!isset($_G['cache']['forums'])) {
	loadcache('forums');
}

$language = lang('forum/misc');
$lpp = empty($_GET['lpp']) ? 20 : intval($_GET['lpp']);
$lpp = min(200, max(5, $lpp));
$logdir = DISCUZ_ROOT.'./data/log/';
$logfiles = get_log_files($logdir, 'modcp');

$logs = [];
foreach($logfiles as $logfile) {
	$logs = array_merge($logs, file($logdir.$logfile));
}

$page = max(1, intval($_G['page']));
$start = ($page - 1) * $lpp;
$logs = array_reverse($logs);

if(!empty($_GET['keyword'])) {
	foreach($logs as $key => $value) {
		if(strpos($value, $_GET['keyword']) === FALSE) {
			unset($logs[$key]);
		}
	}
} else {
	$_GET['keyword'] = '';
}

$num = count($logs);
$multipage = multi($num, $lpp, $page, "$cpscript?mod=modcp&action=log&lpp=$lpp&keyword=".rawurlencode($_GET['keyword']));
$logs = array_slice($logs, $start, $lpp);
$keyword = isset($_GET['keyword']) ? dhtmlspecialchars($_GET['keyword']) : '';

$usergroup = [];

$filters = '';

$loglist = [];

foreach($logs as $logrow) {
	$log = explode("\t", $logrow);
	if(empty($log[1])) {
		continue;
	}
	$log['t1'] = date("Y-m-d", $log[1]);
	$log['t2'] = date("h:i:s", $log[1]);
	if(strtolower($log[2]) == strtolower($_G['member']['username'])) {
		$log['user'] = '<a href="home.php?mod=space&username='.rawurlencode($log[2]).'" target="_blank"><b>'.$log[2].'</b></a>';
	}

	$log['fuc'] = trim($log[5]);
	$check = 'modcp_logs_action_'.$log['fuc'];
	$log['fuc'] = isset($language[$check]) ? $language[$check] : $log['fuc'];

	$log['fucname'] = $log[6];

	$log[7] = intval($log[7]);
	$log[7] = !empty($log[7]) ? '<a href="forum.php?mod=forumdisplay&fid='.$log[7].'" target="_blank">'.strip_tags("{$_G['cache']['forums'][$log[7]]['name']}").'</a>' : '';

    $log[8] = str_replace(array('mod=modcp;', 'action='.$log[5].';', 'nest=;', 'op='.$log[6].';'), '', $log[8]);
    $log[8] = str_replace('GET=', '$GET= ', $log[8]);
    $log[8] = str_replace('}; POST=', '};$$POST= ', $log[8]);
    $log['get-a'] = preg_replace('/\$\$POST(.*)};/','', $log[8]);
	$log['post-a'] = preg_replace('/\$GET(.*)};\$/','', $log[8]);
    $log['get'] = cutstr($log['get-a'], 64);
	$log['post'] = cutstr($log['post-a'], 64);

	$loglist[] = $log;
}

function get_log_files($logdir='', $action='action') {
	$dir = opendir($logdir);
	$files = [];
	while($entry = readdir($dir)) {
		$files[] = $entry;
	}
	closedir($dir);

	sort($files);
	$logfile = $action;
	$logfiles = [];
	foreach($files as $file) {
		if(strpos($file, $logfile) !== FALSE) {
			$logfiles[] = $file;
		}
	}
	$logfiles = array_slice($logfiles, -2, 2);
	return $logfiles;
}

?>