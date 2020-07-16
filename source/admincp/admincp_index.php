<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_index.php 36306 2016-12-16 08:12:49Z nemohou $
 */

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

if (@file_exists(DISCUZ_ROOT . './install/index.php') && !DISCUZ_DEBUG) {
    @unlink(DISCUZ_ROOT . './install/index.php');
    if (@file_exists(DISCUZ_ROOT . './install/index.php')) {
        dexit('Please delete install/index.php via FTP!');
    }
}

@include_once DISCUZ_ROOT . './source/discuz_version.php';
require_once libfile('function/attachment');
$isfounder = isfounder();

$siteuniqueid = C::t('common_setting')->fetch('siteuniqueid');
if (empty($siteuniqueid) || strlen($siteuniqueid) < 16) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    $siteuniqueid = 'DX' . $chars[date('y') % 60] . $chars[date('n')] . $chars[date('j')] . $chars[date('G')] . $chars[date('i')] . $chars[date('s')] . substr(md5($_G['clientip'] . $_G['username'] . TIMESTAMP), 0, 4) . random(4);
    C::t('common_setting')->update('siteuniqueid', $siteuniqueid);
    require_once libfile('function/cache');
    updatecache('setting');
}


if (submitcheck('notesubmit', 1)) {
    if (!empty($_GET['noteid']) && is_numeric($_GET['noteid'])) {
        C::t('common_adminnote')->delete($_GET['noteid'], ($isfounder ? '' : $_G['username']));
    }
    if (!empty($_GET['newmessage'])) {
        $newaccess = 0;
        $_GET['newexpiration'] = TIMESTAMP + (intval($_GET['newexpiration']) > 0 ? intval($_GET['newexpiration']) : 30) * 86400;
        $_GET['newmessage'] = nl2br(dhtmlspecialchars($_GET['newmessage']));
        $data = array('admin' => $_G['username'], 'access' => 0, 'adminid' => $_G['adminid'], 'dateline' => $_G['timestamp'], 'expiration' => $_GET['newexpiration'], 'message' => $_GET['newmessage'],);
        C::t('common_adminnote')->insert($data);
    }
}

$serverinfo = PHP_OS . ' / PHP v' . PHP_VERSION;
$serverinfo .= @ini_get('safe_mode') ? ' Safe Mode' : NULL;
$serversoft = $_SERVER['SERVER_SOFTWARE'];
$dbversion = helper_dbtool::dbversion();

if (@ini_get('file_uploads')) {
    $fileupload = ini_get('upload_max_filesize');
} else {
    $fileupload = '<font color="red">' . $lang['no'] . '</font>';
}


$dbsize = helper_dbtool::dbsize();
$dbsize = $dbsize ? sizecount($dbsize) : $lang['unknown'];

$attachsize = C::t('forum_attachment_n')->get_total_filesize();
$attachsize = is_numeric($attachsize) ? sizecount($attachsize) : $lang['unknown'];


$membersmod = C::t('common_member_validate')->count_by_status(0);
$threadsdel = C::t('forum_thread')->count_by_displayorder(-1);
$groupmod = C::t('forum_forum')->validate_level_num();

$modcount = array();
foreach (C::t('common_moderate')->count_group_idtype_by_status(0) as $value) {
    $modcount[$value['idtype']] = $value['count'];
}

$medalsmod = C::t('forum_medallog')->count_by_type(2);
$threadsmod = $modcount['tid'];
$postsmod = $modcount['pid'];
$blogsmod = $modcount['blogid'];
$doingsmod = $modcount['doid'];
$picturesmod = $modcount['picid'];
$sharesmod = $modcount['sid'];
$commentsmod = $modcount['uid_cid'] + $modcount['blogid_cid'] + $modcount['sid_cid'] + $modcount['picid_cid'];
$articlesmod = $modcount['aid'];
$articlecommentsmod = $modcount['aid_cid'];
$topiccommentsmod = $modcount['topicid_cid'];
$verify = '';
foreach (C::t('common_member_verify_info')->group_by_verifytype_count() as $value) {
    if ($value['num']) {
        if ($value['verifytype']) {
            $verifyinfo = !empty($_G['setting']['verify'][$value['verifytype']]) ? $_G['setting']['verify'][$value['verifytype']] : array();
            if ($verifyinfo['available']) {
                $verify .= '<li><a href="'.ADMINSCRIPT.'?action=verify&operation=verify&do=' . $value['verifytype'] . '">'.cplang('home_mod_verify_prefix').$verifyinfo['title'].'</a><i>'.$value['num'].'</i></li>';
            }
        } else {
            $verify .= '<li><a href="'.ADMINSCRIPT.'?action=verify&operation=verify&do=0">'.cplang('home_mod_verify_prefix').cplang('members_verify_profile').'</a><i>'.$value['num'].'</i></li>';
        }
    }
}

