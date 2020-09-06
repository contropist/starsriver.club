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
    
    function getThread_sample($tid, &$data) {
        
        require_once libfile('function/post');
        
        $thread_id = $tid;
        $thread_info = C::t('forum_thread')->fetch($thread_id);
        $thread_list = C::t('forum_post')->fetch_all_by_tid('tid' . $thread_id, $thread_id, true, '', 0, 0, 1);
        $thread_data = [];
        
        $counter = 0;
        foreach ($thread_list as $row) {
            if ($counter == 0) {
                $thread_data = $row;
                break;
            }
        }
        
        $thread = array_merge($thread_info, $thread_data);
        
        $data['type'] = 'thread';
        $data['data'] = [
            
            'tid'   => $thread['tid'],
            'tsub'  => $thread['subject'],
            'tlink' => 'forum.php?mod=viewthread&tid=' . $thread['tid'],
            'tdate' => $thread['dateline'],
            
            'uid'     => $thread['authorid'],
            'uname'   => $thread['author'],
            'ulink'   => 'home.php?mod=space&uid=' . $thread['authorid'],
            'uavatar' => avatar($thread['authorid'], 'small', true),
            
            'message' => '',
            
            'imgs'   => [],
            'imgnum' => 0,
        ];
        
        if ($thread['price']) {
            $data['template'] = 'quote_need_payoff';
        } elseif ($thread['readperm']) {
            $data['template'] = 'quote_need_perm';
        } elseif ($thread['status']) {
            $data['template'] = 'quote_post_baned';
        } elseif (empty($thread['message'])) {
            $data['template'] = 'quote_post_no_msg';
        } else {
            $data['template'] = 'thread_sample';
            $data['data']['message'] = messagecutstr(messagesafeclear($thread['message']), 200);
            if ($thread['attachment']) {
                getattach_img($thread['tid'], $thread['pid'], 9, $data['data']['imgs']);
            }
        }
    }