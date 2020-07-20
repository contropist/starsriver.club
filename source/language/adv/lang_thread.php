<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: lang_thread.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$lang = array
(
	'thread_name' => '论坛/社团 帖内广告',
	'thread_desc' => '展现方式: 帖内广告显示于文章内容的上方、下方或右方，文章内容的上方和下方通常使用文字的形式，文章内容右方通常使用图片的形式。当前页面有多个帖内广告时，系统会从中抽取与每页帖数相等的条目进行随机显示。你可以在 全局设置中的其他设置中修改每帖显示的广告数量。<br>价值分析: 由于文章是论坛最核心的组成部分，嵌入文章内容内部的帖内广告，便可在用户浏览文章内容时自然的被接受，加上随机播放的特性，适合于特定内容的有效推广，也可用于论坛自身的宣传和公告之用。建议设置多条帖内广告以实现广告内容的差异化，从而吸引更多访问者的注意力。',
	'thread_fids' => '投放版块',
	'thread_fids_comment' => '设置广告投放的论坛版块，当广告投放范围中包含“论坛”时有效',
	'thread_groups' => '投放社团分类',
	'thread_groups_comment' => '设置广告投放的社团分类，当广告投放范围中包含“社团”时有效',
	'thread_position' => '投放位置',
	'thread_position_comment' => '文章内容上方和下方的广告适合使用文字形式，而文章右侧广告适合使用图片或 Flash 形式，也可以同时显示多条文字广告',
	'thread_position_bottom' => '文章下方',
	'thread_position_top' => '文章上方',
	'thread_position_right' => '文章右侧',
	'thread_pnumber' => '广告显示楼层',
	'thread_pnumber_comment' => '选项 #1 #2 #3 ... 表示回复楼层，可以按住 CTRL 多选',
	'thread_pnumber_all' => '全部',
);

?>