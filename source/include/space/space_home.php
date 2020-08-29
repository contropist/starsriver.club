<?php
    
    /**
     *      [Discuz!] (C)2001-2099 Comsenz Inc.
     *      This is NOT a freeware, use is subject to license terms
     *
     *      $Id: space_home.php 30780 2012-06-19 06:01:52Z zhengqingpeng $
     */
    
    if (!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    
    global $_G;
    
    if (!$_G['uid'] && $_G['setting']['privacy']['view']['home']) {
        showmessage('home_no_privilege', '', [], ['login' => true]);
    }
    
    require_once libfile('function/feed');
    
    if (empty($_G['setting']['feedhotday'])) {
        $_G['setting']['feedhotday'] = 2;
    }
    
    $minhot = $_G['setting']['feedhotmin'] < 1 ? 3 : $_G['setting']['feedhotmin'];
    
    space_merge($space, 'count');
    
    if (empty($_GET['view'])) {
        if ($space['self']) {
            if ($_G['setting']['showallfriendnum'] && $space['friends'] < $_G['setting']['showallfriendnum']) {
                $_GET['view'] = 'all';
            } else {
                $_GET['view'] = 'we';
            }
        } else {
            $_GET['view'] = 'all';
        }
    } elseif (!in_array($_GET['view'], ['we', 'me', 'all',])) {
        $_GET['view'] = 'all';
    }

    
    if ($_GET['view'] == 'all' && $_GET['order'] == 'hot') {
        $perpage = 20;
    } else {
        $perpage = mob_perpage($_G['setting']['feedmaxnum'] < 20 ? 20 : $_G['setting']['feedmaxnum']);
    }
    
    $page = intval($_GET['page']);
    
    if ($page < 1) $page = 1;
    
    $start = ($page - 1) * $perpage;
    
    ckstart($start, $perpage);
    
    $_G['home_today'] = $_G['timestamp'] - ($_G['timestamp'] + $_G['setting']['timeoffset'] * 3600) % (3600*24);
    
    $gets = [
        'mod' => 'space',
        'uid' => $space['uid'],
        'do' => 'home',
        'view' => $_GET['view'],
        'order' => !empty($_GET['order']) ? $_GET['order'] : 'dateline',
        'type' => $_GET['type'],
        'icon' => $_GET['icon'],
        'from' => $_GET['from'],
    ];
    
    $theurl = 'home.php?' . url_implode($gets);
    
    $feeds = $hotlist = $filter_list = $magic = $uids = [];
    
    $need_count = true;
    
    $count = $filtercount = 0;
    
    $multi = $hot = '';
    
    if (!IS_ROBOT) {
    
        if($space['self'] && empty($start) && $_G['setting']['feedhotnum'] > 0 && ($gets['view'] == 'we' || $gets['view'] == 'all')) {
            
            $temp = [];
            $term = $_G['timestamp'] - $_G['setting']['feedhotday'] * 3600 * 24;
            $hquery = C::t('home_feed')->fetch_all_by_hot($term);
            
            foreach ($hquery as $value) {
                if ($value['hot'] > 0 && ckfriend($value['uid'], $value['friend'], $value['target_ids'])) {
                    if (empty($hotlist)) {
                        $hotlist[$value['feedid']] = $value;
                    } else {
                        $temp[$value['feedid']] = $value;
                    }
                }
            }
    
            $nexthotnum = $_G['setting']['feedhotnum'] - 1;
    
            if ($nexthotnum > 0) {
                if (count($temp) > $nexthotnum) {
                    $hotlist_key = array_rand($temp, $nexthotnum);
                    if ($nexthotnum == 1) {
                        $hotlist[$hotlist_key] = $temp[$hotlist_key];
                    } else {
                        foreach ($hotlist_key as $key) {
                            $hotlist[$key] = $temp[$key];
                        }
                    }
                } else {
                    $hotlist = array_merge($hotlist, $temp);
                }
            }
        }
        
        if ($gets['view'] == 'all') {
            
            $f_index = '';
            $findex = '';
            
            if ($gets['order'] == 'dateline') {
                $ordersql = "dateline DESC";
                $orderactives = ['dateline' => 'active'];
            } else {
                $hot = $minhot;
                $ordersql = "dateline DESC";
                $orderactives = ['hot' => 'active'];
            }
        } elseif ($gets['view'] == 'me') {
            
            $uids = [$space['uid']];
            $ordersql = "dateline DESC";
            $f_index = '';
            $findex = '';
            
            if ($_GET['from'] == 'space') {
                $nestmode = 1;
            } else {
                $nestmode = 0;
            }
            
        } else {
            
            space_merge($space, 'field_home');
            
            if (empty($space['feedfriend'])) {
                $need_count = false;
            } else {
                $uids = array_merge(explode(',', $space['feedfriend']), [0]);
                $ordersql = "dateline DESC";
                $f_index = 'USE INDEX(dateline)';
                $findex = 'dateline';
            }
        }
        
        $icon = empty($_GET['icon']) ? '' : trim($_GET['icon']);
        $gid = !isset($_GET['gid']) ? '-1' : intval($_GET['gid']);
        if ($gid >= 0) {
            $fuids = [];
            $query = C::t('home_friend')->fetch_all_by_uid_gid($_G['uid'], $gid);
            foreach ($query as $value) {
                $fuids[] = $value['fuid'];
            }
            if (empty($fuids)) {
                $need_count = false;
            } else {
                $uids = $fuids;
            }
        }
        
        $gidactives[$gid] = 'active';
        
        if ($need_count) {
    
            $hash_datas = [];
            $more_list = [];
            $uid_feedcount = [];
    
            $query = C::t('home_feed')->fetch_all_by_search(1, $uids, $icon, '', '', '', $hot, '', $start, $perpage, $findex);
            foreach ($query as $value) {
                if (!isset($hotlist[$value['feedid']]) && ckfriend($value['uid'], $value['friend'], $value['target_ids'])) {
                    $value = mkfeed($value);
                    if ($gets['view'] == 'me' || ckicon_uid($value)) {
                        $feeds[] = $value;
                        $count++;
                    } else {
                        $filtercount++;
                        $filter_list[] = $value;
                    }
                }
            }
            $multi = simplepage($count, $perpage, $page, $theurl);
        }
    }
    
    $olfriendlist = $visitorlist = $task = $ols = $birthlist = $guidelist = $oluids = $groups = $defaultusers = $newusers = $showusers = [];
    
    if ($space['self'] && empty($start)) {
        
        space_merge($space, 'field_home');
        if ($gets['view'] == 'we') {
            require_once libfile('function/friend');
            $groups = friend_group_list();
        }
        
        $isnewer = ($_G['timestamp'] - $space['regdate'] > 3600 * 24 * 7) ? 0 : 1;
        
        if ($isnewer) {
            
            $friendlist = [];
            $query = C::t('home_friend')->fetch_all($space['uid']);
            foreach ($query as $value) {
                $friendlist[$value['fuid']] = 1;
            }
            
            foreach (C::t('home_specialuser')->fetch_all_by_status(1) as $value) {
                if (empty($friendlist[$value['uid']])) {
                    $defaultusers[] = $value;
                    $oluids[] = $value['uid'];
                }
            }
        }
        
        if ($space['newprompt']) {
            space_merge($space, 'status');
        }
        
        if ($_G['setting']['homestyle']) {
            foreach (C::t('home_visitor')->fetch_all_by_uid($space['uid'], 6) as $value) {
                $visitorlist[$value['vuid']] = $value;
                $oluids[] = $value['vuid'];
            }
            
            if ($oluids) {
                foreach (C::app()->session->fetch_all_by_uid($oluids) as $value) {
                    if (!$value['invisible']) {
                        $ols[$value['uid']] = 1;
                    } elseif ($visitorlist[$value['uid']]) {
                        unset($visitorlist[$value['uid']]);
                    }
                }
            }
            
            $oluids = [];
            $olfcount = 0;
            if ($space['feedfriend']) {
                foreach (C::app()->session->fetch_all_by_uid(explode(',', $space['feedfriend']), 6) as $value) {
                    if ($olfcount < 6 && !$value['invisible']) {
                        $olfriendlist[$value['uid']] = $value;
                        $ols[$value['uid']] = 1;
                        $oluids[$value['uid']] = $value['uid'];
                        $olfcount++;
                    }
                }
            }
            if ($olfcount < 6) {
                $query = C::t('home_friend')->fetch_all_by_uid($space['uid'], 0, 24, true);
                foreach ($query as $value) {
                    $value['uid'] = $value['fuid'];
                    $value['username'] = $value['fusername'];
                    if (empty($oluids[$value['uid']])) {
                        $olfriendlist[$value['uid']] = $value;
                        $olfcount++;
                        if ($olfcount == 6)
                            break;
                    }
                }
            }
            
            if ($space['feedfriend']) {
                $date = getdate($_G['timestamp']);
                $exp = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']) + 24 * 3600;
                
                $birthdaycache = C::t('forum_spacecache')->fetch($_G['uid'], 'birthday');
                if (empty($birthdaycache) || $exp > $birthdaycache['expiration']) {
                    $birthlist = C::t('common_member_profile')->fetch_all_will_birthday_by_uid($space['feedfriend']);
                    C::t('forum_spacecache')->insert([
                        'uid' => $_G['uid'],
                        'variable' => 'birthday',
                        'value' => serialize($birthlist),
                        'expiration' => $exp,
                    ], false, true);
                } else {
                    $birthlist = dunserialize($birthdaycache['value']);
                }
            }
            
            if ($_G['setting']['taskon']) {
                require_once libfile('class/task');
                $tasklib = &task::instance();
                $taskarr = $tasklib->tasklist('canapply');
                $task = $taskarr[array_rand($taskarr)];
            }
            if ($_G['setting']['magicstatus']) {
                loadcache('magics');
                if (!empty($_G['cache']['magics'])) {
                    $magic = $_G['cache']['magics'][array_rand($_G['cache']['magics'])];
                    $magic['pic'] = strtolower($magic['identifier']) . '.gif';
                }
            }
        }
    } elseif (empty($_G['uid'])) {
        $defaultusers = C::t('home_specialuser')->fetch_all_by_status(0, 6);
        
        $query = C::t('home_show')->fetch_all_by_credit(0, 6); //DB::query("SELECT * FROM ".DB::table('home_show')." ORDER BY credit DESC LIMIT 0,12");
        foreach ($query as $value) {
            $showusers[] = $value;
        }
        
        foreach (C::t('common_member')->range(0, 6, 'DESC') as $uid => $value) {
            $value['regdate'] = dgmdate($value['regdate'], 'u', 9999, 'm-d');
            $newusers[$uid] = $value;
        }
    }
    
    dsetcookie('home_readfeed', $_G['timestamp'], 365 * 24 * 3600);
    if ($_G['uid']) {
        $defaultstr = getdefaultdoing();
        space_merge($space, 'status');
        if (!$space['profileprogress']) {
            include_once libfile('function/profile');
            $space['profileprogress'] = countprofileprogress();
        }
    }
    $actives = [$gets['view'] => 'active'];
    
    if ($_GET['from'] == 'space') {
        if ($_GET['do'] == 'home') {
            $navtitle = lang('space', 'sb_feed', ['who' => $space['username']]);
            $metakeywords = lang('space', 'sb_feed', ['who' => $space['username']]);
            $metadescription = lang('space', 'sb_feed', ['who' => $space['username']]);
        }
    } else {
        [$navtitle, $metadescription, $metakeywords,] = get_seosetting('home');
        if (!$navtitle) {
            $navtitle = $_G['setting']['navs'][4]['navname'];
            $nobbname = false;
        } else {
            $nobbname = true;
        }
    
        $metakeywords = $metakeywords ? $metakeywords : $_G['setting']['navs'][4]['navname'];
        $metadescription = $metadescription ? $metadescription : $_G['setting']['navs'][4]['navname'];

    }
    if (empty($cp_mode)) {
        include_once template("nest:home/space_route_feed");
    }