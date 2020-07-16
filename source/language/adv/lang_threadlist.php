<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: lang_threadlist.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$lang = array
(
	'threadlist_name' => '论坛/社团 文章列表帖位广告',
	'threadlist_desc' => '展现方式: 帖位广告显示于文章列表页第一页的文章位置，可以模拟出一个具有广告意义的文章地址，吸引访问者的注意力。',
	'threadlist_fids' => '投放版块',
	'threadlist_fids_comment' => '设置广告投放的论坛版块，当广告投放范围中包含“论坛”时有效',
	'threadlist_groups' => '投放社团分类',
	'threadlist_groups_comment' => '设置广告投放的社团分类，当广告投放范围中包含“社团”时有效',
	'threadlist_pos' => '投放位置',
	'threadlist_pos_comment' => '设置在文章列表的第几个文章位置显示此广告，如不指定则将随机位置显示',
	'threadlist_mode' => '显示模式',
	'threadlist_mode_comment' => '自由模式，占用文章列表的全部列宽显示本广告<br />文章模式，把广告模拟成一个文章，点击广告后跳转到指定的文章',
	'threadlist_mode_0' => '自由模式',
	'threadlist_mode_1' => '文章模式',
	'threadlist_tid' => '文章模式指定文章 tid',
	'threadlist_threadurl' => '文章模式自定义文章 URL',
	'threadlist_threadurl_comment' => '留空表示使用指定文章的 URL',
);

?>