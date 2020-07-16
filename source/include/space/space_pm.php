<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_pm.php 33421 2013-06-09 03:30:16Z jeffjzhang $
 */

    if (!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }

    loaducenter();

    function formate_res($arr) {
        if (!empty($arr)) {
            $today = $_G['timestamp'] - ($_G['timestamp'] + $_G['setting']['timeoffset'] * 3600) % 86400;
            foreach ($arr as $key => $value) {
                $value['lastsummary'] = str_replace('&amp;', '&', $value['lastsummary']);
                $value['lastsummary'] = preg_replace("/&[a-z]+\;/i", '', $value['lastsummary']);
                $value['daterange'] = 5;
                if ($value['lastdateline'] >= $today) {
                    $value['daterange'] = 1;
                } elseif ($value['lastdateline'] >= $today - 86400) {
                    $value['daterange'] = 2;
                } elseif ($value['lastdateline'] >= $today - 172800) {
                    $value['daterange'] = 3;
                } elseif ($value['lastdateline'] >= $today - 604800) {
                    $value['daterange'] = 4;
                }
                $arr[$key] = $value;
            }
        } else {
            $arr = null;
        }
        return $arr;
    }

    function pmmulti($count, $perpage, $curpage, $mpurl) {
        $return = '';
        $lang['next'] = lang('core', 'nextpage');
        $lang['prev'] = lang('core', 'prevpage');
        $next = $curpage < ceil($count / $perpage) ? '<a href="' . $mpurl . '&amp;page=' . ($curpage + 1) . '" class="next"></a>' : '<a class="disabled next"></a>';
        $prev = $curpage > 1 ? '<a href="' . $mpurl . '&amp;page=' . ($curpage - 1) . '" class="prev"></a>' : '<a class="disabled prev"></a>';
        $num = $curpage.'/'.ceil($count / $perpage);
        if ($next || $prev) {
            $return =$prev .'<i>'.$num.'</i>'. $next;
        }
        return $return;
    }

    /* 为了方便模板的判定，定义 subpage为逻辑页面 */
    $subpage = 'first';

    $newpm = $newpmcount = 0;
    $list = $grouppms = $gpmids = $gpmstatus = [];

    $filter = in_array($_GET['filter'], array('newpm', 'privatepm', 'announcepm')) ? $_GET['filter'] : 'privatepm';
    if(!empty($_GET['type'])){
        $type = $_GET['type'];
    } else {
        $type = 0;
    }

    $opactives['pm'] = 'class="active"';
    $actives = [$filter => 'class="active"'];

    $plid = empty($_GET['plid']) ? 0 : intval($_GET['plid']);
    $touid = empty($_GET['touid']) ? 0 : intval($_GET['touid']);
    $daterange = empty($_GET['daterange']) ? 0 : intval($_GET['daterange']);

    $announcepm = 0;
    foreach (C::t('common_member_grouppm')->fetch_all_by_uid($_G['uid'], $filter == 'announcepm' ? 1 : 0) as $gpmid => $gpuser) {
        $gpmstatus[$gpmid] = $gpuser['status'];
        if ($gpuser['status'] == 0) {
            $announcepm++;
        }
    }

    $newpmarr = uc_pm_checknew($_G['uid'], 1);
    $newpm = $newpmarr['newpm'];
    $newpmcount = $newpm + $announcepm;

    if ($filter == 'privatepm' || $filter == 'newpm') {
        $result = uc_pm_list($_G['uid'], 1, 1024, 'inbox', $filter, 200);
        $count = $result['count'];
        $session_list = $result['data'];
    }

    if ($filter == 'privatepm' && $page == 1 || $filter == 'announcepm') {
        $gpmids = array_keys($gpmstatus);
        if ($gpmids) {
            $subpage = 'grouppm';
            foreach (C::t('common_grouppm')->fetch_all_by_id_authorid($gpmids) as $grouppm) {
                $grouppm['message'] = htmlspecialchars_decode($grouppm['message']);
                $grouppms[] = $grouppm;
            }
        }
    }

    if ($_G['member']['newpm']) {
        C::t('common_member')->update($_G['uid'], array('newpm' => 0));
        uc_pm_ignore($_G['uid']);
    }

    if (empty($_G['member']['category_num']['manage']) && !in_array($_G['adminid'], array(1, 2, 3))) {
        unset($_G['notice_structure']['manage']);
    }

    if ($_GET['subop'] == 'view') {

        $actives = ['privatepm' => 'class="active"'];

        $page = empty($_GET['page']) ? 0 : intval($_GET['page']);

        $perpage = 50;
        $perpage = mob_perpage($perpage);
        if ($touid) {
            $ols = [];
            if (!$daterange) {
                $member = getuserbyuid($touid);
                $tousername = $member['username'];
                unset($member);
                $count = uc_pm_view_num($_G['uid'], $touid, 0);
                $page = $page ? $page : ceil($count / $perpage);
                $list = uc_pm_view($_G['uid'], 0, $touid, 5, ceil($count / $perpage) - $page + 1, $perpage, 0, 0);
                $multi = pmmulti($count, $perpage, $page, "home.php?mod=space&do=pm&subop=view&touid=$touid");
            } else {
                showmessage('parameters_error');
            }
        } else {
            $count = uc_pm_view_num($_G['uid'], $plid, 1);
            $page = $page ? $page : ceil($count / $perpage);
            $list = uc_pm_view($_G['uid'], 0, $plid, 5, ceil($count / $perpage) - $page + 1, $perpage, 1, 1);
            $multi = pmmulti($count, $perpage, $page, "home.php?mod=space&do=pm&subop=view&plid=$plid");

            $chatpmmember = intval($_GET['chatpmmember']);
            $chatpmmember = uc_pm_chatpmmemberlist($_G['uid'], $plid);
            $chatpmmemberlist = [];

            if (!empty($chatpmmember)) {
                $authorid = $founderuid = $chatpmmember['author'];
                $chatpmmemberlist = C::t('common_member')->fetch_all($chatpmmember['member']);
                foreach (C::t('common_member_field_home')->fetch_all($chatpmmember['member']) as $uid => $member) {
                    $chatpmmemberlist[$uid] = array_merge($member, $chatpmmemberlist[$uid]);
                }
                foreach (C::app()->session->fetch_all_by_uid($chatpmmember['member']) as $value) {
                    if (!$value['invisible']) {
                        $ols[$value['uid']] = $value['lastactivity'];
                    }
                }
            }

            $membernum = count($chatpmmemberlist);
            $subject = $list[0]['subject'];
            $refreshtime = $_G['setting']['chatpmrefreshtime'];
        }
        $founderuid = empty($list) ? 0 : $list[0]['founderuid'];
        $pmid = empty($list) ? 0 : $list[0]['pmid'];

    } elseif ($_GET['subop'] == 'viewg') {

        $subpage = 'global_msg';

        $actives = ['announcepm' => 'class="active"'];

        $grouppm = C::t('common_grouppm')->fetch($_GET['pmid']);
        $grouppm['message'] = htmlspecialchars_decode($grouppm['message']);
        if (!$grouppm) {
            $grouppm = array_merge((array)C::t('common_member_grouppm')->fetch($_G['uid'], $_GET['pmid']), $grouppm);
        }
        if ($grouppm) {
            $grouppm['numbers'] = $grouppm['numbers'] - 1;
        }
        if (!$grouppm['status']) {
            C::t('common_member_grouppm')->update($_G['uid'], $_GET['pmid'], array('status' => 1, 'dateline' => TIMESTAMP));
        }

    } elseif ($_GET['subop'] == 'ignore') {

        $subpage = 'ignore';

        $actives = ['ignore' => ' class="active"'];

        $ignorelist = uc_pm_blackls_get($_G['uid']);

    } elseif ($_GET['subop'] == 'setting') {

        $subpage = 'setting';

        $actives = ['setting' => ' class="active"'];

        $acceptfriendpmstatus = $_G['member']['onlyacceptfriendpm'] ? $_G['member']['onlyacceptfriendpm'] : ($_G['setting']['onlyacceptfriendpm'] ? 1 : 2);
        $ignorelist = uc_pm_blackls_get($_G['uid']);

    }

    $list = formate_res($list);
    $session_list = formate_res($session_list);

    include_once template("nest:home/space_pm");