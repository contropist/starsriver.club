<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: home_space.php 36337 2017-01-05 06:34:27Z nemohou $
 */

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$dos = array(
    'index',
    'doing',
    'blog',
    'album',
    'friend',
    'wall',
    'notice',
    'share',
    'home',
    'pm',
    'favorite',
    'thread',
    'trade',
    'poll',
    'activity',
    'debate',
    'reward',
    'profile',
    'plugin',
    'follow'
);

$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos)) ? $_GET['do'] : 'index';

if (!in_array($do, array(
    'home',
    'doing',
    'blog',
    'album',
    'share',
    'wall'
))) {
    $_G['mnid'] = 'mn_common';
}

if (empty($_G['uid']) && in_array($_GET['do'], array(
        'thread',
        'trade',
        'poll',
        'activity',
        'debate',
        'reward'
    ))) {
    showmessage('login_before_enter_home', null, [], array(
        'showmsg' => true,
        'login'   => 1
    ));
}
$uid = empty($_GET['uid']) ? 0 : intval($_GET['uid']);

$member = [];
if ($_GET['username']) {
    $member = C::t('common_member')->fetch_by_username($_GET['username']);
    if (empty($member) && !($member = C::t('common_member_archive')->fetch_by_username($_GET['username']))) {
        showmessage('space_does_not_exist');
    }
    $uid = $member['uid'];
    $member['self'] = $uid == $_G['uid'] ? 1 : 0;
}

if (empty($uid) || in_array($do, array('notice','pm'))){
    $uid = $_G['uid'];
}

if (empty($_GET['do'])) {
    $do = 'index';
}

if ($_GET['do'] == 'follow') {
    if ($uid != $_G['uid']) {
        $do = 'view';
        $_GET['uid'] = $uid;
    }
    require_once libfile('home/follow', 'module');
    exit;
}

if ($uid && empty($member)) {
    $space = getuserbyuid($uid, 1);
    if (empty($space)) {
        showmessage('space_does_not_exist');
    }
} else {
    $space = &$member;
}

if (empty($space)) {
    if (in_array($do, array(
        'doing',
        'blog',
        'album',
        'share',
        'home',
        'trade',
        'poll',
        'activity',
        'debate',
        'reward',
        'group'
    ))) {
        $_GET['view'] = 'all';
        $space['uid'] = 0;
    } else {
        showmessage('login_before_enter_home', null, [], array(
            'showmsg' => true,
            'login'   => 1
        ));
    }
} else {

    $navtitle = $space['username'];

    if ($space['status'] == -1 && $_G['adminid'] != 1) {
        showmessage('space_has_been_locked');
    }

    $encodeusername = rawurlencode($space['username']);

    if ($do != 'profile' && $do != 'index' && !ckprivacy($do, 'view')) {
        $_G['privacy'] = 1;
        require_once libfile('space/profile', 'include');
        include template('home/space_privacy');
        exit();
    }

    if (!$space['self'] && $_GET['view'] != 'eccredit' && $_GET['view'] != 'admin')
        $_GET['view'] = 'me';
}

$nestmode = 0;

list($seccodecheck, $secqaacheck) = seccheck('publish');

require_once libfile('space/' . $do, 'include');

?>