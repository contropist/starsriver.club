<?php
/********************************************************************
 * Copyright (c) 2020 All Right Reserved By [StarsRiver]            *
 *                                                                  *
 * Author  Zhangyu                                                  *
 * Email   starsriver@yahoo.com                                     *
 ********************************************************************/
    
    if (!defined('IN_DISCUZ')) {
        exit;
    }
    
    global $_G;
    
    $image_not_exist = IMGURL . '/common/no-img/image-broken.svg';
    
    if (empty($_GET['aid']) || empty($_GET['size']) || empty($_GET['key'])) {
        header('location: ' . $image_not_exist);
        exit;
    }
    
    $type = !empty($_GET['type']) ? $_GET['type'] : 'fixwr';
    
    [$w, $h] = explode('x', $_GET['size']);
    
    $w = intval($w);
    $h = intval($h);
    
    $cache = empty($_GET['nocache']) ? 1 : 0;
    $aid = intval($_GET['aid']);
    
    $attachurl = helper_attach::attachpreurl();
    $thumbfile = 'image/' . helper_attach::makethumbpath($aid, $w, $h);
    $localfile = $_G['setting']['attachdir'] . $thumbfile;
    
    if ($cache && file_exists($_G['setting']['attachdir'] . $thumbfile)) {
        dheader('location: ' . $attachurl . $thumbfile);
    }
    
    define('NOROBOT', true);
    
    $id = !empty($_GET['atid']) ? $_GET['atid'] : $aid;
    
    if (dsign($id . '|' . $w . '|' . $h) != $_GET['key']) {
        dheader('location: ' . $image_not_exist);
    }
    
    if ($attach = C::t('forum_attachment_n')->fetch('aid:' . $aid, $aid, [1, -1,])) {
        
        if (!$w && !$h && $attach['tid'] != $id) {
            dheader('location: ' . $image_not_exist);
        } else {
            
            dheader('Expires: ' . gmdate('D, d M Y H:i:s', TIMESTAMP + 3600) . ' GMT');
            
            if ($attach['remote']) {
                $filename = $_G['setting']['ftp']['attachurl'] . 'forum/' . $attach['attachment'];
            } else {
                $filename = $_G['setting']['attachdir'] . 'forum/' . $attach['attachment'];
            }
            
            require_once libfile('class/image');
            
            $img = new image;
            
            if ($img->Thumb($filename, $thumbfile, $w, $h, $type)) {
                if (!$cache) {
                    dheader('Content-Type: image');
                    @readfile($_G['setting']['attachdir'] . $thumbfile);
                    @unlink($_G['setting']['attachdir'] . $thumbfile);
                } else {
                    dheader('location: ' . $attachurl . $thumbfile);
                }
            } else {
                dheader('Content-Type: image');
                @readfile($filename);
            }
        }
    } else {
        @unlink($_G['setting']['attachdir'] . $thumbfile);
        dheader('location: ' . $image_not_exist);
    }