cpheader();
shownav();


$save_master = C::t('common_setting')->fetch_all(array('mastermobile', 'masterqq', 'masteremail'));
$save_mastermobile = $save_master['mastermobile'];
$save_mastermobile = !empty($save_mastermobile) ? authcode($save_mastermobile, 'DECODE', $_G['config']['security']['authkey']) : '';
$save_masterqq = $save_master['masterqq'] ? $save_master['masterqq'] : '';
$save_masteremail = $save_master['masteremail'] ? $save_master['masteremail'] : '';

$securityadvise = '';
if ($isfounder) {
    $securityadvise = '';
    $securityadvise .= !$_G['config']['admincp']['founder'] ? $lang['home_security_nofounder'] : '';
    $securityadvise .= !$_G['config']['admincp']['checkip'] ? $lang['home_security_checkip'] : '';
    $securityadvise .= $_G['config']['admincp']['runquery'] ? $lang['home_security_runquery'] : '';
    if (!empty($_GET['securyservice'])) {
        $_GET['new_mastermobile'] = trim($_GET['new_mastermobile']);
        $_GET['new_masterqq'] = trim($_GET['new_masterqq']);
        $_GET['new_masteremail'] = trim($_GET['new_masteremail']);
        if (empty($_GET['new_mastermobile'])) {
            $save_mastermobile = $_GET['new_mastermobile'];
        } elseif (strlen($_GET['new_mastermobile']) == 11 && is_numeric($_GET['new_mastermobile']) && in_array(substr($_GET['new_mastermobile'], 0, 2), array('13', '15', '18'))) {
            $save_mastermobile = $_GET['new_mastermobile'];
            $_GET['new_mastermobile'] = authcode($_GET['new_mastermobile'], 'ENCODE', $_G['config']['security']['authkey']);
        } else {
            $_GET['new_mastermobile'] = $save_master['mastermobile'];
        }
        if (empty($_GET['new_masterqq']) || is_numeric($_GET['new_masterqq'])) {
            $save_masterqq = $_GET['new_masterqq'];
        } else {
            $_GET['new_masterqq'] = $save_masterqq;
        }
        if (empty($_GET['new_masteremail']) || (strlen($_GET['new_masteremail']) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $_GET['new_masteremail']))) {
            $save_masteremail = $_GET['new_masteremail'];
        } else {
            $_GET['new_masteremail'] = $save_masteremail;
        }

        C::t('common_setting')->update_batch(array('mastermobile' => $_GET['new_mastermobile'], 'masterqq' => $_GET['new_masterqq'], 'masteremail' => $_GET['new_masteremail']));
    }

    $view_mastermobile = !empty($save_mastermobile) ? substr($save_mastermobile, 0, 3) . '*****' . substr($save_mastermobile, -3) : '';
}

/*---------------------
|      Show Start     |
-----------------------------------------------------------------------------------------------------------------------*/

$app_name = 'wctrl-index';

echo '<div class="app wctrl-index">';



/* SR::论坛文件校验 */
if (isfounder()) {
    $filecheck = C::t('common_cache')->fetch('checktools_filecheck_result');
    if ($filecheck) {
        list($modifiedfiles, $deletedfiles, $unknownfiles, $doubt) = unserialize($filecheck['cachevalue']);
        $filecheckresult = "
            <div class='panel no-gap'>
                <ol class='panel-container filesinfo'>
                    <li class='box size-2-1'><i class='heart-pink'>$modifiedfiles</i><span class='heart-pink'>$lang[filecheck_modify]</span></li>
                    <li class='box size-2-1'><i class='heart-pink'>$deletedfiles</i><span class='heart-pink'>$lang[filecheck_delete]</span></li>
                    <li class='box size-2-1'><i class='miku-green'>$unknownfiles</i><span class='miku-green'>$lang[filecheck_unknown]</span></li>
                    <li class='box size-2-1'><i>$doubt</i><span>$lang[filecheck_doubt]</span></li>
                </ol>
            </div>";
        $filecheckresulttip = "<div class='tip'>$lang[filecheck_last_homecheck]：<i id='filecheck_date'>".dgmdate($filecheck['dateline'], 'u')."</i></div>";
    } else {
        $filecheckresult = '<div class="no-data">'.$lang['no_data'].'</div>';
    }

    echo make_vessel([
        'title' => $lang['nav_filecheck'],
        'id' => 'mod_filescheck',
        'datas' => [
            'type' => 'file',
        ],
        'tpl' => [
            'header' => $filecheckresulttip,
            'body' => '<div id="filecheck_info">' . $filecheckresult . '</div>',
            'footer' => '
                <a class="btn" onclick="ajaxget(\''.ADMINSCRIPT.'?action=checktools&operation=filecheck&homecheck=yes\', \'filecheck_info\')">' .$lang['filecheck_check_now']. '</a>
                <a class="btn" href="'.ADMINSCRIPT.'?action=checktools&operation=filecheck&step=3">' .$lang['filecheck_view_list']. '</a>',
            'script' => (TIMESTAMP - $filecheck['dateline'] > 86400 * 7) ? '<script>ajaxget(\''.ADMINSCRIPT.'?action=checktools&operation=filecheck&homecheck=yes\', \'filecheck_info\');</script>' : '',
        ],
    ]);
}



