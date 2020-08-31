<?php
    
    if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    
    $lang = [
        /*
         * share-link
         *
         * B {url}  :网址
         * B {name} :网址名称
         *
         * */
        'share_title_template_link' => '分享了网址',
        'share_body_template_link' =>	'
            <div class="share-element-link icon-link">
                <a href="{url}">{name}</a>
            </div>',
        
        
        /*
        * share-music
        *
        * B {url}   :音乐文件源链接
        * B {{name} :音乐名
        *
        * */
        'share_title_template_music' => '分享了音乐',
        'share_body_template_music' => '
            <a class="share-element-music" onclick="audioload(\'{url}\')">
                <i class="icon-play2"></i>{name}
            </a>',

        
        /*
        * share-media
        *
        * B {url}:iframe媒体源链接
        *
        * */
        'share_title_template_iframe' => '分享了媒体',
        'share_body_template_iframe' =>'
            <div class="share-element-iframe">
                <iframe src="{url}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>',

        
        /*
        * share-video
        *
        * B {url}:视频文件源链接
        *
        * */
        'share_title_template_video' => '分享了视频',
        'share_body_template_video' =>	'
            <div class="share-element-video">
                <video src="{url}" controls="controls">您的浏览器不支持 video 标签。</video>
            </div>',

        
        /*
        * share-pic
        *
        * B {url}  :图片源链接
        * B {name} :图片名称
        *
        * */
        'share_title_template_pic' => '分享了图片',
        'share_body_template_pic' =>	'
            <div class="share-element-image">
                <i>{name}</i>
                <a class="image" href="{url}" target="_blank"><img src="{url}" /></a>
            </div>',

        
        /*
        * share-album
        *
        * B {album}        :画廊名称
        * B {album_link}   :画廊地址
        * B {image_link}   :画廊封面图源链接
         *
        * B {owner}        :用户名
        * B {owner_link}   :用户空间链接
        * B {owner_avatar} :用户头像链接
        *
        * */
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

        
        /*
        * share-album-img
        *
        * B {album}        :画廊名称
        * B {album_link}   :画廊地址
         *
        * B {image}        :图像名称
        * B {image_link}   :图像源链接
        * B {image_togo}   :图像来源
         *
        * B {owner_link}   :用户空间链接
        * B {owner_avatar} :用户头像链接
        * B {owner}        :用户名
        *
        * */
        'share_title_template_album_pic' => '分享了画廊图片',
        'share_body_template_album_pic' => '
            <div class="share-element-image">
                <div class="album-pic-info">
                    <a class="username ellipsis" href="{owner_link}" target="_blank" c="1"><img src="{owner_avatar}">{owner}</a> 的画廊 <a class="albumname ellipsis" href="{album_link}" target="_blank">&nbsp;{album}</a> 中的图片
                </div>
                <i class="ellipsis">{image}</i>
                <a class="image" href="{image_togo}" target="_blank">
                    <img src="{image_link}" />
                </a>
            </div>',
        

        /*
        * share-blog
        *
        * B {url}       :博客链接
        * B {subject}   :博客标题
        * B {content}   :内容截取
        * B {image}     :博客封面图源链接
         *
        * B {user_link}   :用户空间链接
        * B {user_avatar} :用户头像链接
        * B {username}    :用户名
        *
        * */
        'share_title_template_blog' => '分享了日志',
        'share_body_template_blog' =>	'
            <div class="share-element-article">
                <a class="subject ellipsis" href="{url}" target="_blank">{subject}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{content}</div>
            </div>',
        
        'share_title_template_blog_withimg' => '分享了日志',
        'share_body_template_blog_withimg' =>	'
            <div class="share-element-article">
                <div class="image"><img src="{image}"></div>
                <a class="subject ellipsis" href="{url}" target="_blank">{subject}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{content}</div>
            </div>',

        
        /*
        * share-article
        *
        * B {url}       :文章链接
        * B {title}     :文章标题
        * B {summary}   :概述
        * B {image}     :文章封面图源链接
         *
        * B {user_link}   :用户空间链接
        * B {user_avatar} :用户头像链接
        * B {username}    :用户名
        *
        * */
        'share_title_template_article' => '分享了文章',
        'share_body_template_article' =>	'
            <div class="share-element-article">
                <a class="subject ellipsis" href="{url}" target="_blank">{title}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{summary}</div>
            </div>',

        'share_title_template_article_withimg' => '分享了文章',
        'share_body_template_article_withimg' =>	'
            <div class="share-element-article">
                <div class="image"><img src="{image}"></div>
                <a class="subject ellipsis" href="{url}" target="_blank">{title}</a>
                <a class="author ellipsis" href="{user_link}" target="_blank" c="1"><img src="{user_avatar}">{username}</a>
                <div class="content">{summary}</div>
            </div>',


        /*
        * share-thread
        *
        * B {url}       :帖子链接
        * B {subject}   :帖子标题
        * B {message}   :帖子内容截取
        * B {image}     :帖子内图片源链接
         *
        * B {author_link}   :用户空间链接
        * B {author_avatar} :用户头像链接
        * B {author}        :用户名
        *
        * */
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


        /*
        * share-space
        *
        * B {userlink}   :用户空间链接
        * B {avatar}     :用户头像链接
        * B {reside}     :用户居住地
        * B {username}   :用户名
        * B {spacenote}  :空间自述
        *
        * */
        'share_title_template_space' => '分享了用户',
        'share_body_template_space' => '
            <a class="share-element-space" href="{userlink}" title="{reside}" target="_blank" c="1">
                <div class="decrater"></div>
                <s class="avatar"><img class="avatar-main" src="{avatar}"></s>
                <div class="info">
                    <s class="username ellipsis">{username}</s>
                    <s class="space-note">{spacenote}</s>
                </div>
            </a>',
    ];