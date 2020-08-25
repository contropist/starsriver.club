<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: function_share.php 31894 2012-10-23 02:13:29Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function mkshare($share) {
	$share['body_data'] = unserialize($share['body_data']);
	
	/* Template relink */
	if($share['body_data']['retemplate']){
        $share['type'] = $share['body_data']['retemplate'];
    }
	
    $share['body_template'] = $share['body_template'] ? $share['body_template'] : lang('feed','feed_share_body_template_'.$share['type']);
    $share['title_template'] = $share['title_template'] ? $share['title_template'] : lang('feed','feed_share_title_template_'.$share['type']);

	$searchs = $replaces = [];
	if($share['body_data']) {
		if(isset($share['body_data']['flashaddr'])) {
			$share['body_data']['flashaddr'] = addslashes($share['body_data']['flashaddr']);
		} elseif(isset($share['body_data']['musicvar'])) {
			$share['body_data']['musicvar'] = addslashes($share['body_data']['musicvar']);
		}
		foreach (array_keys($share['body_data']) as $key) {
			$searchs[] = '{'.$key.'}';
			$replaces[] = urldecode($share['body_data'][$key]);
		}
	}
	$share['body_template'] = str_replace($searchs, $replaces, $share['body_template']);
	return $share;
}
?>