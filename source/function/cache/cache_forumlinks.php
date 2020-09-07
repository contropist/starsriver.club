<?php
/********************************************************************
 * Copyright (c) 2020 All Right Reserved By [StarsRiver]            *
 *                                                                  *
 * Author  Zhangyu                                                  *
 * Email   starsriver@yahoo.com                                     *
 ********************************************************************/

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: cache_forumlinks.php 28612 2012-03-06 08:10:47Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function build_cache_forumlinks() {
	global $_G;

	$data = [];
	$query = C::t('common_friendlink')->fetch_all_by_displayorder();

	if($_G['setting']['forumlinkstatus']) {
		$tightlink_content = $tightlink_text = $tightlink_logo = $comma = '';
		foreach ($query as $flink) {
			if($flink['description']) {
				if($flink['logo']) {
					$tightlink_content .= '
					<div class="coposite">
						<a href="'.$flink['url'].'" target="_blank">
							<img src="'.$flink['logo'].'" alt="'.strip_tags($flink['name']).'" />
						</a>
					</div>';
				}
			}
		}
		$data = array($tightlink_content, $tightlink_logo, $tightlink_text);
	}

	savecache('forumlinks', $data);
}

?>