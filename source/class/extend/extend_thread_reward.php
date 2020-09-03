<?php

    if (!defined('IN_DISCUZ')) {
        exit('Access Denied');
    }
    
    class extend_thread_reward extends extend_thread_base {
        
        public $rewardprice;
        public $realprice;
        
        public function before_newthread($parameters) {
            
            $this->rewardprice = intval($_GET['rewardprice']);
            $minrewardprice = $this->group['minrewardprice'];
            $maxrewardprice = $this->group['maxrewardprice'];
            if ($this->rewardprice < 1) {
                showmessage('reward_credits_please');
            } elseif ($this->rewardprice > 32767) {
                showmessage('reward_credits_overflow');
            } elseif ($this->rewardprice < $minrewardprice || ($maxrewardprice > 0 && $this->rewardprice > $maxrewardprice)) {
                if ($maxrewardprice > 0) {
                    showmessage('reward_credits_between', '', [
                        'minrewardprice' => $minrewardprice,
                        'maxrewardprice' => $maxrewardprice,
                    ]);
                } else {
                    showmessage('reward_credits_lower', '', ['minrewardprice' => $minrewardprice]);
                }
            } elseif (($this->realprice = $this->rewardprice + ceil($this->rewardprice * $this->setting['creditstax'])) > getuserprofile('extcredits' . $this->setting['creditstransextra'][2])) {
                showmessage('reward_credits_shortage');
            }
            
            $this->param['price'] = $this->rewardprice;
            
        }
        
        public function after_newthread() {
            if ($this->group['allowpostreward']) {
                updatemembercount($this->member['uid'], [$this->setting['creditstransextra']['2'] => -$this->realprice], 1, 'RTC', $this->tid);
            }
        }
        
        public function before_feed() {
            
            global $_G;
            
            $message = !$this->param['readperm'] ? $this->param['message'] : '';
            
            $this->feed = [
                'icon'           => 'reward',
                'title_template' => 'thread_reward',
                'title_data'     => [
                    'tid'   => $this->tid,
                    'tsub'  => $this->param['subject'],
                    'tlink' => 'forum.php?mod=viewthread&tid=' . $this->tid,
                ],
                'body_template'  => 'thread_reward',
                'body_data'      => [
                    'tid'   => $this->tid,
                    'tsub'  => $this->param['subject'],
                    'tlink' => 'forum.php?mod=viewthread&tid=' . $this->tid,
                    
                    'uid'     => $_G['uid'],
                    'uname'   => $_G['username'],
                    'ulink'   => 'home.php?mod=space&uid=' . $_G['uid'],
                    'uavatar' => avatar($_G['uid'], 'small', true),

                    'message'    => messagecutstr($message, 150),
                    'price'      => $this->rewardprice,
                    'extcredits' => $this->setting['extcredits'][$this->setting['creditstransextra']['2']]['title'],
                    
                    'expend0' => '',
                    'expend1' => '',
                    'expend2' => '',
                    'expend3' => '',
                    'expend4' => '',
                    'expend5' => '',
                    'expend6' => '',
                    'expend7' => '',
                ],
                'body_general'   => messagecutstr($message, 150),
            ];
        }
        
        public function before_replyfeed() {
            if ($this->forum['allowfeed'] && !$this->param['isanonymous']) {
                
                if ($this->param['special'] == 3 && $this->thread['authorid'] != $this->member['uid']) {
                    
                    $message = !$this->param['readperm'] ? $this->param['message'] : '';
                    
                    $this->feed = [
                        'icon'           => 'reward',
                        'title_template' => 'thread_reward_reply',
                        'title_data'     => [
                            'tid'   => $this->thread['tid'],
                            'tsub'  => $this->thread['subject'],
                            'tlink' => 'forum.php?mod=viewthread&tid=' . $this->thread['tid'],
                            
                            'uid'     => $this->thread['authorid'],
                            'uname'   => $this->thread['author'],
                            'ulink'   => 'home.php?mod=space&uid=' . $this->thread['authorid'],
                            'uavatar' => avatar($this->thread['authorid'], 'small', true),
                        
                        ],
                        'body_template'  => 'thread_reward_reply',
                        'body_data'      => [
                            'tid'   => $this->thread['tid'],
                            'tsub'  => $this->thread['subject'],
                            'tlink' => 'forum.php?mod=viewthread&tid=' . $this->thread['tid'],
    
                            'uid'     => $this->thread['authorid'],
                            'uname'   => $this->thread['author'],
                            'ulink'   => 'home.php?mod=space&uid=' . $this->thread['authorid'],
                            'uavatar' => avatar($this->thread['authorid'], 'small', true),
                            
                            'expend0' => '',
                            'expend1' => '',
                            'expend2' => '',
                            'expend3' => '',
                            'expend4' => '',
                            'expend5' => '',
                            'expend6' => '',
                            'expend7' => '',
                        ],
                        'body_general'   => messagecutstr($message, 300),
                    ];
                }
            }
        }
        
        public function before_editpost($parameters) {
            $isfirstpost = $this->post['first'] ? 1 : 0;
            $isorigauthor = $this->member['uid'] && $this->member['uid'] == $this->post['authorid'];
            if ($isfirstpost) {
                if ($this->thread['special'] == 3) {
                    $this->param['price'] = $isorigauthor ? ($this->thread['price'] > 0 && $this->thread['price'] != $_GET['rewardprice'] ? $_GET['rewardprice'] : 0) : $this->thread['price'];
                }
                
                if ($this->thread['special'] == 3 && $isorigauthor) {
                    $rewardprice = $this->thread['price'] > 0 ? intval($_GET['rewardprice']) : $this->thread['price'];
                    if ($this->thread['price'] > 0 && $this->thread['price'] != $_GET['rewardprice']) {
                        if ($rewardprice <= 0) {
                            showmessage('reward_credits_invalid');
                        }
                        $addprice = ceil(($rewardprice - $this->thread['price']) + ($rewardprice - $this->thread['price']) * $this->setting['creditstax']);
                        if ($rewardprice < $this->thread['price']) {
                            showmessage('reward_credits_fall');
                        } elseif ($rewardprice < $this->group['minrewardprice'] || ($this->group['maxrewardprice'] > 0 && $rewardprice > $this->group['maxrewardprice'])) {
                            showmessage('reward_credits_between', '', [
                                'minrewardprice' => $this->group['minrewardprice'],
                                'maxrewardprice' => $this->group['maxrewardprice'],
                            ]);
                        } elseif ($addprice > getuserprofile('extcredits' . $this->setting['creditstransextra'][2])) {
                            showmessage('reward_credits_shortage');
                        }
                        $realprice = ceil($this->thread['price'] + $this->thread['price'] * $this->setting['creditstax']);
                        
                        updatemembercount($this->thread['authorid'], [$this->setting['creditstransextra'][2] => -$addprice]);
                        C::t('common_credit_log')->update_by_uid_operation_relatedid($this->thread['authorid'], 'RTC', $this->thread['tid'], ['extcredits' . $this->setting['creditstransextra'][2] => $realprice]);
                    }
                    
                    if (!$this->forum['ismoderator']) {
                        if ($this->thread['replies'] > 1) {
                            $this->param['subject'] = addslashes($this->thread['subject']);
                        }
                    }
                    
                    $this->param['price'] = $rewardprice;
                }
            }
        }
        
        public function before_deletepost($parameters) {
            $isfirstpost = $this->post['first'] ? 1 : 0;
            if ($this->thread['special'] == 3) {
                if ($this->thread['price'] < 0 && ($this->thread['dateline'] + 1 == $this->post['dateline'])) {
                    showmessage('post_edit_reward_nopermission', null);
                }
            }
            
            
            if ($this->thread['special'] == 3 && $isfirstpost) {
                updatemembercount($this->post['authorid'], [$this->setting['creditstransextra'][2] => $this->thread['price']]);
                C::t('common_credit_log')->delete_by_uid_operation_relatedid($this->thread['authorid'], 'RTC', $this->thread['tid']);
            }
        }
    }