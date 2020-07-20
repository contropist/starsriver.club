<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: lang_spacecp.php 32426 2013-01-15 10:00:21Z liulanbo $
 */

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$lang = array(

    'by'        => '通过',
    'tab_space' => ' ',

    'share'        => '分享',
    'share_action' => '分享了',

    'pm_comment'      => '答复评论',
    'pm_thread_about' => '关于你在“{subject}”的回复',

    'wall_pm_subject' => '你好，我给你留言了',
    'wall_pm_message' => '我在你的留言板给你留言了，[url=\\1]点击这里去留言板看看吧[/url]',
    'reward'          => '悬赏',
    'reward_info'     => '参与投票可获得  \\1 积分',
    'poll_separator'  => '"、"',

    'pm_report_content'               => '<a href="home.php?mod=space&uid={reporterid}" target="_blank">{reportername}</a>举报私信：<br>来自<a href="home.php?mod=space&uid={uid}" target="_blank">{username}</a>的私信<br>内容：{message}',
    'message_can_not_send_1'          => '发送失败，你当前超出了24小时内两人会话的上限',
    'message_can_not_send_2'          => '两次发送私信太快，请稍候再发送',
    'message_can_not_send_3'          => '抱歉，你不能给非好友批量发送私信',
    'message_can_not_send_4'          => '抱歉，你目前还不能使用发送私信功能',
    'message_can_not_send_5'          => '你超出了24小时内群聊会话的上限',
    'message_can_not_send_6'          => '对方屏蔽了你的私信',
    'message_can_not_send_7'          => '超过了群聊人数上限',
    'message_can_not_send_8'          => '抱歉，你不能给自己私信',
    'message_can_not_send_9'          => '收件人为空或对方屏蔽了你的私信',
    'message_can_not_send_10'         => '发起群聊人数不能小于两人',
    'message_can_not_send_11'         => '该会话不存在',
    'message_can_not_send_12'         => '抱歉，你没有权限操作',
    'message_can_not_send_13'         => '这不是群聊消息',
    'message_can_not_send_14'         => '这不是私信',
    'message_can_not_send_15'         => '数据有误',
    'message_can_not_send_16'         => '你超出了24小时内私信数量的上限',
    'message_can_not_send_onlyfriend' => '该用户只接收好友发送的私信',


    'friend_subject'      => '<a href="{url}" target="_blank">{username} 请求加你为好友</a>',
    'friend_request_note' => '，附言：{note}',
    'comment_friend'      => '<a href="\\2" target="_blank">\\1 给你留言了</a>',
    'photo_comment'       => '<a href="\\2" target="_blank">\\1 评论了你的照片</a>',
    'blog_comment'        => '<a href="\\2" target="_blank">\\1 评论了你的日志</a>',
    'poll_comment'        => '<a href="\\2" target="_blank">\\1 评论了你的投票</a>',
    'share_comment'       => '<a href="\\2" target="_blank">\\1 评论了你的分享</a>',
    'friend_pm'           => '<a href="\\2" target="_blank">\\1 给你发私信了</a>',
    'poke_subject'        => '<a href="\\2" target="_blank">\\1 向你打招呼</a>',
    'mtag_reply'          => '<a href="\\2" target="_blank">\\1 回复了你的话题</a>',
    'event_comment'       => '<a href="\\2" target="_blank">\\1 评论了你的活动</a>',

    'friend_pm_reply'      => '\\1 回复了你的私信',
    'comment_friend_reply' => '\\1 回复了你的留言',
    'blog_comment_reply'   => '\\1 回复了你的日志评论',
    'photo_comment_reply'  => '\\1 回复了你的照片评论',
    'poll_comment_reply'   => '\\1 回复了你的投票评论',
    'share_comment_reply'  => '\\1 回复了你的分享评论',
    'event_comment_reply'  => '\\1 回复了你的活动评论',

    'mail_my'     => '好友与我的互动提醒',
    'mail_system' => '系统提醒',

    'invite_subject' => '{username}邀请你加入{sitename}，并成为好友',
    'invite_message' => '<table border="0">
		<tr>
		<td valign="top">{avatar}</td>
		<td valign="top">
		<h3>Hi，我是{username}，邀请你也加入{sitename}并成为我的好友</h3><br>
		请加入到我的好友中，你就可以了解我的近况，与我一起交流，随时与我保持联系。<br>
		<br>
		邀请附言：<br>{saymsg}
		<br><br>
		<strong>请你点击以下链接，接受好友邀请：</strong><br>
		<a href="{inviteurl}">{inviteurl}</a><br>
		<br>
		<strong>如果你拥有{sitename}上面的账号，请点击以下链接查看我的个人主页：</strong><br>
		<a href="{siteurl}home.php?mod=space&uid={uid}">{siteurl}home.php?mod=space&uid={uid}</a><br>
		</td></tr></table>',

    'app_invite_subject' => '{username}邀请你加入{sitename}，一起来玩{appname}',
    'app_invite_message' => '<table border="0">
		<tr>
		<td valign="top">{avatar}</td>
		<td valign="top">
		<h3>Hi，我是{username}，在{sitename}上玩 {appname}，邀请你也加入一起玩</h3><br>
		<br>
		邀请附言：<br>
		{saymsg}
		<br><br>
		<strong>请你点击以下链接，接受好友邀请一起玩{appname}：</strong><br>
		<a href="{inviteurl}">{inviteurl}</a><br>
		<br>
		<strong>如果你拥有{sitename}上面的账号，请点击以下链接查看我的个人主页：</strong><br>
		<a href="{siteurl}home.php?mod=space&uid={uid}">{siteurl}home.php?mod=space&uid={uid}</a><br>
		</td></tr></table>',

    'person' => '人',
    'delete' => '删除',
    'select_file' => '选择文件',

    'space_update' => '{actor} 被SHOW了一下',

    'active_email_subject' => '你的邮箱激活邮件',
    'active_email_msg'     => '请复制下面的激活链接到浏览器进行访问，以便激活你的邮箱。<br>邮箱激活链接:<br><a href="{url}" target="_blank">{url}</a>',
    'album'                => '画廊',
    'mtag'                 => '{$_G[setting][navs][3][navname]}',
    'default_albumname'    => '默认画廊',
    'share_mtag'           => '分享了{$_G[setting][navs][3][navname]}',
    'share_mtag_membernum' => '现有 {membernum} 名成员',
    'share_tag'            => '分享了标签',
    'share_tag_blognum'    => '现有 {blognum} 篇日志',
    'share_event'          => '分享了活动',
    'share_poll'           => '分享了\\1投票',
    'event_time'           => '活动时间',
    'event_location'       => '活动地点',
    'event_creator'        => '发起人',
    'the_default_style'    => '默认模板',
    'the_nest_style'       => '自定义风格',

    'thread_edit_trail'         => '<ins class="modify">[本话题由 \\1 于 \\2 编辑]</ins>',
    'create_a_new_album'        => '创建了新画廊',
    'not_allow_upload'          => '你现在没有权限上传图片',
    'not_allow_upload_extend'   => '不允许上传{extend}类型的图片',
    'files_can_not_exceed_size' => '{extend}类文件不能超过{size}',
    'get_passwd_subject'        => '取回密码邮件',
    'get_passwd_message'        => '你只需在提交请求后的三天之内，通过点击下面的链接重置你的密码：<br>\\1<br>(如果上面不是链接形式，请将地址手工粘帖到浏览器地址栏再访问)<br>上面的页面打开后，输入新的密码后提交，之后你即可使用新的密码登录了。',
    'file_is_too_big'           => '文件过大',

    'take_part_in_the_voting'                  => '{actor} 参与了 {touser} 的{reward}投票 <a href="{url}" target="_blank">{subject}</a>',
    'lack_of_access_to_upload_file_size'       => '无法获取上传文件大小',
    'only_allows_upload_file_types'            => '只允许上传jpg、jpeg、gif、png标准格式的图片',
    'unable_to_create_upload_directory_server' => '服务器无法创建上传目录',
    'inadequate_capacity_space'                => '空间容量不足，不能上传新附件',
    'mobile_picture_temporary_failure'         => '无法转移临时文件到服务器指定目录',
    'ftp_upload_file_size'                     => '远程上传图片失败',
    'comment'                                  => '评论',
    'upload_a_new_picture'                     => '上传了新图片',
    'upload_album'                             => '更新了画廊',
    'the_total_picture'                        => '共 \\1 张图片',

    'space_open_subject' => '快来打理一下你的个人主页吧',
    'space_open_message' => 'hi，我今天去拜访了一下你的个人主页，发现你自己还没有打理过呢。赶快来看看吧。地址是：\\1space.php',


    'apply_mtag_manager' => '想申请成为 <a href="\\1" target="_blank">\\2</a> 的团长，理由如下:\\3。<a href="\\1" target="_blank">(点击这里进入管理)</a>',


    'magicunit'       => '个',
    'magic_note_wall' => '{actor}在留言板上给你<a href="{url}" target="_blank">留言</a>',
    'magic_call'      => '在日志中点了你的名，<a href="{url}" target="_blank">快去看看吧</a>',


    'present_user_magics' => '你收到了管理员赠送的道具：\\1',
    'has_not_more_doodle' => '你没有涂鸦板了',

    'do_stat_login'                   => '来访用户',
    'do_stat_mobilelogin'             => '手机访问',
    'do_stat_connectlogin'            => 'QQ登录访问',
    'do_stat_register'                => '新注册用户',
    'do_stat_invite'                  => '好友邀请',
    'do_stat_appinvite'               => '应用邀请',
    'do_stat_add'                     => '信息发布',
    'do_stat_comment'                 => '信息互动',
    'do_stat_space'                   => '互动',
    'do_stat_doing'                   => '记录',
    'do_stat_blog'                    => '日志',
    'do_stat_activity'                => '活动',
    'do_stat_reward'                  => '悬赏',
    'do_stat_debate'                  => '辩论',
    'do_stat_trade'                   => '商品',
    'do_stat_group'                   => "创建{$_G[setting][navs][3][navname]}",
    'do_stat_tgroup'                  => "{$_G[setting][navs][3][navname]}",
    'do_stat_home'                    => "{$_G[setting][navs][4][navname]}",
    'do_stat_forum'                   => "{$_G[setting][navs][2][navname]}",
    'do_stat_groupthread'             => '社团文章',
    'do_stat_post'                    => '文章回复',
    'do_stat_grouppost'               => '社团回复',
    'do_stat_pic'                     => '图片',
    'do_stat_poll'                    => '投票',
    'do_stat_event'                   => '活动',
    'do_stat_share'                   => '分享',
    'do_stat_thread'                  => '文章',
    'do_stat_docomment'               => '记录回复',
    'do_stat_blogcomment'             => '日志评论',
    'do_stat_piccomment'              => '图片评论',
    'do_stat_pollcomment'             => '投票评论',
    'do_stat_pollvote'                => '参与投票',
    'do_stat_eventcomment'            => '活动评论',
    'do_stat_eventjoin'               => '参加活动',
    'do_stat_sharecomment'            => '分享评论',
    'do_stat_post'                    => '文章回复',
    'do_stat_click'                   => '表态',
    'do_stat_wall'                    => '留言',
    'do_stat_poke'                    => '打招呼',
    'do_stat_sendpm'                  => '私信',
    'do_stat_addfriend'               => '好友请求',
    'do_stat_friend'                  => '成为好友',
    'do_stat_post_number'             => '回复量',
    'do_stat_statistic'               => '合并统计',
    'logs_credit_update_INDEX'        => array(
        'TRC',
        'RTC',
        'RAC',
        'MRC',
        'BMC',
        'TFR',
        'RCV',
        'CEC',
        'ECU',
        'SAC',
        'BAC',
        'PRC',
        'RSC',
        'STC',
        'BTC',
        'AFD',
        'UGP',
        'RPC',
        'ACC',
        'RCT',
        'RCA',
        'RCB',
        'CDC',
        'RGC',
        'BGC',
        'AGC',
        'RKC',
        'BME',
        'RPR',
        'RPZ',
        'FCP',
        'BGC'
    ),
    'logs_credit_update_TRC'          => '任务奖励',
    'logs_credit_update_RTC'          => '悬赏文章',
    'logs_credit_update_RAC'          => '最佳答案',
    'logs_credit_update_MRC'          => '道具随机获取',
    'logs_credit_update_BMC'          => '购买道具',
    'logs_credit_update_TFR'          => '转账转出',
    'logs_credit_update_RCV'          => '转账接收',
    'logs_credit_update_CEC'          => '积分兑换',
    'logs_credit_update_ECU'          => 'UCenter积分兑换支出',
    'logs_credit_update_SAC'          => '出售附件',
    'logs_credit_update_BAC'          => '购买附件',
    'logs_credit_update_PRC'          => '回复被投币',
    'logs_credit_update_RSC'          => '回复投币',
    'logs_credit_update_STC'          => '出售文章',
    'logs_credit_update_BTC'          => '购买文章',
    'logs_credit_update_AFD'          => '购买积分',
    'logs_credit_update_UGP'          => '购买扩展用户组',
    'logs_credit_update_RPC'          => '举报奖惩',
    'logs_credit_update_ACC'          => '参与活动',
    'logs_credit_update_RCT'          => '回复奖励',
    'logs_credit_update_RCA'          => '回复中奖',
    'logs_credit_update_RCB'          => '返还回复奖励积分',
    'logs_credit_update_CDC'          => '卡密充值',
    'logs_credit_update_RGC'          => '回收红包',
    'logs_credit_update_BGC'          => '埋下红包',
    'logs_credit_update_AGC'          => '获得红包',
    'logs_credit_update_RKC'          => '续航排名',
    'logs_credit_update_BME'          => '购买勋章',
    'logs_credit_update_RPR'          => '后台积分奖惩',
    'logs_credit_update_RPZ'          => '后台积分奖惩清零',
    'logs_credit_update_FCP'          => '付费版块',
    'logs_credit_update_BGR'          => '创建社团',
    'buildgroup'                      => '查看已创建的社团',
    'logs_credit_update_reward_clean' => '清零',
    'logs_select_operation'           => '请选择操作类型',
    'task_credit'                     => '任务奖励积分',
    'special_3_credit'                => '悬赏文章扣除积分',
    'special_3_best_answer'           => '最佳答案获取悬赏积分',
    'magic_credit'                    => '道具随机获取积分',
    'magic_space_gift'                => '在自已空间首页埋下红包',
    'magic_space_re_gift'             => '回收还没有用完的红包',
    'magic_space_get_gift'            => '访问空间领取的红包',
    'credit_transfer'                 => '进行积分转帐',
    'credit_transfer_tips'            => '的转账收入',
    'credit_exchange_tips_1'          => '执行积分对兑换操作,将 ',
    'credit_exchange_to'              => '兑换成',
    'credit_exchange_center'          => '通过UCenter兑换积分',
    'attach_sell'                     => '出售',
    'attach_sell_tips'                => '的附件获得积分',
    'attach_buy'                      => '购买',
    'attach_buy_tips'                 => '的附件支出积分',
    'grade_credit'                    => '被投币获得的积分',
    'grade_credit2'                   => '回复投币扣除的积分',
    'thread_credit'                   => '文章获得积分',
    'thread_credit2'                  => '文章支出积分',
    'buy_credit'                      => '对积分充值',
    'buy_usergroup'                   => '购买扩展用户组支出积分',
    'buy_medal'                       => '购买勋章',
    'buy_forum'                       => '购买付费版块的访问权限',
    'report_credit'                   => '举报功能中的奖惩',
    'join'                            => '参与',
    'activity_credit'                 => '活动扣除积分',
    'thread_send'                     => '扣除发表',
    'replycredit'                     => '散发的积分',
    'add_credit'                      => '奖励积分',
    'recovery'                        => '回收',
    'replycredit_post'                => '回复奖励',
    'replycredit_thread'              => '散发的回复',
    'card_credit'                     => '卡密充值获得的积分',
    'ranklist_top'                    => '参加续航排名消费积分',
    'admincp_op_credit'               => '后台积分奖惩操作',
    'credit_update_reward_clean'      => '清零',

    'profile_unchangeable'        => '此项提交后 <em>不可修改</em>',
    'profile_is_verifying'        => '此项正在审核中...',
    'profile_mypost'              => '我提交的内容',
    'profile_need_verifying'      => '此项提交后 <em>需要审核</em>',
    'profile_edit'                => '修改',
    'profile_censor'              => '(含有敏感词汇)',
    'profile_verify_modify_error' => '{verify}已经认证通过不允许修改',
    'profile_verify_verifying'    => '你的{verify}信息已提交，请耐心等待核查。',

    'district_level_1'               => '-省份-',
    'district_level_2'               => '-城市-',
    'district_level_3'               => '-州县-',
    'district_level_4'               => '-乡镇-',
    'invite_you_to_visit'            => '{user}邀请你访问{bbname}',
    'portal'                         => '门户',
    'group'                          => '社团',
    'follow'                         => '话题',
    'collection'                     => '专辑',
    'guide'                          => '导读',
    'feed'                           => '动态',
    'blog'                           => '日志',
    'doing'                          => '记录',
    'wall'                           => '留言板',
    'homepage'                       => '个人主页',
    'ranklist'                       => '排行榜',
    'select_the_navigation_position' => '选择{type}导航位置',
    'close_module'                   => '关闭{type}功能',
    'follow_add_remark'              => '添加备注',
    'follow_modify_remark'           => '修改备注',
    'follow_specified_group'         => '话题专区',
    'follow_specified_forum'         => '话题专版',
    'filesize_lessthan'              => '文件大小应该小于',
    'checkbox_max'                   => '最多可勾选 {num} 个选项',
    'input_must'                     => '必填项',
    'spacecp_message_prompt'         => '(支持 {msg} 代码,最大 1000 字)',
    'card_update_doing'              => '',
    'email_acitve_message'           => '{newemail} 正等待验证中...<br>系统已经向该邮箱发送了一封验证激活邮件，请查收邮件，进行验证激活。如果没有收到验证邮件，你可以更换一个邮箱，或者<a href="home.php?mod=spacecp&ac=profile&op=password&resend=1">重新接收验证邮件</a>',
    'qq_set_status'                  => '设置我的QQ在线状态',
    'qq_dialog'                      => '发起QQ聊天',

);

?>