/* SR::安全提示 */
if ($securityadvise) {
    echo make_vessel([
        'title' => $lang['home_security_tips'],
        'id' => 'mod_safetip',
        'datas' => [
            'type' => 'safe',
        ],
        'tpl' => [
            'body' => '<ul class="tipline">'.$securityadvise.'</ul>'
        ]
    ]);
}



/* SR::待处理事件 */
if ($membersmod || $threadsmod || $postsmod || $medalsmod || $groupmod || $blogsmod || $picturesmod || $doingsmod || $sharesmod || $commentsmod || $articlesmod || $articlecommentsmod || $topiccommentsmod || $threadsdel || !empty($verify)) {
    echo make_vessel([
        'title' => $lang['home_mods'],
        'id' => 'mod_tasktip',
        'datas' => [
            'type' => 'task',
        ],
        'tpl' => [
            'body' => '
                <ul>
                    '.($membersmod ? '<li><a href="'.ADMINSCRIPT.'?action=moderate&operation=members">' . cplang('home_mod_members') . '</a><i>' . $membersmod . '</i></li>' : '').'
                    '.($threadsmod ? '<li><a href="'.ADMINSCRIPT.'?action=moderate&operation=threads&dateline=all">' . cplang('home_mod_threads') . '</a><i>' . $threadsmod . '</i></li>' : '').'
                    '.($postsmod ? '<li><a href="'.ADMINSCRIPT.'?action=moderate&operation=replies&dateline=all">' . cplang('home_mod_posts') . '</a><i>' . $postsmod . '</i></li>' : '').'
                    '.($medalsmod ? '<li><a href="'.ADMINSCRIPT.'?action=medals&operation=mod">' . cplang('home_mod_medals') . '</a><i>' . $medalsmod . '</i></li>' : '').'
                    '.($groupmod ? '<li><a href="'.ADMINSCRIPT.'?action=group&operation=mod">' . cplang('group_mod_wait') . '</a><i>' . $groupmod . '</i></li>' : '').'
                    '.($blogsmod ? '<li><a href="'.ADMINSCRIPT.'?action=moderate&operation=blogs&dateline=all">' . cplang('home_mod_blogs') . '</a><i>' . $blogsmod . '</i></li>' : '').'
                    '.($picturesmod ? '<li><a href="'.ADMINSCRIPT.'?action=moderate&operation=pictures&dateline=all">' . cplang('home_mod_pictures') . '</a><i>' . $picturesmod . '</i></li>' : '').'
                    '.($doingsmod ? '<li><a href="'.ADMINSCRIPT.'?action=moderate&operation=doings&dateline=all">' . cplang('home_mod_doings') . '</a><i>' . $doingsmod . '</i></li>' : '').'
                    '.($sharesmod ? '<li><a href="'.ADMINSCRIPT.'?action=moderate&operation=shares&dateline=all">' . cplang('home_mod_shares') . '</a><i>' . $sharesmod . '</i></li>' : '').'
                    '.($commentsmod ? '<li><a href="'.ADMINSCRIPT.'?action=moderate&operation=comments&dateline=all">' . cplang('home_mod_comments') . '</a><i>' . $commentsmod . '</i></li>' : '').'
                    '.($articlesmod ? '<li><a href="'.ADMINSCRIPT.'?action=moderate&operation=articles&dateline=all">' . cplang('home_mod_articles') . '</a><i>' . $articlesmod . '</i></li>' : '').'
                    '.($articlecommentsmod ? '<li><a href="'.ADMINSCRIPT.'?action=moderate&operation=articlecomments&dateline=all">' . cplang('home_mod_articlecomments') . '</a><i>' . $articlecommentsmod . '</i></li>' : '').'
                    '.($topiccommentsmod ? '<li><a href="'.ADMINSCRIPT.'?action=moderate&operation=topiccomments&dateline=all">' . cplang('home_mod_topiccomments') . '</a><i>' . $topiccommentsmod . '</i></li>' : '').'
                    '.($threadsdel ? '<li><a href="'.ADMINSCRIPT.'?action=recyclebin">' . cplang('home_del_threads') . '</a><i>' . $threadsdel . '</i></li>' : '').'
                    '.$verify.'
                </ul>
            '
        ],
    ]);
}



