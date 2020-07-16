<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: misc_nesthelp.php 25889 2011-11-24 09:52:20Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$allownest = false; //nest权限:$_G['group']['allownest'] || $_G['group']['allowaddtopic'] && $topic['uid'] == $_G['uid'] || $_G['group']['allowmanagetopic']
$ref = $_GET['nest'] == 'yes';//NEST模式中
if(!$ref && $_GET['action'] == 'get') {
	if($_GET['type'] == 'index') {
		if($_G['group']['allownest']) {
			$allownest = true;
		}
	} else if($_GET['type'] == 'topic') {
		$topic = [];
		$topicid = max(0, intval($_GET['topicid']));
		if($topicid) {
			if($_G['group']['allowmanagetopic']) {
				$allownest = true;
			} else if($_G['group']['allowaddtopic']) {
				if(($topic=C::t('portal_topic')->fetch($topicid)) && $topic['uid'] == $_G['uid']) {
					$allownest = true;
				}
			}
		}
	}
}

include_once template('portal/portal_nesthelp');

?>