<?php
    
    if (!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    
    global $_G;
    
    $operation = in_array($_GET['op'], ['modify']) ? trim($_GET['op']) : '';
    if ($_G['setting']['creditstransextra'][6]) {
        $key = 'extcredits' . intval($_G['setting']['creditstransextra'][6]);
    } elseif ($_G['setting']['creditstrans']) {
        $key = 'extcredits' . intval($_G['setting']['creditstrans']);
    } else {
        showmessage('trade_credit_invalid', '', [], ['return' => 1]);
    }
    space_merge($space, 'count');
    
    if (submitcheck('friendsubmit')) {
        
        $showcredit = intval($_POST['stakecredit']);
        if ($showcredit > $space[$key])
            $showcredit = $space[$key];
        if ($showcredit < 1) {
            showmessage('showcredit_error');
        }
        
        $_POST['fusername'] = trim($_POST['fusername']);
        $friend = C::t('home_friend')->fetch_all_by_uid_username($space['uid'], $_POST['fusername'], 0, 1);
        $friend = $friend[0];
        $fuid = $friend['fuid'];
        if (empty($_POST['fusername']) || empty($fuid) || $fuid == $space['uid']) {
            showmessage('showcredit_fuid_error', '', [], ['return' => 1]);
        }
        
        $count = getcount('home_show', ['uid' => $fuid]);
        if ($count) {
            C::t('home_show')->update_credit_by_uid($fuid, $showcredit, false);
        } else {
            C::t('home_show')->insert([
                'uid'      => $fuid,
                'username' => $_POST['fusername'],
                'credit'   => $showcredit,
            ], false, true);
        }
        
        updatemembercount($space['uid'], [$_G['setting']['creditstransextra'][6] => (0 - $showcredit)], true, 'RKC', $space['uid']);
        
        notification_add($fuid, 'credit', 'showcredit', ['credit' => $showcredit]);
        
        
        if (ckprivacy('show', 'feed')) {
            
            $avatar = avatar($fuid, '', true);
            
            require_once libfile('function/feed');
            feed_add([
                'icon'           => 'show',
                'title_template' => 'showcredit',
                'title_data'     => [
                    'credit'  => $showcredit,
                    'uid'     => $fuid,
                    'uname'   => $friend['fusername'],
                    'ulink'   => 'home.php?mod=space&uid=' . $fuid,
                    'uavatar' => $avatar,
                ],
                'body_template'  => 'showcredit',
                'body_data'      => [
                    'credit'  => $showcredit,
                    'uid'     => $fuid,
                    'uname'   => $friend['fusername'],
                    'ulink'   => 'home.php?mod=space&uid=' . $fuid,
                    'uavatar' => $avatar,

                    'expend0' => '',
                    'expend1' => '',
                    'expend2' => '',
                    'expend3' => '',
                    'expend4' => '',
                    'expend5' => '',
                    'expend6' => '',
                    'expend7' => '',
                ],
            ]);
        }
        
        showmessage('showcredit_friend_do_success', "misc.php?mod=ranklist&type=member");
        
    } elseif (submitcheck('showsubmit')) {
        
        $showcredit = intval($_POST['showcredit']);
        $unitprice = intval($_POST['unitprice']);
        if ($showcredit > $space[$key])
            $showcredit = $space[$key];
        if ($showcredit < 1 || $unitprice < 1) {
            showmessage('showcredit_error', '', [], ['return' => 1]);
        }
        $_POST['note'] = getstr($_POST['note'], 100);
        $_POST['note'] = censor($_POST['note']);
        $showarr = C::t('home_show')->fetch($_G['uid']);
        if ($showarr) {
            $notesql = $_POST['note'] ? $_POST['note'] : false;
            $unitprice = $unitprice > $showarr['credit'] + $showcredit ? $showarr['credit'] + $showcredit : $unitprice;
            C::t('home_show')->update_credit_by_uid($_G['uid'], $showcredit, false, $unitprice, $notesql);
        } else {
            $unitprice = $unitprice > $showcredit ? $showcredit : $unitprice;
            C::t('home_show')->insert([
                'uid'       => $_G['uid'],
                'username'  => $_G['username'],
                'unitprice' => $unitprice,
                'credit'    => $showcredit,
                'note'      => $_POST['note'],
            ], false, true);
        }
        
        updatemembercount($space['uid'], [$_G['setting']['creditstransextra'][6] => (0 - $showcredit)], true, 'RKC', $space['uid']);
        
        if (ckprivacy('show', 'feed')) {
            
            $avatar = avatar($_G['uid'], '', true);
            
            require_once libfile('function/feed');
            feed_add([
                'icon'           => 'show',
                'title_template' => 'showcredit_self',
                'title_data'     => [
                    'credit'  => $showcredit,
                    'uid'     => $_G['uid'],
                    'uname'   => $_G['username'],
                    'ulink'   => 'home.php?mod=space&uid=' . $_G['uid'],
                    'uavatar' => $avatar,
                ],
                'body_template'  => 'showcredit',
                'body_data'      => [
                    'credit'  => $showcredit,
                    'uid'     => $_G['uid'],
                    'uname'   => $_G['username'],
                    'ulink'   => 'home.php?mod=space&uid=' . $_G['uid'],
                    'uavatar' => $avatar,

                    'expend0' => '',
                    'expend1' => '',
                    'expend2' => '',
                    'expend3' => '',
                    'expend4' => '',
                    'expend5' => '',
                    'expend6' => '',
                    'expend7' => '',
                ],
                'body_general'   => $_POST['note'],
            ]);
        }
        
        showmessage('showcredit_do_success', dreferer());
    }