/* SR::在线成员 */
$admincp_session = C::t('common_admincp_session')->fetch_all_by_panel(1);
if($admincp_session){
    $onlines = '';
    $members = C::t('common_member')->fetch_all(array_keys($admincp_session), false, 0);
    foreach ($admincp_session as $uid => $online) {
        $onlines .= '
            <a class="avatar middle" href="home.php?mod=space&uid=' . $online['uid'] . '" target="_blank">
                '.avatar($online['uid'],'small').'
                <i class="name">' . $members[$uid]['username'] . '</i>
            </a>';
    }
    echo make_vessel([
        'title' => $lang['home_onlines'],
        'id' => 'mod_onlinemember',
        'datas' => [
            'type' => 'user',
        ],
        'tpl' => [
            'body' => "<div class='height-limited' style='max-height: 218px;'>$onlines</div>",
        ],
    ]);
}



/* SR::团队留言 */
$notes = C::t('common_adminnote')->fetch_all_by_access(0);
$notesbody = '';
if($notes){
    foreach ($notes as $note) {
        if ($note['expiration'] < TIMESTAMP) {
            C::t('common_adminnote')->delete($note['id']);
        } else {
            $note['adminenc'] = rawurlencode($note['admin']);
            $note['expiration'] = ceil(($note['expiration'] - $note['dateline']) / 86400);
            $note['dateline'] = dgmdate($note['dateline'], 'dt');
            $notesbody .= '
                <div class="adminnote">
                    <a class="avatar small" href="home.php?mod=space&username='.$note['adminenc'].'" target=\"_blank\">'.avatar($note['adminid'],'small').'</a>
                    <div class="info">
                        <i class="name">'.$note['admin'].'</i>
                        <i class="date">'.$note['dateline'].'，'.cplang('validity').$note['expiration'].cplang('days').'</i>
                    </div>
                    <p class="note">'.$note['message'].'</p>
                    '.($isfounder || $_G['member']['username'] == $note['admin'] ? '<a class="delete" href="'.ADMINSCRIPT.'?action=index&notesubmit=yes&noteid=' . $note['id'] . '">×</a>' : '').'
                </div>';
        }
    }
} else {
    $notesbody = '<div class="no-data">'.$lang['no_data'].'</div>';
}
echo make_vessel([
    'title' => $lang['home_notes'],
    'id' => 'mod_notes',
    'datas' => [
        'type' => 'user',
    ],
    'tpl' => [
        'body' => "<div class='height-limited' style='max-height: 417px;'>$notesbody</div>",
        'footer' =>
            make_formheader('index').'
                <input type="text" name="newmessage" placeholder="'.cplang('home_notes_add').'"/>， 
                '.cplang('validity').'<input type="text" name="newexpiration" value="30" style="width: 50px; text-align: center"/>'.cplang('days').'
                <input class="btn" name="notesubmit" value="' . cplang('home_notes_add') . '" type="submit" />'.
            make_formfooter(),
    ],
]);



/* SR::系统信息 */
loaducenter();
echo make_vessel([
    'title' => $lang['home_sys_info'],
    'id' => 'mod_sysinfo',
    'datas' => [
        'type' => 'sysinfo',
    ],
    'tpl' => [
        'body' => '
            <div class="table-container">
                <table class="wb rb">
                    <tbody>
                        <tr><td class="head">'.cplang('home_discuz_version').'</td><td>'.'Discuz! ' . DISCUZ_VERSION . ' Release ' . DISCUZ_RELEASE.'</td></tr>
                        <tr><td class="head">'.cplang('home_ucclient_version').'</td><td>'.'UCenter ' . UC_CLIENT_VERSION . ' Release ' . UC_CLIENT_RELEASE.'</td></tr>
                        <tr><td class="head">'.cplang('home_environment').'</td><td>'.$serverinfo.'</td></tr>
                        <tr><td class="head">'.cplang('home_serversoftware').'</td><td>'.$serversoft.'</td></tr>
                        <tr><td class="head">'.cplang('home_database').'</td><td>'.$dbversion.'</td></tr>
                        <tr><td class="head">'.cplang('home_upload_perm').'</td><td>'.$fileupload.'</td></tr>
                        <tr><td class="head">'.cplang('home_database_size').'</td><td>'.$dbsize.'</td></tr>
                        <tr><td class="head">'.cplang('home_attach_size').'</td><td>'.$attachsize.'</td></tr>
                    </tbody>
                </table>
            </div>',
    ],
]);

echo '</div></div>';

?>