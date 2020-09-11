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

$checkurl = array('wasu.cn');

function media_wasu($url, $width, $height) {
	if(preg_match("/https?:\/\/(www.|)wasu.cn\/(wap\/|)Play\/show\/id\/(\d+)/i", $url, $matches)) {
		$vid = $matches[3];
		$iframe = 'https://www.wasu.cn/Play/iframe/id/'.$vid;
		$imgurl = '';
	}
	return array($iframe, $url, $imgurl);
}
