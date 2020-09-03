<?php

    if (!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    
    global $_G;
    
    $clickid = empty($_GET['clickid']) ? 0 : intval($_GET['clickid']);
    $idtype = empty($_GET['idtype']) ? '' : trim($_GET['idtype']);
    $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
    
    loadcache('click');
    $clicks = empty($_G['cache']['click'][$idtype]) ? [] : $_G['cache']['click'][$idtype];
    $click = $clicks[$clickid];
    
    if (empty($click)) {
        showmessage('click_error');
    }
    
    switch ($idtype) {
        case 'picid':
            $item = C::t('home_pic')->fetch($id);
            if ($item) {
                $picfield = C::t('home_picfield')->fetch($id);
                $album = C::t('home_album')->fetch($item['albumid']);
                $item['hotuser'] = $picfield['hotuser'];
                $item['friend'] = $album['friend'];
                $item['username'] = $album['username'];
            }
            $tablename = 'home_pic';
            break;
            
        case 'aid':
            $item = array_merge(
                C::t('portal_article_title')->fetch($id),
                C::t('portal_article_content')->fetch($id)
            );
            $tablename = 'portal_article_title';
            break;
            
        default:
            $idtype = 'blogid';
            $item = array_merge(
                C::t('home_blog')->fetch($id),
                C::t('home_blogfield')->fetch($id)
            );
            $tablename = 'home_blog';
            break;
    }
    if (!$item) {
        showmessage('click_item_error');
    }
    
    $hash = md5($item['uid'] . "\t" . $item['dateline']);
    
    if ($_GET['op'] == 'add') {
        
        if (!checkperm('allowclick') || $_GET['hash'] != $hash) {
            showmessage('no_privilege_click');
        }
        
        if ($item['uid'] == $_G['uid']) {
            showmessage('click_no_self');
        }
        
        if (isblacklist($item['uid'])) {
            showmessage('is_blacklist');
        }
        
        if (C::t('home_clickuser')->count_by_uid_id_idtype($space['uid'], $id, $idtype)) {
            showmessage('click_have');
        }
        
        $setarr = [
            'uid'      => $space['uid'],
            'username' => $_G['username'],
            'id'       => $id,
            'idtype'   => $idtype,
            'clickid'  => $clickid,
            'dateline' => $_G['timestamp'],
        ];
        
        C::t('home_clickuser')->insert($setarr);
        
        C::t($tablename)->update_click($id, $clickid, 1);
        
        hot_update($idtype, $id, $item['hotuser']);
        
        $q_note = '';
        $q_note_values = [];
        
        $useravatar = avatar($item['uid'],'',true);
        
        switch ($idtype) {
            case 'blogid':
                
                $blogurl = 'home.php?mod=space&uid=' . $item['uid'] . '&do=blog&id=' . $item['blogid'];
                
                $fs = [
                    'title_template' => 'click_blog',
                    'title_data'     => [
                        'to_uid'     => $item['uid'],
                        'to_uname'   => $item['username'],
                        'to_ulink'   => 'home.php?mod=space&uid=' . $item['uid'],
                        'to_uavatar' => $useravatar,
                        
                        'blog_url'   => $blogurl,
                        'blog_sub'   => $item['subject'],
                    ],
                    'body_template'  => 'click_blog',
                    'body_data'      => [
                        'to_uid'       => $item['uid'],
                        'to_uname'     => $item['username'],
                        'to_ulink'     => 'home.php?mod=space&uid=' . $item['uid'],
                        'to_uavatar'   => $useravatar,
                        
                        'blog_url'     => $blogurl,
                        'blog_sub'     => $item['subject'],
                        'blog_content' => getstr($item['message'], 50, 0, 0, 0, -1),

                        'expend0' => '',
                        'expend1' => '',
                        'expend2' => '',
                        'expend3' => '',
                        'expend4' => '',
                        'expend5' => '',
                        'expend6' => '',
                        'expend7' => '',
                    ],
                ];
   
                if(!empty($item['pic'])){
                    $fs['body_data']['retemplate'] = 'click_blog_withimg';
                    $fs['body_data']['image'] = pic_cover_get($item['pic'], $item['picflag']);
                }
                
                $q_note = 'click_blog';
                $q_note_values = [
                    'url'         => "home.php?mod=space&uid=$item[uid]&do=blog&id=$item[blogid]",
                    'subject'     => $item['subject'],
                    'from_id'     => $item['blogid'],
                    'from_idtype' => 'blogid',
                ];
                break;
            
            case 'aid':
                require_once libfile('function/portal');
                $article_url = fetch_article_url($item);
    
                $fs = [
                    'title_template' => 'click_article',
                    'title_data'     => [
                        'to_uid'     => $item['uid'],
                        'to_uname'   => $item['username'],
                        'to_ulink'   => 'home.php?mod=space&uid=' . $item['uid'],
                        'to_uavatar' => $useravatar,
            
                        'article_url'     => $article_url,
                        'article_subject' => $item['title'],
                    ],
                    'body_template'  => 'click_article',
                    'body_data'      => [
                        'to_uid'     => $item['uid'],
                        'to_uname'   => $item['username'],
                        'to_ulink'   => 'home.php?mod=space&uid=' . $item['uid'],
                        'to_uavatar' => $useravatar,
            
                        'article_url'     => $article_url,
                        'article_subject' => $item['title'],
                        'article_content' => getstr($item['content'], 50, 0, 0, 0, -1),

                        'expend0' => '',
                        'expend1' => '',
                        'expend2' => '',
                        'expend3' => '',
                        'expend4' => '',
                        'expend5' => '',
                        'expend6' => '',
                        'expend7' => '',
                    ],
                ];
    
                if(!empty($item['pic'])){
                    $fs['body_data']['retemplate'] = 'click_article_withimg';
                    $fs['body_data']['image'] = pic_get($item['pic'], 'portal', $item['thumb'], $item['remote'], 1, 1);
                }

                $q_note = 'click_article';
                $q_note_values = [
                    'url'         => $article_url,
                    'subject'     => $item['title'],
                    'from_id'     => $item['aid'],
                    'from_idtype' => 'aid',
                ];
                break;
    
            case 'picid':
                $fs = [
                    'title_template' => 'click_pic',
                    'title_data'     => [
                        'to_uid'     => $item['uid'],
                        'to_uname'   => $item['username'],
                        'to_ulink'   => 'home.php?mod=space&uid=' . $item['uid'],
                        'to_uavatar' => $useravatar,
                        
                        'image'      => $item['title'] ? $item['title'] : $item['filename'],
                        'image_togo' => "home.php?mod=space&uid=$item[uid]&do=album&picid=$item[picid]",
            
                    ],
                    'body_template'  => 'click_pic',
                    'body_data'      => [
                        'to_uid'     => $item['uid'],
                        'to_uname'   => $item['username'],
                        'to_ulink'   => 'home.php?mod=space&uid=' . $item['uid'],
                        'to_uavatar' => $useravatar,
                
                        'image'      => $item['title'] ? $item['title'] : $item['filename'],
                        'image_link' => pic_get($item['filepath'], 'album', $item['thumb'], $item['remote']),
                        'image_togo' => "home.php?mod=space&uid=$item[uid]&do=album&picid=$item[picid]",

                        'expend0' => '',
                        'expend1' => '',
                        'expend2' => '',
                        'expend3' => '',
                        'expend4' => '',
                        'expend5' => '',
                        'expend6' => '',
                        'expend7' => '',
                    ],
                ];
                
                $q_note = 'click_pic';
                $q_note_values = [
                    'url'         => "home.php?mod=space&uid=$item[uid]&do=album&picid=$item[picid]",
                    'from_id'     => $item['picid'],
                    'from_idtype' => 'picid',
                ];
                break;
        }
    
        $fs['title_data']['click'] = $click['name'];
        $fs['icon'] = 'click';
        $fs['id'] = $id;
        $fs['idtype'] = $idtype;
        
        if (empty($item['friend']) && ckprivacy('click', 'feed')) {
            $fs['title_data']['hash_data'] = "{$idtype}{$id}";
            require_once libfile('function/feed');
            feed_add($fs);
        }
        
        updatecreditbyaction('click', 0, [], $idtype . $id);
        
        require_once libfile('function/stat');
        updatestat('click');
        
        notification_add($item['uid'], 'click', $q_note, $q_note_values);
        
        showmessage('click_success', '', [
            'idtype'  => $idtype,
            'id'      => $id,
            'clickid' => $clickid,
        ], [
            'msgtype'   => 3,
            'showmsg'   => true,
            'closetime' => true,
        ]);
        
    } elseif ($_GET['op'] == 'show') {
        
        $maxclicknum = 0;
        foreach ($clicks as $key => $value) {
            $value['clicknum'] = $item["click{$key}"];
            $value['classid'] = rand(1, 4);
            if ($value['clicknum'] > $maxclicknum)
                $maxclicknum = $value['clicknum'];
            $clicks[$key] = $value;
        }
        
        $perpage = 18;
        $page = intval($_GET['page']);
        $start = ($page - 1) * $perpage;
        if ($start < 0)
            $start = 0;
        
        $count = C::t('home_clickuser')->count_by_id_idtype($id, $idtype);
        $clickuserlist = [];
        $click_multi = '';
        
        if ($count) {
            foreach (C::t('home_clickuser')->fetch_all_by_id_idtype($id, $idtype, $start, $perpage) as $value) {
                $value['clickname'] = $clicks[$value['clickid']]['name'];
                $clickuserlist[] = $value;
            }
            
            $click_multi = multi($count, $perpage, $page, "home.php?mod=spacecp&ac=click&op=show&clickid=$clickid&idtype=$idtype&id=$id");
        }
    }
    
    include_once(template('home/spacecp_click'));