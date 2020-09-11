<?php
/********************************************************************
 * Copyright (c) 2020 All Right Reserved By [StarsRiver]            *
 *                                                                  *
 * Author  Zhangyu                                                  *
 * Email   starsriver@yahoo.com                                     *
 ********************************************************************/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$checkurl = array('acfun.cn', 'acfun.tv');

function media_acfun($url, $width, $height) {
	if(preg_match("/https?:\/\/(www.|)acfun.(cn|tv)\/v\/ac(\d+)/i", $url, $matches)) {
		$vid = $matches[3];
		$iframe = 'https://www.acfun.cn/player/ac'.$vid;
		$imgurl = '';
	} elseif(preg_match("/https?:\/\/m.acfun.(cn|tv)\/v\/\?ac=(\d+)/i", $url, $matches)) {
		$vid = $matches[2];
		$iframe = 'https://www.acfun.cn/player/ac'.$vid;
		$imgurl = '';
	}
	return array($iframe, $url, $imgurl);
}
