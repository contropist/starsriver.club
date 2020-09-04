<?php
    
    /*
    * [starsriver] 2010-2110 @copyright reserved by
    *
    * Author Neko_Yurino
    * Email  starsriver@yahoo.com
    * Date   2020/9/4 - 14:30
    *
    */
    
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
            'uid'   => $thread['authorid'],
            'uname' => $thread['author'],
            'ulink' => 'home.php?mod=space&uid='.$thread['authorid'],
            'message' => '',
            'imgs'   => [],
            'imgnum' => 0,
        ];
        
        if (!empty($thread['message'])) {
            $message = messagecutstr(messagesafeclear($thread['message']), 200);
        } else {
            $message = '';
        }
    
        $data['data']['message'] = $message;
        
        if ($thread['attachment']) {
            getattach_img($thread['tid'], $thread['pid'], 9, $data['data']);
        }
    }