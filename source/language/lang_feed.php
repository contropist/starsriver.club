<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: lang_feed.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$lang = array(
	'feed_attach' => '内容附件',
	'feed_blog_password' => '发表了新加密日志 {subject}',
	'feed_blog_title' => '发表了新日志',
	'feed_album_title' => '更新了画廊',
	'feed_album_body' => '{album}共{picnum}张图片',
	'feed_pic_title' => '上传了新图片',
	'feed_pic_body' => '{title}',
	'feed_poll' => '发起了新投票',
	'feed_comment_space' => '在 {touser} 的留言板留了言',
	'feed_comment_image' => '评论了 {touser} 的图片',
	'feed_comment_blog' => '评论了 {touser} 的日志 {blog}',
	'feed_comment_poll' => '评论了 {touser} 的投票 {poll}',
	'feed_comment_event' => '在 {touser} 组织的活动 {event} 中留言了',
	'feed_comment_share' => '对 {touser} 分享的 {share} 发表了评论',
	'feed_showcredit' => '给 {fusername} 充电 {credit}，帮助好友提升在<a href="misc.php?mod=ranklist&type=member" target="_blank">续航剩余榜</a>中的名次',
	'feed_showcredit_self' => '充电 {credit}，提升自己在<a href="misc.php?mod=ranklist&type=member" target="_blank">续航剩余榜</a>中的名次',
	'feed_doing_title' => '发表了新纪录',
	'feed_friend_title' => '和 {touser} 成为了好友',
	'feed_click_blog' => '送了一个“{click}”给 {touser} 的日志 {subject}',
	'feed_click_thread' => '送了一个“{click}”给 {touser} 的话题 {subject}',
	'feed_click_pic' => '送了一个“{click}”给 {touser} 的图片',
	'feed_click_article' => '送了一个“{click}”给 {touser} 的文章 {subject}',
	'feed_task' => '完成了有奖任务 {task}',
	'feed_task_credit' => '完成了有奖任务 {task}，领取了 {credit} 个奖励积分',
	'feed_profile_update_base' => '更新了自己的基本资料',
	'feed_profile_update_contact' => '更新了自己的联系方式',
	'feed_profile_update_edu' => '更新了自己的教育情况',
	'feed_profile_update_work' => '更新了自己的工作信息',
	'feed_profile_update_info' => '更新了自己的个人信息',
	'feed_profile_update_bbs' => '更新了自己的论坛信息',
	'feed_profile_update_verify' => '更新了自己的认证信息',

	'feed_add_attachsize' => '用 {credit} 个积分兑换了 {size} 附件空间，可以上传更多的图片啦(<a href="home.php?mod=spacecp&ac=credit&op=addsize">我也来兑换</a>)',

	'feed_invite' => '发起邀请，和 {username} 成为了好友',

	'magicuse_thunder_announce_title' => '<strong>{username} 发出了“雷鸣之声”</strong>',
	'magicuse_thunder_announce_body' => '大家好，我上线啦<br><a href="home.php?mod=space&uid={uid}" target="_blank">欢迎来我家串个门</a>',

	'feed_thread_title' =>			'发表了新话题',
	'feed_thread_message' =>		'<div class="thread"><span class="title">{subject}</span><div class="article">{message}</div></div>',
	'feed_reply_title' =>			'回复了 {author} 的话题 {subject}',
	'feed_reply_title_anonymous' =>		'回复了话题 {subject}',
	'feed_reply_message' =>			'',
	'feed_thread_poll_title' =>		'发起了新投票',
	'feed_thread_poll_message' =>		'<div class="thread"><span class="title">{subject}</span><div class="article">{message}</div></div>',
	'feed_thread_votepoll_title' =>		'参与了关于 {subject} 的投票',
	'feed_thread_votepoll_message' =>	'',
	'feed_thread_goods_title' =>		'出售了一个新商品',
	'feed_thread_goods_message_1' =>	'<b>{itemname}</b><br>售价 {itemprice} 元 附加 {itemcredit}{creditunit}',
	'feed_thread_goods_message_2' =>	'<b>{itemname}</b><br>售价 {itemprice} 元',
	'feed_thread_goods_message_3' =>	'<b>{itemname}</b><br>售价 {itemcredit}{creditunit}',
	'feed_thread_reward_title' =>		'发起了新悬赏',
	'feed_thread_reward_message' =>		'<b>{subject}</b><br>悬赏 {rewardprice}{extcredits}',
	'feed_reply_reward_title' =>		'回复了关于 {subject} 的悬赏',
	'feed_reply_reward_message' =>		'',
	'feed_thread_activity_title' =>		'发起了新活动',
	'feed_thread_activity_message' =>	'<b>{subject}</b><br>时间：{starttimefrom}<br>地点：{activityplace}<br>{message}',
	'feed_reply_activity_title' =>		'报名参加了 {subject} 的活动',
	'feed_reply_activity_message' =>	'',
	'feed_thread_debate_title' =>		'发起了新辩论',
	'feed_thread_debate_message' =>		'<b>{subject}</b><br>红方：{affirmpoint}<br>蓝方：{negapoint}<br>{message}',
	'feed_thread_debatevote_title_1' =>	'以红方身份参与了关于 {subject} 的辩论',
	'feed_thread_debatevote_title_2' =>	'以蓝方身份参与了关于 {subject} 的辩论',
	'feed_thread_debatevote_title_3' =>	'以中立身份参与了关于 {subject} 的辩论',
	'feed_thread_debatevote_message_1' =>	'',
	'feed_thread_debatevote_message_2' =>	'',
	'feed_thread_debatevote_message_3' =>	'',

	'feed_blog_body' => '<div class="blog"><i class="title">{subject}</i><div class="content">{summary}</div></div>',
	
	
	'feed_share_title_template_space' => '分享了用户',
	'feed_share_title_template_blog' => '分享了日志',
	'feed_share_title_template_album' => '分享了画廊',
	'feed_share_title_template_pic' => '分享了图片',
	'feed_share_title_template_article' => '分享了文章',
	'feed_share_title_template_thread' => '分享了文章：',
	'feed_share_title_template_link' => '分享了网址',
	'feed_share_title_template_video' => '分享了视频',
	'feed_share_title_template_iframe' => '分享了视频',
	'feed_share_title_template_music' => '分享了音乐',

    'feed_share_body_template_link' =>	'<div class="share-item-link icon-link"><a href="{url}">{name}</a></div>',
    'feed_share_body_template_video' =>	'<div class="share-item-video"><video src="{url}" controls="controls">您的浏览器不支持 video 标签。</video></div>',
    'feed_share_body_template_music' => '<a class="share-item-music" onclick="audioload(\'{url}\')"><i class="icon-play2"></i>{name}</a>',
    'feed_share_body_template_pic' =>	'<div class="share-item-image"><img src="{url}" /><i>{name}</i></div>',
    'feed_share_body_template_iframe' =>'<div class="share-item-iframe">{html}</div>',


    'feed_share_body_template_blog' =>	'<div class="blog">{subject}<i class="category">作者：{username}</i><div class="content">{message}</div></div>',
    'feed_share_body_template_space' =>	'<b>{username}</b><br>{reside}<br>{spacenote}',
	'feed_share_body_template_album' =>	'<b>{albumname}</b><br>{username}',
	'feed_share_body_template_thread' =>	'<b>{subject}</b><br>{author}<br>{message}',
	'feed_share_body_template_article' =>	'<b>{title}</b><br>{username}<br>{summary}',




);

?>