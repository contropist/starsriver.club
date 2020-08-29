<?php
    
    if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    
    $lang = [
        'share_title_template_link' => '分享了网址',
        'share_body_template_link' =>	'
            <div class="share-element-link icon-link">
                <a href="{url}">{name}</a>
            </div>',
        
        'share_title_template_music' => '分享了音乐',
        'share_body_template_music' => '
            <a class="share-element-music" onclick="audioload(\'{url}\')">
                <i class="icon-play2"></i>{name}
            </a>',
        
        'share_title_template_iframe' => '分享了媒体',
        'share_body_template_iframe' =>'
            <div class="share-element-iframe">
                <iframe src="{url}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>',
        
        'share_title_template_video' => '分享了视频',
        'share_body_template_video' =>	'
            <div class="share-element-video">
                <video src="{url}" controls="controls">您的浏览器不支持 video 标签。</video>
            </div>',
        
        'share_title_template_pic' => '分享了图片',
        'share_body_template_pic' =>	'
            <div class="share-element-image">
                <a class="image" href="{url}" target="_blank"><img src="{url}" /></a>
                <i>{name}</i>
            </div>',
        
        'share_title_template_album' => '分享了画廊',
        'share_body_template_album' =>	'
            <div class="share-element-album">
                <a class="image" href="{album_link}" target="_blank">
                    <img src="{image_link}">
                </a>
                <div class="album-info">
                    <a class="album-name ellipsis" href="{album_link}" target="_blank">{album}</a>
                    <a class="owner-name ellipsis" href="{owner_link}" target="_blank" c="1"><img src="{owner_avatar}">{owner}</a>
                </div>
                <p class="album-desc">{album_desc}</p>
            </div>',
        
        'share_title_template_album_pic' => '分享了画廊图片',
        'share_body_template_album_pic' =>	'
            <div class="share-element-image">
                <a class="image" href="{image_togo}" target="_blank">
                    <img src="{image_link}" />
                </a>
                <i class="ellipsis">图像：{image}</i>
                <div class="album-pic-info">
                    来源：<a class="username ellipsis" href="{owner_link}" target="_blank" c="1"><img src="{owner_avatar}">{owner}</a> 的画廊 <a class="albumname ellipsis" href="{album_link}" target="_blank">{album}</a>
                </div>
            </div>',
        
        'share_title_template_article' => '分享了文章',
        'share_body_template_article' =>	'
            <div class="share-element-article">
                <a class="title ellipsis" href="{url}" target="_blank">{title}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{summary}</div>
            </div>',
        
        'share_title_template_article_withimg' => '分享了文章',
        'share_body_template_article_withimg' =>	'
            <div class="share-element-article">
                <div class="image"><img src="{image}"></div>
                <a class="title ellipsis" href="{url}" target="_blank">{title}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{summary}</div>
            </div>',
        
        'share_title_template_blog' => '分享了日志',
        'share_body_template_blog' =>	'
            <div class="share-element-blog">
                <a class="subject ellipsis" href="{url}" target="_blank">{subject}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{content}</div>
            </div>',
        
        'share_title_template_blog_withimg' => '分享了日志',
        'share_body_template_blog_withimg' =>	'
            <div class="share-element-blog">
                <div class="image"><img src="{image}"></div>
                <a class="subject ellipsis" href="{url}" target="_blank">{subject}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{content}</div>
            </div>',
        
        'share_title_template_thread' => '分享了帖子',
        'share_body_template_thread' => '
            <div class="share-element-thread">
                <div class="headline">
                    <a class="author ellipsis" href="{author_link}" target="_blank" c="1"><img src="{author_avatar}">{author}</a> 在主题帖 <a class="subject ellipsis" href="{url}" target="_blank">{subject}</a> 中说到
                </div>
                <div class="content">
                    <div class="text">{message}</div>
                </div>
            </div>',
        
        'share_title_template_thread_withimg' => '分享了帖子',
        'share_body_template_thread_withimg' => '
            <div class="share-element-thread">
                <div class="headline">
                    <a class="author ellipsis" href="{author_link}" target="_blank" c="1"><img src="{author_avatar}">{author}</a> 在主题帖 <a class="subject ellipsis" href="{url}" target="_blank">{subject}</a> 中说到
                </div>
                <div class="content">
                    <div class="text">
                        {message}
                    </div>
                    <div class="imgs">
                        <img src="{image}">
                    </div>
                </div>
            </div>',
        
        'share_title_template_space' => '分享了用户',
        'share_body_template_space' =>	'
            <a class="share-element-space" href="{userlink}" title="{reside}" target="_blank" c="1">
                <div class="decrater"></div>
                <s class="avatar"><img class="avatar-main" src="{avatar}"></s>
                <div class="info">
                    <s class="username ellipsis">{username}</s>
                    <s class="space-note">{spacenote}</s>
                </div>
            </a>',
    ];