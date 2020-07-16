<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: lang_bump.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$lang = array
(
	'bump_name' => '提升卡',
	'bump_forum' => '允许使用本道具的版块',
	'bump_desc' => '可以提升某个文章',
	'bump_info' => '提升指定的文章，请输入文章的 ID',
	'bump_info_nonexistence' => '请指定要提升的文章',
	'bump_succeed' => '你操作的文章已提升',
	'bump_info_noperm' => '对不起，文章所在版块不允许使用本道具',

	'bump_notification' => '你的文章 {subject} 被 {actor} 使用了{magicname}，<a href="forum.php?mod=viewthread&tid={tid}">快去看看吧！</a>',
);

?>