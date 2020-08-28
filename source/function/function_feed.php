<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: function_feed.php 28299 2012-02-27 08:48:36Z svn_project_zhangjie $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function feed_add($arr = []) {
    
    global $_G;
    
    if (!helper_access::check_module('feed') || empty($arr)) {
        return false;
    }
    
    $data = [
        'id'                    => !empty($arr['id']) ? $arr['id'] : 0,
        'idtype'                => !empty($arr['idtype']) ? $arr['idtype'] : '',
        'target_ids'            => !empty($arr['target_ids']) ? $arr['target_ids'] : '',
        'uid'                   => !empty($arr['uid']) ? $arr['uid'] : 0,
        'username'              => !empty($arr['username']) ? $arr['username'] : '',
        'icon'                  => !empty($arr['icon']) ? $arr['icon'] : '',
        'type'                  => !empty($arr['type']) ? $arr['type'] : '',
        'title_template'        => !empty($arr['title_template']) ? $arr['title_template'] : '',
        'title_data'            => !empty($arr['title_data']) ? $arr['title_data'] : [],
        'body_template'         => !empty($arr['body_template']) ? $arr['body_template'] : '',
        'body_data'             => !empty($arr['body_data']) ? $arr['body_data'] : [],
        'body_general'          => !empty($arr['body_general']) ? $arr['body_general'] : [],
        'images'                => !empty($arr['images']) ? $arr['images'] : [],
        'images_link'           => !empty($arr['images_link']) ? $arr['images_link'] : [],
        'friend'                => !empty($arr['friend']) ? $arr['friend'] : '',
        'returnid'              => !empty($arr['returnid']) ? $arr['returnid'] : 0,
    ];
    
    $data['title_template'] = $data['title_template'] ? lang('feed', $data['title_template']) : '';
    $data['body_template'] = $data['body_template'] ? lang('feed', $data['body_template']) : '';
    
    if (empty($data['uid']) || empty($data['username'])) {
        $data['uid'] = $data['username'] = '';
    }
    
	foreach ($data['images_link'] as $key => $v){
	    if(!empty($data['images_link'][$key])){
            $imgs[] = [
                'url' => $data['images_link'][$key],
                'name' => $data['images'][$key],
            ];
        }
    }
	
    $feedarr = [
        'id' => $data['id'],
        'idtype' => $data['idtype'],
        'target_ids' => $data['target_ids'],
        'friend' => $data['friend'],
        'icon' => $data['icon'],
        'type' => $data['type'],
        'hash_data' => empty($data['title_data']['hash_data'])?'': $data['title_data']['hash_data'],
        'uid' => $data['uid'] ? intval($data['uid']) : $_G['uid'],
        'username' => $data['username'] ? $data['username'] : $_G['username'],
        'dateline' => $_G['timestamp'],
        'body_data' => serialize($data['body_data']),
        'body_template' => $data['body_template'],
        'title_data' => serialize($data['title_data']),
        'title_template' => $data['title_template'],
        'body_general_template' => $data['body_general_template'],
        'body_general' => $data['body_general'],
        'image_1' => empty($data['images'][0]) ? '' : $data['images'][0],
        'image_1_link' => empty($data['images_link'][0]) ? '' : $data['images_link'][0],
        'image_2' => empty($data['images'][1]) ? '' : $data['images'][1],
        'image_2_link' => empty($data['images_link'][1]) ? '' : $data['images_link'][1],
        'image_3' => empty($data['images'][2]) ? '' : $data['images'][2],
        'image_3_link' => empty($data['images_link'][2]) ? '' : $data['images_link'][2],
        'image_4' => empty($data['images'][3]) ? '' : $data['images'][3],
        'image_4_link' => empty($data['images_link'][3]) ? '' : $data['images_link'][3],
    ];
	
    if($feedarr['hash_data']) {
        $oldfeed = C::t('home_feed')->fetch_feedid_by_hashdata($feedarr['uid'], $feedarr['hash_data']);
        if($oldfeed) {
            return 0;
        }
    }

	return C::t('home_feed')->insert($feedarr, $data['returnid']);
}

