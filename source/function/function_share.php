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
	
    $share['body_template'] = $share['body_template'] ? $share['body_template'] : lang('share','share_body_template_'.$share['type']);
    $share['title_template'] = $share['title_template'] ? $share['title_template'] : lang('share','share_title_template_'.$share['type']);

	if($share['title_data']) {
        $searchs = $replaces = [];
        foreach (array_keys($share['title_data']) as $key) {
			$searchs[] = '{'.$key.'}';
			$replaces[] = urldecode($share['title_data'][$key]);
		}
        $share['title_template'] = str_replace($searchs, $replaces, $share['title_template']);
	}
	if($share['body_data']) {
        $searchs = $replaces = [];
        foreach (array_keys($share['body_data']) as $key) {
			$searchs[] = '{'.$key.'}';
			$replaces[] = urldecode($share['body_data'][$key]);
		}
        $share['body_template'] = str_replace($searchs, $replaces, $share['body_template']);
    }
	return $share;
}