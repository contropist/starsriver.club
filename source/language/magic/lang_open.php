<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: lang_open.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$lang = array
(
	'open_name' => '喧嚣卡',
	'open_desc' => '可以将文章开启，可以回复',
	'open_forum' => '允许使用本道具的版块',
	'open_info' => '开放指定的文章，请输入文章的 ID',
	'open_info_nonexistence' => '请指定要开放的文章',
	'open_succeed' => '你操作的文章已开放回复',
	'open_info_noperm' => '对不起，文章所在版块不允许使用本道具',
	'open_info_user_noperm' => '对不起，你不能对此人使用本道具',

	'open_notification' => '你的文章 {subject} 被 {actor} 使用了{magicname}，<a href="forum.php?mod=viewthread&tid={tid}">快去看看吧！</a>',
);

?>