function mkfeed($feed, $actors=[]) {
	global $_G;
	
	if($feed['icon'] == 'share'){
        require_once libfile('function/share');
        $feed = mkshare($feed);
    } else {
        $feed['title_data'] = empty($feed['title_data']) ? [] : (is_array($feed['title_data']) ? $feed['title_data'] : @dunserialize($feed['title_data']));
        $feed['body_data'] = empty($feed['body_data']) ? [] : (is_array($feed['body_data']) ? $feed['body_data'] : @dunserialize($feed['body_data']));
        
        if (!is_array($feed['title_data'])) $feed['title_data'] = [];
        if(!is_array($feed['body_data'])) $feed['body_data'] = [];
        
        $searchs = $replaces = [];
        if($feed['title_data']) {
            foreach (array_keys($feed['title_data']) as $key) {
                $searchs[] = '{'.$key.'}';
                $replaces[] = $feed['title_data'][$key];
            }
        }
        
        $searchs[] = '{actor}';
        $replaces[] = empty($actors)?"<a href=\"home.php?mod=space&uid=$feed[uid]\" target=\"_blank\">$feed[username]</a>":implode(lang('core', 'dot'), $actors);
        $feed['title_template'] = str_replace($searchs, $replaces, $feed['title_template']);
        $feed['title_template'] = feed_mktarget($feed['title_template']);
        
        $searchs = $replaces = [];
        $searchs[] = '{actor}';
        $replaces[] = empty($actors)?"<a href=\"home.php?mod=space&uid=$feed[uid]\" target=\"_blank\">$feed[username]</a>":implode(lang('core', 'dot'), $actors);
        
        if($feed['body_data'] && is_array($feed['body_data'])) {
            foreach (array_keys($feed['body_data']) as $key) {
                $searchs[] = '{'.$key.'}';
                $replaces[] = $feed['body_data'][$key];
            }
        }
        
        $feed['magic_class'] = '';
        if(!empty($feed['body_data']['magic_thunder'])) {
            $feed['magic_class'] = 'magicthunder';
        }
        
        $feed['body_template'] = str_replace($searchs, $replaces, $feed['body_template']);
        $feed['body_template'] = feed_mktarget($feed['body_template']);
        
        $feed['body_general'] = feed_mktarget($feed['body_general']);
        
        
    }
    
    $feed['icon_image'] = STATICURL."image/feed/{$feed['icon']}.gif";
    
    $feed['new'] = 0;
    if($_G['cookie']['home_readfeed'] && $feed['dateline']+300 > $_G['cookie']['home_readfeed']) {
        $feed['new'] = 1;
    }

	return $feed;
}

function feed_mktarget($html) {
	global $_G;

	if($html && $_G['setting']['feedtargetblank']) {
		$html = preg_replace("/target\=([\'\"]?)[\w]+([\'\"]?)/i", '', $html);
		$html = preg_replace("/<a(.+?)href=([\'\"]?)([^>\s]+)\\2([^>]*)>/i", '<a href="\\3" target="_blank" \\1 \\4>', $html);
	}
	return $html;
}


