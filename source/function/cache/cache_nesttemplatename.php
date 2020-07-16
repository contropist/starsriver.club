<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: cache_nesttemplatename.php 24927 2011-10-17 03:13:33Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function build_cache_nesttemplatename() {
	$data = [];
	$apps = array('portal', 'forum', 'group', 'home');
	$nullname = lang('portalcp', 'nesttemplate_name_null');
	$scriptarr = $lostname = [];

	foreach(C::t('common_nest_data')->range() as $datarow) {
		$datarow['name'] = $datarow['name'] ? $datarow['name'] : lang('portalcp', $datarow['targettplname'], '', '');
		if(empty($datarow['name'])) {
			$lostname[$datarow['targettplname']] = $datarow['targettplname'];
			$datarow['name'] = $nullname;
		}
		$data[$datarow['targettplname']] = dhtmlspecialchars($datarow['name']);
		$curscript = substr($datarow['targettplname'], 0, strpos($datarow['targettplname'], '/'));
		if(in_array($curscript, $apps)) {
			$scriptarr[$curscript][$datarow['targettplname']] = true;
		}
	}
	if($lostname) {
		require_once libfile('function/portalcp');
		foreach(getnesttplnames($lostname) as $pre => $datas) {
			foreach($datas as $id => $name) {
				$data[$pre.$id] = $name;
			}
		}
	}
	savecache('nesttemplatename', $data);
	foreach($scriptarr as $curscript => $value) {
		savecache('nesttemplatename'.$curscript, $value);
	}
}

?>