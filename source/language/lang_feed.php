<?php
/********************************************************************
 * Copyright (c) 2020 All Right Reserved By [StarsRiver]            *
 *                                                                  *
 * Author  Zhangyu                                                  *
 * Email   starsriver@yahoo.com                                     *
 ********************************************************************/

    if (!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    
    $lang = [

        'feed_attach'        => '内容附件',
        'feed_poll'          => '发起了新投票',
        'feed_comment_poll'  => '评论了 {touser} 的投票 {poll}',
        'feed_comment_event' => '在 {touser} 组织的活动 {event} 中留言了',
        'feed_task'          => '完成了有奖任务 {task}',
        'feed_task_credit'   => '完成了有奖任务 {task}，领取了 {credit} 个奖励积分',
        'feed_add_attachsize' => '用 {credit} 个积分兑换了 {size} 附件空间，可以上传更多的图片啦(<a href="home.php?mod=spacecp&ac=credit&op=addsize">我也来兑换</a>)',


        /*
         * 参数注释标志：
         * @ - title或body
         * T - 仅title
         * B - 仅body
         *
         * 通用扩展参数::为【避免】【系统更新】和【模板更新】导致【早期数据库】中【参数不足】问题，预留给后期增加参数使用。
         * B {expend0~8}
         *
         * */
        

        /*
        * feed-thread.ban
        *
        * T {content}     :帖子ID
         *
        * B {content}      :用户ID
        *
        * */
        'feed_template_sitefeed_title' => '系统动态：{content}',
        'feed_template_sitefeed_body'   => '<div class="feed-element-sitefeed">{content}</div>',


        /*
        * feed-thread.ban
        *
        * @ {tid}     :帖子ID
        * @ {tsub}    :帖子标题
        * @ {tink}    :帖子链接
         *
        * B {uid}      :用户ID
        * B {uname}    :用户名
        * B {ulink}    :用户空间链接
        * B {uavatar}  :用户头像源链接
        *
        * */
        'feed_template_need_payoff_body' => '<div class="feed-element-thread-ban"><a class="btn-shadow redirect-needed" href="{tlink}">此帖为付费内容，需转至原文后查看</a></div>',
        'feed_template_need_perm_body'   => '<div class="feed-element-thread-ban"><a class="btn-shadow redirect-needed" href="{tlink}">此贴要求一定的等级权限，需转至原文查看</a></div>',
        'feed_template_post_baned_body'  => '<div class="feed-element-thread-ban"><a class="btn-shadow redirect-needed" href="{tlink}">此内容被屏蔽</a></div>',

        'feed_template_quote_need_payoff_body' => '
            <div class="feed-element-thread-ban">
                <a class="user-tag" href="{ulink}" target="_blank" c="1">
                    <s class="avatar"><img class="avatar-main" src="{uavatar}"></s>
                    <s class="username ellipsis">{uname}</s>
                </a>
                <a class="btn-shadow redirect-needed" href="{tlink}">此帖为付费内容，需转至原文后查看</a>
            </div>',

        'feed_template_quote_need_perm_body'   => '
            <div class="feed-element-thread-ban">
                <a class="user-tag" href="{ulink}" target="_blank" c="1">
                    <s class="avatar"><img class="avatar-main" src="{uavatar}"></s>
                    <s class="username ellipsis">{uname}</s>
                </a>
                <a class="btn-shadow redirect-needed" href="{tlink}">此贴要求一定的等级权限，需转至原文查看</a>
            </div>',

        'feed_template_quote_post_baned_body'  => '
            <div class="feed-element-thread-ban">
                <a class="user-tag" href="{ulink}" target="_blank" c="1">
                    <s class="avatar"><img class="avatar-main" src="{uavatar}"></s>
                    <s class="username ellipsis">{uname}</s>
                </a>
                <a class="btn-shadow redirect-needed" href="{tlink}">此内容被屏蔽</a>
            </div>',
        'feed_template_quote_post_no_msg_body'  => '
            <div class="feed-element-thread-ban">
                <a class="user-tag" href="{ulink}" target="_blank" c="1">
                    <s class="avatar"><img class="avatar-main" src="{uavatar}"></s>
                    <s class="username ellipsis">{uname}</s>
                </a>
                <a class="btn-shadow redirect-needed" href="{tlink}">文章内无文字信息，嵌套内容需转至原文查看</a>
            </div>',

        
        /*
        * feed-thread.sample
        *
        * B {tid}     :帖子ID
        * B {tsub}    :帖子标题
        * B {tink}    :帖子链接
        * B {tdate}   :帖子时间
         *
        * B {uid}      :用户ID
        * B {uname}    :用户名
        * B {ulink}    :用户空间链接
        * B {uavatar}  :用户头像源链接
         *
        * B {message}  :回贴摘要
        *
        * */
        'feed_template_thread_sample_body' => '
            <div class="feed-element-thread-sample">
                <a class="user-tag" href="{ulink}" target="_blank" c="1">
                    <s class="avatar"><img class="avatar-main" src="{uavatar}"></s>
                    <s class="username ellipsis">{uname}</s>
                    <s class="date">发布于 {tdate}</s>
                </a>
                <div class="content">{message}</div>
            </div>',

        
        /*
        * feed-thread
        *
        * @ {tid}     :帖子ID
        * @ {tsub}    :帖子标题
        * @ {tink}    :帖子链接
         *
        * B {uid}      :用户ID
        * B {uname}    :用户名
        * B {ulink}    :用户空间链接
        * B {uavatar}  :用户头像源链接
         *
        * B {message}  :帖子摘要
        *
        * */
        'feed_template_thread_title' => '发表了帖子<a class="link ellipsis" href="{tlink}" target="_blank">{tsub}</a>',
        'feed_template_thread_body'  => '
            <div class="feed-element-thread">
                <div class="content">{message}</div>
            </div>',


        /*
        * feed-thread.reply
        *
        * @ {tid}     :帖子ID
        * @ {tsub}    :帖子标题
        * @ {tink}    :帖子链接
         *
        * @ {uid}      :用户ID
        * @ {uname}    :用户名
        * @ {ulink}    :用户空间链接
        * @ {uavatar}  :用户头像源链接
         *
        * B {message}  :回贴摘要
        *
        * */
        'feed_template_thread_reply_title'   => '在 <a class="link ellipsis" href="{ulink}" target="_blank" c="1">{uname}</a> 的帖子 <a class="link ellipsis" href="{tlink}" target="_blank">{tsub}</a>中回复道',
        'feed_template_thread_reply_a_title' => '在帖子 <a class="link ellipsis" href="{tlink}" target="_blank">{tsub}</a>中回复道',
        'feed_template_thread_reply_body'    => '
            <div class="feed-element-thread-reply">
                <div class="content">{message}</div>
            </div>',


        /*
        * feed-thread-reward
        *
        * @ {tid}     :帖子ID
        * @ {tsub}    :帖子标题
        * @ {tlink}    :帖子链接
         *
        * B {uid}      :用户ID
        * B {uname}    :用户名
        * B {ulink}    :用户空间链接
        * B {uavatar}  :用户头像源链接
         *
        * B {message}     :问题摘要
        * B {price}       :悬赏值
        * B {extcredits}  :货币单位
        *
        * */
        'feed_template_thread_reward_title' => '发起了提问<a class="link ellipsis" href="{tlink}" target="_blank">{tsub}</a>',
        'feed_template_thread_reward_body'  => '
            <div class="feed-element-thread-reward">
                <a class="reward" href="{tlink}" target="_blank">悬赏 {price} {extcredits}</a>
            </div>',


        /*
        * feed-thread-reward.reply
        *
        * @ {tid}     :帖子ID
        * @ {tsub}    :帖子标题
        * @ {tink}    :帖子链接
         *
        * @ {message} :回答
        *
        * */
        'feed_template_thread_reward_reply_title' => '回答了 <a class="link ellipsis" href="{ulink}" target="_blank" c="1">{uname}</a> 的提问 <a class="link ellipsis" href="{tlink}" target="_blank">{tsub}</a>',
        'feed_template_thread_reward_reply_body'  => '
            <div class="feed-element-thread-reward-reply">
                <div class="content">{message}</div>
            </div>',

        
        /*
        * feed-thread-debate
        *
        * @ {tid}     :帖子ID
        * @ {tsub}    :帖子标题
        * @ {tink}    :帖子链接
         *
        * B {uid}      :用户ID
        * B {uname}    :用户名
        * B {ulink}    :用户空间链接
        * B {uavatar}  :用户头像源链接
         *
        * B {message}      :摘要
        * B {affirmpoint}  :红方观点
        * B {negapoint}    :蓝方观点
        *
        * */
        'feed_template_thread_debate_title' => '发起了辩论<a class="link ellipsis" href="{tlink}" target="_blank">{tsub}</a>',
        'feed_template_thread_debate_body'  => '
            <div class="feed-element-thread-debate">
                <div class="content">{message}</div>
                <div class="attitude">
                    <i>红方：{affirmpoint}</i>
                    <i>蓝方：{negapoint}</i>
                </div>
            </div>',


        /*
        * feed-thread-debate.vote
        *
        * @ {tid}     :帖子ID
        * @ {tsub}    :帖子标题
        * @ {tink}    :帖子链接
         *
        * T {stand}    :立场
         *
        * B {message}  :辩论词
        *
        * */
        'feed_vote'   => '中立',
        'feed_vote_1' => '红方',
        'feed_vote_2' => '蓝方',
        'feed_template_thread_debate_vote_title'   => '以 <i class="highlight-strong">{stand}</i> 立场加入了辩论 <a class="link ellipsis" href="{tlink}" target="_blank">{tsub}</a>',
        'feed_template_thread_debate_vote_body' => '
            <div class="feed-element-thread-debate-vote">
                <div class="content">{message}</div>
            </div>',


        /*
        * feed-thread-poll
        *
        * @ {tid}     :帖子ID
        * @ {tsub}    :帖子标题
        * @ {tink}    :帖子链接
         *
        * B {uid}      :用户ID
        * B {uname}    :用户名
        * B {ulink}    :用户空间链接
        * B {uavatar}  :用户头像源链接
         *
        * B {option}   :投票介绍
        * B {message}  :投票选项
        *
        * */
        'feed_template_thread_poll_title' => '发起投票<a class="link ellipsis" href="{tlink}" target="_blank">{tsub}</a>',
        'feed_template_thread_poll_body'  => '
            <div class="feed-element-thread-poll">
                <div class="content">{message}</div>
                <div class="options">本次投票有以下选项可选：<ul class="feed-element-poll-options">{option}</ul></div>
            </div>',


        /*
        * feed-thread-poll.vote
        *
        * @ {tid}     :帖子ID
        * @ {tsub}    :帖子标题
        * @ {tink}    :帖子链接
         *
        * B {uid}      :用户ID
        * B {uname}    :用户名
        * B {ulink}    :用户空间链接
        * B {uavatar}  :用户头像源链接
         *
        * B {option}   :投出的票
        *
        * */
        'feed_template_thread_poll_vote_title' => '参与投票<a class="link ellipsis" href="{tlink}" target="_blank">{tsub}</a>',
        'feed_template_thread_poll_vote_body'  => '
            <div class="feed-element-thread-poll-vote">
                <div class="options">我将选票投给了：<ul class="feed-element-poll-options">{option}</ul></div>
            </div>',

        'feed_template_thread_poll_vote_withimg_title' => '我参与了图片投票<a class="link ellipsis" href="{tlink}" target="_blank">{tsub}</a>',
        'feed_template_thread_poll_vote_withimg_body'  => '
            <div class="feed-element-thread-poll-vote">
                <div class="options">我将选票投给了：<ul class="feed-element-poll-options">{option}</ul></div>
            </div>',

        
        /*
        * feed-thread-activity
        *
        * @ {tid}     :帖子ID
        * @ {tsub}    :帖子标题
        * @ {tink}    :帖子链接
         *
        * B {uid}      :用户ID
        * B {uname}    :用户名
        * B {ulink}    :用户空间链接
        * B {uavatar}  :用户头像源链接
         *
        * B {starttime}    :活动开始时间
        * B {endtime}      :活动结束时间
        * B {endtimemark}  :活动结束时间标志
        * B {city}         :活动所在城市
        * B {location}     :活动地点
        * B {message}      :活动介绍
        *
        * */
        'feed_template_thread_activity_title' => '发起活动<a class="link ellipsis" href="{tlink}" target="_blank">{tsub}</a>',
        'feed_template_thread_activity_body'  => '
            <div class="feed-element-thread-activity">
                <div class="content">{message}</div>
                <div class="feed-element-thread-activity-info">
                    <div class="time"><i class="icon ft-clock"></i>时间：{starttime}<i class="time-E {endtimemark}"> - {endtime}</i></div>
                    <div class="loca"><i class="icon ft-compass"></i>地点：{city} - {location}</div>
                </div>
            </div>',

        /*
        * feed-thread-activity.reply
        *
        * @ {tid}     :帖子ID
        * @ {tsub}    :帖子标题
        * @ {tink}    :帖子链接
         *
        * B {message}   :参加理由
         *
        * B {starttime}    :活动开始时间
        * B {endtime}      :活动结束时间
        * B {endtimemark}  :活动结束时间标志
        * B {location}     :活动地点
        *
        * */
        'feed_template_thread_activity_reply_title' => '报名活动<a class="link ellipsis" href="{tlink}" target="_blank">{tsub}</a>',
        'feed_template_thread_activity_reply_body'  => '
            <div class="feed-element-thread-activity">
                <div class="content">{message}</div>
                <div class="feed-element-thread-activity-info">
                    <div class="time"><i class="icon ft-clock"></i>时间：{starttime}<i class="time-E {endtimemark}"> - {endtime}</i></div>
                    <div class="loca"><i class="icon ft-compass"></i>地点：{city} - {location}</div>
                </div>
            </div>',


        /*
        * feed-thread-goods
        *
        * B {tid}     :帖子ID
        * B {tsub}    :帖子标题
        * B {tink}    :商品链接
         *
        * B {itemname}    :商品名称
        * B {itemprice}   :商品售价-货币
        * B {itemcredit}  :商品售价-积分
        * B {creditunit}  :积分单位
        * B {message}     :商品摘要
        *
        * */
        'feed_template_thread_goods_title'  => '我挂起了一件商品',
        'feed_template_thread_goods_1_body' => '
            <div class="feed-element-thread-trade">
                <div class="goods-info">
                    <a class="goods-info-name" href="{tlink}" target="_blank">{itemname}</a>
                    <a class="goods-info-price" href="{tlink}" target="_blank">售价{itemprice}元，附加{itemcredit}{creditunit}</a>
                </div>
                <div class="content">{message}</div>
            </div>',
        'feed_template_thread_goods_2_body' => '
            <div class="feed-element-thread-trade">
                <div class="goods-info">
                    <a class="goods-info-name" href="{tlink}" target="_blank">{itemname}</a>
                    <a class="goods-info-price" href="{tlink}" target="_blank">售价{itemprice} 元</a>
                </div>
                <div class="content">{message}</div>
            </div>',
        'feed_template_thread_goods_3_body' => '
            <div class="feed-element-thread-trade">
                <div class="goods-info">
                    <a class="goods-info-name" href="{tlink}" target="_blank">{itemname}</a>
                    <a class="goods-info-price" href="{tlink}" target="_blank">售价{itemcredit}{creditunit}</a>
                </div>
                <div class="content">{message}</div>
            </div>',


        /*
        * feed-click.article
        *
        * @ {to_uid}      :用户ID
        * @ {to_uname}    :用户名
        * @ {to_ulink}    :用户空间链接
        * @ {to_uavatar}  :用户头像源链接
        * @ {article_url} :文章链接
        * @ {article_sub} :文章标题
         *
        * T {click}       :click类型
         *
        * B {article_content}  :文章内容截取
        * B {image}            :文章封面图
        *
        * */
        'feed_template_click_article_title' => '给<a class="link ellipsis" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>的文章<a class="link ellipsis" href="{article_url}" target="_blank">{article_sub}</a>送上了<i class="highlight-pink">「{click}」</i>',
        'feed_template_click_article_body'  => '
            <div class="feed-element-evaluate-article">
                <div class="detail">
                    <a class="subject ellipsis" href="{article_url}" target="_blank">{article_sub}</a>
                    <a class="user-tag ellipsis" href="{to_ulink}" target="_blank">
                        <s class="avatar"><img class="avatar-main" src="{to_uavatar}"></s>
                        <s class="username">{to_uname}</s>
                    </a>
                    <div class="content">{article_content}</div>
                </div>
            </div>',

        'feed_template_click_article_withimg_title' => '给<a class="link ellipsis" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>的文章<a class="link ellipsis" href="{article_url}" target="_blank">{article_sub}</a>送上了<i class="highlight-pink">「{click}」</i>',
        'feed_template_click_article_withimg_body'  => '
            <div class="feed-element-evaluate-article withimg">
                <div class="image rec-img" style="background-image: url(\'{image}\')">
                    <img src="' . LIBURL . '/img/row-e-col/1.1.png">
                </div>
                <div class="detail">
                    <a class="subject ellipsis" href="{article_url}" target="_blank">{article_sub}</a>
                    <a class="user-tag ellipsis" href="{to_ulink}" target="_blank">
                        <s class="avatar"><img class="avatar-main" src="{to_uavatar}"></s>
                        <s class="username">{to_uname}</s>
                    </a>
                    <div class="content">{article_content}</div>
                </div>
            </div>',


        /*
        * feed-click.blog
        *
        * @ {to_uid}      :用户ID
        * @ {to_uname}    :用户名
        * @ {to_ulink}    :用户空间链接
        * @ {to_uavatar}  :用户头像源链接
        * @ {blog_url}    :博客链接
        * @ {blog_sub}    :博客标题
         *
        * T {click}       :click类型
         *
        * B {blog_content}  :博客内容截取
        * B {image}         :博客封面图
        *
        * */
        'feed_template_click_blog_title' => '给<a class="link ellipsis" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>的日志<a class="link ellipsis" href="{blog_url}" target="_blank">{blog_sub}</a>送上了<i class="highlight-pink">「{click}」</i>',
        'feed_template_click_blog_body'  => '
            <div class="feed-element-evaluate-article">
                <div class="detail">
                    <a class="subject ellipsis" href="{blog_url}" target="_blank">{blog_sub}</a>
                    <a class="user-tag ellipsis" href="{to_ulink}" target="_blank">
                        <s class="avatar"><img class="avatar-main" src="{to_uavatar}"></s>
                        <s class="username">{to_uname}</s>
                    </a>
                    <div class="content">{blog_content}</div>
                </div>
            </div>',

        'feed_template_click_blog_withimg_title' => '给<a class="link ellipsis" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>的日志<a class="link ellipsis" href="{blog_url}" target="_blank">{blog_sub}</a>送上了<i class="highlight-pink">「{click}」</i>',
        'feed_template_click_blog_withimg_body'  => '
            <div class="feed-element-evaluate-article withimg">
                <div class="image rec-img" style="background-image: url(\'{image}\')">
                    <img src="' . LIBURL . '/img/row-e-col/1.1.png">
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
        * feed-click.image
        *
        * @ {to_uid}      :用户ID
        * @ {to_uname}    :用户名
        * @ {to_ulink}    :用户空间链接
        * @ {to_uavatar}  :用户头像源链接
        * @ {image}       :图像名称
        * @ {image_togo}  :图像来源
        *
         *
        * T {click}       :click类型
         *
        * B {image_link}  :图像源链接
        *
        * */
        'feed_template_click_pic_title'  => '给<a class="link ellipsis" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>的图片<a class="link ellipsis" href="{image_togo}" target="_blank">{image}</a> 送上了<i class="highlight-pink">「{click}」</i>',
        'feed_template_click_pic_body'   => '
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
        * feed-comment.space.wall
        *
        * T {to_uid}      :用户ID
        * T {to_uname}    :用户名
        * T {to_ulink}    :用户空间链接
        * T {to_uavatar}  :用户头像源链接
        *
        * */
        'feed_template_comment_space_title' => '在<a class="link ellipsis" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>的空间留言道',
        

        /*
        * feed-comment.blog
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
        'feed_template_comment_blog_title' => '评论了<a class="link ellipsis" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>的日志<a class="link ellipsis" href="{blog_url}" target="_blank">{blog_sub}</a>',
        'feed_template_comment_blog_body'  => '
            <div class="feed-element-evaluate-article">
                <div class="detail">
                    <a class="subject ellipsis" href="{blog_url}" target="_blank">{blog_sub}</a>
                    <a class="user-tag ellipsis" href="{to_ulink}" target="_blank">
                        <s class="avatar"><img class="avatar-main" src="{to_uavatar}"></s>
                        <s class="username">{to_uname}</s>
                    </a>
                    <div class="content">{blog_content}</div>
                </div>
            </div>',

        'feed_template_comment_blog_withimg_title' => '评论了<a class="link ellipsis" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>的日志<a class="link ellipsis" href="{blog_url}" target="_blank">{blog_sub}</a>',
        'feed_template_comment_blog_withimg_body'  => '
            <div class="feed-element-evaluate-article withimg">
                <div class="image rec-img" style="background-image: url(\'{image}\')">
                    <img src="' . LIBURL . '/img/row-e-col/1.1.png">
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
        * feed-comment.share
        *
        * T {to_uid}      :用户ID
        * T {to_uname}    :用户名
        * T {to_ulink}    :用户空间链接
        * T {to_uavatar}  :用户头像源链接
         *
        * T {share_url}   :分享链接
        * T {share_act}   :分享名
        *
        * */
        'feed_template_comment_share_title' => '评论了<a class="link ellipsis" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>分享的<a class="link ellipsis" href="{share_url}" target="_blank">{share_act}</a>',


        /*
        * feed-comment.image
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
        'feed_template_comment_image_title' => '评论了<a class="link ellipsis" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>的图片',
        'feed_template_comment_image_body'  => '
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
        * feed-space.blog
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
            <div class="feed-element-article">
                <a class="subject ellipsis" href="{url}" target="_blank">{subject}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{content}</div>
            </div>',

        'feed_template_blog_passwd_withimg_title' => '更新了加密日志 <i class="tag passwd mt-lock"></i>',
        'feed_template_blog_passwd_withimg_body'  => '
            <div class="feed-element-article">
                <div class="image"><img src="{image}"></div>
                <a class="subject ellipsis" href="{url}" target="_blank">{subject}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{content}</div>
            </div>',

        'feed_template_blog_title' => '更新了日志',
        'feed_template_blog_body'  => '
            <div class="feed-element-article">
                <a class="subject ellipsis" href="{url}" target="_blank">{subject}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{content}</div>
            </div>',

        'feed_template_blog_withimg_title' => '更新了日志',
        'feed_template_blog_withimg_body'  => '
            <div class="feed-element-article">
                <div class="image"><img src="{image}"></div>
                <a class="subject ellipsis" href="{url}" target="_blank">{subject}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{content}</div>
            </div>',


        /*
        * feed-space.album
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
        * feed-space.pic
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
        'feed_template_pic_title' => '图片 <a class="link ellipsis" href="{url}" target="_blank">{image}</a> 受到关注',
        'feed_template_pic_body'  => '
            <div class="feed-element-image">
                <a class="image" href="{image_togo}" target="_blank">
                    <img src="{image_link}" />
                </a>
                <a class="user-tag" href="{user_link}" target="_blank" c="1">
                    <s class="avatar"><img class="avatar-main" src="{user_avatar}"></s>
                    <s class="username">{username}</s>
                </a>
            </div>',

        
        /*
        * feed-space.doing
        *
        * */
        'feed_template_doing_title' => '更新了记录',

        
        /*
        * feed-space.profile
        *
        * */
        'feed_template_profile_title' => '更新了个人资料',
        'feed_profile_update_base'    => '我刚刚更新了自己的基本资料',
        'feed_profile_update_contact' => '我刚刚更新了自己的联系方式',
        'feed_profile_update_edu'     => '我刚刚更新了自己的教育情况',
        'feed_profile_update_work'    => '我刚刚更新了自己的工作信息',
        'feed_profile_update_info'    => '我刚刚更新了自己的个人信息',
        'feed_profile_update_bbs'     => '我刚刚更新了自己的论坛信息',
        'feed_profile_update_verify'  => '我刚刚更新了自己的认证信息',

        
        /*
        * feed-magic.thunder
        *
        * @ {uid}         :用户ID
        * @ {username}    :用户名
         *
        * B {user_avatar}  :用户头像源链接
        *
        * */
        'feed_template_magic_thunder_title' => '<a class="link ellipsis" href="home.php?mod=space&uid={uid}" style="margin-left: 0" target="_blank" c="1">{username}</a> 发出了“雷鸣之声”',
        'feed_template_magic_thunder_body'  => '
            <div class="feed-element-magic-thunder">
                <a class="avatar" href="home.php?mod=space&uid={uid}" target="_blank"><img class="avatar-main" src="{user_avatar}"></a>
                <i class="hello">初来乍到，请多多指教！我是 {username}</i>
            </div>',

        
        /*
        * feed-ranklist-show
        *
        * @ {credit}      :新增点数
         *
        * @ {uid}      :用户ID
        * @ {uname}    :用户名
        * @ {ulink}    :用户空间链接
        * @ {uavatar}  :用户头像源链接
        *
        * */
        'feed_template_showcredit_title'      => '给<a class="link ellipsis" href="{ulink}" target="_blank" c="1">{uname}</a>充电<i class="highlight-gold">{credit}</i>，助力<a class="link ellipsis" href="misc.php?mod=ranklist&type=member" target="_blank">续航榜</a>排名',
        'feed_template_showcredit_self_title' => '自充电<i class="highlight-gold">{credit}</i>，提升了<a class="link ellipsis" href="misc.php?mod=ranklist&type=member" target="_blank">续航榜</a>名次',
        'feed_template_showcredit_body'       => '
            <div class="feed-element-charge">
                <div class="feed-element-charge-decorator">
                    <img class="background" src="'.IMGURL.'/feed/feed-element-decoration/电力充足.svg">
                </div>
                <a class="user-tag" href="{ulink}" target="_blank" >
                    <s class="avatar"><img class="avatar-main" src="{uavatar}"></s>
                    <s class="username ellipsis">{uname}</s>
                </a>
            </div>',

        
        /*
        * feed-friden
        *
        * @ {to_uid}      :好友ID
        * @ {to_uname}    :好友名
        * @ {to_ulink}    :好友空间链接
        * @ {to_uavatar}  :好友头像源链接
         *
        * @ {uid}      :用户ID
        * @ {uname}    :用户名
        * @ {ulink}    :用户空间链接
        * @ {uavatar}  :用户头像源链接
        *
        * */
        'feed_template_invite_title'     => '邀请了<a class="link ellipsis" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>并与之成为好友',
        'feed_template_friend_title'     => '我和<a class="link ellipsis" href="{to_ulink}" target="_blank" c="1">{to_uname}</a>成为了好友',
        'feed_template_friend_body'      => '
            <div class="feed-element-friend">
                <div class="friend-A">
                    <a class="avatar" href="{ulink}" target="_blank"><img class="avatar-main" src="{uavatar}"></a>
                    <span class="talk-bubble">
                        <img class="bubble" src="'.IMGURL.'/feed/feed-element-decoration/talk-bubble-square-1.svg" />
                        <img class="emotion" src="'.IMGURL.'/expression/nekopara/note_mark.png" />
                        <s class="username">{uname}</s>
                    </span>
                </div>
                <div class="friend-B">
                    <a class="avatar" href="{to_ulink}" target="_blank"><img class="avatar-main" src="{to_uavatar}"></a>
                    <span class="talk-bubble">
                        <img class="bubble" src="'.IMGURL.'/feed/feed-element-decoration/talk-bubble-square-2.svg" />
                        <img class="emotion" src="'.IMGURL.'/expression/nekopara/exclamation_mark.png" />
                        <s class="username">{to_uname}</s>
                    </span>
                </div>
            </div>',
    ];