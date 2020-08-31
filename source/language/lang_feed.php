<?php
    
    /**
     *      [Discuz!] (C)2001-2099 Comsenz Inc.
     *      This is NOT a freeware, use is subject to license terms
     *
     *      $Id: lang_feed.php 27449 2012-02-01 05:32:35Z zhangguosheng $
     */
    
    if (!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    
    $lang = [
        'feed_attach'                 => '内容附件',
        'feed_poll'                   => '发起了新投票',
        'feed_comment_poll'           => '评论了 {touser} 的投票 {poll}',
        'feed_comment_event'          => '在 {touser} 组织的活动 {event} 中留言了',
        'feed_showcredit'             => '给 {fusername} 充电 {credit}，帮助好友提升在<a href="misc.php?mod=ranklist&type=member" target="_blank">续航剩余榜</a>中的名次',
        'feed_showcredit_self'        => '充电 {credit}，提升自己在<a href="misc.php?mod=ranklist&type=member" target="_blank">续航剩余榜</a>中的名次',
        'feed_friend_title'           => '和 {touser} 成为了好友',
        'feed_click_blog'             => '送了一个“{click}”给 {touser} 的日志 {subject}',
        'feed_click_thread'           => '送了一个“{click}”给 {touser} 的话题 {subject}',
        'feed_click_pic'              => '送了一个“{click}”给 {touser} 的图片',
        'feed_click_article'          => '送了一个“{click}”给 {touser} 的文章 {subject}',
        'feed_task'                   => '完成了有奖任务 {task}',
        'feed_task_credit'            => '完成了有奖任务 {task}，领取了 {credit} 个奖励积分',
        
        'feed_add_attachsize' => '用 {credit} 个积分兑换了 {size} 附件空间，可以上传更多的图片啦(<a href="home.php?mod=spacecp&ac=credit&op=addsize">我也来兑换</a>)',
        
        'feed_invite' => '发起邀请，和 {username} 成为了好友',
        
        'feed_thread_title'                => '发表了新话题',
        'feed_thread_message'              => '<div class="thread"><span class="title">{subject}</span><div class="article">{message}</div></div>',
        'feed_reply_title'                 => '回复了 {author} 的话题 {subject}',
        'feed_reply_title_anonymous'       => '回复了话题 {subject}',
        'feed_reply_message'               => '',
        'feed_thread_poll_title'           => '发起了新投票',
        'feed_thread_poll_message'         => '<div class="thread"><span class="title">{subject}</span><div class="article">{message}</div></div>',
        'feed_thread_votepoll_title'       => '参与了关于 {subject} 的投票',
        'feed_thread_votepoll_message'     => '',
        'feed_thread_goods_title'          => '出售了一个新商品',
        'feed_thread_goods_message_1'      => '<b>{itemname}</b><br>售价 {itemprice} 元 附加 {itemcredit}{creditunit}',
        'feed_thread_goods_message_2'      => '<b>{itemname}</b><br>售价 {itemprice} 元',
        'feed_thread_goods_message_3'      => '<b>{itemname}</b><br>售价 {itemcredit}{creditunit}',
        'feed_thread_reward_title'         => '发起了新悬赏',
        'feed_thread_reward_message'       => '<b>{subject}</b><br>悬赏 {rewardprice}{extcredits}',
        'feed_reply_reward_title'          => '回复了关于 {subject} 的悬赏',
        'feed_reply_reward_message'        => '',
        'feed_thread_activity_title'       => '发起了新活动',
        'feed_thread_activity_message'     => '<b>{subject}</b><br>时间：{starttimefrom}<br>地点：{activityplace}<br>{message}',
        'feed_reply_activity_title'        => '报名参加了 {subject} 的活动',
        'feed_reply_activity_message'      => '',
        'feed_thread_debate_title'         => '发起了新辩论',
        'feed_thread_debate_message'       => '<b>{subject}</b><br>红方：{affirmpoint}<br>蓝方：{negapoint}<br>{message}',
        'feed_thread_debatevote_title_1'   => '以红方身份参与了关于 {subject} 的辩论',
        'feed_thread_debatevote_title_2'   => '以蓝方身份参与了关于 {subject} 的辩论',
        'feed_thread_debatevote_title_3'   => '以中立身份参与了关于 {subject} 的辩论',
        'feed_thread_debatevote_message_1' => '',
        'feed_thread_debatevote_message_2' => '',
        'feed_thread_debatevote_message_3' => '',

        
        /* 参数注释标志：@-title或body T-仅title B-仅body */
        'feed_template_default_title' => '动态更新',
        'feed_template_default_body'  => '',
        'feed_template_doing_title' => '更新了记录',
        
        'feed_template_profile_title' => '更新了个人资料',
        'feed_profile_update_base'    => '更新了自己的基本资料',
        'feed_profile_update_contact' => '更新了自己的联系方式',
        'feed_profile_update_edu'     => '更新了自己的教育情况',
        'feed_profile_update_work'    => '更新了自己的工作信息',
        'feed_profile_update_info'    => '更新了自己的个人信息',
        'feed_profile_update_bbs'     => '更新了自己的论坛信息',
        'feed_profile_update_verify'  => '更新了自己的认证信息',
        
        /*
        * feed-space-wall
        *
        * T {to_uid}      :用户ID
        * T {to_uname}    :用户名
        * T {to_ulink}    :用户空间链接
        * T {to_uavatar}  :用户头像源链接
        *
        * */
        'feed_template_comment_space_title' => '在<a class="link" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>的空间留言道',


        /*
        * feed-share
        *
        * T {to_uid}      :用户ID
        * T {to_uname}    :用户名
        * T {to_ulink}    :用户空间链接
        * T {to_uavatar}  :用户头像源链接
         *
        * T {share_url}   :分享源链接
        * T {share_act}   :分享名
        *
        * */
        'feed_template_comment_share_title' => '评论了<a class="link" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>分享的<a class="link ellipsis" href="{share_url}" target="_blank">{share_act}</a>',


        /*
        * share-comment-image
        *
        * @ {to_uid}      :用户ID
        * @ {to_uname}    :用户名
        * @ {to_ulink}    :用户空间链接
        * @ {to_uavatar}  :用户头像源链接
         *
        * B {image_togo}  :图像来源
        * B {image_link}  :图像源链接
        *
        * */
        'feed_template_comment_image_title' => '评论了<a class="link" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>的图片',
        'feed_template_comment_image_body' => '
            <div class="feed-element-image">
                <a class="image" href="{image_togo}" target="_blank">
                    <img src="{image_link}" />
                </a>
                <a class="user-tag" href="{to_ulink}" target="_blank">
                    <s class="avatar"><img class="avatar-main" src="{to_uavatar}"></s>
                    <s class="username">{to_uname}</s>
                </a>
            </div>',


        /*
        * share-comment-blog
        *
        * @ {to_uid}      :用户ID
        * @ {to_uname}    :用户名
        * @ {to_ulink}    :用户空间链接
        * @ {to_uavatar}  :用户头像源链接
         *
        * @ {blog_url}      :博客链接
        * @ {blog_sub}      :博客标题
         *
        * B {blog_content}  :博客内容截取
        * B {image}         :博客封面图
        *
        * */
        'feed_template_comment_blog_title'  => '评论了<a class="link" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>的日志<a class="link ellipsis" href="{blog_url}" target="_blank">{blog_sub}</a>',
        'feed_template_comment_blog_body'  => '
            <div class="feed-element-evaluate-blog">
                <div class="detail">
                    <a class="subject ellipsis" href="{blog_url}" target="_blank">{blog_sub}</a>
                    <a class="user-tag ellipsis" href="{to_ulink}" target="_blank">
                        <s class="avatar"><img class="avatar-main" src="{to_uavatar}"></s>
                        <s class="username">{to_uname}</s>
                    </a>
                    <div class="content">{blog_content}</div>
                </div>
            </div>',
        
        'feed_template_comment_blog_withimg_title'  => '评论了<a class="link" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>的日志<a class="link ellipsis" href="{blog_url}" target="_blank">{blog_sub}</a>',
        'feed_template_comment_blog_withimg_body'  => '
            <div class="feed-element-evaluate-blog withimg">
                <div class="image rec-img" style="background-image: url(\'{image}\')">
                    <img src="'.LIBURL.'/img/row-e-col/1.1.png">
                </div>
                <div class="detail">
                    <a class="subject ellipsis" href="{blog_url}" target="_blank">{blog_sub}</a>
                    <a class="user-tag ellipsis" href="{to_ulink}" target="_blank">
                        <s class="avatar"><img class="avatar-main" src="{to_uavatar}"></s>
                        <s class="username">{to_uname}</s>
                    </a>
                    <div class="content">{blog_content}</div>
                </div>
            </div>',


        /*
        * feed-magic-thunder
        *
        * @ {uid}         :用户ID
        * @ {username}    :用户名
         *
        * B {user_avatar}  :用户头像源链接
        *
        * */
        'feed_template_magic_thunder_title' => '<a class="link" href="home.php?mod=space&uid={uid}" style="margin-left: 0" target="_blank" c="1">{username}</a> 发出了“雷鸣之声”',
        'feed_template_magic_thunder_body'  => '
            <div class="feed-element-magic-thunder">
                <a class="avatar" href="home.php?mod=space&uid={uid}" target="_blank"><img class="avatar-main" src="{user_avatar}"></a>
                <i class="hello">初来乍到，请多多指教！我是 {username}</i>
            </div>',

        
        /*
        * feed-blog
        *
        * B {uid}          :用户ID
        * B {username}     :用户名
        * B {user_link}    :用户空间链接
        * B {user_avatar}  :用户头像源链接
         *
        * B {url}      :博客链接
        * B {blogid}   :博客链接
        * B {subject}  :博客标题
        * B {content}  :博客内容截取
        * B {image}    :博客封面图
        *
        * */
        'feed_template_blog_passwd_title' => '更新了加密日志 <i class="tag passwd mt-lock"></i>',
        'feed_template_blog_passwd_body'  => '
            <div class="feed-element-blog">
                <a class="subject ellipsis" href="{url}" target="_blank">{subject}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{content}</div>
            </div>',
        
        'feed_template_blog_passwd_withimg_title' => '更新了加密日志 <i class="tag passwd mt-lock"></i>',
        'feed_template_blog_passwd_withimg_body'  => '
            <div class="feed-element-blog">
                <div class="image"><img src="{image}"></div>
                <a class="subject ellipsis" href="{url}" target="_blank">{subject}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{content}</div>
            </div>',
        
        'feed_template_blog_title'  => '更新了日志',
        'feed_template_blog_body'   => '
            <div class="feed-element-blog">
                <a class="subject ellipsis" href="{url}" target="_blank">{subject}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{content}</div>
            </div>',
        
        'feed_template_blog_withimg_title'  => '更新了日志',
        'feed_template_blog_withimg_body'   => '
            <div class="feed-element-blog">
                <div class="image"><img src="{image}"></div>
                <a class="subject ellipsis" href="{url}" target="_blank">{subject}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{content}</div>
            </div>',


        /*
        * feed-album
        *
        * B {album}      :画廊名称
        * B {album_link} :画廊链接
        * B {picnum}     :画廊图片总数
        * B {imgs}       :画廊的图像节选 > Rended in template file
        *
        * */
        'feed_template_album_title' => '更新了画廊',
        'feed_template_album_body'  => '
            <div class="feed-element-album">画廊 <a class="link ellipsis" href="{album_link}" target="_blank">{album}</a> 包含 {picnum} 张图片</div>',


        /*
        * feed-pic
        *
        * @ {image}        :图像名称
         *
        * T {url}          :图像来源链接
         *
        * B {uid}          :用户ID
        * B {username}     :用户名
        * B {user_link}    :用户空间链接
        * B {user_avatar}  :用户头像链接
         *
        * B {album}        :画廊名称
        * B {album_link}   :画廊链接
        * B {image_togo}   :图像来源链接
        * B {image_link}   :图像源链接
        *
        * */
        'feed_template_pic_title'   => '图片 <a class="link ellipsis" href="{url}" target="_blank">{image}</a> 受到关注',
        'feed_template_pic_body'    => '
            <div class="feed-element-image">
                <a class="image" href="{image_togo}" target="_blank">
                    <img src="{image_link}" />
                </a>
                <a class="user-tag" href="{user_link}" target="_blank">
                    <s class="avatar"><img class="avatar-main" src="{user_avatar}"></s>
                    <s class="username">{username}</s>
                </a>
            </div>',
    ];