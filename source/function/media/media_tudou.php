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

$checkurl = array('tudou.com/programs/view/');

function media_tudou($url, $width, $height) { 
	if(preg_match("/^http:\/\/(www.)?tudou.com\/programs\/view\/([^\/]+)/i", $url, $matches)) {
		$iframe = 'http://www.tudou.com/programs/view/html5embed.action?code='.$matches[2];
		if(!$width && !$height) {
			$str = file_get_contents($url, false, $ctx);
			if(!empty($str) && preg_match("/<span class=\"s_pic\">(.+?)<\/span>/i", $str, $image)) {
				$imgurl = trim($image[1]);
			}
		}
	}
	return array($iframe, $url, $imgurl);
}