function feed_publish($id, $idtype, $add=0) {
 
	global $_G;
	
	if(!helper_access::check_module('feed') || empty($id)) {
		return 0;
	}
    
    $id = intval($id);
	
    $setarr = [];
	
    switch ($idtype) {
        case 'blogid':
            $value = array_merge(
                C::t('home_blog')->fetch($id),
                C::t('home_blogfield')->fetch($id)
            );
            if ($value) {
                if ($value['friend'] != 3) {
                    
                    $setarr = [
                        'id' => $value['blogid'],
                        'idtype' => $idtype,
                        'icon' => 'blog',
                        'uid' => $value['uid'],
                        'username' => $value['username'],
                        'dateline' => time(),
                        'target_ids' => $value['target_ids'],
                        'friend' => $value['friend'],
                        'hot' => $value['hot'],
                    ];
                    
                    $status = $value['status'];
                    
                    $url = "home.php?mod=space&uid=$value[uid]&do=blog&id=$value[blogid]";
                    if ($value['friend'] == 4) {
                        $setarr['title_template'] = 'feed_blog_password';
                        $setarr['title_data'] = ['subject' => "<a href=\"$url\">$value[subject]</a>"];
                    } else {
                        if ($value['pic']) {
                            $setarr['image_1'] = pic_cover_get($value['pic'], $value['picflag']);
                            $setarr['image_1_link'] = $url;
                        }
                        $setarr['title_template'] = 'feed_blog_title';
                        $setarr['body_template'] = '';
                        $value['message'] = preg_replace("/&[a-z]+\;/i", '', $value['message']);
                        $setarr['body_data'] = [
                            'subject' => "<a href=\"$url\">$value[subject]</a>",
                            'summary' => getstr($value['message'], 150, 0, 0, 0, -1),
                        ];
                    }
                }
            }
            break;
            
        case 'picid':
            $plussql = $id > 0 ? 'p.' . DB::field('picid', $id) : 'p.' . DB::field('uid', $_G[uid]) . ' ORDER BY dateline DESC LIMIT 1';
            $query = C::t('home_pic')->fetch_all_by_sql($plussql);
            if ($value = $query[0]) {
                if (empty($value['friend'])) {
                
                    $status = $value['status'];
                    $url = "home.php?mod=space&uid=$value[uid]&do=album&picid=$value[picid]";
                
                    $setarr = [
                        'id' => $value['picid'],
                        'idtype' => $idtype,
                        'icon' => 'album',
                        'uid' => $value['uid'],
                        'username' => $value['username'],
                        'dateline' => time(),
                        'target_ids' => $value['target_ids'],
                        'friend' => $value['friend'],
                        'hot' => $value['hot'],
                    
                        'image_1' => pic_get($value['filepath'], 'album', $value['thumb'], $value['remote']),
                        'image_1_link' => $url,
                        'title_template' => 'feed_pic_title',
                        'body_template' => 'feed_pic_body',
                        'body_general' => 'feed_pic_body',
                        'body_data' => [
                            'title' => $value['title']
                        ],
                    ];
                }
            }
            break;
            
        case 'albumid':
            $key = 1;
            if ($id > 0) {
                $query = C::t('home_pic')->fetch_all_by_sql('p.' . DB::field('albumid', $id), 'a.dateline DESC', 0, 4);
                foreach ($query as $value) {
                    if ($value['friend'] <= 2) {
                        if (empty($setarr['icon'])) {
                            
                            $setarr['icon'] = 'album';
                            $setarr['id'] = $value['albumid'];
                            $setarr['idtype'] = $idtype;
                            $setarr['uid'] = $value['uid'];
                            $setarr['username'] = $value['username'];
                            $setarr['dateline'] = time();
                            $setarr['target_ids'] = $value['target_ids'];
                            $setarr['friend'] = $value['friend'];
                            $status = $value['status'];
                            $setarr['title_template'] = 'feed_album_title';
                            $setarr['body_template'] = 'feed_album_body';
                            $setarr['body_data'] = [
                                'album' => "<a href=\"home.php?mod=space&uid=$value[uid]&do=album&id=$value[albumid]\">$value[albumname]</a>",
                                'picnum' => $value['picnum'],
                            ];
                        }
                        
                        $setarr['image_' . $key] = pic_get($value['filepath'], 'album', $value['thumb'], $value['remote']);
                        $setarr['image_' . $key . '_link'] = "home.php?mod=space&uid=$value[uid]&do=album&picid=$value[picid]";
                        $key++;
                    } else {
                        break;
                    }
                }
            }
            break;
    }
    
    if ($setarr['icon']) {
        
        $setarr['title_template'] = $setarr['title_template'] ? lang('feed', $setarr['title_template']) : '';
        $setarr['body_template'] = $setarr['body_template'] ? lang('feed', $setarr['body_template']) : '';
        $setarr['body_general'] = $setarr['body_general'] ? lang('feed', $setarr['body_general']) : '';
        
        $setarr['title_data']['hash_data'] = "{$idtype}{$id}";
        $setarr['title_data'] = serialize($setarr['title_data']);
        $setarr['body_data'] = serialize($setarr['body_data']);
        
        $feedid = 0;
        
        if (!$add && $setarr['id']) {
            $feedid = C::t('home_feed')->fetch($id, $idtype);
            $feedid = $feedid['feedid'];
        }
        if ($status == 0) {
            if ($feedid) {
                C::t('home_feed')->update('', $setarr, '', '', $feedid);
            } else {
                C::t('home_feed')->insert($setarr);
            }
        }
    }
}