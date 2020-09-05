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
    
    class extend_thread_trade extends extend_thread_base {
        
        private $trademessage;
        
        public function before_newthread($parameters) {
            
            $item_price = floatval($_GET['item_price']);
            $item_credit = intval($_GET['item_credit']);
            $_GET['item_name'] = censor($_GET['item_name']);
            if (!trim($_GET['item_name'])) {
                return $this->showmessage('trade_please_name');
            } elseif ($this->group['maxtradeprice'] && $item_price > 0 && ($this->group['mintradeprice'] > $item_price || $this->group['maxtradeprice'] < $item_price)) {
                return $this->showmessage('trade_price_between', '', [
                    'mintradeprice' => $this->group['mintradeprice'],
                    'maxtradeprice' => $this->group['maxtradeprice'],
                ]);
            } elseif ($this->group['maxtradeprice'] && $item_credit > 0 && ($this->group['mintradeprice'] > $item_credit || $this->group['maxtradeprice'] < $item_credit)) {
                return $this->showmessage('trade_credit_between', '', [
                    'mintradeprice' => $this->group['mintradeprice'],
                    'maxtradeprice' => $this->group['maxtradeprice'],
                ]);
            } elseif (!$this->group['maxtradeprice'] && $item_price > 0 && $this->group['mintradeprice'] > $item_price) {
                return $this->showmessage('trade_price_more_than', '', ['mintradeprice' => $this->group['mintradeprice']]);
            } elseif (!$this->group['maxtradeprice'] && $item_credit > 0 && $this->group['mintradeprice'] > $item_credit) {
                return $this->showmessage('trade_credit_more_than', '', ['mintradeprice' => $this->group['mintradeprice']]);
            } elseif ($item_price <= 0 && $item_credit <= 0) {
                return $this->showmessage('trade_pricecredit_need');
            } elseif ($_GET['item_number'] < 1) {
                return $this->showmessage('tread_please_number');
            }
    
            if (!empty($_FILES['tradeattach']['tmp_name'][0])) {
                $_FILES['attach'] = array_merge_recursive((array)$_FILES['attach'], $_FILES['tradeattach']);
            }
    
            if (($this->group['allowpostattach'] || $this->group['allowpostimage']) && is_array($_FILES['attach'])) {
                foreach ($_FILES['attach']['name'] as $attachname) {
                    if ($attachname != '') {
                        checklowerlimit('postattach', 0, 1, $this->forum['fid']);
                        break;
                    }
                }
            }
    
            $this->trademessage = $parameters['message'];
            $this->param['message'] = '';
        }
        
        public function after_newthread() {
            if (!$this->tid) {
                return;
            }
            
            // Goods is a comment to trade-thread,
            //   That's means ones Thread is posted, the Goods will post at the same time.
            //   And normaly the value of Goods_pid will be greater 1 or more than Thread_pid.
            //
            // If we use a variable (local)$pid as Goods_pid and use variable (global)$this->pid as Thread_pid,
            //   Ones we post a new Trade-Thread, the attachments' pid will be equal with Thread_pid, so that
            //   the attachments will display on thread page rather than goods page.
            //
            // So, after Thread is post and saved after the Function before_newthread(); excuted,
            //   I set $this->pid to Goods_pid, so that Attachment will be display as we hope.
            //
            // Here, in this function I replace $pid with $this->pid
    
            $this->trademessage = preg_replace('/\[attachimg\](\d+)\[\/attachimg\]/is', '[attach]\1[/attach]', $this->trademessage);
            
            $this->pid = insertpost([
                'tid'         => $this->tid,
                'fid'         => $this->forum['fid'],
                'first'       => '0',
                'author'      => $this->member['username'],
                'authorid'    => $this->member['uid'],
                'subject'     => $_GET['item_name'],
                'dateline'    => getglobal('timestamp'),
                'message'     => $this->trademessage,
                'useip'       => getglobal('clientip'),
                'invisible'   => 0,
                'anonymous'   => $this->param['isanonymous'],
                'usesig'      => $_GET['usesig'],
                'htmlon'      => $this->param['htmlon'],
                'bbcodeoff'   => 0,
                'smileyoff'   => $this->param['smileyoff'],
                'parseurloff' => $this->param['parseurloff'],
                'attachment'  => 0,
                'tags'        => $this->param['tagstr'],
                'status'      => (defined('IN_MOBILE') ? 8 : 0),
            ]);
            
            require_once libfile('function/trade');
 
            if(($this->group['allowpostattach'] || $this->group['allowpostimage']) && ($_GET['attachnew'] || $_GET['tradeaid'])){
                updateattach($this->param['displayorder'] == -4 || $this->param['modnewthreads'], $this->tid, $this->pid, $_GET['attachnew']);
            }
            
            $author = !$this->param['isanonymous'] ? $this->member['username'] : '';
            trade_create([
                'tid'             => $this->tid,
                'pid'             => $this->pid,
                'aid'             => $_GET['tradeaid'],
                'item_expiration' => $_GET['item_expiration'],
                'thread'          => $this->thread,
                'discuz_uid'      => $this->member['uid'],
                'author'          => $author,
                'seller'          => empty($_GET['paymethod']) && $_GET['seller'] ? dhtmlspecialchars(trim($_GET['seller'])) : '',
                'tenpayaccount'   => $_GET['tenpay_account'],
                'item_name'       => $_GET['item_name'],
                'item_price'      => $_GET['item_price'],
                'item_number'     => $_GET['item_number'],
                'item_quality'    => $_GET['item_quality'],
                'item_locus'      => $_GET['item_locus'],
                'item_type'       => $_GET['item_type'],
                'item_costprice'  => $_GET['item_costprice'],
                'item_credit'     => $_GET['item_credit'],
                'item_costcredit' => $_GET['item_costcredit'],
                'transport'       => $_GET['transport'],
                'postage_mail'    => $_GET['postage_mail'],
                'postage_express' => $_GET['postage_express'],
                'postage_ems'     => $_GET['postage_ems'],
            ]);
            
            if (!empty($_GET['tradeaid'])) {
                convertunusedattach($_GET['tradeaid'], $this->tid, $this->pid);
            }
        }
        
        public function before_feed() {

            if ($this->forum['allowfeed'] && !$this->param['isanonymous']) {
                
                $goods_url = 'forum.php?mod=viewthread&do=tradeinfo&tid=' . $this->tid . '&pid=' . $this->pid;
                $extcredits = $this->setting['extcredits'];
                $transextra = $this->setting['creditstransextra'];
    
                if (!empty($this->trademessage)) {
                    $message = messagecutstr(messagesafeclear($this->trademessage), 200);
                } else {
                    $message = '';
                }
    
                if ($_GET['item_price'] > 0) {
                    if ($transextra[5] != -1 && $_GET['item_credit']) {
                        $body_template = 'thread_goods_1';
                    } else {
                        $body_template = 'thread_goods_2';
                    }
                } else {
                    $body_template = 'thread_goods_3';
                }
    
                $this->feed = [
                    'icon'           => 'goods',
                    'title_template' => 'thread_goods',
                    'body_template'  => $body_template,
                    'body_data'      => [
                        'tid'   => $this->tid,
                        'tsub'  => $this->param['subject'],
                        'tlink' => $goods_url,
                        
                        'itemname'   => $_GET['item_name'],
                        'itemprice'  => $_GET['item_price'],
                        'itemcredit' => $_GET['item_credit'],
                        'creditunit' => $extcredits[$transextra[5]]['unit'] . $extcredits[$transextra[5]]['title'],
            
                        'message' => $message,
                        
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
                
                $image_show_num = 6;
                
                if ($_GET['tradeaid']) {
    
                    $attach_keys = [$_GET['tradeaid']];
    
                    if($_GET['attachnew']) {
                        $attach_keys = array_merge($attach_keys, array_keys($_GET['attachnew']));
                    }
    
                    $counter = 0;
                    foreach ($attach_keys as $attach_aid){
                        if($counter < $image_show_num){
                            $counter += 1;
                            $this->feed['body_data']['imgs'][] = [
                                'img'     => getforumimg($attach_aid),
                                'img_url' => $goods_url,
                            ];
                        } else {
                            break;
                        }
                    }
        
                    if (0 < $need_more = $image_show_num - count($this->feed['body_data']['imgs'])) {
                        $attach_imgs = [];
                        getattach_img($this->tid, $this->pid, $need_more, $attach_imgs, $attach_keys);
                        $this->feed['body_data']['imgs'] = array_merge($this->feed['body_data']['imgs'], $attach_imgs);
                    }
                }
            }
        }
        
        public function after_feed() {
            global $extra;
            $values = [
                'fid'      => $this->forum['fid'],
                'tid'      => $this->tid,
                'pid'      => $this->pid,
                'coverimg' => '',
            ];
            $values = array_merge($values, (array)$this->param['values'], $this->param['param']);
            if (!empty($_GET['continueadd'])) {
                showmessage('post_newthread_succeed', "forum.php?mod=post&action=reply&fid=" . $this->forum['fid'] . "&tid=" . $this->tid . "&addtrade=yes", $values, ['header' => true]);
            } else {
                showmessage('post_newthread_succeed', "forum.php?mod=viewthread&tid=" . $this->tid . "&extra=$extra", $values);
            }
        }
    
        public function before_replyfeed() {
            
            if ($this->forum['allowfeed'] && !$this->param['isanonymous']) {
                
                $goods_url = 'forum.php?mod=viewthread&do=tradeinfo&tid=' . $this->thread['tid'] . '&pid=' . $this->pid;
            
                $extcredits = $this->setting['extcredits'];
                $transextra = $this->setting['creditstransextra'];
            
                if (!empty($this->param['message'])) {
                    $message = messagecutstr(messagesafeclear($this->param['message']), 200);
                } else {
                    $message = '';
                }
            
                if ($_GET['item_price'] > 0) {
                    if ($transextra[5] != -1 && $_GET['item_credit']) {
                        $body_template = 'thread_goods_1';
                    } else {
                        $body_template = 'thread_goods_2';
                    }
                } else {
                    $body_template = 'thread_goods_3';
                }
            
                $this->feed = [
                    'icon'           => 'goods',
                    'title_template' => 'thread_goods',
                    'body_template'  => $body_template,
                    'body_data'      => [
                    
                        'tid'   => $this->thread['tid'],
                        'tsub'  => $this->thread['subject'],
                        'tlink' => $goods_url,
                    
                        'itemname'   => dhtmlspecialchars($_GET['item_name']),
                        'itemprice'  => $_GET['item_price'],
                        'itemcredit' => $_GET['item_credit'],
                        'creditunit' => $extcredits[$transextra[5]]['unit'] . $extcredits[$transextra[5]]['title'],
                    
                        'message' => $message,
                    
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
            
                $image_show_num = 6;
            
                if ($_GET['tradeaid']) {
                
                    $attach_keys = [$_GET['tradeaid']];
                
                    if($_GET['attachnew']) {
                        $attach_keys = array_merge($attach_keys, array_keys($_GET['attachnew']));
                    }
                
                    $counter = 0;
                    foreach ($attach_keys as $attach_aid){
                        if($counter < $image_show_num){
                            $counter += 1;
                            $this->feed['body_data']['imgs'][] = [
                                'img'     => getforumimg($attach_aid),
                                'img_url' => $goods_url,
                            ];
                        } else {
                            break;
                        }
                    }
                
                    if (0 < $need_more = $image_show_num - count($this->feed['body_data']['imgs'])) {
                        $attach_imgs = [];
                        getattach_img($this->thread['tid'], $this->pid, $need_more, $attach_imgs, $attach_keys);
                        $this->feed['body_data']['imgs'] = array_merge($this->feed['body_data']['imgs'], $attach_imgs);
                    }
                }
            }
        }
    
        public function after_replyfeed() {
            global $extra;
            if ($this->param['special'] == 2 && $this->group['allowposttrade'] && $this->thread['authorid'] == $this->member['uid']) {
                if (!empty($_GET['continueadd'])) {
                    dheader("location: forum.php?mod=post&action=reply&fid=" . $this->forum['fid'] . "&firstpid=" . $this->pid . "&tid=" . $this->thread['tid'] . "&addtrade=yes");
                } else {
                    if ($this->param['modnewreplies']) {
                        $url = "forum.php?mod=viewthread&tid=" . $this->thread['tid'];
                    } else {
                        $url = "forum.php?mod=viewthread&tid=" . $this->thread['tid'] . "&pid=" . $this->pid . "&page=" . $this->param['page'] . "&extra=" . $extra . "#pid" . $this->pid;
                    }
                    return $this->showmessage('trade_add_succeed', $url, $this->param['showmsgparam']);
                }
            }
        }
        
        public function before_newreply($parameters) {
            $item_price = floatval($_GET['item_price']);
            $item_credit = intval($_GET['item_credit']);
            if (!trim($_GET['item_name'])) {
                return $this->showmessage('trade_please_name');
            } elseif ($this->group['maxtradeprice'] && $item_price > 0 && ($this->group['mintradeprice'] > $item_price || $this->group['maxtradeprice'] < $item_price)) {
                return $this->showmessage('trade_price_between', '', [
                    'mintradeprice' => $this->group['mintradeprice'],
                    'maxtradeprice' => $this->group['maxtradeprice'],
                ]);
            } elseif ($this->group['maxtradeprice'] && $item_credit > 0 && ($this->group['mintradeprice'] > $item_credit || $this->group['maxtradeprice'] < $item_credit)) {
                return $this->showmessage('trade_credit_between', '', [
                    'mintradeprice' => $this->group['mintradeprice'],
                    'maxtradeprice' => $this->group['maxtradeprice'],
                ]);
            } elseif (!$this->group['maxtradeprice'] && $item_price > 0 && $this->group['mintradeprice'] > $item_price) {
                return $this->showmessage('trade_price_more_than', '', ['mintradeprice' => $this->group['mintradeprice']]);
            } elseif (!$this->group['maxtradeprice'] && $item_credit > 0 && $this->group['mintradeprice'] > $item_credit) {
                return $this->showmessage('trade_credit_more_than', '', ['mintradeprice' => $this->group['mintradeprice']]);
            } elseif ($item_price <= 0 && $item_credit <= 0) {
                return $this->showmessage('trade_pricecredit_need');
            } elseif ($_GET['item_number'] < 1) {
                return $this->showmessage('tread_please_number');
            }
        }
        
        public function after_newreply() {
            if (!$this->pid) {
                return;
            }
            if ($this->param['special'] == 2 && $this->group['allowposttrade'] && $this->thread['authorid'] == $this->member['uid'] && !empty($_GET['trade']) && !empty($_GET['item_name'])) {
                $author = (!$this->param['isanonymous']) ? $this->member['username'] : '';
                require_once libfile('function/trade');
                trade_create([
                    'tid'             => $this->thread['tid'],
                    'pid'             => $this->pid,
                    'aid'             => $_GET['tradeaid'],
                    'item_expiration' => $_GET['item_expiration'],
                    'thread'          => $this->thread,
                    'discuz_uid'      => $this->member['uid'],
                    'author'          => $author,
                    'seller'          => empty($_GET['paymethod']) && $_GET['seller'] ? dhtmlspecialchars(trim($_GET['seller'])) : '',
                    'item_name'       => $_GET['item_name'],
                    'item_price'      => $_GET['item_price'],
                    'item_number'     => $_GET['item_number'],
                    'item_quality'    => $_GET['item_quality'],
                    'item_locus'      => $_GET['item_locus'],
                    'transport'       => $_GET['transport'],
                    'postage_mail'    => $_GET['postage_mail'],
                    'postage_express' => $_GET['postage_express'],
                    'postage_ems'     => $_GET['postage_ems'],
                    'item_type'       => $_GET['item_type'],
                    'item_costprice'  => $_GET['item_costprice'],
                    'item_credit'     => $_GET['item_credit'],
                    'item_costcredit' => $_GET['item_costcredit'],
                ]);
                
                if (!empty($_GET['tradeaid'])) {
                    convertunusedattach($_GET['tradeaid'], $this->thread['tid'], $this->pid);
                }
            }
            
            if (!$this->forum['allowfeed'] || !$_GET['addfeed']) {
                $this->after_replyfeed();
            }
        }
        
        public function before_editpost($parameters) {
            global $closed;
            if ($parameters['special'] == 2 && $this->group['allowposttrade']) {
                
                if ($trade = C::t('forum_trade')->fetch_goods($this->thread['tid'], $this->post['pid'])) {
                    $seller = empty($_GET['paymethod']) && $_GET['seller'] ? censor(dhtmlspecialchars(trim($_GET['seller']))) : '';
                    $item_name = censor(dhtmlspecialchars(trim($_GET['item_name'])));
                    $item_price = floatval($_GET['item_price']);
                    $item_credit = intval($_GET['item_credit']);
                    $item_locus = censor(dhtmlspecialchars(trim($_GET['item_locus'])));
                    $item_number = intval($_GET['item_number']);
                    $item_quality = intval($_GET['item_quality']);
                    $item_transport = intval($_GET['item_transport']);
                    $postage_mail = intval($_GET['postage_mail']);
                    $postage_express = intval(trim($_GET['postage_express']));
                    $postage_ems = intval($_GET['postage_ems']);
                    $item_type = intval($_GET['item_type']);
                    $item_costprice = floatval($_GET['item_costprice']);
                    
                    if (!trim($item_name)) {
                        showmessage('trade_please_name');
                    } elseif ($this->group['maxtradeprice'] && $item_price > 0 && ($this->group['mintradeprice'] > $item_price || $this->group['maxtradeprice'] < $item_price)) {
                        showmessage('trade_price_between', '', [
                            'mintradeprice' => $this->group['mintradeprice'],
                            'maxtradeprice' => $this->group['maxtradeprice'],
                        ]);
                    } elseif ($this->group['maxtradeprice'] && $item_credit > 0 && ($this->group['mintradeprice'] > $item_credit || $this->group['maxtradeprice'] < $item_credit)) {
                        showmessage('trade_credit_between', '', [
                            'mintradeprice' => $this->group['mintradeprice'],
                            'maxtradeprice' => $this->group['maxtradeprice'],
                        ]);
                    } elseif (!$this->group['maxtradeprice'] && $item_price > 0 && $this->group['mintradeprice'] > $item_price) {
                        showmessage('trade_price_more_than', '', ['mintradeprice' => $this->group['mintradeprice']]);
                    } elseif (!$this->group['maxtradeprice'] && $item_credit > 0 && $this->group['mintradeprice'] > $item_credit) {
                        showmessage('trade_credit_more_than', '', ['mintradeprice' => $this->group['mintradeprice']]);
                    } elseif ($item_price <= 0 && $item_credit <= 0) {
                        showmessage('trade_pricecredit_need');
                    } elseif ($item_number < 1) {
                        showmessage('tread_please_number');
                    }
                    
                    if ($trade['aid'] && $_GET['tradeaid'] && $trade['aid'] != $_GET['tradeaid']) {
                        $attach = C::t('forum_attachment_n')->fetch('tid:' . $this->thread['tid'], $trade['aid']);
                        C::t('forum_attachment')->delete($trade['aid']);
                        C::t('forum_attachment_n')->delete('tid:' . $this->thread['tid'], $trade['aid']);
                        dunlink($attach);
                        $this->param['threadimageaid'] = $_GET['tradeaid'];
                        convertunusedattach($_GET['tradeaid'], $this->thread['tid'], $this->post['pid']);
                    }
                    
                    $expiration = $_GET['item_expiration'] ? @strtotime($_GET['item_expiration']) : 0;
                    $closed = $expiration > 0 && @strtotime($_GET['item_expiration']) < TIMESTAMP ? 1 : $closed;
                    
                    switch ($_GET['transport']) {
                        case 'seller':
                            $item_transport = 1;
                            break;
                        case 'buyer':
                            $item_transport = 2;
                            break;
                        case 'virtual':
                            $item_transport = 3;
                            break;
                        case 'logistics':
                            $item_transport = 4;
                            break;
                    }
                    if (!$item_price || $item_price <= 0) {
                        $item_price = $postage_mail = $postage_express = $postage_ems = '';
                    }
                    
                    $data = [
                        'aid'           => $_GET['tradeaid'],
                        'account'       => $seller,
                        'tenpayaccount' => $_GET['tenpay_account'],
                        'subject'       => $item_name,
                        'price'         => $item_price,
                        'amount'        => $item_number,
                        'quality'       => $item_quality,
                        'locus'         => $item_locus,
                        'transport'     => $item_transport,
                        'ordinaryfee'   => $postage_mail,
                        'expressfee'    => $postage_express,
                        'emsfee'        => $postage_ems,
                        'itemtype'      => $item_type,
                        'expiration'    => $expiration,
                        'closed'        => $closed,
                        'costprice'     => $item_costprice,
                        'credit'        => $item_credit,
                        'costcredit'    => $_GET['item_costcredit'],
                    ];
                    C::t('forum_trade')->update($this->thread['tid'], $this->post['pid'], $data);
                    if (!empty($_GET['infloat'])) {
                        $viewpid = C::t('forum_post')->fetch_threadpost_by_tid_invisible($this->thread['tid']);
                        $viewpid = $viewpid['pid'];
                        $this->param['redirecturl'] = "forum.php?mod=viewthread&tid=" . $this->thread['tid'] . "&viewpid=$viewpid#pid$viewpid";
                    } else {
                        $this->param['redirecturl'] = "forum.php?mod=viewthread&do=tradeinfo&tid=" . $this->thread['tid'] . "&pid=" . $this->post['pid'];
                    }
                }
                
            }
        }
        
        public function after_deletepost() {
            if ($this->thread['special'] == 2) {
                C::t('forum_trade')->delete_by_id_idtype($this->post['pid'], 'pid');
            }
        }
    }